@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard Overview')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Stores -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100">
                    <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Total Stores</h3>
                    <p class="text-2xl font-bold text-indigo-600">{{ $stats['total_stores'] }}</p>
                </div>
            </div>
        </div>

        <!-- Active Stores -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Active Stores</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['active_stores'] }}</p>
                </div>
            </div>
        </div>

        <!-- Recent Installations -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Recent Installs</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['recent_installs'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Stores Table -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <h3 class="text-lg font-medium text-gray-900">Recent Stores</h3>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($stats['recent_stores'] as $store)
            <div class="px-4 py-4 sm:px-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-lg font-medium text-indigo-600">{{ $store->shop_domain }}</h4>
                        <p class="mt-1 text-sm text-gray-500">Installed: {{ $store->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $store->installed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $store->installed ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
