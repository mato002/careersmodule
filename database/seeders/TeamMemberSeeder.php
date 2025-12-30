<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = [
            [
                'name' => 'James Ndegwa',
                'role' => 'Executive Director',
                'bio' => 'An astute, result oriented and widely experienced businessman well entrenched in business strategy and customer relations. Holds a BSc. in Actuarial Science from JKUAT and an Advanced Diploma in Hardware & Software Engineering from City & Guilds, UK.',
                'email' => 'james@fortresslenders.com',
                'phone' => null,
                'display_order' => 1,
            ],
            [
                'name' => 'Ann Wairimu',
                'role' => 'Human Resource',
                'bio' => 'In charge of the human resource department. Enhances the organization\'s human resources by planning, implementing, and evaluating employee relations and HR policies, programs, and practices. Holds a Bachelor\'s degree in Mass Communication from Daystar University and an MBA from Kenyatta University.',
                'email' => 'ann@fortresslenders.com',
                'phone' => null,
                'display_order' => 2,
            ],
            [
                'name' => 'Allan Libese',
                'role' => 'Head of ICT',
                'bio' => 'ICT Manager with vast experience in financial services and core banking systems. Supports the achievement of Fortress strategic and operational objectives through provision of high quality ICT infrastructure, systems, procedures and solutions. Holds a BSc in Information Technology from Karatina University.',
                'email' => 'allan@fortresslenders.com',
                'phone' => null,
                'display_order' => 3,
            ],
        ];

        foreach ($members as $member) {
            TeamMember::updateOrCreate(
                ['email' => $member['email']],
                array_merge($member, ['is_active' => true])
            );
        }
    }
}



