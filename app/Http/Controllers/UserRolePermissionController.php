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
        if (auth()->user()->hasRole('super-admin')) {
            $user = User::get();
        } else {
            $user = User::where(function ($query) {
                $query->whereDoesntHave('roles', function ($q) {
                    $q->where('name', 'super-admin');
                })->orWhere('id', auth()->id());
            })
                ->get();
        }

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

    public function update(Request $request, User $user) // Update method
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'nullable|min:4|max:25|alpha_dash:ascii|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:8|max:20',
            'role' => 'required|array',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Sync roles to the user
        $user->syncRoles($request->role);

        return redirect()->route('user.index')->with('status', 'User Updated Successfully');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|min:4|max:25|alpha_dash:ascii|unique:users,username',
            'password' => 'required|string|min:8|max:20',
            'role' => 'required|array', // Confirm at least one role is selected
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        // Sync roles to the user
        $user->syncRoles($request->role);

        return redirect()->route('user.index')->with('status', 'User Created Successfully');
    }

    public function destroy(string $id)
    {
        User::find($id)->delete();
        return redirect('user')->with('status', 'User Deleted Successfully');
    }
}
