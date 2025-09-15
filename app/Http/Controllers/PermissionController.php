<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    public function __construct()
    {
        // Apply middleware - only super admins can manage permissions
        $this->middleware('auth');
        $this->middleware('admin')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::latest()->paginate(15);
        return view('permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissionCategories = [
            'user' => 'User Permissions',
            'role' => 'Role Permissions', 
            'permission' => 'Permission Management',
            'content' => 'Content Management',
            'financial' => 'Financial Operations',
            'system' => 'System Settings'
        ];
        
        return view('permissions.create', compact('permissionCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'permission_name' => 'required|string|max:255|unique:permissions,permission_name',
            'description' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100'
        ]);

        Permission::create($validated);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        // Load roles that have this permission
        $permission->load('roles');
        
        return view('permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        $permissionCategories = [
            'user' => 'User Permissions',
            'role' => 'Role Permissions',
            'permission' => 'Permission Management',
            'content' => 'Content Management',
            'financial' => 'Financial Operations',
            'system' => 'System Settings'
        ];
        
        return view('permissions.edit', compact('permission', 'permissionCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'permission_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'permission_name')->ignore($permission->id)
            ],
            'description' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100'
        ]);

        $permission->update($validated);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        // Prevent deletion of core permissions
        $corePermissions = [
            'view_users', 'create_users', 'edit_users', 'delete_users',
            'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
            'view_permissions', 'create_permissions', 'edit_permissions', 'delete_permissions'
        ];
        
        if (in_array($permission->permission_name, $corePermissions)) {
            return redirect()->route('permissions.index')
                ->with('error', 'Cannot delete core system permission.');
        }

        // Detach from roles before deletion
        $permission->roles()->detach();
        
        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }

    /**
     * Search permissions by name or category.
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        
        $permissions = Permission::where('permission_name', 'like', "%{$search}%")
            ->orWhere('category', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->latest()
            ->paginate(15);
            
        return view('permissions.index', compact('permissions'));
    }
}