@extends('layouts.shopify')

@section('title', 'Dashboard')

@section('header', 'Dashboard')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Store Information -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Store Information</h3>
                <dl class="grid grid-cols-1 gap-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Store Domain</dt>
                        <dd class="text-sm text-gray-900">{{ $store->shop_domain }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Installation Date</dt>
                        <dd class="text-sm text-gray-900">{{ $store->created_at->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="#" class="block w-full text-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Action 1
                    </a>
                    <a href="#" class="block w-full text-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Action 2
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-500 text-sm">No recent activity</p>
            </div>
        </div>
    </div>
</div>
@endsection