<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\CommunityPost;
use App\Models\CommunityComment;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // This project uses Bootstrap (not Tailwind) for frontend styling.
        // Use Bootstrap pagination views to avoid "double" pagination blocks.
        Paginator::useBootstrapFive();

        Gate::define('community-post.update', function ($user, CommunityPost $post) {
            return in_array($user->role, ['superadmin_1', 'sieusuperadmin'], true) || $post->user_id === $user->id;
        });

        Gate::define('community-post.delete', function ($user, CommunityPost $post) {
            return in_array($user->role, ['superadmin_1', 'sieusuperadmin'], true) || $post->user_id === $user->id;
        });

        Gate::define('community-comment.update', function ($user, CommunityComment $comment) {
            return in_array($user->role, ['superadmin_1', 'sieusuperadmin'], true) || $comment->user_id === $user->id;
        });

        Gate::define('community-comment.delete', function ($user, CommunityComment $comment) {
            return in_array($user->role, ['superadmin_1', 'sieusuperadmin'], true) || $comment->user_id === $user->id;
        });

        // Fallback tự động 100%: Quét khách hàng hết hạn khi có bất kỳ lượt truy cập web nào trong ngày (cho Hosting)
        if (!app()->runningInConsole()) {
            try {
                $cacheKey = 'daily_expiring_check_ran_' . date('Y-m-d');
                if (!\Illuminate\Support\Facades\Cache::has($cacheKey)) {
                    \Illuminate\Support\Facades\Cache::put($cacheKey, true, now()->endOfDay());
                    app()->terminating(function () {
                        try {
                            \Illuminate\Support\Facades\Artisan::call('durations:check-expiring');
                        } catch (\Throwable $e) {}
                    });
                }
            } catch (\Throwable $e) {
                // Ignore any exception to guarantee page load is never affected
            }

            // Fallback tự động 100% cho Auto Blog AI: Tự động chạy sau mỗi 15 phút khi có bất kỳ lượt truy cập web nào (cho Hosting không có cron daemon)
            try {
                $autoBlogEnabled = (bool) \App\Models\SiteSetting::getValue('auto_blog_enabled', '1');
                if ($autoBlogEnabled) {
                    $lastRunAt = \App\Models\SiteSetting::getValue('auto_blog_last_run_at');
                    $interval = (int) \App\Models\SiteSetting::getValue('auto_blog_interval_minutes', '15');
                    $isOverdue = false;

                    if (empty($lastRunAt)) {
                        $isOverdue = true;
                    } else {
                        $diffMinutes = \Carbon\Carbon::parse($lastRunAt)->diffInMinutes(now());
                        if ($diffMinutes >= $interval) {
                            $isOverdue = true;
                        }
                    }

                    if ($isOverdue) {
                        // Khóa Cache 6 phút để tránh các request đồng thời kích hoạt trùng nhau
                        $lockKey = 'auto_blog_web_execution_lock';
                        if (!\Illuminate\Support\Facades\Cache::has($lockKey)) {
                            \Illuminate\Support\Facades\Cache::put($lockKey, true, now()->addMinutes(6));
                            app()->terminating(function () {
                                @ignore_user_abort(true);
                                @set_time_limit(180);
                                if (!headers_sent()) {
                                    @header('X-LiteSpeed-No-Abort: 1');
                                }
                                try {
                                    \Illuminate\Support\Facades\Artisan::call('blog:auto-write');
                                } catch (\Throwable $e) {
                                    \Illuminate\Support\Facades\Log::error('[AutoBlog WebFallback] Lỗi: ' . $e->getMessage());
                                }
                            });
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Đảm bảo không bao giờ ảnh hưởng tới tốc độ tải trang của người dùng
            }
        }
    }
}
