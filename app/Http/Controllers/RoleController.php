<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->orderBy('name')->paginate(15);

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('slug')->get()->groupBy(fn (Permission $permission) => strtok($permission->slug, '.'));

        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:roles,slug'],
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $permissionIds = $validated['permissions'] ?? [];
        unset($validated['permissions']);

        $role = Role::create($validated);
        $role->permissions()->sync($permissionIds);

        ActivityLog::logAction('created', $role, null, $validated + ['permissions' => $permissionIds]);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('slug')->get()->groupBy(fn (Permission $permission) => strtok($permission->slug, '.'));
        $assignedPermissions = $role->permissions()->pluck('permissions.id')->all();

        return view('roles.edit', compact('role', 'permissions', 'assignedPermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $slugRule = $role->isSystemRole()
            ? ['required', Rule::in([$role->slug])]
            : ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('roles', 'slug')->ignore($role->id)];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($role->id)],
            'slug' => $slugRule,
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $permissionIds = $validated['permissions'] ?? [];
        unset($validated['permissions']);

        $oldValues = $role->load('permissions')->toArray();

        $role->update($validated);
        $role->permissions()->sync($permissionIds);

        if ($role->users()->exists()) {
            $role->users()->update(['role' => $role->slug]);
        }

        ActivityLog::logAction('updated', $role, $oldValues, $validated + ['permissions' => $permissionIds]);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($role->isSystemRole()) {
            return redirect()->route('roles.index')->with('error', 'System roles cannot be deleted.');
        }

        if ($role->users()->exists()) {
            return redirect()->route('roles.index')->with('error', 'Cannot delete a role that is assigned to users.');
        }

        $oldValues = $role->load('permissions')->toArray();
        ActivityLog::logAction('deleted', $role, $oldValues);

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
