<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'title' => 'Individual Loans',
                'category' => 'Personal Finance',
                'summary' => 'Personal loans designed to meet individual financial needs with flexible repayment terms and competitive interest rates.',
                'description' => 'Flexible repayment terms, quick approval process and competitive rates tailored to salaried and self-employed clients.',
                'highlight_color' => 'teal',
                'cta_label' => 'Apply Today',
                'display_order' => 1,
            ],
            [
                'title' => 'Group Loans',
                'category' => 'Group Lending',
                'summary' => 'Collective financing solutions for groups and associations, enabling members to access funds for joint ventures.',
                'description' => 'Group accountability with higher loan amounts and shared responsibility designed for chamas and welfare groups.',
                'highlight_color' => 'blue',
                'cta_label' => 'Start Group Application',
                'display_order' => 2,
            ],
            [
                'title' => 'Agricultural Loans',
                'category' => 'Agri-business',
                'summary' => 'Specialized financing for farmers and agribusinesses to boost productivity and expand operations throughout the crop cycle.',
                'description' => 'Seasonal payment plans, equipment financing and crop-cycle aligned support for agribusiness growth.',
                'highlight_color' => 'green',
                'cta_label' => 'Talk to Agri Team',
                'display_order' => 3,
            ],
            [
                'title' => 'Education Loans',
                'category' => 'Education',
                'summary' => 'Flexible education financing solutions for students and families covering tuition, supplies and accommodation.',
                'description' => 'Flexible repayment, post-graduation options and affordable rates to keep learners in school.',
                'highlight_color' => 'purple',
                'display_order' => 4,
            ],
            [
                'title' => 'Emergency Loans',
                'category' => 'Emergency',
                'summary' => 'Quick access to funds during unexpected financial emergencies with fast approval and disbursement.',
                'description' => 'Fast approval, rapid disbursement and minimal documentation to manage urgent needs.',
                'highlight_color' => 'yellow',
                'cta_label' => 'Get Help Now',
                'display_order' => 5,
            ],
            [
                'title' => 'Asset Finance',
                'category' => 'Business Growth',
                'summary' => 'Finance vehicles, equipment and machinery to grow your operations with manageable payment plans.',
                'description' => 'Asset backed financing with flexible terms, competitive rates and support for expansion assets.',
                'highlight_color' => 'indigo',
                'display_order' => 6,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => Str::slug($product['title'])],
                array_merge(
                    $product,
                    [
                        'slug' => Str::slug($product['title']),
                        'is_active' => true,
                    ]
                )
            );
        }
    }
}
