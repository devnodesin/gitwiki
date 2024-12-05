<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    public function profile(Request $request)
    {
        $title = [
            'title' => 'User Profile',
        ];

        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        return view('pages.user.profile', [
            'title' => $title,
            'user' => $user,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        $updateType = $request->input('update_type');
        $updateData = [];

        if ($updateType === 'profile') {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore(Auth::id())],
            ]);

            $updateData['name'] = $validated['name'];
            $updateData['email'] = $validated['email'];
        } elseif ($updateType === 'password') {
            $validated = $request->validate([
                'password' => ['required', 'string', 'min:6', 'max:100', 'confirmed'],
            ]);

            $updateData['password'] = Hash::make($validated['password']);
        } else {
            return redirect()->back()->with('error', 'Invalid update type');
        }

        $user->update($updateData);
        return redirect()->back()->with('success', 'Profile information updated successfully');
    }
}
