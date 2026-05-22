<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetsByDepartmentExport;
use App\Exports\AssetsByStatusExport;
use App\Exports\AssetsValueExport;

class ReportController extends Controller
{
    

    public function index()
    {
        return view('reports.index');
    }

    public function assetsByDepartment(Request $request)
    {
        $format = $request->get('format');

        $departments = Department::withCount(['assets' => function($query) {
            $query->where('status', '!=', 'retired');
        }])->withSum(['assets' => function($query) {
            $query->where('status', '!=', 'retired');
        }], 'purchase_cost')->get();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.assets_by_department_pdf', compact('departments'));
            return $pdf->download('assets_by_department_' . date('Y-m-d') . '.pdf');
        }

        if ($format === 'xlsx') {
            return Excel::download(new AssetsByDepartmentExport($departments), 'assets_by_department_' . date('Y-m-d') . '.xlsx');
        }

        return view('reports.assets_by_department', compact('departments'));
    }

    public function assetsByStatus(Request $request)
    {
        $format = $request->get('format');

        $statuses = Asset::STATUSES;
        $statusData = [];

        foreach ($statuses as $status) {
            $count = Asset::where('status', $status)->count();
            $totalValue = Asset::where('status', $status)->sum('purchase_cost') ?? 0;
            
            $statusData[$status] = [
                'count' => $count,
                'total_value' => $totalValue,
            ];
        }

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.assets_by_status_pdf', compact('statusData'));
            return $pdf->download('assets_by_status_' . date('Y-m-d') . '.pdf');
        }

        if ($format === 'xlsx') {
            return Excel::download(new AssetsByStatusExport($statusData), 'assets_by_status_' . date('Y-m-d') . '.xlsx');
        }

        return view('reports.assets_by_status', compact('statusData'));
    }

    public function assetsValue(Request $request)
    {
        $format = $request->get('format');

        $totalAssets = Asset::count();
        $totalValue = Asset::sum('purchase_cost') ?? 0;
        $retiredValue = Asset::where('status', 'retired')->sum('purchase_cost') ?? 0;
        $activeValue = Asset::where('status', '!=', 'retired')->sum('purchase_cost') ?? 0;

        $categoryValues = Category::withCount(['assets' => function($query) {
            $query->where('status', '!=', 'retired');
        }])->withSum(['assets' => function($query) {
            $query->where('status', '!=', 'retired');
        }], 'purchase_cost')->get();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.assets_value_pdf', compact(
                'totalAssets',
                'totalValue',
                'retiredValue',
                'activeValue',
                'categoryValues'
            ));
            return $pdf->download('assets_value_summary_' . date('Y-m-d') . '.pdf');
        }

        if ($format === 'xlsx') {
            return Excel::download(new AssetsValueExport($categoryValues), 'assets_value_summary_' . date('Y-m-d') . '.xlsx');
        }

        return view('reports.assets_value', compact(
            'totalAssets',
            'totalValue',
            'retiredValue',
            'activeValue',
            'categoryValues'
        ));
    }

    public function activityLogs(Request $request)
    {
        $format = $request->get('format');

        $query = \App\Models\ActivityLog::with('user');

        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activities = $query->latest()->paginate(25);
        $users = User::all();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.activity_logs_pdf', compact('activities'));
            return $pdf->download('activity_logs_' . date('Y-m-d') . '.pdf');
        }

        return view('reports.activity_logs', compact('activities', 'users'));
    }
}
