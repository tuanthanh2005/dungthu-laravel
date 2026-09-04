<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Blog;
use App\Models\Order;
use App\Models\SiteSetting;
use App\Models\CardExchange;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy danh sách categories active và show_on_home (Cache 10 phút)
        $categories = Cache::remember('home.categories', 600, function () {
            return ProductCategory::where('is_active', true)
                ->where('show_on_home', true)
                ->withCount('products')
                ->orderBy('name')
                ->get();
        });
        
        // Lấy sản phẩm featured cho trang home (Cache 10 phút)
        $featuredProducts = Cache::remember('home.featured_products', 600, function () {
            return Product::active()->featured(12)->get();
        });
        
        // Lấy sản phẩm độc quyền (Cache 10 phút)
        $highlightProducts = Cache::remember('home.highlight_products', 600, function () {
            return Product::active()->where('is_exclusive', true)->latest()->take(12)->get();
        });
        
        // Lấy 24 sản phẩm mới nhất cho trang chủ (Cache 10 phút)
        $latestProducts = Cache::remember('home.latest_products', 600, function () {
            return Product::query()
                ->active()
                ->where('is_combo_ai', true)
                ->latest()
                ->take(24)
                ->get();
        });
        
        // Lấy 10 blog mới nhất (Cache 10 phút)
        $latestBlogs = Cache::remember('home.latest_blogs', 600, function () {
            return Blog::published()->orderBy('published_at', 'desc')->take(10)->get();
        });
        

        // Sản phẩm đang giảm giá (Cache 5 phút)
        $flashSaleEnabled = SiteSetting::getValue('flash_sale_enabled', '1') === '1';
        $saleEndsAt = now()->endOfDay();
        $isExpired = false;

        $saleProducts = Cache::remember('home.sale_products', 300, function () use ($flashSaleEnabled, $saleEndsAt) {
            if ($flashSaleEnabled && now()->lt($saleEndsAt)) {
                $prods = Product::query()
                    ->active()
                    ->where('is_flash_sale', true)
                    ->latest()
                    ->take(6)
                    ->get();
                if ($prods->isNotEmpty()) {
                    return $prods;
                }
            }
            return Product::query()->active()->latest()->take(6)->get();
        });
        if ($saleProducts->isEmpty()) {
            $isExpired = true;
        }

        $cacheKey = 'home.recent_purchases.v3.' . app()->getLocale();
        $recentPurchases = Cache::remember($cacheKey, now()->addMinutes(5), function () {
            $orders = Order::query()
                ->with(['orderItems.product'])
                ->whereNotIn('status', ['cancelled'])
                ->latest()
                ->take(10)
                ->get()
                ->map(function (Order $order) {
                    $firstItem = $order->orderItems->first();
                    $product = $firstItem?->product;
                    $extraItems = max(0, $order->orderItems->count() - 1);

                    $verb = in_array($order->status, ['completed', 'delivered', 'shipped'], true) ? __('vừa mua thành công') : __('vừa đặt hàng');
                    $productName = (app()->getLocale() === 'en' && !empty($product?->name_en)) ? $product->name_en : ($product?->name ?? __('Sản phẩm'));

                    return [
                        'customer_name' => self::maskCustomerName((string) $order->customer_name),
                        'verb' => $verb,
                        'product_name' => $productName,
                        'product_slug' => $product?->slug,
                        'product_url' => $product?->slug ? route('product.show', $product->slug) : null,
                        'extra_items' => $extraItems,
                        'time_ago' => (app()->getLocale() === 'en' ? (rand(5, 55) . 'm ago') : (rand(5, 55) . ' phút trước')),
                        'sort_at' => $order->created_at,
                    ];
                });

            $cardExchanges = CardExchange::query()
                ->with('user')
                ->where('status', 'success')
                ->latest('processed_at')
                ->take(10)
                ->get()
                ->map(function (CardExchange $exchange) {
                    $time = $exchange->processed_at ?? $exchange->updated_at ?? $exchange->created_at;
                    $cardValue = number_format((float) $exchange->card_value, 0, ',', '.') . 'đ';
                    $cardType = $exchange->card_type ? (' ' . $exchange->card_type) : '';

                    return [
                        'customer_name' => self::maskCustomerName((string) optional($exchange->user)->name),
                        'verb' => __('vừa đổi thành công'),
                        'product_name' => __('Đổi thẻ cào') . $cardType . ' ' . $cardValue,
                        'product_slug' => null,
                        'product_url' => route('card-exchange.index'),
                        'extra_items' => 0,
                        'time_ago' => (app()->getLocale() === 'en' ? (rand(5, 55) . 'm ago') : (rand(5, 55) . ' phút trước')),
                        'sort_at' => $time,
                    ];
                });

            return Collection::make()
                ->concat($orders)
                ->concat($cardExchanges)
                ->sortByDesc('sort_at')
                ->take(10)
                ->values()
                ->map(function (array $item) {
                    unset($item['sort_at']);
                    return $item;
                })
                ->all();
        });

        // Lấy 4 sản phẩm Banner Hero: Admin gán thì ưu tiên lấy của Admin, thiếu thì random 1 tiếng đổi 1 lần
        $bannerProducts = Cache::remember('home.banner_products.' . date('YmdH'), 3600, function () {
            $hasColumn = \Illuminate\Support\Facades\Schema::hasColumn('products', 'show_on_banner');
            $prods = collect();
            if ($hasColumn) {
                $prods = Product::active()->where('show_on_banner', true)->latest()->take(4)->get();
            }
            if ($prods->count() < 4) {
                $needed = 4 - $prods->count();
                $existingIds = $prods->pluck('id')->toArray();
                $randomFallback = Product::query()
                    ->active()
                    ->whereNotIn('id', $existingIds)
                    ->inRandomOrder()
                    ->take($needed)
                    ->get();
                $prods = $prods->concat($randomFallback);
            }
            return $prods;
        });

        $totalSoldCount = Cache::remember('home.total_sold_count', 300, function () {
            $sum = Product::all()->sum(fn($p) => $p->sold_count);
            // Nếu bị chẵn đuôi (ví dụ 18.000), cộng số lẻ tự nhiên 387 thành 18.387 để khách hoàn toàn tin tưởng
            if ($sum % 10 === 0) {
                $sum += 387;
            }
            return $sum;
        });

        $totalUserCount = Cache::remember('home.total_user_count', 300, function () {
            $realCount = \App\Models\User::where('role', '!=', 'admin')->count();
            // Nếu ít user thử nghiệm, tạo số thành viên lẻ tự nhiên (tăng dần theo user thực)
            return $realCount > 100 ? $realCount : ($realCount + 1438);
        });

        $totalProductCount = Cache::remember('home.total_product_count', 300, function () {
            return Product::active()->count();
        });

        $todayVisitors = Cache::remember('home.today_visitors.' . date('YmdH'), 300, function () {
            $count = 0;
            if (\Illuminate\Support\Facades\Schema::hasTable('online_sessions')) {
                $count = \App\Models\OnlineSession::whereDate('last_activity', \Carbon\Carbon::today())->count();
            }
            $baseHourOffset = ((int) date('H') + 1) * 37 + 219;
            return $count > 50 ? $count : ($count + $baseHourOffset);
        });

        return view('home', compact(
            'categories',
            'featuredProducts',
            'highlightProducts',
            'bannerProducts',
            'latestProducts',
            'latestBlogs',
            'recentPurchases',
            'saleProducts',
            'saleEndsAt',
            'isExpired',
            'totalSoldCount',
            'totalUserCount',
            'totalProductCount',
            'todayVisitors'
        ));
    }

    private static function maskCustomerName(string $name): string
    {
        $name = trim($name);
        if ($name === '') {
            return 'Khách hàng';
        }

        $parts = preg_split('/\\s+/u', $name) ?: [];
        $parts = array_values(array_filter($parts, fn ($p) => $p !== ''));

        $givenName = count($parts) > 0 ? $parts[count($parts) - 1] : 'Khách';
        $len = mb_strlen($givenName);

        if ($len <= 2) {
            return mb_substr($givenName, 0, 1) . '*';
        }

        $char1 = mb_substr($givenName, 0, 1);
        $char2 = mb_substr($givenName, (int)($len / 2), 1);
        $char3 = mb_substr($givenName, $len - 1, 1);

        if ($len == 3) {
            return $char1 . '*' . $char3;
        }

        return $char1 . '*' . $char2 . '*' . $char3;
    }

    public function getRandomProducts()
    {
        $products = Product::query()
            ->inRandomOrder()
            ->take(6)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image' => $product->image ?? 'https://via.placeholder.com/300',
                    'stock' => $product->stock,
                    'sold_count' => $product->sold_count,
                    'formatted_price' => $product->formatted_price,
                    'formatted_original_price' => $product->formatted_original_price,
                    'show_url' => route('product.show', $product->slug),
                ];
            });

        return response()->json($products);
    }
}

