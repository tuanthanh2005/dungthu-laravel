@php
    $level = $level ?? 0;
@endphp

<div class="comment-card">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            @if($comment->parent)
                <div class="reply-to mb-1">
                    <i class="fas fa-reply"></i>
                    {{ $comment->parent->user->name ?? 'Khách' }}
                </div>
            @endif
            <div class="fw-bold">{{ $comment->user->name ?? 'Khách' }}</div>
            <div class="small text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</div>
        </div>
        @auth
            <div class="d-flex gap-2">
                @can('community-comment.update', $comment)
                    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#edit-comment-{{ $comment->id }}">
                        Sửa
                    </button>
                @endcan
                @can('community-comment.delete', $comment)
                    <form method="POST" action="{{ route('community.comments.delete', $comment) }}" onsubmit="return confirm('Xóa bình luận này?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" type="submit">Xóa</button>
                    </form>
                @endcan
            </div>
        @endauth
    </div>

    <div class="mt-2" style="white-space: pre-line;">{{ $comment->content }}</div>

    <div class="mt-2 d-flex align-items-center gap-2">
        <button class="btn btn-sm reply-btn" type="button" data-bs-toggle="collapse" data-bs-target="#reply-comment-{{ $comment->id }}">
            Trả lời
        </button>
    </div>

    <div class="collapse mt-3" id="reply-comment-{{ $comment->id }}">
        <form method="POST" action="{{ route('community.comments.store', $post) }}">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <textarea name="content" rows="3" class="form-control" placeholder="Viết trả lời..." required></textarea>
            @error('content')<small class="text-danger">{{ $message }}</small>@enderror
            <button type="submit" class="btn btn-sm btn-primary mt-2">Gửi trả lời</button>
        </form>
    </div>

    @can('community-comment.update', $comment)
        <div class="collapse mt-3" id="edit-comment-{{ $comment->id }}">
            <form method="POST" action="{{ route('community.comments.update', $comment) }}">
                @csrf
                @method('PUT')
                <textarea name="content" rows="3" class="form-control" required>{{ $comment->content }}</textarea>
                <button type="submit" class="btn btn-sm btn-primary mt-2">Lưu</button>
            </form>
        </div>
    @endcan
</div>

@if($comment->replies->count())
    @php
        $replies = $comment->replies;
        $visibleReplies = $replies->take(3);
        $hiddenReplies = $replies->slice(3);
    @endphp
    <div class="reply-list d-flex flex-column gap-2">
        @foreach($visibleReplies as $reply)
            @include('community.partials.comment', ['comment' => $reply, 'post' => $post, 'level' => $level + 1])
        @endforeach
        @if($hiddenReplies->count())
            <div>
                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#more-replies-{{ $comment->id }}">
                    Xem thêm {{ $hiddenReplies->count() }} phản hồi
                </button>
            </div>
            <div class="collapse d-flex flex-column gap-2" id="more-replies-{{ $comment->id }}">
                @foreach($hiddenReplies as $reply)
                    @include('community.partials.comment', ['comment' => $reply, 'post' => $post, 'level' => $level + 1])
                @endforeach
            </div>
        @endif
    </div>
@endif
