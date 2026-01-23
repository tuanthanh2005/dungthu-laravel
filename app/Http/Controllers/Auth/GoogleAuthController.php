<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google for authentication
     */
    public function redirect()
    {
        Log::info('Google OAuth redirect initiated');
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback
     */
    public function callback()
    {
        Log::info('Google OAuth callback received');
        
        try {
            $googleUser = Socialite::driver('google')->user();
            Log::info('Google user retrieved', [
                'email' => $googleUser->email,
                'name' => $googleUser->name,
                'id' => $googleUser->id
            ]);

            // Check if user exists
            $existingUser = User::where('email', $googleUser->email)->first();
            
            if ($existingUser) {
                Log::info('Existing user found, logging in', ['email' => $googleUser->email]);
                Auth::login($existingUser);
                Log::info('User logged in successfully', ['user_id' => $existingUser->id]);
                return redirect('/')->with('success', 'Đăng nhập bằng Google thành công!');
            }

            // Create new user
            Log::info('Creating new user from Google', ['email' => $googleUser->email]);
            $newUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => bcrypt(Str::random(16)),
                'email_verified_at' => now(),
                'role' => 'user',
            ]);
            Log::info('New user created', ['user_id' => $newUser->id, 'email' => $newUser->email]);

            Auth::login($newUser);
            Log::info('New user logged in', ['user_id' => $newUser->id]);
            return redirect('/')->with('success', 'Tài khoản Google đã được tạo và đăng nhập thành công!');
        } catch (\Exception $e) {
            Log::error('Google OAuth error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/login')->with('error', 'Lỗi đăng nhập Google: ' . $e->getMessage());
        }
    }
}
