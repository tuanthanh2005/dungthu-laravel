<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Product;
use App\Services\GoogleIndexingService;
use Illuminate\Http\Request;

class GoogleIndexingController extends Controller
{
    /**
     * Show Google Indexing dashboard page.
     */
    public function index(Request $request)
    {
        $service = app(GoogleIndexingService::class);
        $enabled = $service->isEnabled();
        $keyFile = config('services.google_indexing.key_file', '');
        $siteUrl = config('services.google_indexing.site_url', config('app.url'));

        // Check if key file exists
        $keyFileExists = false;
        if ($keyFile) {
            $resolvedPath = $keyFile;
            if (!str_starts_with($keyFile, DIRECTORY_SEPARATOR) && !preg_match('/^[A-Za-z]:\\\\/', $keyFile)) {
                $resolvedPath = base_path($keyFile);
            }
            $keyFileExists = is_file($resolvedPath);
        }

        $totalProducts = Product::count();
        $totalBlogs = Blog::published()->count();
        $totalSeoKeywords = \App\Models\SeoKeyword::where('is_active', true)->count();

        return view('admin.google-indexing.index', compact(
            'enabled',
            'siteUrl',
            'keyFile',
            'keyFileExists',
            'totalProducts',
            'totalBlogs',
            'totalSeoKeywords'
        ));
    }

    /**
     * Show recent indexing submissions (JSON).
     */
    public function recent(Request $request)
    {
        $minutes = (int) $request->query('minutes', 60);
        $limit = (int) $request->query('limit', 100);
        $source = $request->query('source');

        $records = GoogleIndexingService::recentSubmissions($minutes, $source, $limit);

        return response()->json([
            'success' => true,
            'minutes' => max(1, min($minutes, 1440)),
            'count' => count($records),
            'data' => $records,
        ]);
    }

    /**
     * Submit all published blogs for indexing.
     */
    public function submitAllBlogs(Request $request)
    {
        $chunk = max(10, min((int) $request->query('chunk', 50), 200));
        $limit = max(0, (int) $request->query('limit', 0));
        $latest = filter_var($request->query('latest', false), FILTER_VALIDATE_BOOLEAN);

        $query = Blog::published();
        $totalAvailable = (clone $query)->count();

        $processed = 0;
        $success = 0;
        $failed = [];

        if ($latest && $limit > 0) {
            // Fetch exactly $limit newest blogs
            $blogs = Blog::published()->orderBy('id', 'desc')->limit($limit)->get();
            foreach ($blogs as $blog) {
                try {
                    GoogleIndexingService::publishBlog($blog, 'URL_UPDATED', 'manual_bulk');
                    $success++;
                } catch (\Throwable $e) {
                    $failed[] = [
                        'blog_id' => $blog->id,
                        'slug' => $blog->slug,
                        'message' => $e->getMessage(),
                    ];
                }
                $processed++;
            }
        } else {
            $query->orderBy('id');
            $query->chunkById($chunk, function ($blogs) use (&$processed, &$success, &$failed, $limit) {
                foreach ($blogs as $blog) {
                    if ($limit > 0 && $processed >= $limit) {
                        return false;
                    }

                    try {
                        GoogleIndexingService::publishBlog($blog, 'URL_UPDATED', 'manual_bulk');
                        $success++;
                    } catch (\Throwable $e) {
                        $failed[] = [
                            'blog_id' => $blog->id,
                            'slug' => $blog->slug,
                            'message' => $e->getMessage(),
                        ];
                    }

                    $processed++;
                }

                return null;
            });
        }

        return response()->json([
            'success' => count($failed) === 0,
            'total_available' => $totalAvailable,
            'processed' => $processed,
            'submitted' => $success,
            'failed_count' => count($failed),
            'failed' => $failed,
            'chunk' => $chunk,
            'limit' => $limit,
        ]);
    }

    /**
     * Submit all products for indexing.
     */
    public function submitAllProducts(Request $request)
    {
        $chunk = max(10, min((int) $request->query('chunk', 50), 200));
        $limit = max(0, (int) $request->query('limit', 0));

        $query = Product::orderBy('id');
        $totalAvailable = (clone $query)->count();

        $processed = 0;
        $success = 0;
        $failed = [];

        $query->chunkById($chunk, function ($products) use (&$processed, &$success, &$failed, $limit) {
            foreach ($products as $product) {
                if ($limit > 0 && $processed >= $limit) {
                    return false;
                }

                try {
                    GoogleIndexingService::publishProduct($product, 'URL_UPDATED', 'manual_bulk_product');
                    $success++;
                } catch (\Throwable $e) {
                    $failed[] = [
                        'product_id' => $product->id,
                        'slug' => $product->slug,
                        'message' => $e->getMessage(),
                    ];
                }

                $processed++;
            }

            return null;
        });

        return response()->json([
            'success' => count($failed) === 0,
            'total_available' => $totalAvailable,
            'processed' => $processed,
            'submitted' => $success,
            'failed_count' => count($failed),
            'failed' => $failed,
            'chunk' => $chunk,
            'limit' => $limit,
        ]);
    }

