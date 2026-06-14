<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('locale')) {
            App::setLocale(session('locale'));
        } else {
            $locale = 'vi'; // Default language

            // 1. Check Cloudflare Country Header directly (Fastest, no API calls)
            $cfCountry = $request->header('CF-IPCountry');
            if ($cfCountry && strtoupper($cfCountry) !== 'XX') {
                if (strtoupper($cfCountry) !== 'VN') {
                    $locale = 'en';
                }
            } else {
                // 2. Determine Client IP (taking Cloudflare / Proxy headers into account)
                $ip = $request->header('CF-Connecting-IP') 
                    ?? $request->header('X-Forwarded-For') 
                    ?? $request->ip();

                // Clean ip list if comma-separated
                if ($ip && strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }

                // Skip IP check on local/testing environments
                if ($ip && !in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
                    try {
                        // Call a free, fast GeoIP API (timeout 2s)
                        $response = Http::timeout(2)->get("http://ip-api.com/json/{$ip}");
                        if ($response->successful()) {
                            $data = $response->json();
                            $countryCode = $data['countryCode'] ?? 'VN';
                            // If not Vietnam, default to English
                            if (strtoupper($countryCode) !== 'VN') {
                                $locale = 'en';
                            }
                        }
                    } catch (\Exception $e) {
                        Log::warning("SetLocale Middleware: Failed to determine locale for IP {$ip}: " . $e->getMessage());
                    }
                }
            }

            session(['locale' => $locale]);
            App::setLocale($locale);
        }

        return $next($request);
    }
}
