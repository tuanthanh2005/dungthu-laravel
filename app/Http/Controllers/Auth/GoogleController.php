<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
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
            $googleUser = Socialite::driver('google')->stateless()->user();

            $existingUser = User::where('email', $googleUser->email)->first();

            if ($existingUser) {
                Auth::login($existingUser);
                return redirect('/')->with('success', 'Đăng nhập bằng Google thành công!');
            }

            $newUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => bcrypt(Str::random(16)),
                'email_verified_at' => now(),
                'role' => 'user',
            ]);

            Auth::login($newUser);
            
            // Mark as newly registered user to allow checkout without verification
            session()->put('is_new_user', true);
            
            return redirect('/')->with('success', 'Đăng nhập Google thành công!');
        } catch (\Exception $e) {
            Log::error('Google OAuth error', ['error' => $e->getMessage()]);
            return redirect('/login')->with('error', $e->getMessage());
        }
    }

}
