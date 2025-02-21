<?php
// app/Http/Controllers/Admin/SettingController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = AppSettings::firstOrCreate([], [
            'app_name' => config('app.name'),
            'post_install_redirect' => 'admin',
            'display_mode' => 'embedded',
            'enable_frontend' => false,
            'admin_redirect_url' => null,
            'frontend_url' => null,
            'shopify_api_key' => config('shopify.api_key'),
            'shopify_api_secret' => config('shopify.api_secret'),
            'shopify_scopes' => config('shopify.scopes'),
        ]);

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'post_install_redirect' => 'required|in:admin,frontend',
            'display_mode' => 'required|in:embedded,standalone',
            'enable_frontend' => 'boolean',
            'admin_redirect_url' => 'nullable|url',
            'frontend_url' => 'nullable|url',
            'shopify_api_key' => 'required|string',
            'shopify_api_secret' => 'required|string',
            'shopify_scopes' => 'required|string',
        ]);

        $settings = AppSettings::first();
        $settings->update($validated);

        // Optionally update .env file for Shopify settings
        if ($request->has('update_env')) {
            $this->updateEnvFile([
                'SHOPIFY_API_KEY' => $validated['shopify_api_key'],
                'SHOPIFY_API_SECRET' => $validated['shopify_api_secret'],
                'SHOPIFY_SCOPES' => $validated['shopify_scopes'],
            ]);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully');
    }

    private function updateEnvFile(array $values): void
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);

        foreach ($values as $key => $value) {
            if (str_contains($envContent, $key . '=')) {
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}=" . $value,
                    $envContent
                );
            } else {
                $envContent .= "\n{$key}=" . $value;
            }
        }

        file_put_contents($envFile, $envContent);
    }
}
