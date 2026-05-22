<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    

    public function index(Request $request)
    {
        $query = User::with('department');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        if ($request->has('department') && $request->department) {
            $query->where('department_id', $request->department);
        }

        $users = $query->latest()->paginate(15);
        $departments = Department::all();
        $roles = User::ROLES;

        return view('users.index', compact(
            'users',
            'departments',
            'roles'
        ));
    }

    public function create()
    {
        $departments = Department::all();
        $roles = User::ROLES;
        return view('users.create', compact('departments', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'employee_id' => ['nullable', 'string', 'max:50'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'phone' => ['nullable', 'string', 'max:50'],
            'position' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'in:' . implode(',', User::ROLES)],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        ActivityLog::logAction('created', $user);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load('department', 'assignedAssets.category');
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $departments = Department::all();
        $roles = User::ROLES;
        return view('users.edit', compact('user', 'departments', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'employee_id' => ['nullable', 'string', 'max:50'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'phone' => ['nullable', 'string', 'max:50'],
            'position' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'in:' . implode(',', User::ROLES)],
        ]);

        $oldValues = $user->toArray();
        unset($oldValues['password']);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        ActivityLog::logAction('updated', $user, $oldValues, $validated);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        if ($user->assignedAssets()->count() > 0) {
            return redirect()->route('users.index')
                ->with('error', 'Cannot delete user with assigned assets.');
        }

        $oldValues = $user->toArray();

        ActivityLog::logAction('deleted', $user, $oldValues);

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function profile()
    {
        $user = auth()->user();
        $user->load('department', 'assignedAssets.category');
        return view('users.profile', compact('user'));
    }

    public function updateProfile(Request $request, User $user)
    {
        if ($user->id !== auth()->id()) {
            return redirect()->route('users.profile')
                ->with('error', 'You can only update your own profile.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'phone' => ['nullable', 'string', 'max:50'],
            'position' => ['nullable', 'string', 'max:255'],
        ]);

        $oldValues = $user->toArray();
        unset($oldValues['password']);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        ActivityLog::logAction('updated', $user, $oldValues, $validated);

        return redirect()->route('users.profile')
            ->with('success', 'Profile updated successfully.');
    }
}
