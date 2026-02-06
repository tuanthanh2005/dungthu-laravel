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
            'parent_id' => 'nullable|integer|exists:community_comments,id',
        ], [
            'content.required' => 'Nội dung bình luận không được để trống',
            'content.max' => 'Nội dung bình luận không được vượt quá 2000 ký tự',
        ]);

        if (!empty($data['parent_id'])) {
            $parent = CommunityComment::query()->where('id', $data['parent_id'])->where('post_id', $post->id)->first();
            if (!$parent) {
                return back()->withErrors(['content' => 'Bình luận trả lời không hợp lệ.']);
            }
        }

        CommunityComment::create([
            'post_id' => $post->id,
            'user_id' => auth()->check() ? auth()->id() : null,
            'content' => $data['content'],
            'parent_id' => $data['parent_id'] ?? null,
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
