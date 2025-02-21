<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AppSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class SetupController extends Controller
{
    /**
     * Show the setup index page.
     */
    public function index(): View
    {
        $steps = [
            'create_app' => [
                'title' => 'Create Shopify App',
                'description' => 'Create a new app in your Shopify Partners Dashboard',
                'complete' => AppSettings::isStepComplete('create_app')
            ],
            'configure_env' => [
                'title' => 'Configure Environment',
                'description' => 'Set up your API credentials',
                'complete' => AppSettings::isStepComplete('configure_env')
            ],
            'setup_admin' => [
                'title' => 'Set Up Admin Account',
                'description' => 'Create your admin login credentials',
                'complete' => AppSettings::isStepComplete('setup_admin')
            ]
        ];

        return view('setup.index', compact('steps'));
    }

    /**
     * Show the create app step.
     */
    public function createApp(): View
    {
        if (AppSettings::isStepComplete('create_app')) {
            return redirect()->route('setup.configure-env');
        }
        
        return view('setup.create-app');
    }

    /**
     * Show the configure environment step.
     */
    public function configureEnv(): View
    {
        if (!AppSettings::isStepComplete('create_app')) {
            return redirect()->route('setup.create-app')
                ->with('error', 'Please complete the previous step first.');
        }
        
        return view('setup.configure-env');
    }

    /**
     * Show the setup admin step.
     */
    public function setupAdmin(): View
    {
        if (!AppSettings::isStepComplete('configure_env')) {
            return redirect()->route('setup.configure-env')
                ->with('error', 'Please complete the previous step first.');
        }

        return view('setup.setup-admin');
    }

    /**
     * Complete a setup step.
     */
    public function completeStep(Request $request, string $step): RedirectResponse
    {
        if ($step === 'setup_admin') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            try {
                // Create admin user
                User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'is_admin' => true,
                ]);

                AppSettings::markStepComplete($step);
                return redirect()->route('setup.complete');

            } catch (\Exception $e) {
                return back()
                    ->with('error', 'Failed to create admin user. Please try again.')
                    ->withInput();
            }
        }
        
        // For other steps
        try {
            if ($step === 'configure_env') {
                // Validate environment settings
                $validator = Validator::make($request->all(), [
                    'shopify_api_key' => 'required|string',
                    'shopify_api_secret' => 'required|string',
                ]);

                if ($validator->fails()) {
                    return back()
                        ->withErrors($validator)
                        ->withInput();
                }

                // Update .env file or settings as needed
                // You might want to implement this based on your needs
            }

            AppSettings::markStepComplete($step);
            
            $nextSteps = [
                'create_app' => 'configure-env',
                'configure_env' => 'setup-admin',
                'setup_admin' => 'complete'
            ];

            return redirect()->route('setup.' . ($nextSteps[$step] ?? 'complete'))
                ->with('success', 'Step completed successfully');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to complete step. Please try again.')
                ->withInput();
        }
    }

    /**
     * Complete the setup process.
     */
    public function complete(): RedirectResponse
    {
        try {
            if (!AppSettings::isStepComplete('setup_admin')) {
                return redirect()->route('setup.setup-admin')
                    ->with('error', 'Please complete all steps first.');
            }

            $settings = AppSettings::first();
            $settings->update(['is_installed' => true]);
            
            return redirect()->route('admin.login')
                ->with('success', 'Setup completed successfully! Please log in.');

        } catch (\Exception $e) {
            return redirect()->route('setup.index')
                ->with('error', 'Failed to complete setup. Please try again.');
        }
    }
}