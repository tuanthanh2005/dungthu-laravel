@extends('layouts.app')

@section('title', 'Chỉnh Sửa Bài Viết')

@push('styles')
<style>
    .admin-wrapper {
        padding: 40px 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        margin-top: 70px;
    }

    .admin-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        max-width: 1000px;
        margin: 0 auto;
    }

    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 10px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        padding: 12px 15px;
        transition: all 0.3s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    textarea.form-control {
        min-height: 300px;
    }

    .image-preview {
        width: 100%;
        max-width: 500px;
        height: 334px;
        border: 2px dashed #e2e8f0;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f7fafc;
        margin-top: 15px;
    }

    .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-preview .placeholder {
        text-align: center;
        color: #a0aec0;
    }

    .image-preview .placeholder i {
        font-size: 48px;
        margin-bottom: 15px;
    }
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="container">
        <div class="admin-card">
            <div class="mb-4">
                <h3 class="fw-bold mb-2">
                    <i class="fas fa-edit text-primary me-2"></i>Chỉnh Sửa Bài Viết
                </h3>
                <p class="text-muted mb-0">Cập nhật thông tin bài viết</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('admin.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Tiêu đề -->
                <div class="mb-4">
                    <label for="title" class="form-label">
                        <i class="fas fa-heading me-2"></i>Tiêu đề bài viết
                    </label>
                    <input type="text" class="form-control" id="title" name="title" 
                           value="{{ old('title', $blog->title) }}" required 
                           placeholder="Nhập tiêu đề bài viết...">
                </div>

                <!-- Mô tả ngắn -->
                <div class="mb-4">
                    <label for="excerpt" class="form-label">
                        <i class="fas fa-align-left me-2"></i>Mô tả ngắn
                    </label>
                    <textarea class="form-control" id="excerpt" name="excerpt" 
                              rows="3" required 
                              placeholder="Nhập mô tả ngắn về bài viết...">{{ old('excerpt', $blog->excerpt) }}</textarea>
                    <small class="text-muted">Mô tả ngắn sẽ hiển thị trong danh sách bài viết</small>
                </div>

                <!-- Nội dung -->
                <div class="mb-4">
                    <label for="content" class="form-label">
                        <i class="fas fa-file-alt me-2"></i>Nội dung bài viết
                    </label>
                    <textarea class="form-control" id="content" name="content" 
                              required 
                              placeholder="Nhập nội dung chi tiết bài viết...">{{ old('content', $blog->content) }}</textarea>
                </div>

                <!-- Danh mục -->
                <div class="mb-4">
                    <label for="category" class="form-label">
                        <i class="fas fa-folder me-2"></i>Danh mục
                    </label>
                    <select class="form-select" id="category" name="category" required>
                        <option value="">-- Chọn danh mục --</option>
                        <option value="tech" {{ old('category', $blog->category) == 'tech' ? 'selected' : '' }}>Công nghệ</option>
                        <option value="lifestyle" {{ old('category', $blog->category) == 'lifestyle' ? 'selected' : '' }}>Lifestyle</option>
                        <option value="business" {{ old('category', $blog->category) == 'business' ? 'selected' : '' }}>Kinh doanh</option>
                        <option value="other" {{ old('category', $blog->category) == 'other' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>

                <!-- Nổi bật -->
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="is_featured" 
                               name="is_featured" 
                               value="1"
                               {{ old('is_featured', $blog->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">
                            <i class="fas fa-star me-2"></i>Nổi bật - Hiển thị trên trang chủ
                        </label>
                    </div>
                </div>

                <!-- Hình ảnh -->
                <div class="mb-4">
                    <label for="image" class="form-label">
                        <i class="fas fa-image me-2"></i>Hình ảnh đại diện
                    </label>
                    <input type="file" class="form-control" id="image" name="image" 
                           accept="image/*" onchange="previewImage(event)">
                    <small class="text-muted">Để trống nếu không muốn thay đổi ảnh. Ảnh mới sẽ được tự động cắt về 500x334px</small>
                    
                    <!-- Image Preview -->
                    <div class="image-preview" id="imagePreview">
                        @if($blog->image)
                            <img src="{{ $blog->image }}" alt="{{ $blog->title }}">
                        @else
                            <div class="placeholder">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <div>Chưa có ảnh</div>
                                <small class="text-muted">500 x 334 px</small>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill">
                        <i class="fas fa-save me-2"></i>Cập nhật
                    </button>
                    <a href="{{ route('admin.blogs') }}" class="btn btn-outline-secondary px-5 py-2 rounded-pill">
                        <i class="fas fa-times me-2"></i>Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#content',
    height: 500,
    menubar: true,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | table | link image | code fullscreen | help',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 16px; line-height: 1.6; }',
    branding: false
});

function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
@endsection
