<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
    // Hiển thị danh sách blog
    public function index()
    {
        $blogs = Blog::published()->orderBy('published_at', 'desc')->paginate(9);
        return view('blogs.index', compact('blogs'));
    }

    // Hiển thị chi tiết blog
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->published()->firstOrFail();
        $blog->incrementViews();
        
        // Lấy các bài liên quan
        $relatedBlogs = Blog::published()
            ->where('category', $blog->category)
            ->where('id', '!=', $blog->id)
            ->take(3)
            ->get();
        
        return view('blogs.show', compact('blog', 'relatedBlogs'));
    }

    // Lọc blog theo category
    public function category($category)
    {
        $blogs = Blog::published()
            ->byCategory($category)
            ->orderBy('published_at', 'desc')
            ->paginate(9);
        
        return view('blogs.index', compact('blogs', 'category'));
    }
}
