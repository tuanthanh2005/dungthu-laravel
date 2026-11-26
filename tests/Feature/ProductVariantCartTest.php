<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductVariantCartTest extends TestCase
{
    use RefreshDatabase;

    protected function refreshApplication()
    {
        parent::refreshApplication();

        if (!\Illuminate\Support\Facades\Schema::hasTable('products')) {
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
    }

    public function test_product_with_variants_redirects_to_detail_page_when_variant_not_specified(): void
    {
        $product = Product::create([
            'name' => 'Claude Code Pro',
            'slug' => 'claude-code-pro',
            'description' => 'Claude Code Pro Plan',
            'price' => 430000,
            'category' => 'tech',
            'stock' => 10,
            'is_active' => true,
        ]);

        $v1 = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Gói 1 Tháng',
            'price' => 430000,
            'stock' => 0, // Hết hàng
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $v2 = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Gói 5 Tháng',
            'price' => 1950000,
            'stock' => 10, // Còn hàng
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Attempt to add to cart without variant_id
        $response = $this->post(route('cart.add', $product->id));

        // Must redirect to detail page
        $response->assertRedirect(route('product.show', $product->slug));
        $response->assertSessionHas('error');
    }

    public function test_out_of_stock_variant_cannot_be_added(): void
    {
        $product = Product::create([
            'name' => 'Claude Code Pro',
            'slug' => 'claude-code-pro-out',
            'description' => 'Claude Code Pro Plan',
            'price' => 430000,
            'category' => 'tech',
            'stock' => 10,
            'is_active' => true,
        ]);

        $v1 = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Gói Hết Hàng',
            'price' => 430000,
            'stock' => 0,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->from(route('product.show', $product->slug))
            ->post(route('cart.add', $product->id), [
                'variant_id' => $v1->id,
            ]);

        $response->assertRedirect(route('product.show', $product->slug));
        $response->assertSessionHas('error');
    }

    public function test_available_variant_can_be_added(): void
    {
        $product = Product::create([
            'name' => 'Claude Code Pro',
            'slug' => 'claude-code-pro-ok',
            'description' => 'Claude Code Pro Plan',
            'price' => 430000,
            'category' => 'tech',
            'stock' => 10,
            'is_active' => true,
        ]);

        $v2 = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Gói Còn Hàng',
            'price' => 1950000,
            'stock' => 10,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $response = $this->from(route('product.show', $product->slug))
            ->post(route('cart.add', $product->id), [
                'variant_id' => $v2->id,
            ]);

        $response->assertRedirect(route('product.show', $product->slug));
        $response->assertSessionHas('success');

        $cart = session('cart');
        $this->assertNotEmpty($cart);
        $cartKey = $product->id . '_' . $v2->id;
        $this->assertArrayHasKey($cartKey, $cart);
        $this->assertEquals(1950000, $cart[$cartKey]['price']);
    }

    public function test_cart_index_purges_legacy_item_missing_variant(): void
    {
        $product = Product::create([
            'name' => 'Claude Code Pro',
            'slug' => 'claude-code-pro-cart',
            'description' => 'Claude Code Pro Plan',
            'price' => 430000,
            'category' => 'tech',
            'stock' => 10,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Gói 1 Tháng',
            'price' => 430000,
            'stock' => 0,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Put an invalid cart item (no variant_id) into session
        $cart = [
            (string) $product->id => [
                'product_id' => $product->id,
                'variant_id' => null,
                'name' => $product->name,
                'price' => 430000,
                'quantity' => 1,
            ],
        ];

        $response = $this->withSession(['cart' => $cart])->get(route('cart.index'));
        $response->assertStatus(200);

        // Session cart should now be empty because it was purged
        $newCart = session('cart');
        $this->assertEmpty($newCart);
    }

    public function test_shop_page_renders_detail_link_for_product_with_variants(): void
    {
        $product = Product::create([
            'name' => 'Claude Code Pro Shop',
            'slug' => 'claude-code-pro-shop',
            'description' => 'Claude Code Pro Plan',
            'price' => 430000,
            'category' => 'tech',
            'stock' => 10,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Gói 1',
            'price' => 430000,
            'stock' => 5,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->get('/shop');
        $response->assertStatus(200);

        // Should render link to detail page for variant selection, NOT a form directly adding to cart
        $response->assertSee(route('product.show', $product->slug));
        $response->assertDontSee('action="' . route('cart.add', $product->id) . '"', false);
    }

    public function test_user_with_3_cancelled_orders_is_blocked_everywhere(): void
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'spammer_cancel@gmail.com',
            'role' => 'user',
        ]);

        $product = Product::create([
            'name' => 'Test Digital Pack',
            'slug' => 'test-digital-pack-' . uniqid(),
            'description' => 'Desc',
            'price' => 50000,
            'stock' => 10,
            'category' => 'tech',
            'delivery_type' => 'digital',
        ]);

        for ($i = 1; $i <= 3; $i++) {
            \App\Models\Order::create([
                'user_id' => $user->id,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => '0900000000',
                'customer_address' => 'Test',
                'total_amount' => 50000,
                'status' => 'cancelled',
                'order_code' => 'DT-CANCEL' . $i,
            ]);
        }

        // 1. Blocked on add to cart
        $this->actingAs($user)
            ->post(route('cart.add', $product->id))
            ->assertRedirect(route('user.orders'))
            ->assertSessionHas('show_cancelled_limit_modal', true);

        // 2. Blocked on buyNow
        $this->actingAs($user)
            ->post(route('cart.buy-now', $product->id))
            ->assertRedirect(route('user.orders'))
            ->assertSessionHas('show_cancelled_limit_modal', true);

        // 3. Blocked on checkout page (hard redirect, not rendering checkout)
        $cart = [
            (string) $product->id => [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => 50000,
                'quantity' => 1,
            ],
        ];
        $this->actingAs($user)
            ->withSession(['cart' => $cart])
            ->get(route('checkout'))
            ->assertRedirect(route('user.orders'))
            ->assertSessionHas('show_cancelled_limit_modal', true);

        // 4. Blocked on preNotify API (403)
        $this->actingAs($user)
            ->withSession(['cart' => $cart])
            ->postJson(route('checkout.pre-notify'), [
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => '0900000000',
                'order_code' => 'DT-TRY4TH',
            ])
            ->assertStatus(403)
            ->assertJson(['reason' => 'cancelled_limit']);
    }

    public function test_user_with_2_cancelled_and_1_pending_cannot_create_4th_order(): void
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'almost_limit@gmail.com',
            'role' => 'user',
        ]);

        $product = Product::create([
            'name' => 'Test Digital Pack 2',
            'slug' => 'test-digital-pack2-' . uniqid(),
            'description' => 'Desc',
            'price' => 50000,
            'stock' => 10,
            'category' => 'tech',
            'delivery_type' => 'digital',
        ]);

        for ($i = 1; $i <= 2; $i++) {
            \App\Models\Order::create([
                'user_id' => $user->id,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => '0900000000',
                'customer_address' => 'Test',
                'total_amount' => 50000,
                'status' => 'cancelled',
                'order_code' => 'DT-C2P1-C' . $i,
            ]);
        }

        \App\Models\Order::create([
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '0900000000',
            'customer_address' => 'Test',
            'total_amount' => 50000,
            'status' => 'pending',
            'order_code' => 'DT-C2P1-P1',
        ]);

        $cart = [
            (string) $product->id => [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => 50000,
                'quantity' => 1,
            ],
        ];

        $this->actingAs($user)
            ->withSession(['cart' => $cart])
            ->postJson(route('checkout.pre-notify'), [
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => '0900000000',
                'order_code' => 'DT-TRY-EXTRA',
            ])
            ->assertStatus(403)
            ->assertJson(['reason' => 'pending_limit']);
    }
}
