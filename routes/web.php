<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopifyController;

use App\Http\Controllers\SetupController;

use App\Http\Controllers\Auth\LoginController;
use App\Models\AppSettings;

use App\Models\Store;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    // Check if app is not fully installed
    if (AppSettings::isFirstInstall()) {
        return redirect()->route('setup.index');
    }

    $host = $request->get('host');
    $target = $request->get('target');
    
    // If host and target are present, it's a Shopify embedded app redirect
    if ($host && $target) {
        // Parse the target URL to extract shop parameter
        $targetUrl = parse_url($target);
        parse_str($targetUrl['query'] ?? '', $targetQuery);
        
        $shop = $targetQuery['shop'] ?? null;
        
        if ($shop) {
            // Redirect to the target URL directly
            return redirect($target);
        }
    }

    // Get shop parameter from request
    $shop = $request->get('shop');
   
    // If a Shopify shop is specified in the request
    if ($shop) {
        // Check if the store exists and is installed
        $store = Store::where('shop_domain', $shop)
            ->where('installed', true)
            ->first();
        
        if ($store) {
            // Always redirect to Shopify dashboard when a shop is specified
            return redirect()->route('shopify.dashboard', ['shop' => $shop]);
        }
    }

    // Check if user is authenticated
    if (auth()->check()) {
        // Admin user - redirect to admin dashboard
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
    }

    // If no special conditions, show welcome page
    return view('welcome');
})->name('home');

// Auth Routes
Route::middleware('web')->group(function () {
    Route::get('admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('admin/login', [LoginController::class, 'login']);
    Route::post('admin/logout', [LoginController::class, 'logout'])->name('admin.logout');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Setup Routes
Route::group(['prefix' => 'setup'], function () {
    Route::get('/', [SetupController::class, 'index'])->name('setup.index');
    Route::get('/create-app', [SetupController::class, 'createApp'])->name('setup.create-app');
    Route::get('/configure-env', [SetupController::class, 'configureEnv'])->name('setup.configure-env');
    Route::get('/setup-admin', [SetupController::class, 'setupAdmin'])->name('setup.setup-admin');
    Route::post('/complete-step/{step}', [SetupController::class, 'completeStep'])->name('setup.complete-step');
    Route::get('/complete', [SetupController::class, 'complete'])->name('setup.complete');
});

// Shopify routes
Route::prefix('shopify')->group(function () {
    Route::get('install', [ShopifyController::class, 'install'])->name('shopify.install');
    Route::get('callback', [ShopifyController::class, 'callback'])->name('shopify.callback');
    
    Route::middleware(['auth.shopify'])->group(function () {
        
        Route::get('dashboard', [ShopifyController::class, 'dashboard'])->name('shopify.dashboard');
        // Add other protected Shopify routes here
    });
});

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Stores Management
    Route::prefix('stores')->name('stores.')->group(function () {
        Route::get('/', [StoreController::class, 'index'])->name('index');
        Route::get('/{store}', [StoreController::class, 'show'])->name('show');
        Route::post('/{store}/uninstall', [StoreController::class, 'uninstall'])->name('uninstall');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/update', [SettingController::class, 'update'])->name('update');
    });
});
