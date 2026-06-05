<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Product;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function refreshApplication()
    {
        parent::refreshApplication();

        \Illuminate\Support\Facades\Schema::create('products', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('image')->nullable();
            $table->string('category');
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Test applying and placing an order with an unassigned coupon.
     */
    public function test_applying_unassigned_coupon_binds_to_user_on_checkout(): void
    {
        // 1. Create a user
        $user = User::factory()->create([
            'role' => 'user',
            'spin_tickets' => 0
        ]);

        // 2. Create a product
        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 50000.00,
            'category' => 'tech',
            'delivery_type' => 'digital',
            'description' => 'Test',
            'stock' => 10
        ]);

        // 3. Create an unassigned coupon
        $coupon = Coupon::create([
            'code' => 'ADMIN10K-TESTING',
            'value' => 10000.00,
            'user_id' => null,
            'is_used' => false
        ]);

        // 4. Put product in cart
        $cart = [
            $product->id => [
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->effective_price,
                'image' => $product->image
            ]
        ];

        // 5. Apply coupon
        $response = $this->actingAs($user)
            ->withSession(['cart' => $cart])
            ->postJson(route('coupons.apply'), [
                'code' => 'ADMIN10K-TESTING'
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'coupon_code' => 'ADMIN10K-TESTING',
                'discount_amount' => 10000.00
            ]);

        $this->assertEquals($coupon->id, session('applied_coupon'));

        // 6. Place order
        $orderData = [
            'customer_name' => 'Test User',
            'customer_email' => 'test@example.com',
            'customer_phone' => '0123456789',
            'customer_address' => 'Test Address'
        ];

        $responseOrder = $this->actingAs($user)
            ->withSession([
                'cart' => $cart,
                'applied_coupon' => $coupon->id
            ])
            ->post(route('checkout.place'), $orderData);

        $responseOrder->assertRedirect(route('user.orders'));

        // 7. Verify coupon in DB is now bound and marked used
        $coupon->refresh();
        $this->assertTrue((bool)$coupon->is_used);
        $this->assertEquals($user->id, $coupon->user_id);
    }
}
