<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use Illuminate\Http\Request;

class EmailLogController extends Controller
{
    public function index(Request $request)
    {
        $query = EmailLog::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('notification_type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('recipient', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(25);

        $stats = [
            'total' => EmailLog::count(),
            'sent' => EmailLog::where('status', 'sent')->count(),
            'failed' => EmailLog::where('status', 'failed')->count(),
            'queued' => EmailLog::where('status', 'queued')->count(),
        ];

        $types = EmailLog::select('notification_type')
            ->distinct()
            ->pluck('notification_type')
            ->filter()
            ->values();

        return view('email-logs.index', compact('logs', 'stats', 'types'));
    }

    public function show(EmailLog $emailLog)
    {
        return view('email-logs.show', compact('emailLog'));
    }

    public function destroy(EmailLog $emailLog)
    {
        $emailLog->delete();

        return redirect()->route('email-logs.index')
            ->with('success', 'Log entry deleted successfully.');
    }

    public function clear()
    {
        EmailLog::truncate();

        return redirect()->route('email-logs.index')
            ->with('success', 'All email logs cleared.');
    }
}
