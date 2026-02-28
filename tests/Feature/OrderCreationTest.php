<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;

class OrderCreationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест заказ через Api
     */
    public function test_successful_order_creation(): void
    {

        $product = Product::factory()->create([
            'price' => 100,
            'stock_quantity' => 10,
        ]);

        $customer = Customer::factory()->create();


        $payload = [
            'customer_id' => $customer->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ];


        $response = $this->postJson('/api/orders', $payload);


        $response->assertStatus(200);


        $response->assertJsonStructure([
            'order_id',
            'status',
        ]);


        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'total_amount' => 200,
        ]);


        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 8,
        ]);


        $order = Order::latest()->first();
        $this->assertEquals(200, $order->total_amount);
    }
}