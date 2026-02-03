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
            <textarea id="community-editor" name="content" rows="10" class="form-control">{{ old('content', $post->content) }}</textarea>
            @error('content')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('community.show', $post) }}" class="btn btn-light">Hủy</a>
            <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#community-editor',
            height: 500,
            menubar: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | table | link image | code fullscreen | help',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif; font-size: 16px; line-height: 1.6; }',
            branding: false,
            images_upload_handler: function (blobInfo) {
                return new Promise(function (resolve, reject) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '{{ route('community.images.upload') }}');
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                    xhr.onload = function () {
                        if (xhr.status < 200 || xhr.status >= 300) {
                            reject('Upload failed: ' + xhr.status);
                            return;
                        }
                        try {
                            var json = JSON.parse(xhr.responseText);
                            if (!json.location) {
                                reject('Invalid response');
                                return;
                            }
                            resolve(json.location);
                        } catch (e) {
                            reject('Invalid JSON response');
                        }
                    };
                    xhr.onerror = function () {
                        reject('Upload failed');
                    };

                    var formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    xhr.send(formData);
                });
            }
        });

        document.querySelector('form').addEventListener('submit', function (e) {
            tinymce.triggerSave();
            const content = tinymce.get('community-editor').getContent();
            if (!content || content.trim() === '') {
                e.preventDefault();
                alert('Vui lòng nhập nội dung bài viết!');
                tinymce.get('community-editor').focus();
                return false;
            }
        });
    </script>
@endpush
