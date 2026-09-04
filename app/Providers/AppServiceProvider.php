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
                    dispatch(function () {
                        \Illuminate\Support\Facades\Artisan::call('durations:check-expiring');
                    })->afterResponse();
                }
            } catch (\Throwable $e) {
                // Ignore any exception to guarantee page load is never affected
            }
        }
    }
}
