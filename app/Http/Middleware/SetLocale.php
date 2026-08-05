<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

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
        // Check query parameter 'lang' or 'locale' to set language (useful when sharing links)
        $queryLocale = $request->query('lang') ?? $request->query('locale');
        if ($queryLocale && in_array(strtolower($queryLocale), ['vi', 'en'])) {
            $locale = strtolower($queryLocale);
            session(['locale' => $locale]);
            App::setLocale($locale);
            return $next($request);
        }

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
            }

            session(['locale' => $locale]);
            App::setLocale($locale);
        }

        return $next($request);
    }
}
