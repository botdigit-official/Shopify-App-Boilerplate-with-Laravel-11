@extends('layouts.admin')

@section('title', 'Settings')
@section('header', 'App Settings')

@section('content')
<div class="space-y-6">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- General Settings -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">General Settings</h3>
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">App Name</label>
                        <input type="text" name="app_name" value="{{ old('app_name', $settings->app_name) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- Installation Settings -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Installation Settings</h3>
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Post-Installation Redirect</label>
                        <select name="post_install_redirect" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="admin" {{ $settings->post_install_redirect === 'admin' ? 'selected' : '' }}>
                                Admin Dashboard
                            </option>
                            <option value="frontend" {{ $settings->post_install_redirect === 'frontend' ? 'selected' : '' }}>
                                Frontend
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Display Mode</label>
                        <select name="display_mode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="embedded" {{ $settings->display_mode === 'embedded' ? 'selected' : '' }}>
                                Embedded in Shopify Admin
                            </option>
                            <option value="standalone" {{ $settings->display_mode === 'standalone' ? 'selected' : '' }}>
                                Standalone Window
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection