<?php

namespace Database\Seeders;

use App\Models\JobPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = [
            [
                'title' => 'Senior Software Developer',
                'description' => 'We are looking for an experienced Software Developer to join our technology team. You will be responsible for developing and maintaining web applications, working with modern technologies, and collaborating with cross-functional teams to deliver high-quality software solutions.',
                'requirements' => '• Bachelor\'s degree in Computer Science, Software Engineering, or related field
• Minimum 4 years of experience in web development
• Strong proficiency in PHP (Laravel framework) and JavaScript
• Experience with frontend frameworks (Vue.js, React, or similar)
• Knowledge of database design and optimization (MySQL, PostgreSQL)
• Experience with RESTful APIs and microservices architecture
• Familiarity with version control systems (Git)
• Strong problem-solving and debugging skills
• Excellent communication and teamwork abilities',
                'responsibilities' => '• Develop and maintain web applications using Laravel and modern JavaScript frameworks
• Write clean, maintainable, and well-documented code
• Collaborate with designers, product managers, and other developers
• Participate in code reviews and maintain coding standards
• Debug and fix issues in existing applications
• Optimize application performance and scalability
• Stay updated with latest technologies and best practices
• Mentor junior developers when needed',
                'location' => 'Nairobi',
                'department' => 'Technology',
                'employment_type' => 'full-time',
                'experience_level' => 'Senior Level',
                'application_deadline' => now()->addDays(30),
                'is_active' => true,
            ],
            [
                'title' => 'HR Manager',
                'description' => 'Join our Human Resources team as an HR Manager. You will oversee recruitment processes, manage employee relations, develop HR policies, and ensure compliance with labor laws. This role is perfect for someone passionate about talent acquisition and employee development.',
                'requirements' => '• Bachelor\'s degree in Human Resources, Business Administration, or related field
• Minimum 5 years of HR experience, with at least 2 years in a management role
• Strong knowledge of HR best practices and labor laws
• Experience with recruitment and talent acquisition
• Excellent interpersonal and communication skills
• Strong organizational and problem-solving abilities
• Proficiency in HRIS systems
• Professional HR certification (CHRP, SHRM, or equivalent) preferred
• Ability to handle confidential information with discretion',
                'responsibilities' => '• Develop and implement HR strategies and initiatives aligned with business objectives
• Manage the recruitment and selection process
• Oversee employee onboarding and offboarding processes
• Handle employee relations and resolve conflicts
• Develop and update HR policies and procedures
• Ensure compliance with labor laws and regulations
• Manage performance appraisal systems
• Coordinate training and development programs
• Maintain employee records and HR databases
• Prepare HR reports and analytics for management',
                'location' => 'Nairobi',
                'department' => 'Human Resources',
                'employment_type' => 'full-time',
                'experience_level' => 'Senior Level',
                'application_deadline' => now()->addDays(35),
                'is_active' => true,
            ],
        ];

        foreach ($jobs as $job) {
            JobPost::updateOrCreate(
                ['slug' => Str::slug($job['title'])],
                array_merge(
                    $job,
                    [
                        'slug' => Str::slug($job['title']),
                    ]
                )
            );
        }

        $this->command->info('Created ' . count($jobs) . ' job posts successfully!');
    }
}
