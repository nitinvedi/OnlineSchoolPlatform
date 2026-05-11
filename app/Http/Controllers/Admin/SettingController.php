<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'platform_name' => config('app.name'),
            'maintenance_mode' => app()->isDownForMaintenance(),
            'features' => [
                'reviews' => true,
                'live_sessions' => true,
                'certificates' => true,
            ],
        ];

        // Fetch real coupons if table exists
        $coupons = [];
        if (Schema::hasTable('coupons')) {
            $coupons = \App\Models\Coupon::where('expires_at', '>', now())
                ->orWhereNull('expires_at')
                ->get();
        }

        return view('admin.settings.index', compact('settings', 'coupons'));
    }

    public function update(Request $request)
    {
        // Update settings
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            // Save to settings
        }

        if ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->store('favicons', 'public');
            // Save to settings
        }

        // Update other settings
        return back()->with('success', 'Settings updated.');
    }

    public function toggleMaintenance()
    {
        if (app()->isDownForMaintenance()) {
            // Bring up
            // Artisan::call('up');
        } else {
            // Take down
            // Artisan::call('down');
        }

        return back()->with('success', 'Maintenance mode toggled.');
    }

    public function createCoupon(Request $request)
    {
        // Implement coupon creation
        return back()->with('success', 'Coupon created.');
    }

    public function expireCoupon($couponId)
    {
        // Implement coupon expiration
        return back()->with('success', 'Coupon expired.');
    }
}
