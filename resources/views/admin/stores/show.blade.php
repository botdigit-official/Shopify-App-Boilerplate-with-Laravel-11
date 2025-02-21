@extends('layouts.admin')

@section('title', 'Store Details')
@section('header', $store->shop_domain)

@section('content')
<div class="space-y-6">
    <!-- Store Details Card -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Store Domain</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $store->shop_domain }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $store->installed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $store->installed ? 'Active' : 'Inactive' }}
                        </span>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Installation Date</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $store->created_at->format('M d, Y H:i:s') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $store->updated_at->format('M d, Y H:i:s') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900">Store Actions</h3>
            <div class="mt-5">
                <form action="{{ route('admin.stores.uninstall', $store) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                        Uninstall App
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
