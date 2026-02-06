<?php

namespace App\Http\Controllers;

use App\Models\CommunityPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use App\Helpers\PathHelper;

class CommunityPostController extends Controller
{
    public function index()
    {
        $posts = CommunityPost::with([
                'user',
                'comments.user',
            ])
            ->withCount('comments')
            ->where('is_published', true)
            ->latest()
            ->paginate(12);

        return view('community.index', compact('posts'));
    }

    public function show(CommunityPost $post)
    {
        if (!$post->is_published) {
            abort(404);
        }

        $post->load([
            'user',
            'comments.user',
            'comments.parent.user',
            'comments.replies.user',
            'comments.replies.parent.user',
            'comments.replies.replies.user',
            'comments.replies.replies.parent.user',
            'comments.replies.replies.replies.user',
            'comments.replies.replies.replies.parent.user',
        ]);

        return view('community.show', compact('post'));
    }

    public function create()
    {
        return view('community.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ], [
            'title.required' => 'Tiêu đề không được để trống',
            'content.required' => 'Nội dung không được để trống',
        ]);

        $slug = Str::slug($data['title']) . '-' . time();

        CommunityPost::create([
            'user_id' => auth()->id(),
            'title' => $data['title'],
            'slug' => $slug,
            'content' => $data['content'],
            'is_published' => true,
        ]);

        return redirect()->route('community.index')->with('success', 'Đăng bài thành công!');
    }

    public function edit(CommunityPost $post)
    {
        Gate::authorize('community-post.update', $post);

        return view('community.edit', compact('post'));
    }

    public function update(Request $request, CommunityPost $post)
    {
        Gate::authorize('community-post.update', $post);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ], [
            'title.required' => 'Tiêu đề không được để trống',
            'content.required' => 'Nội dung không được để trống',
        ]);

        $post->update([
            'title' => $data['title'],
            'content' => $data['content'],
        ]);

        return redirect()->route('community.show', $post)->with('success', 'Cập nhật bài viết thành công!');
    }

    public function destroy(CommunityPost $post)
    {
        Gate::authorize('community-post.delete', $post);

        $post->delete();

        return redirect()->route('community.index')->with('success', 'Xóa bài viết thành công!');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '_' . uniqid() . '.' . $extension;

        $dir = PathHelper::publicRootPath('images/community');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file->move($dir, $fileName);

        return response()->json([
            'location' => asset('images/community/' . $fileName),
        ]);
    }
}
