<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use App\Models\AppSettings;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function dashboard(): View
    {
        // Get recent installations (last 30 days)
        $recentInstallations = Store::where('installed', true)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->count();

        // Get stores with pagination
        $stores = Store::latest()->paginate(10);

        // Get 5 most recent stores directly without mapping
        $recentStores = Store::latest()->take(5)->get();

        // Compile all stats
        $stats = [
            'total_stores' => Store::count(),
            'active_stores' => Store::where('installed', true)->count(),
            'recent_installs' => $recentInstallations,
            'total_admins' => User::where('is_admin', true)->count(),
            'recent_stores' => $recentStores
        ];

        return view('admin.dashboard', compact('stats', 'stores'));
    }

    public function stores(): View
    {
        $stores = Store::with('subscription')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.stores', compact('stores'));
    }

    public function storeDetails(Store $store): View
    {
        return view('admin.store-details', compact('store'));
    }

    public function settings(): View
    {
        $settings = AppSettings::first();
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'post_install_redirect' => 'required|in:admin,frontend',
            'display_mode' => 'required|in:embedded,standalone',
        ]);

        AppSettings::first()->update($validated);

        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully');
    }
}