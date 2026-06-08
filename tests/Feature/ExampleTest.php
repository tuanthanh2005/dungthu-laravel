<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Product;

use App\Models\Order;

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

    /**
     * Test admin user management sorting options.
     */
    public function test_admin_user_sorting_options(): void
    {
        // Create admin
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        // Create 3 users with different registration dates
        $user1 = User::factory()->create([
            'role' => 'user',
            'name' => 'User One',
            'created_at' => now()->subDays(5)
        ]);

        $user2 = User::factory()->create([
            'role' => 'user',
            'name' => 'User Two',
            'created_at' => now()->subDays(2)
        ]);

        $user3 = User::factory()->create([
            'role' => 'user',
            'name' => 'User Three',
            'created_at' => now()->subDays(10)
        ]);

        // Create orders to test orders sorting and spent sorting
        // User1 has 2 orders, total = 30000
        Order::create([
            'user_id' => $user1->id,
            'customer_name' => $user1->name,
            'customer_email' => $user1->email,
            'customer_phone' => '123',
            'customer_address' => 'Addr',
            'total_amount' => 10000.00
        ]);
        Order::create([
            'user_id' => $user1->id,
            'customer_name' => $user1->name,
            'customer_email' => $user1->email,
            'customer_phone' => '123',
            'customer_address' => 'Addr',
            'total_amount' => 20000.00
        ]);

        // User2 has 1 order, total = 50000
        Order::create([
            'user_id' => $user2->id,
            'customer_name' => $user2->name,
            'customer_email' => $user2->email,
            'customer_phone' => '123',
            'customer_address' => 'Addr',
            'total_amount' => 50000.00
        ]);

        // User3 has 0 orders, total = 0

        // 1. Test Sort: newest (Default) -> user2, user1, user3
        $response = $this->actingAs($admin)
            ->withSession(['admin_unlocked' => true])
            ->get(route('admin.users'));
        $response->assertStatus(200);
        $users = $response->viewData('users');
        $this->assertEquals($user2->id, $users[0]->id);
        $this->assertEquals($user1->id, $users[1]->id);
        $this->assertEquals($user3->id, $users[2]->id);

        // 2. Test Sort: oldest -> user3, user1, user2
        $response = $this->actingAs($admin)
            ->withSession(['admin_unlocked' => true])
            ->get(route('admin.users', ['sort' => 'oldest']));
        $response->assertStatus(200);
        $users = $response->viewData('users');
        $this->assertEquals($user3->id, $users[0]->id);
        $this->assertEquals($user1->id, $users[1]->id);
        $this->assertEquals($user2->id, $users[2]->id);

        // 3. Test Sort: most_orders -> user1 (2 orders), user2 (1 order), user3 (0 orders)
        $response = $this->actingAs($admin)
            ->withSession(['admin_unlocked' => true])
            ->get(route('admin.users', ['sort' => 'most_orders']));
        $response->assertStatus(200);
        $users = $response->viewData('users');
        $this->assertEquals($user1->id, $users[0]->id);
        $this->assertEquals($user2->id, $users[1]->id);
        $this->assertEquals($user3->id, $users[2]->id);

        // 4. Test Sort: most_spent -> user2 (50000 spent), user1 (30000 spent), user3 (0 spent)
        $response = $this->actingAs($admin)
            ->withSession(['admin_unlocked' => true])
            ->get(route('admin.users', ['sort' => 'most_spent']));
        $response->assertStatus(200);
        $users = $response->viewData('users');
        $this->assertEquals($user2->id, $users[0]->id);
        $this->assertEquals($user1->id, $users[1]->id);
        $this->assertEquals($user3->id, $users[2]->id);
    }

    /**
     * Test Auto-Matching Dynamic SEO Router.
     */
    public function test_seo_router_matching_and_preorder_workflow(): void
    {
        // 1. Visit non-existent AI router url
        $response = $this->get('/go/super-coder-ai');
        $response->assertStatus(200);
        $response->assertViewIs('pages.seo-placeholder');
        $response->assertSee('Super Coder Ai'); // dynamically converted title

        // 1b. Visit search keyword page with 0 products -> should redirect to /go/super-coder-ai
        $responseSearch = $this->get(route('product.keyword', 'super-coder-ai'));
        $responseSearch->assertRedirect(route('seo.router', 'super-coder-ai'));

        // 2. Submit preorder form
        $responseSub = $this->postJson('/go/super-coder-ai/subscribe', [
            'email' => 'subscriber@example.com'
        ]);

        $responseSub->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        // Verify it was stored in db
        $this->assertDatabaseHas('pre_orders', [
            'email' => 'subscriber@example.com',
            'keyword' => 'super-coder-ai',
            'status' => 'pending'
        ]);

        // 3. Create a matching product and check redirect
        $product = Product::create([
            'name' => 'Tài Khoản Super Coder AI Pro',
            'slug' => 'tai-khoan-super-coder-ai-pro',
            'price' => 120000.00,
            'category' => 'tech',
            'delivery_type' => 'digital',
            'description' => 'Super Coder AI',
            'stock' => 5
        ]);

        $responseRedirect = $this->get('/go/super-coder-ai');
        $responseRedirect->assertRedirect(route('product.show', $product->slug));
        $this->assertEquals(301, $responseRedirect->getStatusCode());
    }

    /**
     * Test SEO Keywords CRUD workflow.
     */
    public function test_seo_keywords_crud_workflow(): void
    {
        // Disable Google Indexing during tests to avoid RuntimeException for missing key files
        config(['services.google_indexing.enabled' => false]);

        // 1. Create an admin user
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        // 2. Access index page
        $response = $this->actingAs($admin)
            ->withSession(['admin_unlocked' => true])
            ->get(route('admin.seo-keywords'));
        $response->assertStatus(200);

        // 3. Access create page
        $response = $this->actingAs($admin)
            ->withSession(['admin_unlocked' => true])
            ->get(route('admin.seo-keywords.create'));
        $response->assertStatus(200);

        // 4. Submit store form
        $keywordData = [
            'slug' => 'test-keyword-ai',
            'label' => 'Test Keyword AI',
            'heading' => 'Mua tài khoản Test Keyword AI giá rẻ',
            'title' => 'Mua tài khoản Test Keyword AI giá rẻ - DungThu.com',
            'description' => 'Mô tả chi tiết về Test Keyword AI',
            'aliases' => "test key, test keyword\ntest keyword ai",
            'is_active' => '1',
            'admin_pin' => '999',
        ];

        $response = $this->actingAs($admin)
            ->withSession(['admin_unlocked' => true])
            ->post(route('admin.seo-keywords.store'), $keywordData);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.seo-keywords'));

        // Verify stored in DB
        $this->assertDatabaseHas('seo_keywords', [
            'slug' => 'test-keyword-ai',
            'label' => 'Test Keyword AI',
            'is_active' => true,
        ]);

        $keyword = \App\Models\SeoKeyword::where('slug', 'test-keyword-ai')->first();
        $this->assertNotNull($keyword);
        $this->assertEquals(['test key', 'test keyword', 'test keyword ai'], $keyword->aliases);

        // 5. Access edit page
        $response = $this->actingAs($admin)
            ->withSession(['admin_unlocked' => true])
            ->get(route('admin.seo-keywords.edit', $keyword->id));
        $response->assertStatus(200);

        // 6. Submit update form
        $updateData = [
            'slug' => 'test-keyword-ai-updated',
            'label' => 'Test Keyword AI Updated',
            'heading' => 'Mua tài khoản Test Keyword AI Updated giá rẻ',
            'title' => 'Mua tài khoản Test Keyword AI Updated giá rẻ - DungThu.com',
            'description' => 'Mô tả chi tiết đã cập nhật',
            'aliases' => 'test key updated, test keyword updated',
            'is_active' => '0',
            'admin_pin' => '999',
        ];

        $response = $this->actingAs($admin)
            ->withSession(['admin_unlocked' => true])
            ->put(route('admin.seo-keywords.update', $keyword->id), $updateData);

        $response->assertRedirect(route('admin.seo-keywords'));

        // Verify updated in DB
        $keyword->refresh();
        $this->assertEquals('test-keyword-ai-updated', $keyword->slug);
        $this->assertEquals('Test Keyword AI Updated', $keyword->label);
        $this->assertFalse($keyword->is_active);
        $this->assertEquals(['test key updated', 'test keyword updated'], $keyword->aliases);

        // 6b. Submit manual index request via AJAX (expects JSON response)
        $response = $this->actingAs($admin)
            ->withSession(['admin_unlocked' => true])
            ->postJson(route('admin.seo-keywords.submit-index', $keyword->id), ['admin_pin' => '999']);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Gửi yêu cầu Index thành công!'
            ]);

        // 7. Delete keyword
        $response = $this->actingAs($admin)
            ->withSession(['admin_unlocked' => true])
            ->delete(route('admin.seo-keywords.delete', $keyword->id), ['admin_pin' => '999']);

        $response->assertRedirect(route('admin.seo-keywords'));

        // Verify removed from DB
        $this->assertDatabaseMissing('seo_keywords', [
            'id' => $keyword->id
        ]);
    }
}
