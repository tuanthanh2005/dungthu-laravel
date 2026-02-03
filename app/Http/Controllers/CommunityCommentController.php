<?php

namespace App\Http\Controllers;

use App\Models\CommunityComment;
use App\Models\CommunityPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommunityCommentController extends Controller
{
    public function store(Request $request, CommunityPost $post)
    {
        $data = $request->validate([
            'content' => 'required|string|max:2000',
        ], [
            'content.required' => 'Nội dung bình luận không được để trống',
            'content.max' => 'Nội dung bình luận không được vượt quá 2000 ký tự',
        ]);

        CommunityComment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'content' => $data['content'],
        ]);

        return back()->with('success', 'Đã đăng bình luận!');
    }

    public function update(Request $request, CommunityComment $comment)
    {
        Gate::authorize('community-comment.update', $comment);

        $data = $request->validate([
            'content' => 'required|string|max:2000',
        ], [
            'content.required' => 'Nội dung bình luận không được để trống',
            'content.max' => 'Nội dung bình luận không được vượt quá 2000 ký tự',
        ]);

        $comment->update([
            'content' => $data['content'],
        ]);

        return back()->with('success', 'Đã cập nhật bình luận!');
    }

    public function destroy(CommunityComment $comment)
    {
        Gate::authorize('community-comment.delete', $comment);

        $comment->delete();

        return back()->with('success', 'Đã xóa bình luận!');
    }
}
