<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRoles;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $title = [
            'title' => 'Users',
        ];

        $users = User::paginate(15);

        return view('pages.admin.user', compact('title', 'users'));
    }

    public function add(Request $request)
    {
        $user = $request->user();

        if (! $user || $user->role !== UserRoles::Admin) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'max:100'],
            'role' => ['required', 'string', Rule::in(UserRoles::values())],
        ]);

        /** @var User $newUser */
        $newUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => UserRoles::from($validated['role']),
        ]);

        return redirect()->route('user.list')->with('success', 'User created successfully');
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        if (! $user || $user->role !== UserRoles::Admin) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        /** @var User $targetUser */
        $targetUser = User::findOrFail($id);

        // Check if trying to change the last admin's role
        if ($targetUser->role === UserRoles::Admin && $request->role !== UserRoles::Admin->value) {
            $adminCount = User::where('role', UserRoles::Admin)->count();
            if ($adminCount === 1) {
                return redirect()->back()->with('error', 'Cannot change role of the last admin user');
            }
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'role' => ['required', 'string', Rule::in(UserRoles::values())],
            'password' => ['nullable', 'string', 'min:6', 'max:100'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => UserRoles::from($validated['role']),
        ];

        if (isset($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $targetUser->update($updateData);

        return redirect()->route('user.list')->with('success', 'User updated successfully');
    }

    public function delete($id)
    {
        $user = Auth::user();

        if (! $user || $user->role !== UserRoles::Admin) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        /** @var User $targetUser */
        $targetUser = User::findOrFail($id);

        // Prevent deleting yourself
        if ($targetUser->id === Auth::id()) {
            return redirect()->back()->with('error', 'Cannot delete your own account');
        }

        // Check if trying to delete the last admin
        if ($targetUser->role === UserRoles::Admin) {
            $adminCount = User::where('role', UserRoles::Admin)->count();
            if ($adminCount === 1) {
                return redirect()->back()->with('error', 'Cannot delete the last admin user');
            }
        }

        $targetUser->delete();

        return redirect()->route('user.list')->with('success', 'User deleted successfully');
    }
}
