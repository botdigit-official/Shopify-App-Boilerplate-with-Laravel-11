<!-- resources/views/layouts/shopify.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Shopify App Bridge via CDN -->
    <script src="https://unpkg.com/@shopify/app-bridge@3"></script>
    <script src="https://unpkg.com/@shopify/app-bridge-utils@3"></script>
    
    <script>
        // Configure Tailwind (optional, but allows customization)
        tailwind.config = {
            theme: {
                extend: {
                    // Add any custom configurations here
                }
            }
        };

        // Shopify App Bridge initialization
        document.addEventListener('DOMContentLoaded', () => {
            const AppBridge = window['app-bridge'];
            const createApp = AppBridge.createApp;
            
            const app = createApp({
                apiKey: '{{ config('shopify.api_key') }}',
                host: new URLSearchParams(location.search).get("host"),
                forceRedirect: true
            });
        });
    </script>
</head>
<body class="bg-white">
    <div class="app-wrapper">
        @yield('content')
    </div>
</body>
</html>