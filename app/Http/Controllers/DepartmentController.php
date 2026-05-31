<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Department::class);

        $departments = Department::withCount('assets')->latest()->paginate(15);
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        $this->authorize('create', Department::class);

        return view('departments.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Department::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:departments,code'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'manager' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $department = Department::create($validated);

        ActivityLog::logAction('created', $department);

        return redirect()->route('departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function show(Department $department)
    {
        $this->authorize('view', $department);

        $department->load('assets.category');
        return view('departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        $this->authorize('update', $department);

        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $this->authorize('update', $department);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:departments,code,' . $department->id],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'manager' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $oldValues = $department->toArray();

        $department->update($validated);

        ActivityLog::logAction('updated', $department, $oldValues, $validated);

        return redirect()->route('departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $this->authorize('delete', $department);

        if ($department->assets()->count() > 0) {
            return redirect()->route('departments.index')
                ->with('error', 'Cannot delete department with associated assets.');
        }

        $oldValues = $department->toArray();

        ActivityLog::logAction('deleted', $department, $oldValues);

        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
