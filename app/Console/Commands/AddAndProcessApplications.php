<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Models\JobPost;
use App\Models\JobApplication;
use App\Models\CvParsedData;
use App\Models\TokenUsageLog;
use App\Jobs\ProcessCvJob;
use App\Services\AISievingService;
use App\Services\TokenService;
use Illuminate\Support\Facades\Storage;

class AddAndProcessApplications extends Command
{
    protected $signature = 'test:add-applications {--count=3 : Number of applications to create} {--process : Process applications immediately}';
    protected $description = 'Add job applications with CVs and process them to test token tracking';

    protected TokenService $tokenService;
    protected AISievingService $sievingService;

    public function __construct()
    {
        parent::__construct();
        $this->tokenService = app(TokenService::class);
        $this->sievingService = app(AISievingService::class);
    }

    public function handle()
    {
        $this->info('=== ADDING JOB APPLICATIONS FOR TOKEN TRACKING TEST ===');
        $this->newLine();

        // Get company
        $company = Company::first();
        if (!$company) {
            $this->error('No company found! Please create a company first.');
            return Command::FAILURE;
        }

        $this->info("Company: {$company->name} (ID: {$company->id})");
        $this->newLine();

        // Check token balance before
        $balanceBefore = $this->tokenService->getBalance($company->id);
        $this->info('📊 Token Balance BEFORE:');
        $this->line("   Remaining: " . number_format($balanceBefore['remaining'] ?? 0));
        $this->line("   Used: " . number_format($balanceBefore['used'] ?? 0));
        $this->newLine();

        // Get or create job post
        $jobPost = $this->getOrCreateJobPost($company);
        $this->info("Using Job Post: {$jobPost->title} (ID: {$jobPost->id})");
        $this->newLine();

        // Count existing applications
        $existingCount = JobApplication::where('company_id', $company->id)->count();
        $this->info("Existing applications: {$existingCount}");
        $this->newLine();

        // Create applications
        $count = (int) $this->option('count');
        $this->info("Creating {$count} job applications...");
        $this->newLine();

        $applications = [];
        $cvTemplates = $this->getCvTemplates();

        for ($i = 1; $i <= $count; $i++) {
            $this->info("Creating application #{$i}...");
            
            $cvTemplate = $cvTemplates[($i - 1) % count($cvTemplates)];
            
            $application = JobApplication::create([
                'job_post_id' => $jobPost->id,
                'company_id' => $company->id,
                'name' => $cvTemplate['name'],
                'email' => strtolower(str_replace(' ', '.', $cvTemplate['name'])) . '.' . time() . '@example.com',
                'phone' => '+123456789' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'education_level' => $cvTemplate['education_level'],
                'area_of_study' => $cvTemplate['area_of_study'],
                'institution' => $cvTemplate['institution'],
                'current_job_title' => $cvTemplate['current_job_title'],
                'current_company' => $cvTemplate['current_company'],
                'relevant_skills' => implode(', ', $cvTemplate['skills']),
                'status' => 'pending',
            ]);

            // Create CV parsed data
            CvParsedData::create([
                'job_application_id' => $application->id,
                'raw_text' => $cvTemplate['cv_text'],
                'parsing_confidence' => 0.95,
                'extracted_data' => [
                    'name' => $cvTemplate['name'],
                    'email' => $application->email,
                    'education' => $cvTemplate['education_level'] . ' in ' . $cvTemplate['area_of_study'],
                    'experience' => $cvTemplate['current_job_title'] . ' at ' . $cvTemplate['current_company'],
                    'skills' => $cvTemplate['skills'],
                ],
            ]);

            $applications[] = $application;
            $this->line("   ✓ Created application #{$application->id}: {$application->name}");
        }

        $this->newLine();
        $this->info("✅ Created {$count} applications successfully!");
        $this->newLine();

        // Process applications if --process flag is set
        if ($this->option('process')) {
            $this->info('🔄 Processing applications...');
            $this->newLine();

            foreach ($applications as $index => $application) {
                $this->info("Processing application #{$application->id} ({$application->name})...");
                
                try {
                    // Process CV (this will trigger AI analysis)
                    $cvJob = new ProcessCvJob($application);
                    $cvJob->handle(
                        app(\App\Services\CvParserService::class),
                        app(\App\Services\AIAnalysisService::class)
                    );
                    $this->line("   ✓ CV processed");

                    // Run AI sieving
                    $decision = $this->sievingService->evaluate($application);
                    if ($decision) {
                        $this->line("   ✓ Sieved - Decision: " . strtoupper($decision->ai_decision) . ", Score: " . ($decision->ai_score ?? 'N/A'));
                    } else {
                        $this->warn("   ⚠️  Sieving returned no decision");
                    }
                } catch (\Exception $e) {
                    $this->error("   ✗ Processing failed: " . $e->getMessage());
                }
                
                $this->newLine();
            }
        } else {
            // Dispatch to queue
            $this->info('📤 Dispatching applications to queue...');
            foreach ($applications as $application) {
                ProcessCvJob::dispatch($application);
                $this->line("   ✓ Dispatched application #{$application->id}");
            }
            $this->newLine();
            $this->warn('⚠️  Applications have been queued. Run "php artisan queue:work" to process them.');
            $this->line('   Or run this command again with --process flag to process immediately.');
        }

        // Wait a moment for logs
        if ($this->option('process')) {
            sleep(2);
        }

        // Check token balance after
        $balanceAfter = $this->tokenService->getBalance($company->id);
        $this->info('📊 Token Balance AFTER:');
        $this->line("   Remaining: " . number_format($balanceAfter['remaining'] ?? 0));
        $this->line("   Used: " . number_format($balanceAfter['used'] ?? 0));
        $this->newLine();

        // Calculate tokens used
        $tokensUsed = ($balanceBefore['remaining'] ?? 0) - ($balanceAfter['remaining'] ?? 0);
        if ($tokensUsed > 0) {
            $this->info("✅ Tokens Used: " . number_format($tokensUsed));
        } else {
            $this->warn("⚠️  No tokens were deducted yet.");
            if (!$this->option('process')) {
                $this->line('   This is expected if you used --process flag. Process the queue to see token usage.');
            }
        }
        $this->newLine();

        // Show usage logs
        $logs = TokenUsageLog::where('company_id', $company->id)
            ->whereIn('job_application_id', array_column($applications, 'id'))
            ->orderBy('created_at', 'desc')
            ->get();

        if ($logs->count() > 0) {
            $this->info('📋 Token Usage Logs for these applications:');
            $tableData = [];
            foreach ($logs as $log) {
                $tableData[] = [
                    'App ID' => $log->job_application_id,
                    'Operation' => $log->operation_type,
                    'Tokens' => number_format($log->tokens_used),
                    'Input' => number_format($log->input_tokens),
                    'Output' => number_format($log->output_tokens),
                    'Cost' => '$' . number_format($log->total_cost, 4),
                    'Time' => $log->created_at->format('H:i:s'),
                ];
            }
            $this->table(
                ['App ID', 'Operation', 'Tokens', 'Input', 'Output', 'Cost', 'Time'],
                $tableData
            );
        } else {
            $this->warn('⚠️  No usage logs found yet.');
            if (!$this->option('process')) {
                $this->line('   Process the queue to generate logs.');
            }
        }

        $this->newLine();
        $this->info('✅ Applications created successfully!');
        $this->line('   Application IDs: ' . implode(', ', array_column($applications, 'id')));
        
        if (!$this->option('process')) {
            $this->newLine();
            $this->comment('To process these applications and test token tracking:');
            $this->line('   1. Run: php artisan queue:work');
            $this->line('   2. Or run: php artisan test:add-applications --count=' . $count . ' --process');
        }

        return Command::SUCCESS;
    }

