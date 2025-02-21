<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\AppSettings;
use App\Services\ShopifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ShopifyController extends Controller
{
    protected $shopifyService;

    public function __construct(ShopifyService $shopifyService)
    {
        $this->shopifyService = $shopifyService;
    }

    public function install(Request $request): RedirectResponse
    {
        // Validate shop parameter
        $validator = Validator::make($request->all(), [
            'shop' => ['required', 'string', 'regex:/^[a-zA-Z0-9][a-zA-Z0-9\-]*\.myshopify\.com$/']
        ]);

        if ($validator->fails()) {
            return redirect()->route('home')
                ->with('error', 'Invalid shop domain. Please enter a valid .myshopify.com domain.');
        }

        try {
            $shop = $request->get('shop');
            
            // Check if store already exists and is installed
            $store = Store::where('shop_domain', $shop)->first();
            
            if ($store && $store->installed && $store->access_token) {
                // If store is already installed, check app settings for redirect
                $settings = AppSettings::first();
                if ($settings && $settings->shouldDisplayEmbedded()) {
                    return $this->redirectToEmbeddedAdmin($shop, route('shopify.dashboard', ['shop' => $shop]));
                }
                return redirect()->route('shopify.dashboard', ['shop' => $shop]);
            }

            // Create or update store record
            $store = Store::updateOrCreate(
                ['shop_domain' => $shop],
                ['installed' => false, 'access_token' => null]
            );

            // Generate authorization URL
            $authUrl = $this->shopifyService->getAuthorizationUrl($store);
            
            if (!$authUrl) {
                throw new \Exception('Failed to generate authorization URL');
            }

            return redirect($authUrl);

        } catch (\Exception $e) {
            Log::error('Shopify installation error: ' . $e->getMessage(), [
                'shop' => $request->get('shop'),
                'error' => $e->getMessage()
            ]);

            return redirect()->route('home')
                ->with('error', 'Failed to start installation. Please try again.');
        }
    }

    public function callback(Request $request): RedirectResponse
    {
        // Validate callback parameters
        $validator = Validator::make($request->all(), [
            'shop' => 'required|string',
            'code' => 'required|string',
            'hmac' => 'required|string',
            'timestamp' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->route('home')
                ->with('error', 'Invalid callback parameters');
        }

        try {
            $shop = $request->get('shop');
            $code = $request->get('code');
            
            // Verify HMAC
            if (!$this->shopifyService->verifyHmac($request->all())) {
                throw new \Exception('HMAC verification failed');
            }

            // Get store record
            $store = Store::where('shop_domain', $shop)->firstOrFail();
            
            // Get access token
            $accessToken = $this->shopifyService->getAccessToken($store, $code);
            
            if (!$accessToken) {
                throw new \Exception('Failed to get access token');
            }

            // Update store record
            $store->update([
                'access_token' => $accessToken,
                'installed' => true,
                'installed_at' => now(),
            ]);

            // Set up initial webhooks
            $this->shopifyService->setupWebhooks($store);

            // Get app settings and determine redirect
            $settings = AppSettings::first();
            $redirectUrl = $settings ? $settings->getPostInstallUrl() : route('shopify.dashboard', ['shop' => $shop]);

            // Check if we should redirect to embedded admin
            if ($settings && $settings->shouldDisplayEmbedded() && $settings->post_install_redirect === 'admin') {
                return $this->redirectToEmbeddedAdmin($shop, $redirectUrl);
            }

            return redirect($redirectUrl)->with('success', 'App installed successfully!');

        } catch (\Exception $e) {
            Log::error('Shopify callback error: ' . $e->getMessage(), [
                'shop' => $request->get('shop'),
                'error' => $e->getMessage()
            ]);

            return redirect()->route('home')
                ->with('error', 'Installation failed. Please try again.');
        }
    }

    public function dashboard(Request $request): View
    {
      
        // Get store from the request (added by middleware)
        $store = $request->get('store');
        if (!$store) {
            $shop = $request->get('shop');
            $store = Store::where('shop_domain', $shop)
                ->where('installed', true)
                ->firstOrFail();
        }

        // Get shop information from Shopify
        $shopInfo = $this->shopifyService->getShopInfo($store);

        return view('shopify.dashboard', [
            'store' => $store,
            'shopInfo' => $shopInfo ?? null
        ]);
    }

    private function redirectToEmbeddedAdmin(string $shop, string $redirectUrl): RedirectResponse
    {
        $apiKey = config('shopify.api_key');
        $host = base64_encode("admin.shopify.com/store/{$shop}/apps/{$apiKey}");
        
        return redirect("https://{$shop}/admin/apps/{$apiKey}?host={$host}&target={$redirectUrl}");
    }
}