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
            return $user->role === 'admin' || $post->user_id === $user->id;
        });

        Gate::define('community-post.delete', function ($user, CommunityPost $post) {
            return $user->role === 'admin' || $post->user_id === $user->id;
        });

        Gate::define('community-comment.update', function ($user, CommunityComment $comment) {
            return $user->role === 'admin' || $comment->user_id === $user->id;
        });

        Gate::define('community-comment.delete', function ($user, CommunityComment $comment) {
            return $user->role === 'admin' || $comment->user_id === $user->id;
        });
    }
}
