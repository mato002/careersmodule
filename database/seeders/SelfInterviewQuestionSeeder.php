<?php

namespace Database\Seeders;

use App\Models\JobPost;
use App\Models\SelfInterviewQuestion;
use Illuminate\Database\Seeder;

class SelfInterviewQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Map job titles to a few tailored self‑interview questions
        $jobSpecificQuestions = [
            'Loan Officer' => [
                [
                    'question' => 'Describe a time when you helped a client structure a loan that was both affordable for them and profitable for the institution.',
                    'options' => null,
                    'correct_answer' => null,
                    'points' => 5,
                ],
                [
                    'question' => 'When assessing a new loan application, which factor do you consider most critical?',
                    'options' => [
                        'a' => 'Client\'s character and repayment history',
                        'b' => 'Collateral value only',
                        'c' => 'Size of the loan requested',
                        'd' => 'Interest rate charged',
                    ],
                    'correct_answer' => 'a',
                    'points' => 3,
                ],
            ],
            'Customer Service Representative' => [
                [
                    'question' => 'How would you handle a frustrated customer who feels their loan was unfairly declined?',
                    'options' => null,
                    'correct_answer' => null,
                    'points' => 5,
                ],
                [
                    'question' => 'What is the most important skill for a customer service representative at a microfinance institution?',
                    'options' => [
                        'a' => 'Ability to sell as many products as possible',
                        'b' => 'Listening and explaining products in simple language',
                        'c' => 'Using complex financial terminology',
                        'd' => 'Working alone without a team',
                    ],
                    'correct_answer' => 'b',
                    'points' => 3,
                ],
            ],
        ];

        // Global questions that apply to any job
        $globalQuestions = [
            [
                'question' => 'Why do you want to work at Fortress Lenders and what makes you a good fit for our culture?',
                'options' => null,
                'correct_answer' => null,
                'points' => 5,
            ],
            [
                'question' => 'How do you stay organized when handling multiple tasks with tight deadlines?',
                'options' => null,
                'correct_answer' => null,
                'points' => 4,
            ],
            [
                'question' => 'Which of the following best describes your approach to teamwork?',
                'options' => [
                    'a' => 'I prefer to work alone and avoid collaboration.',
                    'b' => 'I only help when my tasks are finished.',
                    'c' => 'I communicate openly and support my teammates when needed.',
                    'd' => 'I let others make all the decisions.',
                ],
                'correct_answer' => 'c',
                'points' => 3,
            ],
        ];

        // Seed global questions
        foreach ($globalQuestions as $index => $data) {
            SelfInterviewQuestion::updateOrCreate(
                [
                    'job_post_id' => null,
                    'question' => $data['question'],
                ],
                [
                    'options' => $data['options'],
                    'correct_answer' => $data['correct_answer'],
                    'points' => $data['points'],
                    'display_order' => $index,
                    'is_active' => true,
                ]
            );
        }

        // Seed job‑specific questions
        foreach ($jobSpecificQuestions as $jobTitle => $questions) {
            $job = JobPost::where('title', $jobTitle)->first();
            if (! $job) {
                continue;
            }

            foreach ($questions as $index => $data) {
                SelfInterviewQuestion::updateOrCreate(
                    [
                        'job_post_id' => $job->id,
                        'question' => $data['question'],
                    ],
                    [
                        'options' => $data['options'],
                        'correct_answer' => $data['correct_answer'],
                        'points' => $data['points'],
                        'display_order' => $index,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}


