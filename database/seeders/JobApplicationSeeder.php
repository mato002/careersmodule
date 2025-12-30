<?php

namespace Database\Seeders;

use App\Models\JobApplication;
use App\Models\JobPost;
use App\Services\AISievingService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first 2 active job posts
        $jobs = JobPost::query()
            ->where('is_active', true)
            ->take(2)
            ->get();

        if ($jobs->isEmpty()) {
            $this->command->warn('No active job posts found. Please run JobPostSeeder first.');
            return;
        }

        $sievingService = new AISievingService();

        foreach ($jobs as $job) {
            $this->command->info("Creating applications for: {$job->title}");

            // Strong candidates (likely to pass sieving) - 3 per job
            for ($i = 1; $i <= 3; $i++) {
                $application = JobApplication::create([
                    'job_post_id' => $job->id,
                    'name' => "Strong Candidate {$i} for {$job->title}",
                    'phone' => '+2547' . rand(10000000, 99999999),
                    'email' => "strong{$i}." . Str::slug($job->title) . '@example.com',
                    'why_interested' => "I am highly interested in the {$job->title} position. I have been following your company's growth and am impressed by your commitment to innovation and excellence. This role aligns perfectly with my career aspirations and I am excited about the opportunity to contribute to your team's success.",
                    'why_good_fit' => "I bring {$job->experience_level} experience in {$job->department} with a proven track record of success. My skills in " . $this->getRelevantSkills($job->department) . " make me an ideal candidate. I am a collaborative team player, detail-oriented, and committed to delivering high-quality results.",
                    'career_goals' => "My career goal is to grow within the organization, take on increasing responsibilities, and contribute to the company's strategic objectives. I am particularly interested in " . strtolower($job->department) . " and look forward to developing expertise in this area while making a meaningful impact.",
                    'salary_expectations' => 'Competitive salary based on market rates and my experience level',
                    'availability_date' => now()->addDays(rand(14, 30)),
                    'relevant_skills' => $this->getRelevantSkills($job->department),
                    'education_level' => "Bachelor's Degree",
                    'area_of_study' => $this->getAreaOfStudy($job->department),
                    'institution' => 'University of Nairobi',
                    'education_status' => 'Completed',
                    'education_start_year' => 2016,
                    'education_end_year' => 2020,
                    'other_achievements' => 'Dean\'s list, academic excellence awards, leadership roles in student organizations, published research papers.',
                    'work_experience' => [
                        [
                            'company' => $this->getCompanyName($job->department),
                            'role' => $job->title,
                            'start_date' => now()->subYears(rand(3, 5))->format('Y-m-d'),
                            'end_date' => now()->format('Y-m-d'),
                        ],
                    ],
                    'current_job_title' => $job->title,
                    'current_company' => $this->getCompanyName($job->department),
                    'currently_working' => true,
                    'duties_and_responsibilities' => $this->getDuties($job->title),
                    'other_experiences' => 'Volunteered in professional development programs, mentored junior professionals, participated in industry conferences and workshops.',
                    'support_details' => null,
                    'referrers' => [],
                    'notice_period' => '1 month',
                    'agreement_accepted' => true,
                    'application_message' => 'I am very excited about this opportunity and look forward to discussing how my experience and skills can contribute to your team\'s success. Thank you for considering my application.',
                    'status' => 'pending',
                ]);

                // Run AI sieving to simulate real flow
                try {
                    $sievingService->evaluate($application);
                } catch (\Exception $e) {
                    // If AI sieving fails, continue without it
                    $this->command->warn("AI sieving failed for application {$application->id}: " . $e->getMessage());
                }
            }

            // Average candidates (mixed results) - 2 per job
            for ($i = 1; $i <= 2; $i++) {
                $application = JobApplication::create([
                    'job_post_id' => $job->id,
                    'name' => "Average Candidate {$i} for {$job->title}",
                    'phone' => '+2547' . rand(10000000, 99999999),
                    'email' => "average{$i}." . Str::slug($job->title) . '@example.com',
                    'why_interested' => "I am interested in this position as it seems like a good opportunity to advance my career in {$job->department}. I believe I can learn and grow in this role.",
                    'why_good_fit' => "I have some experience in related fields and I am eager to learn. I am hardworking and willing to put in the effort to succeed.",
                    'career_goals' => "To develop my skills and advance in my career.",
                    'salary_expectations' => 'Market rate',
                    'availability_date' => now()->addDays(rand(30, 60)),
                    'relevant_skills' => 'Basic skills in ' . strtolower($job->department) . ', communication, teamwork',
                    'education_level' => "Bachelor's Degree",
                    'area_of_study' => $this->getAreaOfStudy($job->department),
                    'institution' => 'Kenyatta University',
                    'education_status' => 'Completed',
                    'education_start_year' => 2018,
                    'education_end_year' => 2022,
                    'other_achievements' => null,
                    'work_experience' => [
                        [
                            'company' => 'Local Company',
                            'role' => 'Junior ' . $job->title,
                            'start_date' => now()->subYears(1)->format('Y-m-d'),
                            'end_date' => now()->format('Y-m-d'),
                        ],
                    ],
                    'current_job_title' => 'Junior ' . $job->title,
                    'current_company' => 'Local Company',
                    'currently_working' => true,
                    'duties_and_responsibilities' => 'Assisted with various tasks and projects.',
                    'other_experiences' => null,
                    'support_details' => null,
                    'referrers' => [],
                    'notice_period' => '2 weeks',
                    'agreement_accepted' => true,
                    'application_message' => 'I hope to be considered for this position.',
                    'status' => 'pending',
                ]);

                // Run AI sieving to simulate real flow
                try {
                    $sievingService->evaluate($application);
                } catch (\Exception $e) {
                    // If AI sieving fails, continue without it
                    $this->command->warn("AI sieving failed for application {$application->id}: " . $e->getMessage());
                }
            }

            // Weak candidates (likely to be rejected) - 2 per job
            for ($i = 1; $i <= 2; $i++) {
                $application = JobApplication::create([
                    'job_post_id' => $job->id,
                    'name' => "Weak Candidate {$i} for {$job->title}",
                    'phone' => '+2547' . rand(10000000, 99999999),
                    'email' => "weak{$i}." . Str::slug($job->title) . '@example.com',
                    'why_interested' => 'I need a job.',
                    'why_good_fit' => 'I think I can do it.',
                    'career_goals' => 'To work somewhere.',
                    'salary_expectations' => 'Very high salary',
                    'availability_date' => now()->addDays(rand(90, 180)),
                    'relevant_skills' => 'Hard worker',
                    'education_level' => 'High School',
                    'area_of_study' => null,
                    'institution' => null,
                    'education_status' => 'Completed',
                    'education_start_year' => null,
                    'education_end_year' => null,
                    'other_achievements' => null,
                    'work_experience' => [],
                    'current_job_title' => null,
                    'current_company' => null,
                    'currently_working' => false,
                    'duties_and_responsibilities' => null,
                    'other_experiences' => null,
                    'support_details' => null,
                    'referrers' => [],
                    'notice_period' => null,
                    'agreement_accepted' => true,
                    'application_message' => 'Please hire me.',
                    'status' => 'pending',
                ]);

                // Run AI sieving to simulate real flow
                try {
                    $sievingService->evaluate($application);
                } catch (\Exception $e) {
                    // If AI sieving fails, continue without it
                    $this->command->warn("AI sieving failed for application {$application->id}: " . $e->getMessage());
                }
            }
        }

        $totalApplications = JobApplication::count();
        $this->command->info("Created applications successfully! Total applications: {$totalApplications}");
    }

    private function getRelevantSkills(string $department): string
    {
        $skills = [
            'Technology' => 'PHP, Laravel, JavaScript, Vue.js, MySQL, Git, RESTful APIs, system architecture',
            'Human Resources' => 'Recruitment, talent acquisition, employee relations, HR policies, labor law, HRIS systems, performance management',
            'Marketing' => 'Digital marketing, social media, content creation, SEO, analytics, campaign management',
            'Finance' => 'Financial analysis, accounting, budgeting, financial reporting, Excel, financial software',
        ];

        return $skills[$department] ?? 'Communication, teamwork, problem-solving, time management';
    }

    private function getAreaOfStudy(string $department): string
    {
        $studies = [
            'Technology' => 'Computer Science',
            'Human Resources' => 'Human Resources Management',
            'Marketing' => 'Marketing',
            'Finance' => 'Finance',
        ];

        return $studies[$department] ?? 'Business Administration';
    }

    private function getCompanyName(string $department): string
    {
        $companies = [
            'Technology' => 'Tech Solutions Ltd',
            'Human Resources' => 'HR Consulting Group',
            'Marketing' => 'Digital Marketing Agency',
            'Finance' => 'Financial Services Inc',
        ];

        return $companies[$department] ?? 'Previous Company';
    }

    private function getDuties(string $title): string
    {
        if (str_contains(strtolower($title), 'developer')) {
            return 'Developed and maintained web applications, collaborated with cross-functional teams, participated in code reviews, optimized application performance.';
        } elseif (str_contains(strtolower($title), 'hr') || str_contains(strtolower($title), 'human resources')) {
            return 'Managed recruitment processes, handled employee relations, developed HR policies, ensured compliance with labor laws, coordinated training programs.';
        }

        return 'Performed various duties related to the role, collaborated with team members, met performance targets.';
    }
}
