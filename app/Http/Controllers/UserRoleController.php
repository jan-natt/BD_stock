<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserRoleController extends Controller
{
    public function __construct()
    {
        // Apply middleware - only admins can manage user roles
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of all user-role relationships.
     */
    public function index()
    {
        $userRoles = UserRole::with(['user', 'role'])
            ->latest()
            ->paginate(20);
            
        return view('user-roles.index', compact('userRoles'));
    }

    /**
     * Show the form for assigning roles to a user.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        $roles = Role::orderBy('role_name')->get();
        
        return view('user-roles.create', compact('users', 'roles'));
    }

    /**
     * Store a newly created user-role relationship.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id'
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($validated['user_id']);
            
            // Sync roles (remove existing and add new ones)
            $user->roles()->sync($validated['role_ids']);

            DB::commit();

            return redirect()->route('users.show', $user->id)
                ->with('success', 'Roles assigned to user successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to assign roles: ' . $e->getMessage());
        }
    }

    /**
     * Display roles for a specific user.
     */
    public function showByUser($userId)
    {
        $user = User::with('roles')->findOrFail($userId);
        $roles = Role::orderBy('role_name')->get();
        
        return view('user-roles.user-roles', compact('user', 'roles'));
    }

    /**
     * Display users that have a specific role.
     */
    public function showByRole($roleId)
    {
        $role = Role::with('users')->findOrFail($roleId);
        $users = User::orderBy('name')->get();
        
        return view('user-roles.role-users', compact('role', 'users'));
    }

    /**
     * Show the form for editing roles of a specific user.
     */
    public function editUserRoles($userId)
    {
        $user = User::with('roles')->findOrFail($userId);
        $roles = Role::orderBy('role_name')->get();
        
        return view('user-roles.edit-user', compact('user', 'roles'));
    }

    /**
     * Update roles for a specific user.
     */
    public function updateUserRoles(Request $request, $userId)
    {
        $validated = $request->validate([
            'role_ids' => 'array',
            'role_ids.*' => 'exists:roles,id'
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($userId);
            $user->roles()->sync($validated['role_ids'] ?? []);

            DB::commit();

            return redirect()->route('users.show', $user->id)
                ->with('success', 'User roles updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update user roles: ' . $e->getMessage());
        }
    }

    /**
     * Remove a specific role from a user.
     */
    public function destroy(Request $request, $userId, $roleId)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($userId);
            
            // Prevent removing the last admin role from the last admin user
            if ($this->isLastAdminRemoval($user, $roleId)) {
                return redirect()->back()
                    ->with('error', 'Cannot remove the last admin role from the last admin user.');
            }

            $user->roles()->detach($roleId);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Role removed from user successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to remove role: ' . $e->getMessage());
        }
    }

    /**
     * Check if this is the last admin role being removed from the last admin user.
     */
    protected function isLastAdminRemoval(User $user, $roleId): bool
    {
        $role = Role::find($roleId);
        
        if ($role && $role->role_name === 'admin') {
            $adminUsersCount = User::whereHas('roles', function ($query) {
                $query->where('role_name', 'admin');
            })->count();
            
            $userAdminRolesCount = $user->roles()->where('role_name', 'admin')->count();
            
            // If this is the only admin role for this user and this is the only admin user
            if ($userAdminRolesCount === 1 && $adminUsersCount === 1) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Bulk assign roles to multiple users.
     */
    public function bulkAssign(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id'
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['user_ids'] as $userId) {
                $user = User::find($userId);
                $user->roles()->syncWithoutDetaching($validated['role_ids']);
            }

            DB::commit();

            return redirect()->route('user-roles.index')
                ->with('success', 'Roles bulk assigned successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to bulk assign roles: ' . $e->getMessage());
        }
    }

    /**
     * Show bulk assignment form.
     */
    public function showBulkForm()
    {
        $users = User::orderBy('name')->get();
        $roles = Role::orderBy('role_name')->get();
        
        return view('user-roles.bulk-assign', compact('users', 'roles'));
    }

    /**
     * Search users by name or email for role assignment.
     */
    public function searchUsers(Request $request)
    {
        $search = $request->input('search');
        
        $users = User::where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orderBy('name')
            ->paginate(15);
            
        $roles = Role::orderBy('role_name')->get();
        
        return view('user-roles.user-roles-search', compact('users', 'roles', 'search'));
    }
}