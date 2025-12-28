<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        // TODO: Select columns
        $users = User::all();

        return view('users.index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        $role = Role::get();
        return view('users.create', compact('role'));

        // return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $requestData = $request->all();

        if ($request->has('password')) {
            $requestData['password'] = bcrypt($request->password);
        }

        $user = User::create($requestData);

        // Assign Role to User
        if ($request->has('roles')) {
            $user->assignRole($request->roles);
        }

        /**
         * Handle upload an image
         */
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();

            $file->storeAs('profile/', $filename, 'public');
            $user->update([
                'photo' => $filename
            ]);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'New User has been created!');
    }

    public function show(User $user)
    {
        return view('users.show', [
            'user' => $user
        ]);
    }

    public function edit(User $user)
    {
        // Assign Role to User
        $roles = Role::all();
        return view('users.edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {

        // ১. সাধারণ ডাটা আপডেট (ফটো বাদে)
        $user->update($request->except(['photo', 'roles']));

        // ২. স্প্যাটি রোল আপডেট (সিঙ্ক করা)
        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        // ৩. ফটো হ্যান্ডলিং এবং আপডেট
        if ($request->hasFile('photo')) {
            if ($user->photo && file_exists(public_path('storage/profile/') . $user->photo)) {
                unlink(public_path('storage/profile/') . $user->photo);
            }

            $file = $request->file('photo');
            $fileName = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('profile/', $fileName, 'public');

            $user->update(['photo' => $fileName]);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User has been updated!');
    }

    public function updatePassword(Request $request, User $user)
    {
        # Validation
        $validated = $request->validate([
            'password' => 'required_with:password_confirmation|min:6',
            'password_confirmation' => 'same:password|min:6',
        ]);

        # Update the new Password
        // User::where('username', $username)->update([
        //     'password' => Hash::make($validated['password'])
        // ]);
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User has been updated!');
    }

    public function destroy(User $user)
    {
        /**
         * Delete photo if exists.
         */
        if ($user->photo) {
            unlink(public_path('storage/profile/') . $user->photo);
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User has been deleted!');
    }
}
