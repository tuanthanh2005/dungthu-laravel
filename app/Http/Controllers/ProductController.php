<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\TiktokDeal;
use App\Helpers\PathHelper;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $currentCategory = $request->category ?? 'all';
        $searchTerm = $request->search ?? '';
        
        // Xử lý category Tiktok
        if ($currentCategory === 'tiktok') {
            $query = TiktokDeal::active()->ordered();
            
            // Search trong tiktok deals
            if ($searchTerm) {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('description', 'LIKE', "%{$searchTerm}%");
                });
            }
            
            $items = $query->paginate(12)->withQueryString();
            $isTiktok = true;
        } else {
            // Xử lý products bình thường
            $query = Product::inStock();
            
            // Filter theo category nếu có
            if ($currentCategory != 'all') {
                $query->byCategory($currentCategory);
            }
            
            // Search theo tên hoặc mô tả
            if ($searchTerm) {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('description', 'LIKE', "%{$searchTerm}%");
                });
            }
            
            $items = $query->latest()->paginate(12)->withQueryString();
            $isTiktok = false;
        }
        
        return view('products.index', compact('items', 'currentCategory', 'searchTerm', 'isTiktok'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->with('comments')->firstOrFail();
        
        // Lấy sản phẩm liên quan
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->inStock()
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

