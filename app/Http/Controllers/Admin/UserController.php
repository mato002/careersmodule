<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role') && $request->string('role') !== 'all') {
            $query->where('role', $request->string('role'));
        }

        // Admin filter
        if ($request->filled('is_admin') && $request->string('is_admin') !== 'all') {
            $query->where('is_admin', $request->boolean('is_admin'));
        }

        $totalUsersCount = User::count();
        $adminUsersCount = User::where('is_admin', true)->orWhere('role', 'admin')->count();
        $regularUsersCount = User::where('is_admin', false)->where('role', '!=', 'admin')->count();
        $filteredUsersCount = $query->count();

        $users = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $roles = User::getRoles();

        return view('admin.users.index', compact(
            'users',
            'totalUsersCount',
            'adminUsersCount',
            'regularUsersCount',
            'filteredUsersCount',
            'roles'
        ));
    }

    public function create(): View
    {
        $user = new User([
            'role' => 'user',
            'is_admin' => false,
        ]);

        $roles = User::getRoles();

        return view('admin.users.create', compact('user', 'roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        
        // Hash password
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Set is_admin based on role for backward compatibility
        if (isset($data['role'])) {
            $data['is_admin'] = $data['role'] === 'admin';
        }

        $user = User::create($data);

        // If creating a candidate user, link any existing job applications by email
        if (($user->role === 'candidate' || $user->role === 'user') && !empty($user->email)) {
            \App\Models\JobApplication::where('email', $user->email)
                ->whereNull('user_id')
                ->update(['user_id' => $user->id]);
        }

        return redirect()->route('admin.users.index')->with('status', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $roles = User::getRoles();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $oldEmail = $user->email;
        $data = $this->validatedData($request, $user);

        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Set is_admin based on role for backward compatibility
        if (isset($data['role'])) {
            $data['is_admin'] = $data['role'] === 'admin';
        }

        // Prevent user from removing their own admin access
        if ($request->user()->id === $user->id && isset($data['role']) && $data['role'] !== 'admin') {
            return back()->withErrors(['role' => 'You cannot remove your own admin access.']);
        }

        $user->update($data);

        // If user is a candidate and email changed or role changed to candidate, link applications
        if (($user->role === 'candidate' || $user->role === 'user') && !empty($user->email)) {
            // Link applications with new email
            \App\Models\JobApplication::where('email', $user->email)
                ->whereNull('user_id')
                ->update(['user_id' => $user->id]);
            
            // If email changed, unlink old applications (optional - you might want to keep them linked)
            // Or you could link both old and new email applications
        }

        return redirect()->route('admin.users.index')->with('status', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        // Prevent deleting yourself
        if (auth()->id() === $user->id) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        $user->delete();

        return back()->with('status', 'User deleted successfully.');
    }

    protected function validatedData(Request $request, ?User $user = null): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email' . ($user ? ",{$user->id}" : '')],
            'role' => ['required', 'string', 'in:user,admin,hr_manager,editor,client'],
            'is_admin' => ['sometimes', 'boolean'],
        ];

        // Password is required when creating, optional when updating
        if ($user) {
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        } else {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        return $request->validate($rules);
    }
}

