<?php
// app/Http/Middleware/ShopifyAuth.php

namespace App\Http\Middleware;

use Closure;
use App\Models\Store;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShopifyAuth
{
    public function handle(Request $request, Closure $next): Response
    {
      
        $shop = $request->get('shop');
        
        if (!$shop) {
            return redirect()->route('home')
                ->with('error', 'No shop provided');
        }

        $store = Store::where('shop_domain', $shop)
            ->where('installed', true)
            ->where('access_token', '!=', null)
            ->first();
        
        if (!$store) {
            return redirect()->route('shopify.install', ['shop' => $shop]);
        }

        // Add store to request for controller access
        $request->attributes->add(['store' => $store]);
        
        return $next($request);
    }
}