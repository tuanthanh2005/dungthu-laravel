<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin'              => \App\Http\Middleware\AdminMiddleware::class,
            'admin.pin'          => \App\Http\Middleware\RequireAdminPin::class,
            'admin.lock'         => \App\Http\Middleware\AdminRouteLock::class,
            'menu.check'         => \App\Http\Middleware\CheckMenuEnabled::class,
            'affiliate.auth'     => \App\Http\Middleware\AffiliateAuth::class,
            'affiliate.approved' => \App\Http\Middleware\AffiliateApproved::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);


        
        // Exclude CSRF for OAuth callbacks
        $middleware->validateCsrfTokens(except: [
            'auth/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            // For API requests, return standard JSON 404 response
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['message' => 'Not Found'], 404);
            }
            
            // For admin paths, redirect to admin dashboard
            if ($request->is('admin*')) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Trang quản trị không tồn tại hoặc đã bị xóa.');
            }
            
            // For regular web requests, redirect to homepage with an error alert
            return redirect()->route('home')
                ->with('error', 'Trang bạn tìm kiếm không tồn tại hoặc đã bị đổi địa chỉ. Đang đưa bạn về trang chủ.');
        });
    })->create();

