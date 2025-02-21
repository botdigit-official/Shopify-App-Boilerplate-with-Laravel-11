<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\AppSettings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Excluded paths that should bypass setup check
        $excludedPaths = [
            'setup*',      // Setup routes
            'assets*',     // Asset files
            '_debugbar/*', // Debug bar routes
            'api/*',       // API routes
            '_ignition/*', // Ignition error pages
            'livewire/*',  // Livewire routes if used
            'storage/*',   // Storage routes
        ];

        // Skip checks in console or for excluded paths
        if ($this->app->runningInConsole() || 
            $this->isExcludedPath($excludedPaths)) {
            return;
        }

        // Register the check after the application has booted
        $this->app->booted(function () {
            $this->handleSetupRedirect();
        });

        // Share CSRF token with all views
        view()->share('csrf_token', csrf_token());
    }

    /**
     * Check if current path should be excluded
     */
    private function isExcludedPath(array $excludedPaths): bool
    {
        $currentPath = request()->path();
        
        foreach ($excludedPaths as $path) {
            if (request()->is($path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Handle setup redirect logic
     */
    private function handleSetupRedirect(): void
    {
        try {
            // Check if we're already on a setup page
            if (request()->is('setup*')) {
                return;
            }

            // Verify database connection first
            \DB::connection()->getPdo();

            // Check if app_settings table exists
            if (!Schema::hasTable('app_settings')) {
                if (Route::has('setup.index')) {
                    redirect()->route('setup.index')->send();
                }
                return;
            }

            // Check if it's first install
            if (AppSettings::where('is_installed', false)->exists() || 
                !AppSettings::exists()) {
                if (Route::has('setup.index')) {
                    redirect()->route('setup.index')->send();
                }
            }

        } catch (\Exception $e) {
            // Log the error but don't redirect if there's a database issue
            \Log::error('Setup check failed: ' . $e->getMessage());
            
            // Only redirect to setup if we can confirm it's a database issue
            if ($e instanceof \PDOException || 
                $e instanceof \Illuminate\Database\QueryException) {
                if (Route::has('setup.index')) {
                    redirect()->route('setup.index')->send();
                }
            }
        }
    }
}