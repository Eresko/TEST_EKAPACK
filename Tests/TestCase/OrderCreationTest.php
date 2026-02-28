<?php
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;

class OrderCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_order_creation()
    {

        $productQty = 10;
        $productPrice = 100;
        $product = Product::inRandomOrder()->first();
        $customer = Customer::inRandomOrder()->first();

        $data = [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
            'customer_id' => $customer->id
        ];


        $response = $this->postJson(route('orders.create'), $data);


        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'customer',
            'status',
            'total_amount',
            'items'
        ]);

        $this->assertDatabaseHas('orders', [
            'id',
            'customer',
            'status',
            'total_amount',
            'items'
        ]);

        $this->assertDatabaseHas('inventories', [
            'product_id' => 1,
            'stock' => $productQty - 2,
        ]);


        $order = Order::latest()->first();
        $expectedTotal = $productPrice * 2;
        $this->assertEquals($expectedTotal, $order->total);
    }
}