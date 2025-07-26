<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function test_products_route_displays_products(): void
    {
        $store = \App\Models\Store::create([
            'shop_domain' => 'test.myshopify.com',
            'access_token' => 'dummy',
            'installed' => true,
        ]);

        Http::fake([
            "https://{$store->shop_domain}/*" => Http::response([
                'products' => [
                    ['title' => 'Sample Product'],
                ],
            ], 200),
        ]);

        $response = $this->get("/shopify/products?shop={$store->shop_domain}");

        $response->assertStatus(200);
        $response->assertSee('Sample Product');
    }
}
