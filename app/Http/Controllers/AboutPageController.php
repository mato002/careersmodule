<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;

class AboutPageController extends Controller
{
    public function __invoke()
    {
        $teamMembers = TeamMember::where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('about', compact('teamMembers'));
    }
}


