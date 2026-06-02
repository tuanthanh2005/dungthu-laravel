<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\TiktokDeal;
use App\Helpers\PathHelper;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private const SEO_KEYWORDS = [
        'gpt' => ['label' => 'GPT', 'aliases' => ['gpt', 'chatgpt', 'chat gpt', 'openai']],
        'cursor' => ['label' => 'Cursor', 'aliases' => ['cursor', 'cursor ai']],
        'claude' => ['label' => 'Claude', 'aliases' => ['claude', 'claude ai', 'anthropic']],
        'canva' => ['label' => 'Canva', 'aliases' => ['canva', 'canva pro']],
        'capcut' => ['label' => 'CapCut', 'aliases' => ['capcut', 'capcut pro']],
        'vpn' => ['label' => 'VPN', 'aliases' => ['vpn']],
    ];

    public static function seoKeywords(): array
    {
        return self::SEO_KEYWORDS;
    }

    public function index(Request $request)
    {
        $currentCategoryId = $request->category_id ?? 'all';
        $searchTerm = $request->search ?? '';

        if ($request->filled('search') && $currentCategoryId === 'all' && !$request->filled('page')) {
            return redirect()->route('product.keyword', $this->resolveKeywordSlug($searchTerm));
        }
        
        // Lấy danh sách categories active
        $categories = ProductCategory::where('is_active', true)
            ->withCount('products')
            ->orderBy('name')
            ->get();
        
        // Xử lý products
        $query = Product::query();
        
        // Filter theo category_id nếu có
        if ($currentCategoryId != 'all') {
            $query->where('category_id', $currentCategoryId);
        }
        
        // Search theo tên hoặc mô tả (hỗ trợ tìm kiếm linh hoạt: "chat gpt" → "chatgpt")
        if ($searchTerm) {
            $this->applyLooseSearch($query, $searchTerm);
        }
        
        $items = $query->latest()->paginate(18)->withQueryString();
        
        return view('products.index', compact('items', 'categories', 'currentCategoryId', 'searchTerm'));
    }

    public function keyword(Request $request, string $keyword)
    {
        $keywordSlug = $this->resolveKeywordSlug($keyword);
        if ($keywordSlug !== Str::slug($keyword)) {
            return redirect()->route('product.keyword', $keywordSlug);
        }

        $keywordConfig = self::SEO_KEYWORDS[$keywordSlug] ?? null;
        $searchTerm = $keywordConfig['label'] ?? Str::headline(str_replace('-', ' ', $keywordSlug));
        $aliases = $keywordConfig['aliases'] ?? [str_replace('-', ' ', $keywordSlug)];
        $currentCategoryId = 'all';

        $categories = ProductCategory::where('is_active', true)
            ->withCount('products')
            ->orderBy('name')
            ->get();

        $query = Product::query();
        $this->applyKeywordSearch($query, $aliases);

        $items = $query->latest()->paginate(18)->withQueryString();
        $seoTitle = "Mua tài khoản {$searchTerm} giá tốt - DungThu.com";
        $seoDescription = "Danh sách sản phẩm {$searchTerm} đang bán tại DungThu.com: tài khoản, gói sử dụng và dịch vụ liên quan, cập nhật theo kho.";
        $canonical = route('product.keyword', $keywordSlug);
        $pageHeading = "Sản phẩm {$searchTerm}";
        $isKeywordLanding = true;

        return view('products.index', compact(
            'items',
            'categories',
            'currentCategoryId',
            'searchTerm',
            'seoTitle',
            'seoDescription',
            'canonical',
            'pageHeading',
            'isKeywordLanding'
        ));
    }

    private function applyKeywordSearch($query, array $aliases): void
    {
        $query->where(function($q) use ($aliases) {
            foreach ($aliases as $alias) {
                $q->orWhere('name', 'LIKE', '%' . $alias . '%');

                $noSpace = str_replace(' ', '', $alias);
                if ($noSpace !== $alias) {
                    $q->orWhere('name', 'LIKE', '%' . $noSpace . '%');
                }
            }
        });
    }

    private function resolveKeywordSlug(string $value): string
    {
        $normalized = Str::slug($value);
        $compact = str_replace('-', '', $normalized);

        foreach (self::SEO_KEYWORDS as $keyword => $config) {
            if ($normalized === $keyword || str_contains($normalized, $keyword)) {
                return $keyword;
            }

            foreach ($config['aliases'] as $alias) {
                $aliasSlug = Str::slug($alias);
                $aliasCompact = str_replace('-', '', $aliasSlug);

                if ($aliasSlug && (str_contains($normalized, $aliasSlug) || str_contains($compact, $aliasCompact))) {
                    return $keyword;
                }
            }
        }

        return $normalized;
    }

    private function applyLooseSearch($query, string $searchTerm): void
    {
        $query->where(function($q) use ($searchTerm) {
            $q->where('name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('description', 'LIKE', "%{$searchTerm}%");

            $noSpace = str_replace(' ', '', $searchTerm);
            if ($noSpace !== $searchTerm) {
                $q->orWhere('name', 'LIKE', "%{$noSpace}%")
                  ->orWhere('description', 'LIKE', "%{$noSpace}%");
            }

            $tokens = array_filter(explode(' ', trim($searchTerm)));
            if (count($tokens) > 1) {
                $q->orWhere(function($sub) use ($tokens) {
                    foreach ($tokens as $token) {
                        $sub->where(function($t) use ($token) {
                            $t->where('name', 'LIKE', "%{$token}%")
                              ->orWhere('description', 'LIKE', "%{$token}%");
                        });
                    }
                });
            }
        });
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->with(['comments', 'features'])->firstOrFail();
        
        // Lấy sản phẩm liên quan
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();
        
        // Tính rating trung bình
        $averageRating = $product->comments()->avg('rating') ?? 0;
        $totalReviews = $product->comments()->count();
        
        // Chọn view theo category
        $viewMap = [
            'tech' => 'products.show-tech',
            'ebooks' => 'products.show-ebooks',
            'doc' => 'products.show-doc',
        ];
        
        $view = $viewMap[$product->category] ?? 'products.show';
        
        // Check if user has purchased this product (for ebooks)
        $hasPurchased = false;
        if (auth()->check() && $product->category === 'ebooks') {
            $hasPurchased = $product->isPurchasedBy(auth()->id());
        }
        
        return view($view, compact('product', 'relatedProducts', 'averageRating', 'totalReviews', 'hasPurchased'));
    }

    public function storeComment(Request $request, Product $product)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ], [
            'comment.required' => 'Vui lòng nhập nội dung đánh giá',
            'comment.max' => 'Nội dung đánh giá không được vượt quá 1000 ký tự',
            'rating.required' => 'Vui lòng chọn số sao',
            'rating.min' => 'Số sao tối thiểu là 1',
            'rating.max' => 'Số sao tối đa là 5',
        ]);

        $product->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $request->comment,
            'rating' => $request->rating,
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }

    public function download($id)
    {
        $product = Product::findOrFail($id);
        
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tải file');
        }

        // Check if product has file
        if (!$product->hasFile()) {
            abort(404, 'Sản phẩm này không có file để tải');
        }

        // Check if user has purchased this product
        if (!$product->isPurchasedBy(auth()->id())) {
            return back()->with('error', 'Bạn cần mua sản phẩm này trước khi tải về');
        }

        // Get file path
        $filePath = PathHelper::publicRootPath('files/' . $product->file_path);

        // Check if file exists
        if (!file_exists($filePath)) {
            abort(404, 'File không tồn tại');
        }

        // Return file download
        return response()->download($filePath, $product->name . '.' . $product->file_type);
    }
}
