<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Setup Guide</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-3xl font-bold mb-8">Welcome to App Setup</h1>
                    
                    <div class="space-y-8">
                        @foreach($steps as $key => $step)
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    @if($step['complete'])
                                        <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </span>
                                    @else
                                        <span class="h-8 w-8 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                            <span class="text-gray-500">{{ $loop->iteration }}</span>
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="ml-4 flex-1">
                                    <h2 class="text-lg font-medium">{{ $step['title'] }}</h2>
                                    <p class="mt-2 text-gray-600">{{ $step['description'] }}</p>
                                    
                                    @if(!$step['complete'])
                                        <a href="{{ route('setup.' . str_replace('_', '-', $key)) }}" 
                                           class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                            Start This Step
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>