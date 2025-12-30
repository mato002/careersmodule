<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prefer the seeded admin user as the author
        $author = User::where('is_admin', true)->first() ?? User::first();

        if (! $author) {
            return;
        }

        $posts = [
            [
                'title' => 'Welcome to Fortress Lenders Ltd',
                'excerpt' => 'Learn who we are, what we do and how we support individuals, groups and businesses with accessible credit solutions.',
                'content' => <<<HTML
<p>Fortress Lenders Ltd is a licensed credit-only microfinance institution dedicated to providing fast, flexible and responsible lending solutions to our customers.</p>

<p>We understand that access to finance is critical for growth &mdash; whether you are running a business, managing a farm, supporting your family or pursuing education. Our products are tailored to meet real needs on the ground.</p>

<ul>
    <li>Flexible repayment terms aligned to your cash flows</li>
    <li>Transparent pricing with no hidden charges</li>
    <li>Dedicated relationship officers at every branch</li>
</ul>

<p>We are committed to responsible lending, customer education and long‑term partnerships with our clients.</p>
HTML,
                'days_ago' => 7,
            ],
            [
                'title' => 'Simple Steps to Apply for a Loan with Fortress',
                'excerpt' => 'A quick guide on how to start and complete your loan application with Fortress Lenders Ltd.',
                'content' => <<<HTML
<p>Applying for a loan with Fortress Lenders Ltd is simple and transparent. You can start online or visit any of our branches.</p>

<ol>
    <li>Choose the loan product that best fits your needs.</li>
    <li>Submit your basic details and supporting documents.</li>
    <li>Our team reviews your application and provides feedback.</li>
    <li>Once approved, you sign the offer letter and funds are disbursed.</li>
</ol>

<p>If you are unsure which product is best for you, our team will gladly walk you through the options and recommend a suitable solution.</p>
HTML,
                'days_ago' => 5,
            ],
            [
                'title' => 'Managing Your Loan Responsibly',
                'excerpt' => 'Practical tips to help you stay on top of your repayments and maintain a healthy credit relationship.',
                'content' => <<<HTML
<p>At Fortress Lenders Ltd, we believe that responsible borrowing leads to long‑term financial stability.</p>

<p>Here are a few tips to help you manage your facility:</p>
<ul>
    <li>Borrow only what you can comfortably repay from your income.</li>
    <li>Set reminders for repayment dates to avoid penalties.</li>
    <li>Talk to us early if you anticipate challenges in meeting your obligations.</li>
</ul>

<p>Our relationship officers are available to support you throughout the life of your loan.</p>
HTML,
                'days_ago' => 3,
            ],
            [
                'title' => 'Fortress Branch Network and Customer Support',
                'excerpt' => 'An overview of our growing branch network and how to get help when you need it.',
                'content' => <<<HTML
<p>We are steadily expanding our footprint to bring services closer to you. Our branches are currently located in Nakuru, Gilgil, Olkalou, Nyahururu and Rumuruti.</p>

<p>Each branch is staffed with experienced officers who understand the local market and are ready to support you through the entire loan journey.</p>

<p>You can also reach us via phone, email or by sending a message through our contact page on this website.</p>
HTML,
                'days_ago' => 2,
            ],
        ];

        foreach ($posts as $postData) {
            $publishedAt = now()->subDays($postData['days_ago']);

            Post::updateOrCreate(
                ['slug' => Str::slug($postData['title'])],
                [
                    'title' => $postData['title'],
                    'slug' => Str::slug($postData['title']),
                    'excerpt' => $postData['excerpt'],
                    'content' => $postData['content'],
                    'featured_image_path' => null,
                    'published_at' => $publishedAt,
                    'is_published' => true,
                    'author_id' => $author->id,
                ]
            );
        }
    }
}



