<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use App\Models\TeamMember;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $teamMembers = TeamMember::where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->limit(6)
            ->get();

        $generalSettings = GeneralSetting::query()->latest()->first();

        return view('home', compact('teamMembers', 'generalSettings'));
    }
}

