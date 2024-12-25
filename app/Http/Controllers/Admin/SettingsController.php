<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.settings', [
            'title' => ['title' => 'Settings'],
            'settings' => Settings::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function update(Request $request)
    {
        try {
            foreach ($request->except('_token', '_method') as $key => $value) {
                // Get current setting to check if editable
                $setting = Settings::where('key', $key)->first();

                if (! $setting || ! $setting->edit) {
                    continue;
                }

                set_setting($key, $value);
            }

            return redirect()
                ->back()
                ->with('success', 'Settings updated successfully');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to update settings: '.$e->getMessage());
        }
    }
}
