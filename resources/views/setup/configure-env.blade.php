<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configure Environment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-3xl font-bold mb-8">Configure Environment</h1>
                    @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">Please fix the following errors:</strong>
                    <ul class="mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Message -->
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif
                    <div class="prose max-w-none">
                        <h2 class="text-xl font-semibold mb-4">Update Your .env File</h2>
                        
                        <p class="mb-4">Add these variables to your .env file:</p>
                        
                        <pre class="bg-gray-100 p-4 rounded mb-6">
SHOPIFY_API_KEY=your_api_key
SHOPIFY_API_SECRET=your_api_secret
SHOPIFY_SCOPES=read_products,write_products
SHOPIFY_REDIRECT_URI={{ url('/shopify/callback') }}</pre>

                        <form action="{{ route('setup.complete-step', 'configure_env') }}" method="POST" class="mt-8">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">API Key</label>
                                    <input type="text" name="shopify_api_key" required 
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">API Secret</label>
                                    <input type="password" name="shopify_api_secret" required 
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                            </div>

                            <button type="submit" class="mt-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Save Configuration
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>