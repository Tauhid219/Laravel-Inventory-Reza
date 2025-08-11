<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create permission')->only(['create', 'store']);
        $this->middleware('permission:update permission')->only(['edit', 'update']);
        $this->middleware('permission:view permission')->only(['index', 'show']);
        $this->middleware('permission:delete permission')->only(['destroy']);
    }

    public function index()
    {
        $permission = Permission::get();
        return view('role-permission.permission.index', [
            'permission' => $permission
        ]);
    }

    public function create()
    {
        return view('role-permission.permission.create');
    }

    public function edit(string $id)
    {
        $permission = Permission::find($id);
        return view('role-permission.permission.edit', compact('permission'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:permissions,name']
        ]);

        Permission::find($id)->update([
            'name' => $request->name
        ]);

        return redirect('permission')->with('status', 'Permission Updated Successfully');
    }

    public function destroy(string $id)
    {
        Permission::find($id)->delete();
        return redirect('permission')->with('status', 'Permission Deleted Successfully');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:permissions,name']
        ]);

        Permission::create([
            'name' => $request->name
        ]);

        return redirect('permission')->with('status', 'Permission Created Successfully');
    }
}
