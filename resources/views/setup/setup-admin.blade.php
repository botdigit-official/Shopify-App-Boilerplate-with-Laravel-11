<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Admin Account</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-3xl font-bold mb-8">Create Admin Account</h1>
                    
                    <form action="{{ route('setup.complete-step', 'setup_admin') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" required 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" required 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" required 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" name="password_confirmation" required 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Create Admin Account
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>