<?php

namespace App\Http\Controllers;

use App\Models\BackupLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Display backup logs
     */
    public function index(Request $request)
    {
        $statusFilter = $request->input('status');
        $dateFilter = $request->input('date');

        $logs = BackupLog::query();

        if ($statusFilter) {
            $logs = $logs->where('status', '=', $statusFilter);
        }

        if ($dateFilter) {
            $logs = $logs->whereDate('created_at', '=', $dateFilter, 'and');
        }

        $logs = $logs->orderByDesc('created_at')
            ->paginate(20, ['*'], 'page', $request->input('page', 1));

        return view('backup.logs', [
            'logs' => $logs,
            'statusFilter' => $statusFilter,
            'dateFilter' => $dateFilter,
        ]);
    }

    /**
     * Show log details
     */
    public function show(int $id)
    {
        $log = BackupLog::findOrFail($id);

        return view('backup.log-detail', [
            'log' => $log,
        ]);
    }

    /**
     * Get logs as JSON (for AJAX)
     */
    public function list(Request $request)
    {
        $logs = BackupLog::orderByDesc('created_at')
            ->limit(50)
            ->get();

        return response()->json($logs);
    }
}
