@extends('layouts.app')

@section('title', 'Sửa Deal Tiktok')

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
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }

    .admin-nav {
        background: white;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }

    .admin-nav .nav-link {
        color: #4a5568;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 10px;
        transition: all 0.3s ease;
        margin: 0 5px;
    }

    .admin-nav .nav-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .admin-nav .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="container">
        <!-- Admin Navigation -->
        <nav class="admin-nav" data-aos="fade-down">
            <ul class="nav nav-pills justify-content-center flex-wrap">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.products') }}">
                        <i class="fas fa-box me-2"></i>Sản phẩm
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.tiktok-deals.index') }}">
                        <i class="fab fa-tiktok me-2"></i>Săn Sale TikTok
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.orders') }}">
                        <i class="fas fa-shopping-cart me-2"></i>Đơn hàng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0)" title="Tính năng sắp có">
                        <i class="fas fa-users me-2"></i>Người dùng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0)" title="Tính năng sắp có">
                        <i class="fas fa-blog me-2"></i>Bài viết
                    </a>
                </li>
            </ul>
        </nav>

<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="{{ route('admin.tiktok-deals.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning">
                    <h4 class="mb-0"><i class="fas fa-edit"></i> Sửa Deal Tiktok</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tiktok-deals.update', $tiktokDeal) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Tên Deal <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $tiktokDeal->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô Tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3">{{ old('description', $tiktokDeal->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Hình Ảnh</label>
                            
                            @if($tiktokDeal->image)
                                <div class="mb-2">
                                    <img src="{{ asset('images/products/' . $tiktokDeal->image) }}" 
                                         alt="{{ $tiktokDeal->name }}" 
                                         class="img-thumbnail"
                                         style="max-width: 200px;">
                                    <p class="text-muted small mt-1">Hình hiện tại</p>
                                </div>
                            @endif
                            
                            <input type="file" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   id="image" 
                                   name="image" 
                                   accept="image/*"
                                   onchange="previewImage(event)">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Để trống nếu không muốn thay đổi hình ảnh</small>
                            <div id="imagePreview" class="mt-2"></div>
                        </div>

                        <div class="mb-3">
                            <label for="tiktok_link" class="form-label">Link Tiktok Shop <span class="text-danger">*</span></label>
                            <input type="url" 
                                   class="form-control @error('tiktok_link') is-invalid @enderror" 
                                   id="tiktok_link" 
                                   name="tiktok_link" 
                                   value="{{ old('tiktok_link', $tiktokDeal->tiktok_link) }}" 
                                   placeholder="https://vt.tiktok.com/..."
                                   required>
                            @error('tiktok_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="original_price" class="form-label">Giá Gốc (₫)</label>
                                    <input type="number" 
                                           class="form-control @error('original_price') is-invalid @enderror" 
                                           id="original_price" 
                                           name="original_price" 
                                           value="{{ old('original_price', $tiktokDeal->original_price) }}" 
                                           step="0.01"
                                           min="0">
                                    @error('original_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="sale_price" class="form-label">Giá Sale (₫)</label>
                                    <input type="number" 
                                           class="form-control @error('sale_price') is-invalid @enderror" 
                                           id="sale_price" 
                                           name="sale_price" 
                                           value="{{ old('sale_price', $tiktokDeal->sale_price) }}" 
                                           step="0.01"
                                           min="0">
                                    @error('sale_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="discount_percent" class="form-label">% Giảm Giá</label>
                                    <input type="number" 
                                           class="form-control @error('discount_percent') is-invalid @enderror" 
                                           id="discount_percent" 
                                           name="discount_percent" 
                                           value="{{ old('discount_percent', $tiktokDeal->discount_percent) }}" 
                                           min="0"
                                           max="100">
                                    @error('discount_percent')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="order" class="form-label">Thứ Tự Hiển Thị</label>
                                    <input type="number" 
                                           class="form-control @error('order') is-invalid @enderror" 
                                           id="order" 
                                           name="order" 
                                           value="{{ old('order', $tiktokDeal->order) }}" 
                                           min="0">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Số nhỏ sẽ hiển thị trước</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label d-block">Trạng Thái</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1"
                                               {{ old('is_active', $tiktokDeal->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Kích hoạt hiển thị
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label d-block">Nổi Bật</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_featured" 
                                               name="is_featured" 
                                               value="1"
                                               {{ old('is_featured', $tiktokDeal->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            Hiển thị trên trang chủ
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Cập Nhật
                            </button>
                            <a href="{{ route('admin.tiktok-deals.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <p class="text-success small mb-1">Hình mới:</p>
                <img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px;">
            `;
        }
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
}
</script>
    </div>
</div>
@endsection
