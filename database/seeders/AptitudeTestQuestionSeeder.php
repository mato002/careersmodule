<?php

namespace Database\Seeders;

use App\Models\AptitudeTestQuestion;
use App\Models\User;
use Illuminate\Database\Seeder;

class AptitudeTestQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        $questions = [
            // Numerical Questions (10)
            [
                'section' => 'numerical',
                'question' => 'A client borrows KES 50,000 at 10% simple interest per year for 2 years. How much interest will they pay in total?',
                'options' => ['a' => 'KES 5,000', 'b' => 'KES 10,000', 'c' => 'KES 8,000', 'd' => 'KES 12,000'],
                'correct_answer' => 'b',
                'points' => 4,
                'explanation' => 'Simple interest = Principal × Rate × Time = 50,000 × 0.10 × 2 = 10,000',
                'display_order' => 1,
            ],
            [
                'section' => 'numerical',
                'question' => 'If a loan of KES 100,000 is to be repaid in 12 equal monthly installments of KES 9,000 each, what is the total amount to be repaid?',
                'options' => ['a' => 'KES 108,000', 'b' => 'KES 100,000', 'c' => 'KES 120,000', 'd' => 'KES 90,000'],
                'correct_answer' => 'a',
                'points' => 4,
                'explanation' => 'Total repayment = 12 × 9,000 = 108,000',
                'display_order' => 2,
            ],
            [
                'section' => 'numerical',
                'question' => 'A customer pays 15% of their loan as down payment. If the loan amount is KES 200,000, how much is the down payment?',
                'options' => ['a' => 'KES 15,000', 'b' => 'KES 30,000', 'c' => 'KES 20,000', 'd' => 'KES 25,000'],
                'correct_answer' => 'b',
                'points' => 4,
                'explanation' => 'Down payment = 200,000 × 0.15 = 30,000',
                'display_order' => 3,
            ],
            [
                'section' => 'numerical',
                'question' => 'If 3 customers each borrow KES 25,000, what is the total loan amount?',
                'options' => ['a' => 'KES 50,000', 'b' => 'KES 75,000', 'c' => 'KES 100,000', 'd' => 'KES 25,000'],
                'correct_answer' => 'b',
                'points' => 4,
                'explanation' => 'Total = 3 × 25,000 = 75,000',
                'display_order' => 4,
            ],
            [
                'section' => 'numerical',
                'question' => 'A loan of KES 80,000 earns 8% interest. What is the interest amount?',
                'options' => ['a' => 'KES 6,400', 'b' => 'KES 8,000', 'c' => 'KES 6,800', 'd' => 'KES 7,200'],
                'correct_answer' => 'a',
                'points' => 4,
                'explanation' => 'Interest = 80,000 × 0.08 = 6,400',
                'display_order' => 5,
            ],
            [
                'section' => 'numerical',
                'question' => 'If a customer repays KES 5,000 per month for 6 months, what is the total amount repaid?',
                'options' => ['a' => 'KES 25,000', 'b' => 'KES 30,000', 'c' => 'KES 35,000', 'd' => 'KES 40,000'],
                'correct_answer' => 'b',
                'points' => 4,
                'explanation' => 'Total = 5,000 × 6 = 30,000',
                'display_order' => 6,
            ],
            [
                'section' => 'numerical',
                'question' => 'What is 25% of KES 120,000?',
                'options' => ['a' => 'KES 30,000', 'b' => 'KES 25,000', 'c' => 'KES 35,000', 'd' => 'KES 40,000'],
                'correct_answer' => 'a',
                'points' => 4,
                'explanation' => '25% of 120,000 = 120,000 × 0.25 = 30,000',
                'display_order' => 7,
            ],
            [
                'section' => 'numerical',
                'question' => 'A customer borrows KES 60,000 and repays KES 72,000. What is the interest amount?',
                'options' => ['a' => 'KES 10,000', 'b' => 'KES 12,000', 'c' => 'KES 15,000', 'd' => 'KES 18,000'],
                'correct_answer' => 'b',
                'points' => 4,
                'explanation' => 'Interest = 72,000 - 60,000 = 12,000',
                'display_order' => 8,
            ],
            [
                'section' => 'numerical',
                'question' => 'If 5% of a loan amount is KES 2,500, what is the total loan amount?',
                'options' => ['a' => 'KES 50,000', 'b' => 'KES 45,000', 'c' => 'KES 55,000', 'd' => 'KES 60,000'],
                'correct_answer' => 'a',
                'points' => 4,
                'explanation' => 'If 5% = 2,500, then 100% = 2,500 ÷ 0.05 = 50,000',
                'display_order' => 9,
            ],
            [
                'section' => 'numerical',
                'question' => 'A customer pays KES 1,500 interest on a KES 30,000 loan. What is the interest rate?',
                'options' => ['a' => '3%', 'b' => '5%', 'c' => '7%', 'd' => '10%'],
                'correct_answer' => 'b',
                'points' => 4,
                'explanation' => 'Rate = (1,500 ÷ 30,000) × 100 = 5%',
                'display_order' => 10,
            ],

            // Logical Questions (6)
            [
                'section' => 'logical',
                'question' => 'All Fortress loan officers must have at least 2 years\' experience. Jane has 3 years\' experience in banking. Which statement is true?',
                'options' => ['a' => 'Jane cannot be a loan officer at Fortress', 'b' => 'Jane meets the experience requirement', 'c' => 'Jane must have a finance degree', 'd' => 'Jane is automatically a manager'],
                'correct_answer' => 'b',
                'points' => 4,
                'explanation' => 'Jane has 3 years which exceeds the 2-year requirement, so she meets the requirement.',
                'display_order' => 1,
            ],
            [
                'section' => 'logical',
                'question' => 'If all loans above KES 100,000 require manager approval, and a customer applies for KES 150,000, what must happen?',
                'options' => ['a' => 'The loan is automatically approved', 'b' => 'Manager approval is required', 'c' => 'The loan is automatically rejected', 'd' => 'The customer must apply for less'],
                'correct_answer' => 'b',
                'points' => 4,
                'explanation' => 'Since 150,000 is above 100,000, manager approval is required.',
                'display_order' => 2,
            ],
            [
                'section' => 'logical',
                'question' => 'Look at this sequence: 5, 10, 15, 20, ? What comes next?',
                'options' => ['a' => '22', 'b' => '25', 'c' => '30', 'd' => '35'],
                'correct_answer' => 'b',
                'points' => 4,
                'explanation' => 'The sequence increases by 5 each time: 5, 10, 15, 20, 25',
                'display_order' => 3,
            ],
            [
                'section' => 'logical',
                'question' => 'If Monday is day 1, and a loan application takes 5 business days to process, when will an application submitted on Monday be ready?',
                'options' => ['a' => 'Tuesday', 'b' => 'Wednesday', 'c' => 'Friday', 'd' => 'Next Monday'],
                'correct_answer' => 'c',
                'points' => 4,
                'explanation' => 'Monday (day 1), Tuesday (2), Wednesday (3), Thursday (4), Friday (5) - ready on Friday',
                'display_order' => 4,
            ],
            [
                'section' => 'logical',
                'question' => 'All customers with credit scores below 600 are high risk. Customer A has a score of 550. What can we conclude?',
                'options' => ['a' => 'Customer A is low risk', 'b' => 'Customer A is high risk', 'c' => 'Customer A needs more information', 'd' => 'Customer A is automatically approved'],
                'correct_answer' => 'b',
                'points' => 4,
                'explanation' => 'Since 550 is below 600, Customer A is classified as high risk.',
                'display_order' => 5,
            ],
            [
                'section' => 'logical',
                'question' => 'If every loan application requires ID verification, and John submits an application, what must happen?',
                'options' => ['a' => 'John\'s ID may or may not be verified', 'b' => 'John\'s ID must be verified', 'c' => 'John doesn\'t need ID', 'd' => 'John\'s application is automatically approved'],
                'correct_answer' => 'b',
                'points' => 4,
                'explanation' => 'Since every application requires ID verification, John\'s ID must be verified.',
                'display_order' => 6,
            ],

            // Verbal Questions (5)
            [
                'section' => 'verbal',
                'question' => 'Read the passage: "Fortress Lenders offers competitive interest rates and flexible repayment terms. Our loans are designed to help customers achieve their financial goals. We prioritize customer service and transparency in all our dealings." What is the main focus of Fortress Lenders?',
                'options' => ['a' => 'Only offering low interest rates', 'b' => 'Helping customers achieve financial goals with competitive rates and good service', 'c' => 'Making profits only', 'd' => 'Offering loans to everyone'],
                'correct_answer' => 'b',
                'points' => 4,
                'explanation' => 'The passage emphasizes competitive rates, flexible terms, helping customers, and good service.',
                'display_order' => 1,
            ],
            [
                'section' => 'verbal',
                'question' => 'Based on the passage: "All loan applications are reviewed within 48 hours. Approved applications receive funds within 5 business days." If someone applies on Monday, when is the earliest they could receive funds?',
                'options' => ['a' => 'Tuesday', 'b' => 'Wednesday', 'c' => 'Friday', 'd' => 'Next Monday'],
                'correct_answer' => 'd',
                'points' => 4,
                'explanation' => 'Review by Wednesday (48 hours), then 5 business days from approval = next Monday earliest.',
                'display_order' => 2,
            ],
            [
                'section' => 'verbal',
                'question' => 'Which word best describes a person who carefully reviews loan applications?',
                'options' => ['a' => 'Careless', 'b' => 'Diligent', 'c' => 'Hasty', 'd' => 'Indifferent'],
                'correct_answer' => 'b',
                'points' => 4,
                'explanation' => 'Diligent means careful and thorough, which is appropriate for reviewing applications.',
                'display_order' => 3,
            ],
            [
                'section' => 'verbal',
                'question' => 'What does "transparency" mean in a business context?',
                'options' => ['a' => 'Hiding information', 'b' => 'Being open and clear about processes and terms', 'c' => 'Making things complicated', 'd' => 'Avoiding customer contact'],
                'correct_answer' => 'b',
                'points' => 4,
                'explanation' => 'Transparency means being open, honest, and clear about business practices.',
                'display_order' => 4,
            ],
            [
                'section' => 'verbal',
                'question' => 'If a document states "All applicants must provide proof of income," what does this mean?',
                'options' => ['a' => 'Proof of income is optional', 'b' => 'Only some applicants need proof of income', 'c' => 'Every applicant must provide proof of income', 'd' => 'Proof of income is not required'],
                'correct_answer' => 'c',
                'points' => 4,
                'explanation' => 'The word "must" indicates a requirement for all applicants.',
                'display_order' => 5,
            ],

            // Scenario Questions (4)
            [
                'section' => 'scenario',
                'question' => 'A customer is upset because their loan was declined and is raising their voice in the branch. What is the best first response?',
                'options' => ['a' => 'Ask them to lower their voice or leave', 'b' => 'Tell them the system made the decision, not you', 'c' => 'Calmly invite them to a private area and listen to their concerns', 'd' => 'Ignore them until they calm down'],
                'correct_answer' => 'c',
                'points' => 4,
                'explanation' => 'The best approach is to de-escalate by moving to a private area and showing empathy by listening.',
                'display_order' => 1,
            ],
            [
                'section' => 'scenario',
                'question' => 'You have two customers waiting: one has been waiting 10 minutes for a simple inquiry, and another just arrived for a loan application. What should you do?',
                'options' => ['a' => 'Help the loan applicant first because loans are more important', 'b' => 'Help the customer who arrived first (first come, first served)', 'c' => 'Ask both to wait while you take a break', 'd' => 'Help whoever is more important'],
                'correct_answer' => 'b',
                'points' => 4,
                'explanation' => 'Fair service means first come, first served, unless there\'s a clear priority system in place.',
                'display_order' => 2,
            ],
            [
                'section' => 'scenario',
                'question' => 'A customer calls saying they can\'t make their loan payment this month due to an emergency. What is the best response?',
                'options' => ['a' => 'Tell them they must pay or face consequences', 'b' => 'Listen to their situation, explain options like payment plans, and document the conversation', 'c' => 'Ignore the call', 'd' => 'Automatically extend their loan'],
                'correct_answer' => 'b',
                'points' => 4,
                'explanation' => 'Best practice is to listen, show empathy, explore solutions, and document for follow-up.',
                'display_order' => 3,
            ],
            [
                'section' => 'scenario',
                'question' => 'You notice a colleague making errors in loan calculations that could cost the company money. What should you do?',
                'options' => ['a' => 'Ignore it, it\'s not your problem', 'b' => 'Report it immediately to a supervisor', 'c' => 'Confront the colleague publicly', 'd' => 'Wait and see if it happens again'],
                'correct_answer' => 'b',
                'points' => 4,
                'explanation' => 'Financial errors should be reported to supervisors promptly to prevent losses and ensure accuracy.',
                'display_order' => 4,
            ],
        ];

        foreach ($questions as $question) {
            AptitudeTestQuestion::create([
                ...$question,
                'is_active' => true,
                'created_by' => $admin?->id,
            ]);
        }
    }
}

