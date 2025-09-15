<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AuditLogController extends Controller
{
    public function __construct()
    {
        // Apply middleware - only admins can view audit logs
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the audit logs.
     */
    public function index(Request $request)
    {
        $query = AuditLog::with(['user']);

        // Apply filters
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('action') && $request->action) {
            $query->where('action', 'like', "%{$request->action}%");
        }

        if ($request->has('ip_address') && $request->ip_address) {
            $query->where('ip_address', 'like', "%{$request->ip_address}%");
        }

        if ($request->has('start_date') && $request->start_date) {
            $query->where('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('user_agent', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Sort results
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'user':
                $query->join('users', 'audit_logs.user_id', '=', 'users.id')
                      ->orderBy('users.name', 'asc');
                break;
            case 'action':
                $query->orderBy('action', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $auditLogs = $query->paginate(50);
        
        $users = User::all();
        $commonActions = $this->getCommonActions();

        return view('audit-logs.index', compact('auditLogs', 'users', 'commonActions'));
    }

    /**
     * Display the specified audit log.
     */
    public function show(AuditLog $auditLog)
    {
        $auditLog->load(['user']);

        return view('audit-logs.show', compact('auditLog'));
    }

    /**
     * Get common actions for filter dropdown.
     */
    protected function getCommonActions()
    {
        return [
            'login' => 'User Login',
            'logout' => 'User Logout',
            'create' => 'Create Operation',
            'update' => 'Update Operation',
            'delete' => 'Delete Operation',
            'export' => 'Export Data',
            'import' => 'Import Data',
            'password_change' => 'Password Change',
            'profile_update' => 'Profile Update',
            'failed_login' => 'Failed Login Attempt',
        ];
    }

    /**
     * Export audit logs to CSV.
     */
    public function export(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'user_id' => 'nullable|exists:users,id',
            'format' => ['required', Rule::in(['csv', 'json'])],
        ]);

        $query = AuditLog::with(['user']);

        if ($validated['start_date'] ?? false) {
            $query->where('created_at', '>=', $validated['start_date']);
        }

        if ($validated['end_date'] ?? false) {
            $query->where('created_at', '<=', $validated['end_date'] . ' 23:59:59');
        }

        if ($validated['user_id'] ?? false) {
            $query->where('user_id', $validated['user_id']);
        }

        $auditLogs = $query->orderBy('created_at', 'desc')->get();

        if ($validated['format'] === 'csv') {
            return $this->exportToCSV($auditLogs);
        }

        return $this->exportToJSON($auditLogs);
    }

    /**
     * Export to CSV.
     */
    protected function exportToCSV($auditLogs)
    {
        $fileName = 'audit-logs-export-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($auditLogs) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID', 'Timestamp', 'User', 'Email', 'Action', 
                'IP Address', 'User Agent', 'Created At'
            ]);

            // Add data rows
            foreach ($auditLogs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user ? $log->user->name : 'System',
                    $log->user ? $log->user->email : 'N/A',
                    $log->action,
                    $log->ip_address,
                    $log->user_agent,
                    $log->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to JSON.
     */
    protected function exportToJSON($auditLogs)
    {
        $fileName = 'audit-logs-export-' . date('Y-m-d') . '.json';
        
        $data = $auditLogs->map(function($log) {
            return [
                'id' => $log->id,
                'timestamp' => $log->created_at->toISOString(),
                'user' => $log->user ? [
                    'id' => $log->user->id,
                    'name' => $log->user->name,
                    'email' => $log->user->email,
                ] : null,
                'action' => $log->action,
                'ip_address' => $log->ip_address,
                'user_agent' => $log->user_agent,
                'created_at' => $log->created_at->toISOString(),
            ];
        });

        return response()->json($data)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Get audit log statistics.
     */
    public function statistics(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = AuditLog::query();

        if ($validated['start_date'] ?? false) {
            $query->where('created_at', '>=', $validated['start_date']);
        }

        if ($validated['end_date'] ?? false) {
            $query->where('created_at', '<=', $validated['end_date'] . ' 23:59:59');
        }

        $stats = [
            'total_logs' => $query->count(),
            'unique_users' => $query->distinct('user_id')->count('user_id'),
            'unique_ips' => $query->distinct('ip_address')->count('ip_address'),
            'top_actions' => $query->select('action', DB::raw('COUNT(*) as count'))
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            'top_users' => $query->join('users', 'audit_logs.user_id', '=', 'users.id')
                ->select('users.name', 'users.email', DB::raw('COUNT(*) as count'))
                ->groupBy('users.id', 'users.name', 'users.email')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            'daily_activity' => $query->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'hourly_activity' => $query->select(
                    DB::raw('HOUR(created_at) as hour'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('hour')
                ->orderBy('hour')
                ->get(),
        ];

        return view('audit-logs.statistics', compact('stats'));
    }

    /**
     * Clean up old audit logs.
     */
    public function cleanup(Request $request)
    {
        $validated = $request->validate([
            'older_than' => 'required|integer|min:1|max:3650', // Max 10 years
            'confirm' => 'required|accepted',
        ]);

        try {
            DB::beginTransaction();

            $cutoffDate = now()->subDays($validated['older_than']);
            $deletedCount = AuditLog::where('created_at', '<', $cutoffDate)->delete();

            // Log the cleanup action
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'audit_log_cleanup',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('audit-logs.index')
                ->with('success', "Successfully deleted {$deletedCount} audit logs older than {$validated['older_than']} days.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to clean up audit logs: ' . $e->getMessage());
        }
    }

    /**
     * Show cleanup form.
     */
    public function showCleanupForm()
    {
        $oldestLog = AuditLog::orderBy('created_at')->first();
        $totalLogs = AuditLog::count();
        
        return view('audit-logs.cleanup', compact('oldestLog', 'totalLogs'));
    }

    /**
     * Search audit logs by specific criteria.
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|min:2|max:255',
            'search_type' => 'required|in:action,ip,user,user_agent',
        ]);

        $query = AuditLog::with(['user']);

        switch ($validated['search_type']) {
            case 'action':
                $query->where('action', 'like', "%{$validated['query']}%");
                break;
            case 'ip':
                $query->where('ip_address', 'like', "%{$validated['query']}%");
                break;
            case 'user':
                $query->whereHas('user', function($q) use ($validated) {
                    $q->where('name', 'like', "%{$validated['query']}%")
                      ->orWhere('email', 'like', "%{$validated['query']}%");
                });
                break;
            case 'user_agent':
                $query->where('user_agent', 'like', "%{$validated['query']}%");
                break;
        }

        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(50);

        return view('audit-logs.search-results', compact('auditLogs', 'validated'));
    }

    /**
     * Get real-time audit log feed (for dashboard).
     */
    public function liveFeed()
    {
        $logs = AuditLog::with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'logs' => $logs->map(function($log) {
                return [
                    'id' => $log->id,
                    'action' => $log->action,
                    'user' => $log->user ? $log->user->name : 'System',
                    'ip_address' => $log->ip_address,
                    'timestamp' => $log->created_at->diffForHumans(),
                    'time' => $log->created_at->format('H:i:s'),
                ];
            }),
            'total' => AuditLog::count(),
            'last_update' => now()->toISOString()
        ]);
    }

    /**
     * Get user activity report.
     */
    public function userActivityReport($userId)
    {
        $user = User::findOrFail($userId);

        $activity = AuditLog::where('user_id', $userId)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_actions'),
                DB::raw('COUNT(DISTINCT action) as unique_actions'),
                DB::raw('COUNT(DISTINCT ip_address) as unique_ips')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->paginate(30);

        $mostCommonActions = AuditLog::where('user_id', $userId)
            ->select('action', DB::raw('COUNT(*) as count'))
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $ipAddresses = AuditLog::where('user_id', $userId)
            ->select('ip_address', DB::raw('MAX(created_at) as last_used'), DB::raw('COUNT(*) as usage_count'))
            ->groupBy('ip_address')
            ->orderBy('last_used', 'desc')
            ->get();

        return view('audit-logs.user-activity', compact(
            'user', 'activity', 'mostCommonActions', 'ipAddresses'
        ));
    }

    /**
     * Get suspicious activity alerts.
     */
    public function suspiciousActivity()
    {
        // Detect multiple failed login attempts
        $failedLogins = AuditLog::where('action', 'failed_login')
            ->select('ip_address', DB::raw('COUNT(*) as attempt_count'))
            ->groupBy('ip_address')
            ->having('attempt_count', '>', 5)
            ->orderBy('attempt_count', 'desc')
            ->get();

        // Detect multiple user agents from same IP
        $multipleUserAgents = AuditLog::select('ip_address', DB::raw('COUNT(DISTINCT user_agent) as agent_count'))
            ->groupBy('ip_address')
            ->having('agent_count', '>', 3)
            ->orderBy('agent_count', 'desc')
            ->get();

        // Detect activity from unusual locations (simplified)
        $unusualHours = AuditLog::whereIn(DB::raw('HOUR(created_at)'), [0, 1, 2, 3, 4, 5]) // Midnight to 5 AM
            ->select('user_id', DB::raw('COUNT(*) as night_actions'))
            ->groupBy('user_id')
            ->having('night_actions', '>', 10)
            ->orderBy('night_actions', 'desc')
            ->get();

        return view('audit-logs.suspicious-activity', compact(
            'failedLogins', 'multipleUserAgents', 'unusualHours'
        ));
    }
}