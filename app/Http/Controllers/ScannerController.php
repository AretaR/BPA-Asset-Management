<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\ScanLog;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    /**
     * Render the mobile-friendly scanner page.
     */
    public function index()
    {
        return view('scanner.index');
    }

    /**
     * AJAX: look up an asset by QR UUID, serial number, or asset tag.
     * Returns JSON for the popup modal.
     */
    public function lookup(Request $request)
    {
        $term = trim($request->input('term', ''));
        $type = $request->input('type', 'qr_code'); // qr_code | serial_number | asset_tag

        if (empty($term)) {
            return response()->json(['error' => 'No scan data provided.'], 422);
        }

        $asset = null;

        if ($type === 'qr_code') {
            $asset = Asset::where('qr_uuid', $term)->first();
        } elseif ($type === 'serial_number') {
            $asset = Asset::where('serial_number', $term)->first();
        } elseif ($type === 'asset_tag') {
            $asset = Asset::where('asset_tag', $term)->first();
        }

        if (!$asset) {
            return response()->json(['error' => 'Asset not found for: ' . $term], 404);
        }

        // Load relations
        $asset->load(['category', 'department', 'assignedUser']);

        // Log the scan
        ScanLog::create([
            'asset_id'   => $asset->id,
            'scanned_by' => auth()->id(),
            'scan_type'  => $type,
            'device'     => $request->header('User-Agent'),
            'ip_address' => $request->ip(),
            'location'   => null,
            'scanned_at' => now(),
        ]);

        return response()->json([
            'asset' => [
                'id'              => $asset->id,
                'name'            => $asset->name,
                'asset_tag'       => $asset->asset_tag,

                'serial_number'   => $asset->serial_number,
                'status'          => $asset->status,
                'status_badge'    => $asset->status_badge_class ?? 'bg-secondary',
                'category'        => optional($asset->category)->name,
                'department'      => optional($asset->department)->name,
                'assigned_to'     => optional($asset->assignedUser)->name,
                'location'        => $asset->location,
                'manufacturer'    => $asset->manufacturer,
                'model'           => $asset->model,
                'purchase_date'   => $asset->purchase_date?->format('d M Y'),
                'purchase_cost'   => $asset->purchase_cost ? number_format($asset->purchase_cost, 2) : null,
                'warranty_expiry' => $asset->warranty_expiry?->format('d M Y'),
                'image'           => $asset->image ? asset('storage/' . $asset->image) : null,
                'view_url'        => route('assets.show', $asset->id),
            ],
        ]);
    }

    /**
     * AJAX: quick search by name / asset_tag / serial_number.
     */
    public function search(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $assets = Asset::with(['category', 'department'])
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('asset_tag', 'like', "%{$q}%")
                    ->orWhere('serial_number', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->limit(10)
            ->get();

        return response()->json($assets->map(fn($a) => [
            'id'        => $a->id,
            'name'      => $a->name,
            'asset_tag' => $a->asset_tag,

            'category'  => optional($a->category)->name,
            'status'    => $a->status,
            'view_url'  => route('assets.show', $a->id),
        ]));
    }

    /**
     * Show the scan history log.
     */
    public function history(Request $request)
    {
        $logs = ScanLog::with(['asset', 'user'])
            ->latest('scanned_at')
            ->paginate(25);

        return view('scanner.history', compact('logs'));
    }
}
