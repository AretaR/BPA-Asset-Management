<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::withCount('roles')->orderBy('slug')->paginate(20);

        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
            'slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9]+(\.[a-z0-9_]+)+$/', 'unique:permissions,slug'],
            'description' => ['nullable', 'string'],
        ]);

        $permission = Permission::create($validated);

        ActivityLog::logAction('created', $permission);

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $slugRule = $permission->isSystemPermission()
            ? ['required', Rule::in([$permission->slug])]
            : ['required', 'string', 'max:255', 'regex:/^[a-z0-9]+(\.[a-z0-9_]+)+$/', Rule::unique('permissions', 'slug')->ignore($permission->id)];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions', 'name')->ignore($permission->id)],
            'slug' => $slugRule,
            'description' => ['nullable', 'string'],
        ]);

        $oldValues = $permission->toArray();
        $permission->update($validated);

        ActivityLog::logAction('updated', $permission, $oldValues, $validated);

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        if ($permission->isSystemPermission()) {
            return redirect()->route('permissions.index')->with('error', 'System permissions cannot be deleted.');
        }

        $oldValues = $permission->toArray();
        ActivityLog::logAction('deleted', $permission, $oldValues);

        $permission->delete();

        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
