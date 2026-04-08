<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Product;
use Google\Client;
use Google\Service\Indexing;
use Google\Service\Indexing\UrlNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GoogleIndexingService
{
    private const RECENT_CACHE_KEY = 'google_indexing_recent_submissions';
    private const RECENT_CACHE_LIMIT = 500;

    // ──────────────────────────────────────────────────────────────
    //  Status helpers
    // ──────────────────────────────────────────────────────────────

    public function isEnabled(): bool
    {
        return (bool) config('services.google_indexing.enabled', false);
    }

    // ──────────────────────────────────────────────────────────────
    //  Core – publish / delete a single URL
    // ──────────────────────────────────────────────────────────────

    public function publishUrl(string $url, string $type = 'URL_UPDATED', string $source = 'manual'): array
    {
        $url = trim($url);

        if (!$this->isEnabled()) {
            $record = [
                'url' => $url,
                'type' => $type,
                'source' => $source,
                'status' => 'skipped',
                'message' => 'Google Indexing is disabled',
                'submitted_at' => now()->toIso8601String(),
            ];

            $this->appendRecentRecord($record);
            Log::info('[GoogleIndexing] Skipped – feature disabled', $record);

            return $record;
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Invalid URL for Google indexing: ' . $url);
        }

        if (!in_array($type, ['URL_UPDATED', 'URL_DELETED'], true)) {
            throw new \InvalidArgumentException('Invalid indexing type: ' . $type);
        }

        try {
            $service = new Indexing($this->buildClient());

            $notification = new UrlNotification();
            $notification->setUrl($url);
            $notification->setType($type);

            $response = $service->urlNotifications->publish($notification);

            $record = [
                'url' => $url,
                'type' => $type,
                'source' => $source,
                'status' => 'success',
                'submitted_at' => now()->toIso8601String(),
                'response' => json_decode(json_encode($response), true),
            ];

            $this->appendRecentRecord($record);
            Log::info('[GoogleIndexing] Success', ['url' => $url, 'type' => $type, 'source' => $source]);

            return $record;
        } catch (\Throwable $e) {
            $record = [
                'url' => $url,
                'type' => $type,
                'source' => $source,
                'status' => 'failed',
                'message' => $e->getMessage(),
                'submitted_at' => now()->toIso8601String(),
            ];

            $this->appendRecentRecord($record);
            Log::error('[GoogleIndexing] Failed', ['url' => $url, 'type' => $type, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    // ──────────────────────────────────────────────────────────────
    //  Static convenience wrappers
    // ──────────────────────────────────────────────────────────────

    /**
     * Publish an arbitrary URL (static version).
     */
    public static function publishUrlStatic(string $url, string $type = 'URL_UPDATED', string $source = 'blog'): array
    {
        return app(self::class)->publishUrl($url, $type, $source);
    }

    /**
     * Publish a Blog post URL.
     */
    public static function publishBlog(Blog $blog, string $type = 'URL_UPDATED', string $source = 'blog'): array
    {
        $url = self::buildSiteUrl('/blog/' . $blog->slug);
        return app(self::class)->publishUrl($url, $type, $source);
    }

    /**
     * Publish a Product URL.
     */
    public static function publishProduct(Product $product, string $type = 'URL_UPDATED', string $source = 'product'): array
    {
        $url = self::buildSiteUrl('/product/' . $product->slug);
        return app(self::class)->publishUrl($url, $type, $source);
    }

    /**
     * Notify Google that a URL has been removed.
     */
    public static function removeUrl(string $url, string $source = 'manual'): array
    {
        return app(self::class)->publishUrl($url, 'URL_DELETED', $source);
    }

    // ──────────────────────────────────────────────────────────────
    //  Safe wrappers (catch errors silently, log them)
    // ──────────────────────────────────────────────────────────────

    /**
     * Submit blog to Google Indexing silently (logs failures instead of throwing).
     */
    public static function submitBlogSafe(Blog $blog, string $source = 'blog_auto'): ?array
    {
        if (!$blog->is_published) {
            return null;
        }

        try {
            return self::publishBlog($blog, 'URL_UPDATED', $source);
        } catch (\Throwable $e) {
            Log::warning('[GoogleIndexing] submitBlogSafe failed', [
                'blog_id' => $blog->id,
                'slug' => $blog->slug,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Submit product to Google Indexing silently.
     */
    public static function submitProductSafe(Product $product, string $source = 'product_auto'): ?array
    {
        try {
            return self::publishProduct($product, 'URL_UPDATED', $source);
        } catch (\Throwable $e) {
            Log::warning('[GoogleIndexing] submitProductSafe failed', [
                'product_id' => $product->id,
                'slug' => $product->slug,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Notify Google that a blog has been deleted.
     */
    public static function removeBlogSafe(Blog $blog, string $source = 'blog_delete'): ?array
    {
        try {
            $url = self::buildSiteUrl('/blog/' . $blog->slug);
            return self::removeUrl($url, $source);
        } catch (\Throwable $e) {
            Log::warning('[GoogleIndexing] removeBlogSafe failed', [
                'blog_id' => $blog->id,
                'slug' => $blog->slug,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    // ──────────────────────────────────────────────────────────────
    //  Recent submissions (for admin dashboard)
    // ──────────────────────────────────────────────────────────────

    public static function recentSubmissions(int $minutes = 60, ?string $source = null, int $limit = 100): array
    {
        $minutes = max(1, min($minutes, 1440));
        $limit = max(1, min($limit, self::RECENT_CACHE_LIMIT));

        $records = Cache::get(self::RECENT_CACHE_KEY, []);
        if (!is_array($records)) {
            return [];
        }

        $threshold = now()->subMinutes($minutes);

        $filtered = array_values(array_filter($records, function ($record) use ($threshold, $source) {
            if (!is_array($record)) {
                return false;
            }

            if ($source !== null && ($record['source'] ?? null) !== $source) {
                return false;
            }

            $submittedAt = $record['submitted_at'] ?? null;
            if (!$submittedAt) {
                return false;
            }

            try {
                return Carbon::parse($submittedAt)->greaterThanOrEqualTo($threshold);
            } catch (\Throwable $e) {
                return false;
            }
        }));

        return array_slice($filtered, 0, $limit);
    }

    // ──────────────────────────────────────────────────────────────
    //  Internal helpers
    // ──────────────────────────────────────────────────────────────

    private static function buildSiteUrl(string $path): string
    {
        $baseUrl = rtrim((string) config('services.google_indexing.site_url', config('app.url')), '/');
        return $baseUrl . '/' . ltrim($path, '/');
    }

    private function appendRecentRecord(array $record): void
    {
        try {
            $records = Cache::get(self::RECENT_CACHE_KEY, []);
            if (!is_array($records)) {
                $records = [];
            }

            array_unshift($records, $record);
            $records = array_slice($records, 0, self::RECENT_CACHE_LIMIT);

            Cache::forever(self::RECENT_CACHE_KEY, $records);
        } catch (\Throwable $e) {
            // Keep indexing flow alive even if cache store is unavailable.
        }
    }

    private function buildClient(): Client
    {
        $keyFile = (string) config('services.google_indexing.key_file', '');
        $resolvedKeyFile = $this->resolveKeyFilePath($keyFile);

        if (!is_file($resolvedKeyFile)) {
            throw new \RuntimeException('Google Indexing key file not found: ' . $resolvedKeyFile);
        }

        $client = new Client();
        $client->setApplicationName((string) config('app.name', 'Laravel') . ' Google Indexing');
        $client->setScopes([Indexing::INDEXING]);
        $client->setAuthConfig($resolvedKeyFile);

        return $client;
    }

    private function resolveKeyFilePath(string $keyFile): string
    {
        $keyFile = trim($keyFile);

        if ($keyFile === '') {
            throw new \RuntimeException('GOOGLE_INDEXING_KEY_FILE is empty.');
        }

        // Absolute path (Linux/Mac or Windows)
        if (str_starts_with($keyFile, DIRECTORY_SEPARATOR) || preg_match('/^[A-Za-z]:\\\\/', $keyFile) === 1) {
            return $keyFile;
        }

        return base_path($keyFile);
    }
}
