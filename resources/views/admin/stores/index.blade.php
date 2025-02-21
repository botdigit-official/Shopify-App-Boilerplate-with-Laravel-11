@extends('layouts.admin')

@section('title', 'Stores')
@section('header', 'Store Management')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">All Stores</h3>
            <div class="flex gap-2">
                <input type="text" 
                       placeholder="Search stores..." 
                       class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Installed Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($stores as $store)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $store->shop_domain }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $store->installed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $store->installed ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $store->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.stores.show', $store) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="px-4 py-3 border-t border-gray-200">
        {{ $stores->links() }}
    </div>
</div>
@endsection