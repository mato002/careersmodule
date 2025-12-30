<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TeamMemberController extends Controller
{
    public function index(Request $request): View
    {
        $query = TeamMember::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('is_active') && $request->string('is_active') !== 'all') {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $totalTeamMembersCount = TeamMember::count();
        $activeTeamMembersCount = TeamMember::where('is_active', true)->count();
        $hiddenTeamMembersCount = TeamMember::where('is_active', false)->count();
        $filteredTeamMembersCount = $query->count();

        $teamMembers = $query->orderBy('display_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.team-members.index', compact('teamMembers', 'totalTeamMembersCount', 'activeTeamMembersCount', 'hiddenTeamMembersCount', 'filteredTeamMembersCount'));
    }

    public function create(): View
    {
        $teamMember = new TeamMember([
            'is_active' => true,
            'display_order' => TeamMember::count() + 1,
        ]);

        return view('admin.team-members.create', compact('teamMember'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $teamMember = TeamMember::create($data);
        $this->handlePhotoUpload($request, $teamMember);

        return redirect()->route('admin.team-members.index')->with('status', 'Team member added.');
    }

    public function show(TeamMember $teamMember): View
    {
        return view('admin.team-members.show', compact('teamMember'));
    }

    public function edit(TeamMember $teamMember): View
    {
        return view('admin.team-members.edit', compact('teamMember'));
    }

    public function update(Request $request, TeamMember $teamMember): RedirectResponse
    {
        $data = $this->validatedData($request, $teamMember);
        $teamMember->update($data);
        $this->handlePhotoUpload($request, $teamMember);

        return redirect()->route('admin.team-members.index')->with('status', 'Team member updated.');
    }

    public function destroy(TeamMember $teamMember): RedirectResponse
    {
        if ($teamMember->photo_path) {
            Storage::disk('public')->delete($teamMember->photo_path);
        }

        $teamMember->delete();

        return back()->with('status', 'Team member removed.');
    }

    protected function validatedData(Request $request, ?TeamMember $teamMember = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'bio' => ['nullable', 'string'],
            'display_order' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['sometimes', 'boolean'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['display_order'] = $validated['display_order'] ?? ($teamMember?->display_order ?? TeamMember::count() + 1);

        return $validated;
    }

    protected function handlePhotoUpload(Request $request, TeamMember $teamMember): void
    {
        if (! $request->hasFile('photo')) {
            return;
        }

        if ($teamMember->photo_path) {
            Storage::disk('public')->delete($teamMember->photo_path);
        }

        $path = $request->file('photo')->store('team', 'public');
        $teamMember->update(['photo_path' => $path]);
    }
}







