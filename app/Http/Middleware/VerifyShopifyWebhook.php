<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyShopifyWebhook
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->hasHeader('X-Shopify-Hmac-Sha256')) {
            abort(401, 'Missing Shopify HMAC header');
        }

        $hmac = $request->header('X-Shopify-Hmac-Sha256');
        $data = $request->getContent();
        $calculatedHmac = base64_encode(hash_hmac('sha256', $data, config('shopify.api_secret'), true));

        if (!hash_equals($hmac, $calculatedHmac)) {
            abort(401, 'Invalid HMAC');
        }

        return $next($request);
    }
}