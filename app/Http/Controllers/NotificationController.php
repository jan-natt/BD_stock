<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class NotificationController extends Controller
{
    public function __construct()
    {
        // Apply middleware
        $this->middleware('auth');
    }

    /**
     * Display a listing of the user's notifications.
     */
    public function index(Request $request)
    {
        $query = Notification::where('user_id', auth()->id());

        // Apply filters
        if ($request->has('is_read') && $request->is_read !== '') {
            $query->where('is_read', $request->is_read);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Sort results
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'unread_first':
                $query->orderBy('is_read', 'asc')->orderBy('created_at', 'desc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $notifications = $query->paginate(20);
        
        $unreadCount = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        $totalCount = Notification::where('user_id', auth()->id())->count();

        return view('notifications.index', compact(
            'notifications', 'unreadCount', 'totalCount'
        ));
    }

    /**
     * Display the specified notification.
     */
    public function show(Notification $notification)
    {
        $this->authorize('view', $notification);

        // Mark as read when viewed
        if (!$notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        return view('notifications.show', compact('notification'));
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        $this->authorize('update', $notification);

        $notification->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.'
        ]);
    }

    /**
     * Mark a notification as unread.
     */
    public function markAsUnread(Notification $notification)
    {
        $this->authorize('update', $notification);

        $notification->update(['is_read' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as unread.'
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.'
        ]);
    }

    /**
     * Delete a notification.
     */
    public function destroy(Notification $notification)
    {
        $this->authorize('delete', $notification);

        $notification->delete();

        return redirect()->route('notifications.index')
            ->with('success', 'Notification deleted successfully.');
    }

    /**
     * Clear all read notifications.
     */
    public function clearRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', true)
            ->delete();

        return redirect()->route('notifications.index')
            ->with('success', 'Read notifications cleared successfully.');
    }

    /**
     * Clear all notifications.
     */
    public function clearAll()
    {
        Notification::where('user_id', auth()->id())->delete();

        return redirect()->route('notifications.index')
            ->with('success', 'All notifications cleared successfully.');
    }

    /**
     * Get unread notifications count for API.
     */
    public function unreadCount()
    {
        $count = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json([
            'unread_count' => $count
        ]);
    }

    /**
     * Get recent notifications for API.
     */
    public function recentNotifications()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'time' => $notification->created_at->format('H:i'),
                ];
            });

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Notification::where('user_id', auth()->id())
                ->where('is_read', false)
                ->count()
        ]);
    }

    /**
     * Create a new notification (Admin only).
     */
    public function create()
    {
        $this->authorize('create', Notification::class);

        $users = User::all();
        $notificationTypes = [
            'info' => 'Information',
            'success' => 'Success',
            'warning' => 'Warning',
            'error' => 'Error',
            'system' => 'System Update',
            'security' => 'Security Alert',
        ];

        return view('notifications.create', compact('users', 'notificationTypes'));
    }

    /**
     * Store a new notification (Admin only).
     */
    public function store(Request $request)
    {
        $this->authorize('create', Notification::class);

        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => ['required', Rule::in(['info', 'success', 'warning', 'error', 'system', 'security'])],
            'priority' => ['required', Rule::in(['low', 'normal', 'high', 'urgent'])],
            'action_url' => 'nullable|url',
            'action_text' => 'nullable|string|max:50',
        ]);

        try {
            DB::beginTransaction();

            $notifications = [];
            foreach ($validated['user_ids'] as $userId) {
                $notifications[] = [
                    'user_id' => $userId,
                    'title' => $validated['title'],
                    'message' => $validated['message'],
                    'type' => $validated['type'],
                    'priority' => $validated['priority'],
                    'action_url' => $validated['action_url'] ?? null,
                    'action_text' => $validated['action_text'] ?? null,
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Bulk insert for better performance
            Notification::insert($notifications);

            DB::commit();

            return redirect()->route('notifications.index')
                ->with('success', 'Notifications sent successfully to ' . count($validated['user_ids']) . ' users.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to send notifications: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Send notification to all users (Admin only).
     */
    public function broadcastToAll(Request $request)
    {
        $this->authorize('create', Notification::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => ['required', Rule::in(['info', 'success', 'warning', 'error', 'system', 'security'])],
            'priority' => ['required', Rule::in(['low', 'normal', 'high', 'urgent'])],
            'action_url' => 'nullable|url',
            'action_text' => 'nullable|string|max:50',
        ]);

        try {
            DB::beginTransaction();

            $userIds = User::where('status', true)->pluck('id');
            $notifications = [];

            foreach ($userIds as $userId) {
                $notifications[] = [
                    'user_id' => $userId,
                    'title' => $validated['title'],
                    'message' => $validated['message'],
                    'type' => $validated['type'],
                    'priority' => $validated['priority'],
                    'action_url' => $validated['action_url'] ?? null,
                    'action_text' => $validated['action_text'] ?? null,
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Notification::insert($notifications);

            DB::commit();

            return redirect()->route('notifications.index')
                ->with('success', 'Notification broadcasted to all ' . count($userIds) . ' users.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to broadcast notification: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Get notification statistics for dashboard.
     */
    public function statistics()
    {
        $this->authorize('viewStatistics', Notification::class);

        $stats = [
            'total_notifications' => Notification::count(),
            'unread_notifications' => Notification::where('is_read', false)->count(),
            'read_notifications' => Notification::where('is_read', true)->count(),
            'notifications_today' => Notification::whereDate('created_at', today())->count(),
            'notifications_this_week' => Notification::whereBetween('created_at', [
                now()->startOfWeek(), now()->endOfWeek()
            ])->count(),
            'notifications_this_month' => Notification::whereBetween('created_at', [
                now()->startOfMonth(), now()->endOfMonth()
            ])->count(),
            'by_type' => Notification::select('type', DB::raw('COUNT(*) as count'))
                ->groupBy('type')
                ->get()
                ->pluck('count', 'type'),
            'by_priority' => Notification::select('priority', DB::raw('COUNT(*) as count'))
                ->groupBy('priority')
                ->get()
                ->pluck('count', 'priority'),
            'top_users' => Notification::join('users', 'notifications.user_id', '=', 'users.id')
                ->select('users.name', DB::raw('COUNT(notifications.id) as count'))
                ->groupBy('users.id', 'users.name')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
        ];

        return view('notifications.statistics', compact('stats'));
    }

    /**
     * Export notifications (Admin only).
     */
    public function export(Request $request)
    {
        $this->authorize('export', Notification::class);

        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'user_id' => 'nullable|exists:users,id',
            'type' => 'nullable|in:info,success,warning,error,system,security',
            'format' => ['required', Rule::in(['csv', 'json'])],
        ]);

        $query = Notification::with(['user']);

        if ($validated['start_date'] ?? false) {
            $query->where('created_at', '>=', $validated['start_date']);
        }

        if ($validated['end_date'] ?? false) {
            $query->where('created_at', '<=', $validated['end_date'] . ' 23:59:59');
        }

        if ($validated['user_id'] ?? false) {
            $query->where('user_id', $validated['user_id']);
        }

        if ($validated['type'] ?? false) {
            $query->where('type', $validated['type']);
        }

        $notifications = $query->orderBy('created_at', 'desc')->get();

        if ($validated['format'] === 'csv') {
            return $this->exportToCSV($notifications);
        }

        return $this->exportToJSON($notifications);
    }

    /**
     * Export to CSV.
     */
    protected function exportToCSV($notifications)
    {
        $fileName = 'notifications-export-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($notifications) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID', 'User', 'Title', 'Message', 'Type', 
                'Priority', 'Is Read', 'Action URL', 'Created At'
            ]);

            // Add data rows
            foreach ($notifications as $notification) {
                fputcsv($file, [
                    $notification->id,
                    $notification->user->name,
                    $notification->title,
                    $notification->message,
                    $notification->type,
                    $notification->priority,
                    $notification->is_read ? 'Yes' : 'No',
                    $notification->action_url,
                    $notification->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to JSON.
     */
    protected function exportToJSON($notifications)
    {
        $fileName = 'notifications-export-' . date('Y-m-d') . '.json';
        
        $data = $notifications->map(function($notification) {
            return [
                'id' => $notification->id,
                'user' => [
                    'id' => $notification->user->id,
                    'name' => $notification->user->name,
                    'email' => $notification->user->email,
                ],
                'title' => $notification->title,
                'message' => $notification->message,
                'type' => $notification->type,
                'priority' => $notification->priority,
                'is_read' => $notification->is_read,
                'action_url' => $notification->action_url,
                'action_text' => $notification->action_text,
                'created_at' => $notification->created_at->toISOString(),
            ];
        });

        return response()->json($data)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Get notification preferences for user.
     */
    public function getPreferences()
    {
        $user = auth()->user();
        
        // This would typically come from user settings
        $defaultPreferences = [
            'email_notifications' => true,
            'push_notifications' => true,
            'browser_notifications' => true,
            'notification_types' => [
                'info' => true,
                'success' => true,
                'warning' => true,
                'error' => true,
                'system' => true,
                'security' => true,
            ],
            'quiet_hours' => [
                'enabled' => false,
                'start_time' => '22:00',
                'end_time' => '07:00',
            ],
        ];

        $preferences = array_merge($defaultPreferences, $user->notification_preferences ?? []);

        return response()->json($preferences);
    }

    /**
     * Update notification preferences.
     */
    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'sometimes|boolean',
            'push_notifications' => 'sometimes|boolean',
            'browser_notifications' => 'sometimes|boolean',
            'notification_types' => 'sometimes|array',
            'notification_types.*' => 'boolean',
            'quiet_hours' => 'sometimes|array',
            'quiet_hours.enabled' => 'sometimes|boolean',
            'quiet_hours.start_time' => 'sometimes|date_format:H:i',
            'quiet_hours.end_time' => 'sometimes|date_format:H:i',
        ]);

        $user = auth()->user();
        $currentPreferences = $user->notification_preferences ?? [];
        $updatedPreferences = array_merge($currentPreferences, $validated);

        $user->update(['notification_preferences' => $updatedPreferences]);

        return response()->json([
            'success' => true,
            'message' => 'Notification preferences updated successfully.',
            'preferences' => $updatedPreferences
        ]);
    }

    /**
     * Test notification for current user.
     */
    public function testNotification()
    {
        try {
            $notification = Notification::create([
                'user_id' => auth()->id(),
                'title' => 'Test Notification',
                'message' => 'This is a test notification to verify your notification settings are working correctly.',
                'type' => 'info',
                'priority' => 'normal',
                'is_read' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Test notification sent successfully.',
                'notification' => $notification
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test notification: ' . $e->getMessage()
            ], 500);
        }
    }
}