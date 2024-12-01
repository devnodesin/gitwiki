<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRoles;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->role) {
            abort(403);
        }
        $title = [
            'title' => 'Dashboard',
        ];

        $links = [
            'Wiki' => '#',
        ];

        return view('pages.dashboard', compact('title', 'links'));
    }
}