    protected function getOrCreateJobPost(Company $company): JobPost
    {
        $jobPost = JobPost::where('company_id', $company->id)
            ->where('is_active', true)
            ->first();

        if (!$jobPost) {
            $this->info('Creating test job post...');
            $jobPost = JobPost::create([
                'title' => 'Software Developer',
                'slug' => 'software-developer-' . time(),
                'description' => 'We are looking for an experienced software developer to join our team. You will be responsible for developing and maintaining web applications using modern technologies.',
                'requirements' => 'Bachelor\'s degree in Computer Science or related field. 3+ years of experience with PHP, Laravel, JavaScript, and MySQL. Strong problem-solving skills and ability to work in a team environment.',
                'responsibilities' => 'Develop and maintain web applications. Write clean, maintainable code. Collaborate with team members. Participate in code reviews.',
                'location' => 'Remote',
                'employment_type' => 'full-time',
                'experience_level' => 'mid-level',
                'company_id' => $company->id,
                'is_active' => true,
            ]);
            $this->info("   ✓ Created job post #{$jobPost->id}");
        }

        return $jobPost;
    }

    protected function getCvTemplates(): array
    {
        return [
            [
                'name' => 'John Smith',
                'education_level' => 'Bachelor\'s Degree',
                'area_of_study' => 'Computer Science',
                'institution' => 'University of Technology',
                'current_job_title' => 'Senior Software Developer',
                'current_company' => 'Tech Solutions Inc.',
                'skills' => ['PHP', 'Laravel', 'JavaScript', 'MySQL', 'Git', 'Docker'],
                'cv_text' => "JOHN SMITH
Senior Software Developer

Email: john.smith@example.com
Phone: +1234567890

EDUCATION
Bachelor's Degree in Computer Science
University of Technology, 2015-2019

EXPERIENCE
Senior Software Developer | Tech Solutions Inc. | 2020-Present
- Developed and maintained web applications using PHP and Laravel
- Implemented RESTful APIs for mobile applications
- Optimized database queries improving performance by 40%
- Led a team of 3 junior developers
- Used Docker for containerization and deployment

Software Developer | Web Apps Co. | 2018-2020
- Built responsive web applications using JavaScript and PHP
- Integrated third-party APIs and payment gateways
- Maintained MySQL databases and performed optimizations

SKILLS
- PHP, Laravel Framework
- JavaScript, Vue.js
- MySQL, Database Design
- Git, Docker, CI/CD
- RESTful API Development
- Agile Methodologies"
            ],
            [
                'name' => 'Sarah Johnson',
                'education_level' => 'Master\'s Degree',
                'area_of_study' => 'Software Engineering',
                'institution' => 'Tech University',
                'current_job_title' => 'Full Stack Developer',
                'current_company' => 'Digital Innovations',
                'skills' => ['PHP', 'Laravel', 'React', 'PostgreSQL', 'AWS'],
                'cv_text' => "SARAH JOHNSON
Full Stack Developer

Email: sarah.johnson@example.com
Phone: +1234567891

EDUCATION
Master's Degree in Software Engineering
Tech University, 2016-2018

Bachelor's Degree in Computer Science
Tech University, 2012-2016

EXPERIENCE
Full Stack Developer | Digital Innovations | 2019-Present
- Developed full-stack applications using Laravel and React
- Designed and implemented database schemas using PostgreSQL
- Deployed applications on AWS cloud infrastructure
- Implemented automated testing reducing bugs by 50%
- Collaborated with cross-functional teams

Junior Developer | StartupXYZ | 2018-2019
- Built web applications using PHP and JavaScript
- Participated in code reviews and learned best practices
- Fixed bugs and implemented new features

SKILLS
- PHP, Laravel Framework
- JavaScript, React.js
- PostgreSQL, Database Design
- AWS Cloud Services
- Git, GitHub Actions
- Test-Driven Development"
            ],
            [
                'name' => 'Michael Chen',
                'education_level' => 'Bachelor\'s Degree',
                'area_of_study' => 'Information Technology',
                'institution' => 'State University',
                'current_job_title' => 'Backend Developer',
                'current_company' => 'Cloud Systems Ltd',
                'skills' => ['PHP', 'Laravel', 'Node.js', 'MongoDB', 'Redis'],
                'cv_text' => "MICHAEL CHEN
Backend Developer

Email: michael.chen@example.com
Phone: +1234567892

EDUCATION
Bachelor's Degree in Information Technology
State University, 2014-2018

EXPERIENCE
Backend Developer | Cloud Systems Ltd | 2019-Present
- Developed scalable backend services using Laravel and Node.js
- Implemented caching solutions using Redis
- Worked with MongoDB for NoSQL database needs
- Built microservices architecture
- Optimized API response times by 60%

Web Developer | Local Agency | 2018-2019
- Developed custom web applications for clients
- Maintained and updated existing PHP applications
- Worked directly with clients to gather requirements

SKILLS
- PHP, Laravel Framework
- Node.js, Express.js
- MongoDB, Redis
- RESTful API Design
- Microservices Architecture
- Linux Server Administration"
            ],
        ];
    }
}




