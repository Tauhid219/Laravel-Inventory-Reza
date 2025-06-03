<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserRolePermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view user')->only(['index']);
        $this->middleware('permission:create user')->only(['create', 'store']);
        $this->middleware('permission:update user')->only(['edit', 'update', 'updatePassword']);
        $this->middleware('permission:delete user')->only(['destroy']);
    }

    public function index()
    {
        $user = User::get();
        return view('role-permission.user.index', compact('user'));
    }

    public function create()
    {
        $role = Role::get();
        return view('role-permission.user.create', compact('role'));
    }

    public function edit(User $user)
    {
        $role = Role::all();
        $userRole = $user->roles->pluck('id')->toArray(); // or getRoleNames() if you are using role names
        return view('role-permission.user.edit', compact('user', 'role', 'userRole'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|max:20',
            'role' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,

        ];

        if (!empty($request->password)) {
            $data += [
                'password' => Hash::make($request->password)
            ];
        }

        $user = User::find($id);
        $user->update($data);

        // Get role names from role IDs
        $roleNames = Role::whereIn('id', $request->role)->pluck('name')->toArray();
        $user->syncRoles($roleNames);

        return redirect('user')->with('status', 'User Updated Successfully with Roles');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:20',
            'role' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->syncRoles($request->role);

        return redirect('/user')->with('status', 'User Created Successfully with Roles');
    }

    public function destroy(string $id)
    {
        User::find($id)->delete();
        return redirect('user')->with('status', 'User Deleted Successfully');
    }
}
