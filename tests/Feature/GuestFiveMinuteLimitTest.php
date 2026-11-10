<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

class GuestFiveMinuteLimitTest extends TestCase
{
    use RefreshDatabase;

    protected function refreshApplication()
    {
        parent::refreshApplication();

        if (!Schema::hasTable('products')) {
            Schema::create('products', function ($table) {
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

    public function test_guest_under_5_minutes_can_browse_freely(): void
    {
        $response = $this->withSession(['guest_first_seen_at' => time() - 60])
            ->get(route('login'));

        $response->assertStatus(200);
    }

    public function test_guest_after_5_minutes_is_redirected_to_login_on_regular_pages(): void
    {
        $response = $this->withSession(['guest_first_seen_at' => time() - 350])
            ->get('/shop');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('info');
    }

    public function test_guest_after_5_minutes_receives_401_on_json_request(): void
    {
        $response = $this->withSession(['guest_first_seen_at' => time() - 350])
            ->getJson('/shop');

        $response->assertStatus(401);
        $response->assertJson([
            'redirect' => route('login'),
        ]);
    }

    public function test_guest_access_forgot_password_page_redirects_to_login(): void
    {
        $response = $this->withSession(['guest_first_seen_at' => time() - 400])
            ->get(route('password.request'));

        $response->assertRedirect(route('login', ['forgot' => 1]));
    }

    public function test_guest_access_reset_password_page_with_token_redirects_to_login(): void
    {
        $response = $this->withSession(['guest_first_seen_at' => time() - 400])
            ->get('/reset-password/sample-reset-token-12345?email=test@example.com');

        $response->assertRedirect(route('login', ['forgot' => 1]));
    }

    public function test_guest_after_5_minutes_can_access_login_and_register_pages(): void
    {
        $loginResponse = $this->withSession(['guest_first_seen_at' => time() - 500])
            ->get(route('login'));
        $loginResponse->assertStatus(200);

        $registerResponse = $this->withSession(['guest_first_seen_at' => time() - 500])
            ->get(route('register'));
        $registerResponse->assertStatus(200);
    }

    public function test_guest_submitting_forgot_password_form_redirects_to_login(): void
    {
        $response = $this->withSession(['guest_first_seen_at' => time() - 400])
            ->post('/forgot-password', [
                'email' => 'guest_forgot@example.com',
            ]);

        $response->assertRedirect(route('login', ['forgot' => 1]));
    }

    public function test_guest_submitting_reset_password_form_redirects_to_login(): void
    {
        $response = $this->withSession(['guest_first_seen_at' => time() - 400])
            ->post('/reset-password', [
                'token' => 'invalid-or-sample-token',
                'email' => 'nonexistent@example.com',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertRedirect(route('login', ['forgot' => 1]));
    }

    public function test_login_page_renders_vietnamese(): void
    {
        $response = $this->withSession(['locale' => 'vi'])
            ->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Đăng Nhập');
        $response->assertSee('Email của bạn');
        $response->assertSee('Mật khẩu');
        $response->assertSee('Ghi nhớ đăng nhập');
        $response->assertSee('Quên mật khẩu?');
        $response->assertSee('HOẶC');
        $response->assertSee('Đăng nhập bằng Google');
        $response->assertSee('Chưa có tài khoản?');
        $response->assertSee('Đăng ký ngay');
    }

    public function test_login_page_renders_english(): void
    {
        $response = $this->withSession(['locale' => 'en'])
            ->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Login');
        $response->assertSee('Your Email');
        $response->assertSee('Password');
        $response->assertSee('Remember me');
        $response->assertSee('Forgot Username / Password?');
        $response->assertSee('OR');
        $response->assertSee('Login with Google');
        $response->assertSee("Don't have an account?");
        $response->assertSee('Sign up now');
    }

    public function test_forgot_password_redirects_to_login_with_modal(): void
    {
        $response = $this->get('/forgot-password');
        $response->assertRedirect(route('login', ['forgot' => 1]));
        $response->assertSessionHas('show_forgot_modal', true);
    }

    public function test_send_register_otp_enforces_360_seconds_cooldown(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $email = 'test_cooldown_' . time() . '@gmail.com';

        // Lần 1: Thành công
        $res1 = $this->postJson(route('auth.send-otp'), ['email' => $email]);
        $res1->assertStatus(200);
        $res1->assertJson(['success' => true]);

        // Lần 2 (ngay lập tức): Bị chặn 429 với countdown 360s
        $res2 = $this->postJson(route('auth.send-otp'), ['email' => $email]);
        $res2->assertStatus(429);
        $res2->assertJson(['success' => false]);
        $this->assertArrayHasKey('remaining_seconds', $res2->json());
        $this->assertGreaterThanOrEqual(358, $res2->json('remaining_seconds'));
    }
}
