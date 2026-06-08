<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Blog;
use App\Models\PreOrder;
use Illuminate\Support\Str;

class SeoRouterController extends Controller
{
    public function handle($slug)
    {
        $normalizedSlug = Str::slug($slug);

        // 1. Tìm sản phẩm thật có slug khớp
        // Exact match
        $product = Product::where('slug', $normalizedSlug)->first();
        if ($product) {
            return redirect()->route('product.show', $product->slug, 301);
        }

        // Substring match: Tìm xem có sản phẩm nào chứa từ khóa slug này không
        $product = Product::where('slug', 'like', '%' . $normalizedSlug . '%')->first();
        if ($product) {
            return redirect()->route('product.show', $product->slug, 301);
        }

        // 2. Tìm bài viết blog thật có slug khớp
        // Exact match
        $blog = Blog::published()->where('slug', $normalizedSlug)->first();
        if ($blog) {
            return redirect()->route('blog.show', $blog->slug, 301);
        }

        // Substring match
        $blog = Blog::published()->where('slug', 'like', '%' . $normalizedSlug . '%')->first();
        if ($blog) {
            return redirect()->route('blog.show', $blog->slug, 301);
        }

        // 3. Nếu chưa có hàng thật -> Hiển thị trang Landing Page giữ chỗ (Pre-order)
        // Tạo tiêu đề và mô tả đẹp dựa trên slug
        $keywordTitle = Str::headline(str_replace('-', ' ', $normalizedSlug));
        
        // Lấy danh sách sản phẩm bán chạy/liên quan để hiển thị bên dưới
        $popularProducts = Product::latest()->take(4)->get();

        return view('pages.seo-placeholder', [
            'slug' => $normalizedSlug,
            'keywordTitle' => $keywordTitle,
            'popularProducts' => $popularProducts
        ]);
    }

    public function subscribe(Request $request, $slug)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
        ]);

        $normalizedSlug = Str::slug($slug);

        try {
            PreOrder::updateOrCreate(
                [
                    'email' => $request->email,
                    'keyword' => $normalizedSlug
                ],
                [
                    'status' => 'pending'
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Đăng ký nhận thông báo thành công! Chúng tôi sẽ gửi email cho bạn ngay khi có sản phẩm.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau.'
            ], 500);
        }
    }
}
