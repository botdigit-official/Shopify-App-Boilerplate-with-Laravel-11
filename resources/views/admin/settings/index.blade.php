@extends('layouts.admin')

@section('title', 'Settings')
@section('header', 'App Settings')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- General Settings -->
        <div class="bg-white shadow rounded-lg">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">General Settings</h2>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="app_name" class="block text-sm font-medium text-gray-700">App Name</label>
                        <input type="text" 
                               name="app_name" 
                               id="app_name"
                               value="{{ old('app_name', $settings->app_name) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('app_name') border-red-500 @enderror">
                        @error('app_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Shopify Settings -->
        <div class="bg-white shadow rounded-lg">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Shopify Settings</h2>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="shopify_api_key" class="block text-sm font-medium text-gray-700">API Key</label>
                        <input type="text" 
                               name="shopify_api_key" 
                               id="shopify_api_key"
                               value="{{ old('shopify_api_key', $settings->shopify_api_key) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="shopify_api_secret" class="block text-sm font-medium text-gray-700">API Secret</label>
                        <input type="password" 
                               name="shopify_api_secret" 
                               id="shopify_api_secret"
                               value="{{ old('shopify_api_secret', $settings->shopify_api_secret) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="shopify_scopes" class="block text-sm font-medium text-gray-700">API Scopes</label>
                        <input type="text" 
                               name="shopify_scopes" 
                               id="shopify_scopes"
                               value="{{ old('shopify_scopes', $settings->shopify_scopes) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-sm text-gray-500">Comma-separated list of scopes (e.g., read_products,write_products)</p>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="update_env" 
                               id="update_env"
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="update_env" class="ml-2 block text-sm text-gray-700">
                            Update .env file with these settings
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Installation Settings -->
        <div class="bg-white shadow rounded-lg">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Installation Settings</h2>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="post_install_redirect" class="block text-sm font-medium text-gray-700">Post-Installation Redirect</label>
                        <select name="post_install_redirect" 
                                id="post_install_redirect"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="admin" {{ $settings->post_install_redirect === 'admin' ? 'selected' : '' }}>Admin Dashboard</option>
                            <option value="frontend" {{ $settings->post_install_redirect === 'frontend' ? 'selected' : '' }}>Frontend</option>
                        </select>
                    </div>

                    <div>
                        <label for="display_mode" class="block text-sm font-medium text-gray-700">Display Mode</label>
                        <select name="display_mode" 
                                id="display_mode"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="embedded" {{ $settings->display_mode === 'embedded' ? 'selected' : '' }}>Embedded in Shopify Admin</option>
                            <option value="standalone" {{ $settings->display_mode === 'standalone' ? 'selected' : '' }}>Standalone Window</option>
                        </select>
                    </div>

                    <div>
                        <label for="admin_redirect_url" class="block text-sm font-medium text-gray-700">Custom Admin Redirect URL (Optional)</label>
                        <input type="url" 
                               name="admin_redirect_url" 
                               id="admin_redirect_url"
                               value="{{ old('admin_redirect_url', $settings->admin_redirect_url) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-sm text-gray-500">Leave empty to use default admin dashboard</p>
                    </div>

                    <div>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="enable_frontend" 
                                   id="enable_frontend"
                                   value="1"
                                   {{ $settings->enable_frontend ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="enable_frontend" class="ml-2 block text-sm text-gray-700">
                                Enable Frontend Access
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="frontend_url" class="block text-sm font-medium text-gray-700">Frontend URL</label>
                        <input type="url" 
                               name="frontend_url" 
                               id="frontend_url"
                               value="{{ old('frontend_url', $settings->frontend_url) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection