<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    public function __construct()
    {
        // Apply middleware - only admins can manage role permissions
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of all role-permission relationships.
     */
    public function index()
    {
        $rolePermissions = RolePermission::with(['role', 'permission'])
            ->latest()
            ->paginate(20);
            
        return view('role-permissions.index', compact('rolePermissions'));
    }

    /**
     * Show the form for assigning permissions to a role.
     */
    public function create()
    {
        $roles = Role::orderBy('role_name')->get();
        $permissions = Permission::orderBy('permission_name')->get();
        
        return view('role-permissions.create', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created role-permission relationship.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id'
        ]);

        try {
            DB::beginTransaction();

            $role = Role::findOrFail($validated['role_id']);
            
            // Sync permissions (remove existing and add new ones)
            $role->permissions()->sync($validated['permission_ids']);

            DB::commit();

            return redirect()->route('roles.show', $role->id)
                ->with('success', 'Permissions assigned to role successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to assign permissions: ' . $e->getMessage());
        }
    }

    /**
     * Display permissions for a specific role.
     */
    public function showByRole($roleId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);
        $permissions = Permission::orderBy('permission_name')->get();
        
        return view('role-permissions.role-permissions', compact('role', 'permissions'));
    }

    /**
     * Display roles that have a specific permission.
     */
    public function showByPermission($permissionId)
    {
        $permission = Permission::with('roles')->findOrFail($permissionId);
        $roles = Role::orderBy('role_name')->get();
        
        return view('role-permissions.permission-roles', compact('permission', 'roles'));
    }

    /**
     * Show the form for editing permissions of a specific role.
     */
    public function editRolePermissions($roleId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);
        $permissions = Permission::orderBy('permission_name')->get();
        
        return view('role-permissions.edit-role', compact('role', 'permissions'));
    }

    /**
     * Update permissions for a specific role.
     */
    public function updateRolePermissions(Request $request, $roleId)
    {
        $validated = $request->validate([
            'permission_ids' => 'array',
            'permission_ids.*' => 'exists:permissions,id'
        ]);

        try {
            DB::beginTransaction();

            $role = Role::findOrFail($roleId);
            $role->permissions()->sync($validated['permission_ids'] ?? []);

            DB::commit();

            return redirect()->route('roles.show', $role->id)
                ->with('success', 'Role permissions updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update role permissions: ' . $e->getMessage());
        }
    }

    /**
     * Remove a specific permission from a role.
     */
    public function destroy(Request $request, $roleId, $permissionId)
    {
        try {
            DB::beginTransaction();

            $role = Role::findOrFail($roleId);
            $role->permissions()->detach($permissionId);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Permission removed from role successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to remove permission: ' . $e->getMessage());
        }
    }

    /**
     * Bulk assign permissions to multiple roles.
     */
    public function bulkAssign(Request $request)
    {
        $validated = $request->validate([
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id',
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id'
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['role_ids'] as $roleId) {
                $role = Role::find($roleId);
                $role->permissions()->syncWithoutDetaching($validated['permission_ids']);
            }

            DB::commit();

            return redirect()->route('role-permissions.index')
                ->with('success', 'Permissions bulk assigned successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to bulk assign permissions: ' . $e->getMessage());
        }
    }

    /**
     * Show bulk assignment form.
     */
    public function showBulkForm()
    {
        $roles = Role::orderBy('role_name')->get();
        $permissions = Permission::orderBy('permission_name')->get();
        
        return view('role-permissions.bulk-assign', compact('roles', 'permissions'));
    }
}