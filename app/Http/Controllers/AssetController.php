<?php

namespace App\Http\Controllers;

use App\Exports\AssetsExport;
use App\Models\ActivityLog;
use App\Models\Asset;
use App\Models\AssetMovement;
use App\Models\Category;
use App\Models\Department;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Picqer\Barcode\BarcodeGeneratorPNG;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::with(['category', 'department', 'assignedUser']);

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('asset_tag', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('department') && $request->department) {
            $query->where('department_id', $request->department);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $assets = $query->latest()->paginate(15);
        $categories = Category::all();
        $departments = Department::all();
        $statuses = Asset::STATUSES;

        return view('assets.index', compact(
            'assets',
            'categories',
            'departments',
            'statuses'
        ));
    }

    public function create()
    {
        $categories = Category::all();
        $departments = Department::all();
        $users = User::all();
        $statuses = Asset::STATUSES;

        return view('assets.create', compact(
            'categories',
            'departments',
            'users',
            'statuses'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'purchase_date' => ['nullable', 'date'],
            'purchase_cost' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:'.implode(',', Asset::STATUSES)],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'warranty_expiry' => ['nullable', 'date'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = Str::uuid().'.'.$image->getClientOriginalExtension();
            $path = $image->storeAs('assets', $filename, 'public');
            $validated['image'] = $path;
        }

        if ($request->status === Asset::STATUS_ASSIGNED && $request->assigned_to) {
            $validated['status'] = Asset::STATUS_ASSIGNED;
        }

        $asset = Asset::create($validated);

        ActivityLog::logAction('created', $asset);

        return redirect()->route('assets.index')
            ->with('success', 'Asset created successfully.');
    }

    public function show(Asset $asset)
    {
        $asset->load(['category', 'department', 'assignedUser', 'movements.fromDepartment', 'movements.toDepartment']);

        return view('assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        $categories = Category::all();
        $departments = Department::all();
        $users = User::all();
        $statuses = Asset::STATUSES;

        return view('assets.edit', compact(
            'asset',
            'categories',
            'departments',
            'users',
            'statuses'
        ));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'purchase_date' => ['nullable', 'date'],
            'purchase_cost' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:'.implode(',', Asset::STATUSES)],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'warranty_expiry' => ['nullable', 'date'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $oldValues = $asset->toArray();

        if ($request->hasFile('image')) {
            if ($asset->image) {
                Storage::disk('public')->delete($asset->image);
            }
            $image = $request->file('image');
            $filename = Str::uuid().'.'.$image->getClientOriginalExtension();
            $path = $image->storeAs('assets', $filename, 'public');
            $validated['image'] = $path;
        }

        if ($request->status === Asset::STATUS_ASSIGNED && $request->assigned_to) {
            $validated['status'] = Asset::STATUS_ASSIGNED;
        } elseif ($request->status === Asset::STATUS_ASSIGNED && ! $request->assigned_to) {
            $validated['status'] = Asset::STATUS_AVAILABLE;
        }

        $asset->update($validated);

        ActivityLog::logAction('updated', $asset, $oldValues, $validated);

        return redirect()->route('assets.index')
            ->with('success', 'Asset updated successfully.');
    }

    public function destroy(Asset $asset)
    {
        $oldValues = $asset->toArray();

        if ($asset->image) {
            Storage::disk('public')->delete($asset->image);
        }

        ActivityLog::logAction('deleted', $asset, $oldValues);

        $asset->delete();

        return redirect()->route('assets.index')
            ->with('success', 'Asset deleted successfully.');
    }

    public function checkout(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'assigned_to' => ['required', 'exists:users,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'notes' => ['nullable', 'string'],
        ]);

        $oldStatus = $asset->status;
        $oldAssignedTo = $asset->assigned_to;
        $oldDepartment = $asset->department_id;

        $asset->update([
            'status' => Asset::STATUS_ASSIGNED,
            'assigned_to' => $validated['assigned_to'],
            'department_id' => $validated['department_id'] ?? $asset->department_id,
        ]);

        AssetMovement::create([
            'asset_id' => $asset->id,
            'from_department_id' => $oldDepartment,
            'to_department_id' => $validated['department_id'] ?? $asset->department_id,
            'assigned_from' => $oldAssignedTo,
            'assigned_to' => $validated['assigned_to'],
            'movement_type' => AssetMovement::TYPE_CHECKOUT,
            'movement_date' => now(),
            'notes' => $validated['notes'] ?? null,
        ]);

        ActivityLog::logAction('updated', $asset, ['status' => $oldStatus], ['status' => $asset->status, 'assigned_to' => $validated['assigned_to']]);

        return redirect()->back()
            ->with('success', 'Asset checked out successfully.');
    }

    public function checkin(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string'],
        ]);

        $oldStatus = $asset->status;
        $oldAssignedTo = $asset->assigned_to;

        $asset->update([
            'status' => Asset::STATUS_AVAILABLE,
            'assigned_to' => null,
        ]);

        AssetMovement::create([
            'asset_id' => $asset->id,
            'from_department_id' => $asset->department_id,
            'to_department_id' => null,
            'assigned_from' => $oldAssignedTo,
            'assigned_to' => null,
            'movement_type' => AssetMovement::TYPE_CHECKIN,
            'movement_date' => now(),
            'notes' => $validated['notes'] ?? null,
        ]);

        ActivityLog::logAction('updated', $asset, ['status' => $oldStatus], ['status' => $asset->status]);

        return redirect()->back()
            ->with('success', 'Asset checked in successfully.');
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'xlsx');

        $filename = 'assets_'.date('Y-m-d_H-i-s');

        if ($format === 'pdf') {
            $assets = Asset::with(['category', 'department', 'assignedUser'])->get();
            $pdf = Pdf::loadView('reports.assets_pdf', compact('assets'));

            return $pdf->download($filename.'.pdf');
        }

        return Excel::download(new AssetsExport, $filename.'.xlsx');
    }

    public function barcode(string $code)
    {
        $generator = new BarcodeGeneratorPNG;
        $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128, 2, 50);

        return response($barcode, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function scan(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $code = trim($request->code);

        $asset = Asset::where('asset_tag', $code)
            ->orWhere('serial_number', $code)
            ->orWhere('id', $code)
            ->first();

        if (! $asset) {
            return response()->json([
                'found' => false,
                'message' => 'No asset found with that barcode.',
            ]);
        }

        return response()->json([
            'found' => true,
            'id' => $asset->id,
            'name' => $asset->name,
            'asset_tag' => $asset->asset_tag,
            'url' => route('assets.show', $asset),
        ]);
    }
}
