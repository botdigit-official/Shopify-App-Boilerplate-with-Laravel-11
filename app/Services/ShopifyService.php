<?php
namespace App\Services;

use App\Models\Store;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShopifyService
{
    protected $apiKey;
    protected $apiSecret;
    protected $scopes;
    protected $redirectUri;

    public function __construct()
    {
        $this->apiKey = config('shopify.api_key');
        $this->apiSecret = config('shopify.api_secret');
        $this->scopes = config('shopify.scopes');
        $this->redirectUri = config('shopify.redirect_uri');
    }

    public function getAuthorizationUrl(Store $store): string
    {
        // Generate and store nonce
        $nonce = Str::random(20);
        $store->update(['nonce' => $nonce]);

        $params = [
            'client_id' => $this->apiKey,
            'scope' => $this->scopes,
            'redirect_uri' => $this->redirectUri,
            'state' => $nonce,
            'access_mode' => 'offline'
        ];

        return "https://{$store->shop_domain}/admin/oauth/authorize?" . http_build_query($params);
    }

    public function verifyHmac(array $params): bool
    {
        // Remove hmac from params
        $hmac = $params['hmac'];
        unset($params['hmac']);

        // Sort parameters
        ksort($params);

        // Create signature
        $signature = http_build_query($params);
        $calculatedHmac = hash_hmac('sha256', $signature, $this->apiSecret);

        return hash_equals($hmac, $calculatedHmac);
    }

    public function getAccessToken(Store $store, string $code): ?string
    {
        try {
            $response = Http::post("https://{$store->shop_domain}/admin/oauth/access_token", [
                'client_id' => $this->apiKey,
                'client_secret' => $this->apiSecret,
                'code' => $code,
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to get access token: ' . $response->body());
            }

            return $response->json('access_token');

        } catch (\Exception $e) {
            Log::error('Failed to get access token: ' . $e->getMessage());
            return null;
        }
    }

    public function setupWebhooks(Store $store): void
    {
        $webhooks = [
            [
                'topic' => 'app/uninstalled',
                'address' => route('webhooks.app_uninstalled')
            ],
            // Add more webhooks as needed
        ];

        foreach ($webhooks as $webhook) {
            $this->createWebhook($store, $webhook['topic'], $webhook['address']);
        }
    }

    public function getShopInfo(Store $store): ?array
    {
        try {
            $response = Http::withToken($store->access_token)
                ->get("https://{$store->shop_domain}/admin/api/2024-01/shop.json");

            if (!$response->successful()) {
                throw new \Exception('Failed to get shop info: ' . $response->body());
            }

            return $response->json('shop');

        } catch (\Exception $e) {
            Log::error('Failed to get shop info: ' . $e->getMessage());
            return null;
        }
    }

    protected function createWebhook(Store $store, string $topic, string $address): void
    {
        try {
            $response = Http::withToken($store->access_token)
                ->post("https://{$store->shop_domain}/admin/api/2024-01/webhooks.json", [
                    'webhook' => [
                        'topic' => $topic,
                        'address' => $address,
                        'format' => 'json'
                    ]
                ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to create webhook: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error("Failed to create webhook {$topic}: " . $e->getMessage());
        }
    }
}