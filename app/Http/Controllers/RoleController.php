<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()?->hasRole('demo-admin')) {
                return $next($request);
            }

            abort_unless(auth()->user()?->can('view role'), 403);

            return $next($request);
        })->only(['index', 'show']);

        $this->middleware('permission:create role')->only(['create', 'store', 'edit', 'update']);
        $this->middleware('permission:update role')->only(['addPermissionToRole', 'givePermissionToRole']);
        $this->middleware('permission:delete role')->only(['destroy']);
    }

    public function index()
    {
        if (auth()->user()->hasRole(['super-admin', 'demo-admin'])) {
            $roles = Role::get();
        } else {
            $roles = Role::where('name', '!=', 'super-admin')->get();
        }

        return view('role-permission.role.index', compact('roles'));
    }

    public function create()
    {
        return view('role-permission.role.create');
    }

    public function edit(string $id)
    {
        $role = Role::find($id);
        return view('role-permission.role.edit', compact('role'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:roles,name']
        ]);

        Role::find($id)->update([
            'name' => $request->name
        ]);

        return redirect('role')->with('status', 'Role Updated Successfully');
    }

    public function show(string $id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('role-permission.role.show', compact('role', 'rolePermissions'));
    }

    public function destroy(string $id)
    {
        Role::find($id)->delete();
        return redirect('role')->with('status', 'Role Deleted Successfully');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:roles,name']
        ]);

        Role::create([
            'name' => $request->name
        ]);

        return redirect('role')->with('status', 'Role Created Successfully');
    }

    public function addPermissionToRole(string $id)
    {
        $permission = Permission::get();
        $role = Role::find($id);
        $rolepermission = DB::table('role_has_permissions')
            ->where('role_has_permissions.role_id', $role->id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        $restrictedPermissions = [
            'create role',
            'view role',
            'update role',
            'delete role',
            'create permission',
            'view permission',
            'update permission',
            'delete permission',
            'create user',
            'view user',
            'update user',
            'delete user'
        ];

        return view('role-permission.role.add-permission', compact('role', 'permission', 'rolepermission', 'restrictedPermissions'));
    }

    public function givePermissionToRole(Request $request, string $id)
    {
        $request->validate([
            'permission' => ['required']
        ]);

        $role = Role::find($id);
        $role->syncPermissions($request->permission);

        return redirect()->back()->with('status', 'Permission added to Role');
    }
}
