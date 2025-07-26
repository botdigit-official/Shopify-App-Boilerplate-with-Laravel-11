@extends('layouts.shopify')

@section('title', 'Products')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Products</h1>
        @if (empty($products))
            <p class="text-gray-500">No products found.</p>
        @else
            <ul class="space-y-2">
                @foreach ($products as $product)
                    <li class="bg-gray-100 p-4 rounded">
                        <h2 class="font-semibold">{{ $product['title'] }}</h2>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection

