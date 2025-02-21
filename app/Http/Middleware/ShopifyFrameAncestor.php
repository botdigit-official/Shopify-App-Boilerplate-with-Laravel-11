<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class ShopifyFrameAncestor
{
    public function handle(Request $request, Closure $next): Response
    {
        // Process the request and get the response
        $response = $next($request);
        
        // Get the target URL from the request
        $targetUrl = $request->get('target');
        
        // Parse the target URL to extract domain/IP
        $parsedUrl = parse_url($targetUrl);
        $targetHost = $parsedUrl['host'] ?? '';
        
        // Comprehensive CSP that includes both HTTP and HTTPS
        $csp = 
            "default-src 'self' 'unsafe-inline' 'unsafe-eval' http: https:;" .
            "frame-src " .
            "app.shopify.com " .
            "*.shopifyapps.com " .
            "*.myshopify.com " .
            "http://* " .
            "https://* " .
            "http://{$targetHost} " .
            "https://{$targetHost} " .
            "shopify-pos://* " .
            "hcaptcha.com " .
            "*.hcaptcha.com " .
            "blob:;" .
            "frame-ancestors " .
            "app.shopify.com " .
            "*.shopifyapps.com " .
            "*.myshopify.com " .
            "https://admin.shopify.com " .
            "http://{$targetHost} " .
            "https://{$targetHost};";

        // Log for debugging
        Log::info('CSP Configuration', [
            'target_url' => $targetUrl,
            'target_host' => $targetHost,
            'csp' => $csp
        ]);

        $response->headers->set('Content-Security-Policy', $csp);
    
        return $response;
    }
}