    /**
     * Submit all active categories for indexing.
     */
    public function submitAllCategories(Request $request)
    {
        $query = \App\Models\ProductCategory::where('is_active', true)->orderBy('id');
        $totalAvailable = (clone $query)->count();

        $processed = 0;
        $success = 0;
        $failed = [];

        $baseUrl = rtrim((string) config('services.google_indexing.site_url', config('app.url')), '/');

        foreach ($query->get() as $category) {
            try {
                $url = $baseUrl . '/shop?category_id=' . $category->id;
                GoogleIndexingService::publishUrlStatic($url, 'URL_UPDATED', 'manual_bulk_category');
                $success++;
            } catch (\Throwable $e) {
                $failed[] = [
                    'category_id' => $category->id,
                    'name' => $category->name,
                    'message' => $e->getMessage(),
                ];
            }
            $processed++;
        }

        return response()->json([
            'success' => count($failed) === 0,
            'total_available' => $totalAvailable,
            'processed' => $processed,
            'submitted' => $success,
            'failed_count' => count($failed),
            'failed' => $failed,
            'message' => "Đã gửi yêu cầu Index cho {$success}/{$processed} danh mục thành công!"
        ]);
    }

    /**
     * Submit Proxy and VPN pages for indexing.
     */
    public function submitAllProxies(Request $request)
    {
        $success = 0;
        $failed = [];
        $urls = [
            route('vpn.tab', ['tab' => 'proxy']),
            route('vpn.tab', ['tab' => 'vpn'])
        ];

        foreach ($urls as $url) {
            try {
                GoogleIndexingService::publishUrlStatic($url, 'URL_UPDATED', 'manual_bulk_proxy');
                $success++;
            } catch (\Throwable $e) {
                $failed[] = [
                    'url' => $url,
                    'message' => $e->getMessage(),
                ];
            }
        }

        return response()->json([
            'success' => count($failed) === 0,
            'total_available' => 2,
            'processed' => 2,
            'submitted' => $success,
            'failed_count' => count($failed),
            'failed' => $failed,
            'message' => "Đã gửi yêu cầu Index cho trang Proxy và VPN thành công!"
        ]);
    }

    /**
     * Submit a single URL to Google Indexing.
     */
    public function submitUrl(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'type' => 'nullable|in:URL_UPDATED,URL_DELETED',
        ]);

        $url = $request->input('url');
        $type = $request->input('type', 'URL_UPDATED');

        try {
            $result = GoogleIndexingService::publishUrlStatic($url, $type, 'manual_single');
            return response()->json(['success' => true, 'data' => $result]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show indexing service status.
     */
    public function status()
    {
        $service = app(GoogleIndexingService::class);
        $enabled = $service->isEnabled();
        $keyFile = config('services.google_indexing.key_file', '');
        $siteUrl = config('services.google_indexing.site_url', config('app.url'));

        // Check if key file exists
        $keyFileExists = false;
        if ($keyFile) {
            $resolvedPath = $keyFile;
            if (!str_starts_with($keyFile, DIRECTORY_SEPARATOR) && !preg_match('/^[A-Za-z]:\\\\/', $keyFile)) {
                $resolvedPath = base_path($keyFile);
            }
            $keyFileExists = is_file($resolvedPath);
        }

        $recentCount = count(GoogleIndexingService::recentSubmissions(1440));
        $recentSuccessful = count(array_filter(
            GoogleIndexingService::recentSubmissions(1440),
            fn($r) => ($r['status'] ?? '') === 'success'
        ));

        return response()->json([
            'enabled' => $enabled,
            'site_url' => $siteUrl,
            'key_file_configured' => !empty($keyFile),
            'key_file_exists' => $keyFileExists,
            'total_blogs_published' => Blog::published()->count(),
            'total_products' => Product::count(),
            'recent_submissions_24h' => $recentCount,
            'recent_success_24h' => $recentSuccessful,
        ]);
    }
}
