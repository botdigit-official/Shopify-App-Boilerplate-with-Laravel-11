
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <title>Create Shopify App</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-3xl font-bold mb-8">Create Your Shopify App</h1>
                    
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    
                    <div class="prose max-w-none">
                        <h2 class="text-xl font-semibold mb-4">Follow these steps:</h2>
                        
                        <ol class="list-decimal list-inside space-y-4">
                            <li>Log in to your <a href="https://partners.shopify.com" target="_blank" class="text-indigo-600 hover:text-indigo-800">Shopify Partners Dashboard</a></li>
                            <li>Click on "Apps" in the left sidebar</li>
                            <li>Click "Create app"</li>
                            <li>Set the following configuration:
                                <ul class="list-disc list-inside ml-6 mt-2">
                                    <li>App URL: <code class="bg-gray-100 px-2 py-1 rounded">{{ url('/') }}</code></li>
                                    <li>Allowed redirection URL(s): <code class="bg-gray-100 px-2 py-1 rounded">{{ url('/shopify/callback') }}</code></li>
                                </ul>
                            </li>
                            <li>Copy your API credentials for the next step</li>
                        </ol>

                        <form action="{{ route('setup.complete-step', ['step' => 'create_app']) }}" method="POST" class="mt-8">
                            @csrf
                         
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                I've Created My App
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>