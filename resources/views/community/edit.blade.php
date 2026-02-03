@extends('layouts.app')

@section('title', 'Chỉnh Sửa Bài Viết')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
<div class="container py-5" style="margin-top: 80px; max-width: 900px;">
    <h2 class="fw-bold mb-4">Chỉnh sửa bài viết</h2>

    <form method="POST" action="{{ route('community.update', $post) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label fw-bold">Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
            @error('title')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Nội dung</label>
            <textarea name="content" rows="10" class="form-control" required>{{ old('content', $post->content) }}</textarea>
            @error('content')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('community.show', $post) }}" class="btn btn-light">Hủy</a>
            <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
    </form>
</div>
@endsection
