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
        // Lấy danh sách categories active và show_on_home
        $categories = ProductCategory::where('is_active', true)
            ->where('show_on_home', true)
            ->withCount('products')
            ->orderBy('name')
            ->get();
        
        // Lấy 4 sản phẩm featured cho trang home - Hàng 1
        $featuredProducts = Product::featured(12)->get();
        
        // Lấy 8 sản phẩm độc quyền - 2 hàng x 4 sản phẩm
        $highlightProducts = Product::where('is_exclusive', true)->latest()->take(12)->get();
        
        // Lấy 24 sản phẩm mới nhất cho trang chủ (phần Sản Phẩm)
        $latestProducts = Product::query()
            ->where('is_combo_ai', true)
            ->latest()
            ->take(24)
            ->get();
        
        // Lấy 10 blog mới nhất (published)
        $latestBlogs = Blog::published()->orderBy('published_at', 'desc')->take(10)->get();
        

        // Sản phẩm đang giảm giá
        // Đếm ngược đến cuối ngày. Khi đếm về 0 hoặc hết hạn thì random 6 sản phẩm.
        $flashSaleEnabled = SiteSetting::getValue('flash_sale_enabled', '1') === '1';
        $saleEndsAt = now()->endOfDay();
        $isExpired = false;

        if ($flashSaleEnabled && now()->lt($saleEndsAt)) {
            $saleProducts = Product::query()
                ->where('is_flash_sale', true)
                ->latest()
                ->take(6)
                ->get();
            if ($saleProducts->isEmpty()) {
                $saleProducts = Product::query()->inRandomOrder()->take(6)->get();
                $isExpired = true;
            }
        } else {
            $saleProducts = Product::query()->inRandomOrder()->take(6)->get();
            $isExpired = true;
        }

        $recentPurchases = Cache::remember('home.recent_purchases.v2', now()->addMinutes(5), function () {
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

                    $verb = in_array($order->status, ['completed', 'delivered', 'shipped'], true) ? 'mua' : 'đặt';

                    return [
                        'customer_name' => self::maskCustomerName((string) $order->customer_name),
                        'verb' => $verb,
                        'product_name' => $product?->name ?? 'Sản phẩm',
                        'product_slug' => $product?->slug,
                        'product_url' => $product?->slug ? route('product.show', $product->slug) : null,
                        'extra_items' => $extraItems,
                        'time_ago' => optional($order->created_at)->diffForHumans(),
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
                        'verb' => 'đổi',
                        'product_name' => 'Đổi thẻ cào' . $cardType . ' ' . $cardValue,
                        'product_slug' => null,
                        'product_url' => route('card-exchange.index'),
                        'extra_items' => 0,
                        'time_ago' => optional($time)->diffForHumans(),
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

        return view('home', compact(
            'categories',
            'featuredProducts',
            'highlightProducts',
            'latestProducts',
            'latestBlogs',
            'recentPurchases',
            'saleProducts',
            'saleEndsAt',
            'isExpired'
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

        if (count($parts) === 1) {
            $first = mb_substr($parts[0], 0, 1);
            return $first . '***';
        }

        $givenName = $parts[count($parts) - 1];
        $surnameInitial = mb_substr($parts[0], 0, 1);

        return $givenName . ' ' . $surnameInitial . '.';
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
                    'formatted_price' => $product->formatted_price,
                    'formatted_original_price' => $product->formatted_original_price,
                    'show_url' => route('product.show', $product->slug),
                ];
            });

        return response()->json($products);
    }
}

