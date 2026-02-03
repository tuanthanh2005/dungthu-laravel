@extends('layouts.app')

@section('title', 'Sửa Danh mục - Admin')

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
        max-width: 800px;
        margin: 0 auto;
    }

    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        padding: 12px 16px;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 14px 40px;
        border-radius: 25px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(102,126,234,0.4);
    }

    .preview-image {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 14px;
        border: 1px solid rgba(0,0,0,0.06);
    }
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="container">
        <div class="admin-card" data-aos="fade-up">
            <div class="mb-4">
                <a href="{{ route('admin.categories') }}" class="btn btn-outline-secondary rounded-pill mb-3">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
                <h3 class="fw-bold mb-0">
                    <i class="fas fa-edit text-primary me-3"></i>Sửa Danh mục
                </h3>
            </div>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Có lỗi xảy ra!</h6>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="form-label">
                        <i class="fas fa-tag me-2 text-primary"></i>Tên danh mục <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $category->name) }}"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="type" class="form-label">
                        <i class="fas fa-layer-group me-2 text-primary"></i>Loại danh mục <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('type') is-invalid @enderror" name="type" id="type" required>
                        <option value="tech" {{ old('type', $category->type) === 'tech' ? 'selected' : '' }}>Công nghệ</option>
                        <option value="ebooks" {{ old('type', $category->type) === 'ebooks' ? 'selected' : '' }}>Ebooks</option>
                        <option value="doc" {{ old('type', $category->type) === 'doc' ? 'selected' : '' }}>Tài liệu</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="image" class="form-label">
                        <i class="fas fa-image me-2 text-primary"></i>Ảnh danh mục
                    </label>
                    <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image" accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($category->image)
                        <div class="mt-3">
                            <img src="{{ $category->image }}" alt="{{ $category->name }}" class="preview-image">
                        </div>
                    @endif
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label">
                        <i class="fas fa-align-left me-2 text-primary"></i>Mô tả
                    </label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="4">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check form-switch mb-4" style="padding-left: 2.5rem;">
                    <input class="form-check-input"
                           type="checkbox"
                           role="switch"
                           id="is_active"
                           name="is_active"
                           value="1"
                           {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                           style="width: 46px; height: 22px; cursor: pointer;">
                    <label class="form-check-label fw-bold" for="is_active" style="margin-left: 8px; cursor: pointer;">
                        <i class="fas fa-toggle-on text-success me-1"></i>Hiển thị
                    </label>
                </div>

                <div class="form-check form-switch mb-4" style="padding-left: 2.5rem;">
                    <input class="form-check-input"
                           type="checkbox"
                           role="switch"
                           id="show_on_home"
                           name="show_on_home"
                           value="1"
                           {{ old('show_on_home', $category->show_on_home) ? 'checked' : '' }}
                           style="width: 46px; height: 22px; cursor: pointer;">
                    <label class="form-check-label fw-bold" for="show_on_home" style="margin-left: 8px; cursor: pointer;">
                        <i class="fas fa-home text-primary me-1"></i>Hiển thị trên trang chủ
                    </label>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-save me-2"></i>Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true });
</script>
@endpush
