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

    public function test_guest_after_5_minutes_can_access_forgot_password_page(): void
    {
        $response = $this->withSession(['guest_first_seen_at' => time() - 400])
            ->get(route('password.request'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.forgot-password');
    }

    public function test_guest_after_5_minutes_can_access_reset_password_page_with_token(): void
    {
        $response = $this->withSession(['guest_first_seen_at' => time() - 400])
            ->get('/reset-password/sample-reset-token-12345?email=test@example.com');

        $response->assertStatus(200);
        $response->assertViewIs('auth.reset-password');
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

    public function test_guest_after_5_minutes_can_submit_forgot_password_form(): void
    {
        $user = User::factory()->create(['email' => 'guest_forgot@example.com']);

        $response = $this->withSession(['guest_first_seen_at' => time() - 400])
            ->post('/forgot-password', [
                'email' => 'guest_forgot@example.com',
            ]);

        // Should not redirect to login, but back (or with status)
        $this->assertNotEquals(route('login'), $response->headers->get('Location'));
    }

    public function test_guest_after_5_minutes_can_submit_reset_password_form(): void
    {
        $response = $this->withSession(['guest_first_seen_at' => time() - 400])
            ->post('/reset-password', [
                'token' => 'invalid-or-sample-token',
                'email' => 'nonexistent@example.com',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        // Should not redirect to login page (which indicates 5-min blocker didn't block it)
        $this->assertNotEquals(route('login'), $response->headers->get('Location'));
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

    public function test_forgot_password_allows_5_attempts_and_blocks_6th_for_1_hour(): void
    {
        \Illuminate\Support\Facades\RateLimiter::clear('forgot_password_ip:127.0.0.1');
        \Illuminate\Support\Facades\RateLimiter::clear('forgot_password_email:' . md5('spam_user@example.com'));

        $user = User::factory()->create(['email' => 'spam_user@example.com']);

        // Attempts 1 to 5: All allowed
        for ($i = 1; $i <= 5; $i++) {
            $response = $this->post('/forgot-password', [
                'email' => 'spam_user@example.com',
            ]);
            $response->assertSessionMissing('error');
        }

        // Attempt 6: Blocked for 1 hour!
        $response6 = $this->post('/forgot-password', [
            'email' => 'spam_user@example.com',
        ]);

        $response6->assertSessionHas('error');
        $response6->assertSessionHasErrors('email');
        
        $error = session('errors')->first('email');
        $this->assertStringContainsString('quá 5 lần', $error);
        $this->assertStringContainsString('1 tiếng', $error);
    }
}
