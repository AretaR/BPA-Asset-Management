<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total_assets' => Asset::count(),
            'available_assets' => Asset::available()->count(),
            'assigned_assets' => Asset::assigned()->count(),
            'maintenance_assets' => Asset::maintenance()->count(),
            'retired_assets' => Asset::retired()->count(),
            'total_value' => Asset::sum('purchase_cost') ?? 0,
            'total_users' => User::count(),
            'recent_activities' => ActivityLog::with('user')->latest()->take(10)->get(),
        ];

        $recentAssets = Asset::with(['category', 'department', 'assignedUser'])
            ->latest()
            ->take(5)
            ->get();

        $assetsByStatus = Asset::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $assetsByCategory = Asset::with('category')
            ->get()
            ->groupBy('category.name')
            ->map->count();

        return view('dashboard.index', compact(
            'stats',
            'recentAssets',
            'assetsByStatus',
            'assetsByCategory'
        ));
    }
}
