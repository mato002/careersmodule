<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'name' => 'Nakuru Branch',
                'address_line1' => 'Fortress Lenders Hse',
                'address_line2' => 'Barnabas Muguga Opp. Epic ridge Academy',
                'city' => 'Nakuru',
                'phone_primary' => '+254 743 838 312',
                'accent_color' => 'teal',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Gilgil Branch',
                'address_line1' => 'Makutano House',
                'address_line2' => 'Safaricom Shop',
                'city' => 'Gilgil',
                'phone_primary' => '+254 743 451 983',
                'phone_secondary' => '+254 797 628 301',
                'accent_color' => 'amber',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Olkalou Branch',
                'address_line1' => 'Tower Sacco',
                'address_line2' => '1st Floor Right Wing',
                'city' => 'Olkalou',
                'phone_primary' => '+254 714 429 675',
                'accent_color' => 'teal',
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Nyahururu Branch',
                'address_line1' => 'Mbaria Complex',
                'address_line2' => '2nd Floor',
                'city' => 'Nyahururu',
                'phone_primary' => '+254 792 640 802',
                'accent_color' => 'green',
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Rumuruti Branch',
                'address_line1' => 'Home Business Plaza',
                'city' => 'Rumuruti',
                'phone_primary' => '+254 715 110 163',
                'accent_color' => 'purple',
                'display_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($branches as $branchData) {
            Branch::updateOrCreate(
                ['name' => $branchData['name']],
                $branchData
            );
        }
    }
}

