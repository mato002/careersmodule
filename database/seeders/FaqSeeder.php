<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Who can qualify for a loan with Fortress Lenders Ltd?',
                'answer' => "We serve formally employed, self‑employed and business owners with a verifiable and consistent source of income.\n\nEligibility is assessed based on your income, existing commitments and ability to repay comfortably.",
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'What documents do I need to apply for a loan?',
                'answer' => "Typical documentation includes a copy of your national ID, KRA PIN, recent payslips or bank statements and proof of residence.\n\nDepending on the product, we may also request additional documents such as business registration certificates or security documentation.",
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'How long does loan approval and disbursement take?',
                'answer' => "Once we receive all the required documentation, most applications are processed within 24–48 business hours.\n\nYou will receive an SMS or call from our team once your application has been approved and is ready for disbursement.",
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'Can I repay my loan early without penalties?',
                'answer' => "Yes. Early repayment is allowed on most of our products and helps you save on interest.\n\nTalk to your relationship officer so we can guide you through the early settlement process for your specific facility.",
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'question' => 'Where are your branches located?',
                'answer' => "We currently serve customers through branches in Nakuru, Gilgil, Olkalou, Nyahururu and Rumuruti.\n\nYou can also start your application online and our team will direct you to the nearest branch for completion.",
                'display_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }
    }
}



