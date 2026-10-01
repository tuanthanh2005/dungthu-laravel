@extends('layouts.admin')

@section('title', 'Thêm Sản phẩm - Admin')

@section('page_title', 'Thêm sản phẩm')

@push('styles')
<style>
    .form-container-clean {
        width: 100%;
        max-width: 100%;
    }

    .form-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        margin-bottom: 22px;
        overflow: hidden;
    }

    .form-card-header {
        background: #f8fafc;
        border-bottom: 1px solid #eef2f6;
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .form-card-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-card-body {
        padding: 18px 20px;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #334155;
        margin-bottom: 5px;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        padding: 8px 12px;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.12);
    }

    .category-hint {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 4px;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 10px 24px;
        border-radius: 25px;
        color: white;
        font-weight: 600;
        transition: all 0.2s ease;
        box-shadow: 0 4px 15px rgba(102,126,234,0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102,126,234,0.45);
        color: white;
    }

    .toggle-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s ease;
    }

    .toggle-card:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .feature-item-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 12px;
        height: 100%;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-bottom: 0;
    }

    .feature-item-card:hover {
        background: #ffffff;
        border-color: #667eea;
        box-shadow: 0 2px 8px rgba(102,126,234,0.1);
    }

    .feature-item-card input:checked ~ div .feature-name {
        color: #4f46e5;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .image-preview {
        max-width: 140px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .variant-table-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f8fafc;
    }
    .variant-table-container::-webkit-scrollbar {
        height: 7px;
    }
    .variant-table-container::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    .variant-table-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .variant-table-container::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    .variant-table th {
        position: relative;
    }
    .variant-col-resizer {
        position: absolute;
        top: 0;
        right: 0;
        width: 6px;
        cursor: col-resize;
        user-select: none;
        height: 100%;
        z-index: 2;
    }
    .variant-col-resizer:hover,
    .variant-col-resizer.is-resizing {
        background: rgba(99, 102, 241, 0.4);
    }
</style>
@endpush

@section('content')
<div class="form-container-clean" data-aos="fade-up">

    <!-- Top Action & Title Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary rounded-pill btn-sm mb-2">
                <i class="fas fa-arrow-left me-1"></i>Quay lại danh sách
            </a>
            <h3 class="fw-bold mb-0 text-dark">
                <i class="fas fa-plus-circle text-primary me-2"></i>Thêm Sản phẩm Mới
            </h3>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="fas fa-times me-1"></i>Hủy
            </a>
            <button type="submit" form="productForm" class="btn btn-submit">
                <i class="fas fa-save me-1"></i>Lưu sản phẩm
            </button>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <h6 class="alert-heading fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Có lỗi xảy ra!</h6>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form id="productForm" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- 1. Thông tin cơ bản & Phân loại -->
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-title">
                    <i class="fas fa-cube text-primary"></i> 1. Thông tin sản phẩm & Phân loại
                </div>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1" style="font-size: 0.75rem;">Thông tin chính</span>
            </div>
            <div class="form-card-body">
                <!-- Names Row -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">
                            <i class="fas fa-tag me-1 text-primary"></i>Tên sản phẩm <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               placeholder="Nhập tên sản phẩm..."
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="name_en" class="form-label text-success">
                            <i class="fas fa-tag me-1"></i>Tên sản phẩm (Tiếng Anh)
                        </label>
                        <input type="text" 
                               class="form-control @error('name_en') is-invalid @enderror" 
                               id="name_en" 
                               name="name_en" 
                               value="{{ old('name_en') }}"
                               placeholder="Enter product name in English...">
                        @error('name_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Category, Delivery Type, Duration Row -->
                <div class="row g-3 mb-3">
                    <!-- Category -->
                    <div class="col-md-4">
                        <label for="category_id" class="form-label">
                            <i class="fas fa-list me-1 text-primary"></i>Danh mục <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" id="category_id" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                        data-type="{{ $cat->type }}"
                                        {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($categories->isEmpty())
                            <div class="text-danger mt-1 small">Chưa có danh mục phù hợp. Vui lòng thêm danh mục trước.</div>
                        @else
                            <div class="category-hint">Quyết định loại SP (ebooks / tài liệu / tech).</div>
                        @endif
                    </div>

                    <!-- Delivery Type -->
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="fas fa-shipping-fast me-1 text-primary"></i>Loại giao hàng <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex gap-3 pt-1">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="delivery_type" id="digital" value="digital" {{ old('delivery_type', 'digital') == 'digital' ? 'checked' : '' }} required>
                                <label class="form-check-label ms-1 cursor-pointer fw-semibold" for="digital">
                                    <i class="fas fa-download text-primary me-1"></i>Sản phẩm số (Digital)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="delivery_type" id="physical" value="physical" {{ old('delivery_type', 'digital') == 'physical' ? 'checked' : '' }}>
                                <label class="form-check-label ms-1 cursor-pointer fw-semibold" for="physical">
                                    <i class="fas fa-box text-secondary me-1"></i>Vật lý (Physical)
                                </label>
                            </div>
                        </div>
                        @error('delivery_type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Duration -->
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="fas fa-clock me-1 text-primary"></i>Thời hạn sản phẩm
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control @error('duration_value') is-invalid @enderror" 
                                   id="duration_value" 
                                   name="duration_value" 
                                   value="{{ old('duration_value') }}" 
                                   min="1" 
                                   placeholder="Số: 1, 7, 30...">
                            <select class="form-select @error('duration_type') is-invalid @enderror" 
                                    id="duration_type" 
                                    name="duration_type" 
                                    style="max-width: 140px;">
                                <option value="">Không giới hạn</option>
                                <option value="days" {{ old('duration_type') == 'days' ? 'selected' : '' }}>Ngày</option>
                                <option value="months" {{ old('duration_type') == 'months' ? 'selected' : '' }}>Tháng</option>
                            </select>
                        </div>
                        @error('duration_value')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('duration_type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Descriptions Row -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="description" class="form-label">
                            <i class="fas fa-align-left me-1 text-primary"></i>Mô tả chi tiết <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3"
                                  placeholder="Nhập mô tả chi tiết sản phẩm..."
                                  required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="description_en" class="form-label text-success">
                            <i class="fas fa-align-left me-1"></i>Mô tả chi tiết (Tiếng Anh)
                        </label>
                        <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                  id="description_en" 
                                  name="description_en" 
                                  rows="3"
                                  placeholder="Enter detailed description in English...">{{ old('description_en') }}</textarea>
                        @error('description_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Giá bán, Tồn kho & Giảm giá -->
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-title">
                    <i class="fas fa-coins text-warning"></i> 2. Giá bán, Tồn kho & Giảm giá
                </div>
            </div>
            <div class="form-card-body">
                <!-- Price, USD, Stock, Fake Sold -->
                <div class="row g-3 mb-3">
                    <div class="col-md-3 col-6">
                        <label for="price" class="form-label">
                            <i class="fas fa-dollar-sign text-primary me-1"></i>Giá (VNĐ) <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               class="form-control @error('price') is-invalid @enderror" 
                               id="price" 
                               name="price" 
                               value="{{ old('price') }}"
                               min="0"
                               step="1000"
                               placeholder="0"
                               required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 col-6">
                        <label for="price_usd" class="form-label text-success">
                            <i class="fas fa-dollar-sign me-1"></i>Giá (USD)
                        </label>
                        <input type="number" 
                               class="form-control @error('price_usd') is-invalid @enderror" 
                               id="price_usd" 
                               name="price_usd" 
                               value="{{ old('price_usd') }}"
                               min="0"
                               step="0.01"
                               placeholder="Tự động tính nếu trống">
                        @error('price_usd')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 col-6">
                        <label for="stock" class="form-label">
                            <i class="fas fa-warehouse text-primary me-1"></i>Tồn kho <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               class="form-control @error('stock') is-invalid @enderror" 
                               id="stock" 
                               name="stock" 
                               value="{{ old('stock', 0) }}"
                               min="0"
                               placeholder="0"
                               required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 col-6">
                        <label for="fake_sold" class="form-label text-warning">
                            <i class="fas fa-shopping-bag me-1"></i>Đã Bán (Ảo/Cộng thêm)
                        </label>
                        <input type="number" 
                               class="form-control @error('fake_sold') is-invalid @enderror" 
                               id="fake_sold" 
                               name="fake_sold" 
                               value="{{ old('fake_sold', 0) }}"
                               min="0"
                               placeholder="0">
                        @error('fake_sold')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Sale Section (Inline Box) -->
                <div class="p-3 bg-light rounded-3 border">
                    <div class="d-flex align-items-center mb-2">
                        <div class="form-check form-switch m-0 me-2" style="padding-left: 2.5rem;">
                            <input class="form-check-input"
                                   type="checkbox"
                                   role="switch"
                                   id="is_on_sale"
                                   name="is_on_sale"
                                   value="1"
                                   {{ old('is_on_sale') ? 'checked' : '' }}
                                   style="width: 44px; height: 22px; cursor: pointer;">
                        </div>
                        <label class="form-check-label fw-bold cursor-pointer" for="is_on_sale">
                            <i class="fas fa-tags text-danger me-1"></i>Bật chương trình giảm giá
                        </label>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="sale_price" class="form-label small text-muted">Giá giảm (VNĐ)</label>
                            <input type="number" 
                                   class="form-control @error('sale_price') is-invalid @enderror" 
                                   id="sale_price" 
                                   name="sale_price" 
                                   value="{{ old('sale_price') }}"
                                   min="0"
                                   step="1000"
                                   placeholder="Để trống nếu không giảm">
                            @error('sale_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="sale_price_usd" class="form-label small text-muted">Giá giảm (USD)</label>
                            <input type="number" 
                                   class="form-control @error('sale_price_usd') is-invalid @enderror" 
                                   id="sale_price_usd" 
                                   name="sale_price_usd" 
                                   value="{{ old('sale_price_usd') }}"
                                   min="0"
                                   step="0.01"
                                   placeholder="Tự động tính nếu trống">
                            @error('sale_price_usd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Hình ảnh & Tệp tải về -->
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-title">
                    <i class="fas fa-image text-primary"></i> 3. Hình ảnh sản phẩm & Tệp tải về
                </div>
            </div>
            <div class="form-card-body">
                <div class="row g-4">
                    <!-- Image Upload -->
                    <div class="col-md-6">
                        <label for="image" class="form-label">
                            <i class="fas fa-camera me-1 text-primary"></i>Ảnh sản phẩm
                        </label>
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/*"
                               onchange="previewImage(event)">
                        <small class="text-muted d-block mt-1" style="font-size: 0.78rem;">JPG, PNG, GIF (tối đa 2MB). Crop chuẩn về 500x334 pixels.</small>
                        @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        
                        <!-- Image Preview -->
                        <div id="imagePreview" class="mt-2" style="display: none;">
                            <div class="position-relative border rounded-3 p-1 bg-light text-center" style="max-width: 200px;">
                                <img id="preview" src="" alt="Preview" class="img-fluid rounded" style="max-height: 140px; object-fit: contain;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle" style="width: 24px; height: 24px; padding: 0; line-height: 1;" onclick="removeImage()">
                                    <i class="fas fa-times" style="font-size: 11px;"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- File Upload (ebooks) -->
                    <div class="col-md-6" id="fileUploadSection" style="display: none;">
                        <label for="file" class="form-label">
                            <i class="fas fa-file-upload me-1 text-primary"></i>File tải về (PDF, DOCX, ZIP)
                        </label>
                        <input type="file" 
                               class="form-control @error('file') is-invalid @enderror" 
                               id="file" 
                               name="file"
                               accept=".pdf,.doc,.docx,.txt,.zip,.rar"
                               onchange="previewFile(event)">
                        <small class="text-muted d-block mt-1" style="font-size: 0.78rem;">File tài liệu để khách tải về sau khi mua (tối đa 50MB).</small>
                        @error('file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        
                        <!-- File Preview -->
                        <div id="filePreview" class="mt-2" style="display: none;">
                            <div class="alert alert-info py-2 px-3 mb-0 small">
                                <i class="fas fa-file me-1"></i><span id="fileName"></span> 
                                <span class="badge bg-primary ms-1" id="fileSize"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Cài đặt hiển thị & Vị trí (Lưới ngang 4 cột) -->
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-title">
                    <i class="fas fa-sliders-h text-primary"></i> 4. Cài đặt hiển thị & Vị trí xuất hiện
                </div>
            </div>
            <div class="form-card-body">
                <div class="row g-3">
                    <!-- is_active -->
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="toggle-card">
                            <div class="pe-2">
                                <label class="form-check-label fw-bold d-block mb-0 cursor-pointer" for="is_active">
                                    <i class="fas fa-eye text-success me-1"></i> Hiển thị sản phẩm
                                </label>
                                <small class="text-muted" style="font-size: 0.75rem;">Hiện trên trang chủ & shop</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', '1') === '1' ? 'checked' : '' }} style="width: 44px; height: 22px;">
                            </div>
                        </div>
                    </div>

                    <!-- is_flash_sale -->
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="toggle-card">
                            <div class="pe-2">
                                <label class="form-check-label fw-bold d-block mb-0 cursor-pointer" for="is_flash_sale">
                                    <i class="fas fa-bolt text-danger me-1"></i> Ưu tiên Flash Sale
                                </label>
                                <small class="text-muted" style="font-size: 0.75rem;">4 ô giảm giá trang chủ</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="is_flash_sale" name="is_flash_sale" value="1" {{ old('is_flash_sale') ? 'checked' : '' }} style="width: 44px; height: 22px;">
                            </div>
                        </div>
                    </div>

                    <!-- is_vpn -->
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="toggle-card">
                            <div class="pe-2">
                                <label class="form-check-label fw-bold d-block mb-0 cursor-pointer" for="is_vpn">
                                    <i class="fas fa-network-wired text-info me-1"></i> Sản phẩm VPN
                                </label>
                                <small class="text-muted" style="font-size: 0.75rem;">Hiện trong chuyên mục VPN</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="is_vpn" name="is_vpn" value="1" {{ old('is_vpn') ? 'checked' : '' }} style="width: 44px; height: 22px;">
                            </div>
                        </div>
                    </div>

                    <!-- is_featured -->
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="toggle-card">
                            <div class="pe-2">
                                <label class="form-check-label fw-bold d-block mb-0 cursor-pointer" for="is_featured">
                                    <i class="fas fa-star text-warning me-1"></i> Sản phẩm nổi bật
                                </label>
                                <small class="text-muted" style="font-size: 0.75rem;">Trang chủ - Hàng 1</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} style="width: 44px; height: 22px;">
                            </div>
                        </div>
                    </div>

                    <!-- is_exclusive -->
                    <div class="col-xl-4 col-md-6 col-12">
                        <div class="toggle-card">
                            <div class="pe-2">
                                <label class="form-check-label fw-bold d-block mb-0 cursor-pointer" for="is_exclusive">
                                    <i class="fas fa-gem text-success me-1"></i> Sản phẩm độc quyền
                                </label>
                                <small class="text-muted" style="font-size: 0.75rem;">Trang chủ - Hàng 2</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="is_exclusive" name="is_exclusive" value="1" {{ old('is_exclusive') ? 'checked' : '' }} style="width: 44px; height: 22px;">
                            </div>
                        </div>
                    </div>

                    <!-- show_on_banner -->
                    <div class="col-xl-4 col-md-6 col-12">
                        <div class="toggle-card">
                            <div class="pe-2">
                                <label class="form-check-label fw-bold d-block mb-0 cursor-pointer" for="show_on_banner">
                                    <i class="fas fa-desktop text-primary me-1"></i> Nổi bật Banner Hero
                                </label>
                                <small class="text-muted" style="font-size: 0.75rem;">4 thẻ cập nhật mới ở banner chính</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="show_on_banner" name="show_on_banner" value="1" {{ old('show_on_banner') ? 'checked' : '' }} style="width: 44px; height: 22px;">
                            </div>
                        </div>
                    </div>

                    <!-- is_combo_ai -->
                    <div class="col-xl-4 col-md-6 col-12">
                        <div class="toggle-card">
                            <div class="pe-2">
                                <label class="form-check-label fw-bold d-block mb-0 cursor-pointer" for="is_combo_ai">
                                    <i class="fas fa-robot text-primary me-1"></i> Combo AI giá rẻ
                                </label>
                                <small class="text-muted" style="font-size: 0.75rem;">Mục Combo AI trên trang chủ</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="is_combo_ai" name="is_combo_ai" value="1" {{ old('is_combo_ai') ? 'checked' : '' }} style="width: 44px; height: 22px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Tính năng sản phẩm (Lưới 4 cột) -->
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-title">
                    <i class="fas fa-star text-warning"></i> 5. Tính năng sản phẩm
                </div>
                <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2 py-1" style="font-size: 0.75rem;">Tùy chọn</span>
            </div>
            <div class="form-card-body">
                @if(isset($features) && $features->count() > 0)
                    <div class="row g-3">
                        @foreach($features as $feature)
                            <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                <label class="feature-item-card" for="feature_{{ $feature->id }}">
                                    <input class="form-check-input mt-1" type="checkbox" name="features[]" value="{{ $feature->id }}" id="feature_{{ $feature->id }}" {{ in_array($feature->id, old('features', [])) ? 'checked' : '' }}>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="fw-semibold feature-name text-truncate" style="font-size: 0.86rem;">
                                            <i class="{{ $feature->icon }} me-1" style="color: {{ $feature->color }}"></i>{{ $feature->name }}
                                        </div>
                                        @if($feature->description)
                                            <small class="text-muted d-block text-truncate" style="font-size: 0.75rem;" title="{{ $feature->description }}">{{ $feature->description }}</small>
                                        @endif
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i>Chưa có tính năng nào cho loại sản phẩm này.
                    </div>
                @endif
            </div>
        </div>

        <!-- 6. Các loại / Gói dịch vụ (Variants) -->
        @php
            $hasVariants = old('has_variants', false);
            $oldVariantNames = old('variant_names');
            $oldVariantIds = old('variant_ids', []);
            $oldVariantPrices = old('variant_prices', []);
            $oldVariantSalePrices = old('variant_sale_prices', []);
            $oldVariantStocks = old('variant_stocks', []);
            $oldVariantDurValues = old('variant_duration_values', []);
            $oldVariantDurTypes = old('variant_duration_types', []);
            $oldVariantSpecs = old('variant_specs', []);
        @endphp
        <div class="form-card">
            <div class="form-card-header">
                <div>
                    <div class="form-card-title">
                        <i class="fas fa-layer-group text-primary"></i> 6. Các loại / Gói dịch vụ (Variants)
                    </div>
                    <small class="text-muted" style="font-size: 0.8rem;">Bật khi sản phẩm có nhiều gói thời hạn, phiên bản hoặc mức giá khác nhau</small>
                </div>
                <div class="form-check form-switch m-0" style="padding-left: 2.5rem;">
                    <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="has_variants" name="has_variants" value="1" {{ $hasVariants ? 'checked' : '' }} onchange="toggleVariantsSection()" style="width: 46px; height: 22px;">
                </div>
            </div>
            <div class="form-card-body" id="variantsContainer" style="{{ $hasVariants ? '' : 'display: none;' }}">
                <!-- AI Quick Parse Box -->
                <div class="p-3 mb-3 rounded-3" style="background: linear-gradient(135deg, #f0f7ff 0%, #f5f3ff 100%); border: 1.5px dashed #a5b4fc;">
                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                        <label class="fw-bold text-dark d-flex align-items-center gap-2 mb-0" style="font-size: 0.9rem;">
                            <i class="fas fa-wand-magic-sparkles text-primary"></i> Nhập nhanh nhiều gói bằng AI (Theo mô tả của Leader)
                        </label>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                            <i class="fas fa-robot me-1"></i>Tự động nhận diện mô tả ở cuối dòng
                        </span>
                    </div>
                    <textarea class="form-control mb-2" id="aiVariantsInput" rows="2" 
                              placeholder="Ví dụ dán vào đây:
Gói 1 tháng: 35k (giá gốc 50k) - 1 profile, cấp sẵn, kho 50
Gói 3 tháng: 90k - bảo hành 90 ngày, kho 30"></textarea>
                    <div class="d-flex gap-2 justify-content-between align-items-center flex-wrap">
                        <small class="text-muted" style="font-size: 0.8rem;">
                            <i class="fas fa-info-circle text-info me-1"></i>Hệ thống sẽ giữ trọn vẹn mô tả ở cuối dòng và gán đúng giá, kho, hạn dùng.
                        </small>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="parseVariantsFast()">
                                <i class="fas fa-bolt me-1 text-warning"></i>Phân tích nhanh
                            </button>
                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm" id="btnRunAiVariants" onclick="parseVariantsWithAI()">
                                <i class="fas fa-wand-magic-sparkles me-1"></i>AI Phân Tích & Tạo Gói
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive variant-table-container border rounded">
                    <table class="table table-bordered align-middle mb-0 variant-table" style="min-width: 1080px;">
                        <thead class="table-light">
                            <tr class="text-center text-nowrap" style="font-size: 0.84rem;">
                                <th style="min-width: 270px;">Tên gói / Phiên bản <span class="text-danger">*</span></th>
                                <th style="width: 125px; min-width: 115px;">Giá bán (VNĐ) <span class="text-danger">*</span></th>
                                <th style="width: 125px; min-width: 115px;">Giá gốc (VNĐ)</th>
                                <th style="width: 75px; min-width: 70px;">Kho</th>
                                <th style="width: 150px; min-width: 145px;">Thời hạn bảo hành</th>
                                <th style="min-width: 320px;">Nội dung / Thông số kỹ thuật</th>
                                <th style="width: 42px; min-width: 42px;" class="no-resize text-center"><i class="fas fa-trash-alt text-muted" title="Thao tác"></i></th>
                            </tr>
                        </thead>
                        <tbody id="variantRows">
                            @if($oldVariantNames !== null)
                                @foreach($oldVariantNames as $vIdx => $vName)
                                    <tr class="variant-row">
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="variant_names[]" value="{{ $vName }}" placeholder="VD: Gói 1 tháng..." required>
                                            <input type="hidden" name="variant_ids[]" value="{{ $oldVariantIds[$vIdx] ?? '' }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-end px-2" name="variant_prices[]" value="{{ $oldVariantPrices[$vIdx] ?? '' }}" placeholder="Giá bán" min="0" step="1000" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-end px-2" name="variant_sale_prices[]" value="{{ $oldVariantSalePrices[$vIdx] ?? '' }}" placeholder="Giá gốc" title="Để trống nếu không giảm giá" min="0" step="1000">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-center px-1" name="variant_stocks[]" value="{{ $oldVariantStocks[$vIdx] ?? '10' }}" min="0">
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <input type="number" class="form-control px-1 text-center" style="max-width: 48px;" name="variant_duration_values[]" value="{{ $oldVariantDurValues[$vIdx] ?? '' }}" placeholder="Số" min="1">
                                                <select class="form-select px-1" style="min-width: 82px;" name="variant_duration_types[]">
                                                    <option value="" {{ ($oldVariantDurTypes[$vIdx] ?? '') == '' ? 'selected' : '' }}>Không</option>
                                                    <option value="days" {{ ($oldVariantDurTypes[$vIdx] ?? '') == 'days' ? 'selected' : '' }}>Ngày</option>
                                                    <option value="months" {{ ($oldVariantDurTypes[$vIdx] ?? 'months') == 'months' ? 'selected' : '' }}>Tháng</option>
                                                    <option value="years" {{ ($oldVariantDurTypes[$vIdx] ?? '') == 'years' ? 'selected' : '' }}>Năm</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea class="form-control form-control-sm" name="variant_specs[]" rows="2" placeholder="Nội dung / thông số kỹ thuật riêng của gói này...">{{ $oldVariantSpecs[$vIdx] ?? '' }}</textarea>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm border-0 p-1" onclick="removeVariantRow(this)" title="Xóa gói này">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="addVariantRow()">
                        <i class="fas fa-plus me-1"></i>Thêm gói mới
                    </button>
                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Khi bật gói dịch vụ, khách hàng sẽ chọn gói trực tiếp khi mua.</small>
                </div>
            </div>
        </div>

        <!-- 7. Thông số kỹ thuật (Side-by-side) -->
        @php
            $specType = old('spec_type', 'table');
            $specText = old('spec_text', '');
            $oldSpecKeys = old('spec_keys', ['']);
            $oldSpecValues = old('spec_values', ['']);

            $specTypeEn = old('spec_type_en', 'table');
            $specTextEn = old('spec_text_en', '');
            $oldSpecKeysEn = old('spec_keys_en', ['']);
            $oldSpecValuesEn = old('spec_values_en', ['']);
        @endphp
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-title">
                    <i class="fas fa-cogs text-primary"></i> 7. Thông số kỹ thuật (Song ngữ)
                </div>
                <small class="text-muted">Nhập từng dòng hoặc dạng mô tả tự do</small>
            </div>
            <div class="form-card-body">
                <div class="row g-4">
                    <!-- Vietnamese Specs Column -->
                    <div class="col-md-6 border-end-md">
                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                            <label class="form-label mb-0 fw-bold">
                                <i class="fas fa-flag text-primary me-1"></i>Tiếng Việt
                            </label>
                            <div class="btn-group btn-group-sm" role="group">
                                <input type="radio" class="btn-check" name="spec_type" id="spec_type_table" value="table" autocomplete="off" {{ $specType === 'table' ? 'checked' : '' }} onchange="switchSpecMode('table')">
                                <label class="btn btn-outline-primary btn-sm py-0 px-2" for="spec_type_table" style="font-size: 0.78rem;">
                                    <i class="fas fa-table me-1"></i>Từng dòng
                                </label>

                                <input type="radio" class="btn-check" name="spec_type" id="spec_type_text" value="text" autocomplete="off" {{ $specType === 'text' ? 'checked' : '' }} onchange="switchSpecMode('text')">
                                <label class="btn btn-outline-primary btn-sm py-0 px-2" for="spec_type_text" style="font-size: 0.78rem;">
                                    <i class="fas fa-align-left me-1"></i>Dạng text
                                </label>
                            </div>
                        </div>

                        <!-- Mode Table (Key - Value) -->
                        <div id="specTableContainer" style="{{ $specType === 'text' ? 'display: none;' : '' }}">
                            <div id="specRows">
                                @foreach($oldSpecKeys as $index => $oldSpecKey)
                                    <div class="row g-2 mb-2 spec-row-input">
                                        <div class="col-5">
                                            <input type="text" class="form-control form-control-sm" name="spec_keys[]" value="{{ $oldSpecKey }}" placeholder="Tên thông số">
                                        </div>
                                        <div class="col-5">
                                            <input type="text" class="form-control form-control-sm" name="spec_values[]" value="{{ $oldSpecValues[$index] ?? '' }}" placeholder="Giá trị">
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeSpecRow(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill mt-1" onclick="addSpecRow()">
                                <i class="fas fa-plus me-1"></i>Thêm dòng
                            </button>
                        </div>

                        <!-- Mode Text (Textarea) -->
                        <div id="specTextContainer" style="{{ $specType === 'text' ? '' : 'display: none;' }}">
                            <textarea class="form-control form-control-sm" name="spec_text" id="spec_text" rows="5" placeholder="Nhập thông số dạng mô tả (xuống dòng, gạch đầu dòng)...">{{ $specText }}</textarea>
                            <small class="text-muted d-block mt-1" style="font-size: 0.78rem;">Nhập văn bản tự do, hỗ trợ xuống dòng.</small>
                        </div>
                    </div>

                    <!-- English Specs Column -->
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                            <label class="form-label mb-0 fw-bold text-success">
                                <i class="fas fa-globe me-1"></i>Tiếng Anh
                            </label>
                            <div class="btn-group btn-group-sm" role="group">
                                <input type="radio" class="btn-check" name="spec_type_en" id="spec_type_en_table" value="table" autocomplete="off" {{ $specTypeEn === 'table' ? 'checked' : '' }} onchange="switchSpecEnMode('table')">
                                <label class="btn btn-outline-success btn-sm py-0 px-2" for="spec_type_en_table" style="font-size: 0.78rem;">
                                    <i class="fas fa-table me-1"></i>Key - Value
                                </label>

                                <input type="radio" class="btn-check" name="spec_type_en" id="spec_type_en_text" value="text" autocomplete="off" {{ $specTypeEn === 'text' ? 'checked' : '' }} onchange="switchSpecEnMode('text')">
                                <label class="btn btn-outline-success btn-sm py-0 px-2" for="spec_type_en_text" style="font-size: 0.78rem;">
                                    <i class="fas fa-align-left me-1"></i>Text mode
                                </label>
                            </div>
                        </div>

                        <!-- Mode Table EN -->
                        <div id="specTableContainerEn" style="{{ $specTypeEn === 'text' ? 'display: none;' : '' }}">
                            <div id="specRowsEn">
                                @foreach($oldSpecKeysEn as $index => $oldSpecKeyEn)
                                    <div class="row g-2 mb-2 spec-row-input-en">
                                        <div class="col-5">
                                            <input type="text" class="form-control form-control-sm" name="spec_keys_en[]" value="{{ $oldSpecKeyEn }}" placeholder="Spec name">
                                        </div>
                                        <div class="col-5">
                                            <input type="text" class="form-control form-control-sm" name="spec_values_en[]" value="{{ $oldSpecValuesEn[$index] ?? '' }}" placeholder="Value">
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeSpecRowEn(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-outline-success btn-sm rounded-pill mt-1" onclick="addSpecRowEn()">
                                <i class="fas fa-plus me-1"></i>Thêm dòng (EN)
                            </button>
                        </div>

                        <!-- Mode Text EN -->
                        <div id="specTextContainerEn" style="{{ $specTypeEn === 'text' ? '' : 'display: none;' }}">
                            <textarea class="form-control form-control-sm" name="spec_text_en" id="spec_text_en" rows="5" placeholder="Enter specifications in description/text format...">{{ $specTextEn }}</textarea>
                            <small class="text-muted d-block mt-1" style="font-size: 0.78rem;">Free text format in English.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Action Bar -->
        <div class="d-flex justify-content-between align-items-center my-4 py-3 border-top">
            <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-arrow-left me-1"></i>Quay lại
            </a>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fas fa-times me-1"></i>Hủy
                </a>
                <button type="submit" class="btn btn-submit px-4">
                    <i class="fas fa-save me-1"></i>Lưu sản phẩm
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true });

    const categorySelect = document.getElementById('category_id');
    function syncFileSection() {
        const selected = categorySelect.options[categorySelect.selectedIndex];
        const type = selected ? selected.dataset.type : null;
        const fileSection = document.getElementById('fileUploadSection');
        if (type === 'ebooks') {
            fileSection.style.display = 'block';
        } else {
            fileSection.style.display = 'none';
            document.getElementById('file').value = '';
            document.getElementById('filePreview').style.display = 'none';
        }
    }

    categorySelect.addEventListener('change', syncFileSection);
    syncFileSection();

    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        document.getElementById('image').value = '';
        document.getElementById('imagePreview').style.display = 'none';
        document.getElementById('preview').src = '';
    }

    function previewFile(event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = formatFileSize(file.size);
            document.getElementById('filePreview').style.display = 'block';
        }
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    function addSpecRow() {
        const wrapper = document.getElementById('specRows');
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 spec-row-input';
        row.innerHTML = `
            <div class="col-md-5">
                <input type="text" class="form-control" name="spec_keys[]" placeholder="Tên thông số">
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="spec_values[]" placeholder="Giá trị">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-danger w-100" onclick="removeSpecRow(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        wrapper.appendChild(row);
    }

    function removeSpecRow(button) {
        const rows = document.querySelectorAll('.spec-row-input');
        if (rows.length > 1) {
            button.closest('.spec-row-input').remove();
        } else {
            button.closest('.spec-row-input').querySelectorAll('input').forEach(input => input.value = '');
        }
    }

    function addSpecRowEn() {
        const wrapper = document.getElementById('specRowsEn');
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 spec-row-input-en';
        row.innerHTML = `
            <div class="col-md-5">
                <input type="text" class="form-control" name="spec_keys_en[]" placeholder="Spec name (EN)">
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="spec_values_en[]" placeholder="Value (EN)">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-danger w-100" onclick="removeSpecRowEn(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        wrapper.appendChild(row);
    }

    function switchSpecMode(mode) {
        const tableContainer = document.getElementById('specTableContainer');
        const textContainer = document.getElementById('specTextContainer');
        const textarea = document.getElementById('spec_text');

        if (mode === 'text') {
            tableContainer.style.display = 'none';
            textContainer.style.display = 'block';

            if (!textarea.value.trim()) {
                const rows = document.querySelectorAll('.spec-row-input');
                const lines = [];
                rows.forEach(r => {
                    const k = r.querySelector('input[name="spec_keys[]"]')?.value.trim();
                    const v = r.querySelector('input[name="spec_values[]"]')?.value.trim();
                    if (k || v) {
                        lines.push(k && v ? `${k}: ${v}` : (k || v));
                    }
                });
                if (lines.length > 0) {
                    textarea.value = lines.join('\n');
                }
            }
        } else {
            textContainer.style.display = 'none';
            tableContainer.style.display = 'block';
        }
    }

    function switchSpecEnMode(mode) {
        const tableContainer = document.getElementById('specTableContainerEn');
        const textContainer = document.getElementById('specTextContainerEn');
        const textarea = document.getElementById('spec_text_en');

        if (mode === 'text') {
            tableContainer.style.display = 'none';
            textContainer.style.display = 'block';

            if (!textarea.value.trim()) {
                const rows = document.querySelectorAll('.spec-row-input-en');
                const lines = [];
                rows.forEach(r => {
                    const k = r.querySelector('input[name="spec_keys_en[]"]')?.value.trim();
                    const v = r.querySelector('input[name="spec_values_en[]"]')?.value.trim();
                    if (k || v) {
                        lines.push(k && v ? `${k}: ${v}` : (k || v));
                    }
                });
                if (lines.length > 0) {
                    textarea.value = lines.join('\n');
                }
            }
        } else {
            textContainer.style.display = 'none';
            tableContainer.style.display = 'block';
        }
    }

    function toggleVariantsSection() {
        const isChecked = document.getElementById('has_variants').checked;
        const container = document.getElementById('variantsContainer');
        container.style.display = isChecked ? 'block' : 'none';
        if (isChecked && document.querySelectorAll('.variant-row').length === 0) {
            addVariantRow();
        }
    }

    function addVariantRow(data = {}) {
        const tbody = document.getElementById('variantRows');
        const tr = document.createElement('tr');
        tr.className = 'variant-row';
        tr.innerHTML = `
            <td>
                <input type="text" class="form-control form-control-sm" name="variant_names[]" value="${data.name || ''}" placeholder="VD: Gói 1 tháng, 10M Token..." required>
                <input type="hidden" name="variant_ids[]" value="${data.id || ''}">
            </td>
            <td>
                <input type="number" class="form-control form-control-sm text-end px-2" name="variant_prices[]" value="${data.price || ''}" placeholder="Giá bán" min="0" step="1000" required>
            </td>
            <td>
                <input type="number" class="form-control form-control-sm text-end px-2" name="variant_sale_prices[]" value="${data.sale_price || ''}" placeholder="Giá gốc" title="Để trống nếu không giảm giá" min="0" step="1000">
            </td>
            <td>
                <input type="number" class="form-control form-control-sm text-center px-1" name="variant_stocks[]" value="${data.stock !== undefined ? data.stock : '10'}" min="0">
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <input type="number" class="form-control px-1 text-center" style="max-width: 48px;" name="variant_duration_values[]" value="${data.duration_value || ''}" placeholder="Số" min="1">
                    <select class="form-select px-1" style="min-width: 82px;" name="variant_duration_types[]">
                        <option value="" ${!data.duration_type ? 'selected' : ''}>Không</option>
                        <option value="days" ${data.duration_type === 'days' ? 'selected' : ''}>Ngày</option>
                        <option value="months" ${data.duration_type === 'months' || !data.duration_type ? 'selected' : ''}>Tháng</option>
                        <option value="years" ${data.duration_type === 'years' ? 'selected' : ''}>Năm</option>
                    </select>
                </div>
            </td>
            <td>
                <textarea class="form-control form-control-sm" name="variant_specs[]" rows="2" placeholder="Nội dung / thông số kỹ thuật riêng của gói này...">${data.specs || ''}</textarea>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm border-0 p-1" onclick="removeVariantRow(this)" title="Xóa gói này">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        initResizableVariantTable();
    }

    function removeVariantRow(btn) {
        const row = btn.closest('.variant-row');
        if (row) {
            row.remove();
        }
    }

    function initResizableVariantTable() {
        const table = document.querySelector('.variant-table');
        if (!table) return;
        const ths = table.querySelectorAll('thead th');
        ths.forEach(th => {
            if (th.classList.contains('no-resize') || th.querySelector('.variant-col-resizer')) return;
            th.style.position = 'relative';
            const resizer = document.createElement('div');
            resizer.className = 'variant-col-resizer';
            th.appendChild(resizer);

            let startX, startWidth;

            resizer.addEventListener('mousedown', function (e) {
                e.preventDefault();
                e.stopPropagation();
                startX = e.pageX;
                startWidth = th.offsetWidth;
                resizer.classList.add('is-resizing');

                function onMouseMove(e) {
                    const newWidth = Math.max(65, startWidth + (e.pageX - startX));
                    th.style.width = newWidth + 'px';
                    th.style.minWidth = newWidth + 'px';
                }

                function onMouseUp() {
                    resizer.classList.remove('is-resizing');
                    document.removeEventListener('mousemove', onMouseMove);
                    document.removeEventListener('mouseup', onMouseUp);
                }

                document.addEventListener('mousemove', onMouseMove);
                document.addEventListener('mouseup', onMouseUp);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        initResizableVariantTable();
    });

    function populateVariantsFromList(variantsList) {
        if (!variantsList || !Array.isArray(variantsList) || variantsList.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Không có dữ liệu',
                text: 'Không tìm thấy gói nào từ nội dung nhập vào.'
            });
            return;
        }

        const toggle = document.getElementById('has_variants');
        if (toggle && !toggle.checked) {
            toggle.checked = true;
            toggleVariantsSection();
        }

        const tbody = document.getElementById('variantRows');
        tbody.innerHTML = '';

        variantsList.forEach(item => {
            addVariantRow({
                name: item.name || '',
                price: item.price || '',
                sale_price: item.sale_price || '',
                stock: item.stock !== undefined ? item.stock : 10,
                duration_value: item.duration_value || '',
                duration_type: item.duration_type || 'months'
            });
        });

        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: `Đã tự động tạo ${variantsList.length} gói dịch vụ theo mô tả của Leader.`,
            timer: 2000,
            showConfirmButton: false
        });
    }

    function parseVariantsWithAI() {
        const textInput = document.getElementById('aiVariantsInput');
        const rawText = textInput ? textInput.value.trim() : '';

        if (!rawText) {
            Swal.fire({
                icon: 'warning',
                title: 'Chưa có nội dung',
                text: 'Vui lòng dán danh sách các gói hoặc mô tả của Leader vào ô văn bản!'
            });
            return;
        }

        const btn = document.getElementById('btnRunAiVariants');
        const originalHtml = btn ? btn.innerHTML : '';
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>AI đang phân tích mô tả Leader...';
        }

        const productNameInput = document.getElementById('name');
        const productName = productNameInput ? productNameInput.value : '';

        fetch(@json(route('admin.products.parse-variants-ai')), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                raw_text: rawText,
                product_name: productName
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.variants) {
                populateVariantsFromList(data.variants);
            } else {
                throw new Error(data.message || 'Lỗi khi phân tích dữ liệu AI');
            }
        })
        .catch(err => {
            console.warn('AI Parse failed, falling back to fast regex parser:', err);
            const fastVariants = runFastRegexParser(rawText);
            if (fastVariants.length > 0) {
                populateVariantsFromList(fastVariants);
                Swal.fire({
                    icon: 'info',
                    title: 'Phân tích tự động',
                    text: `Đã phân tích nhanh ${fastVariants.length} gói theo quy tắc văn bản (AI có thông báo: ${err.message})`
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Không thể phân tích',
                    text: err.message || 'Có lỗi xảy ra khi gọi AI phân tích.'
                });
            }
        })
        .finally(() => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        });
    }

    function runFastRegexParser(rawText) {
        const lines = rawText.split('\n').map(l => l.trim()).filter(l => l.length > 0);
        const results = [];

        lines.forEach(line => {
            let price = '';
            let salePrice = '';
            let durationValue = '';
            let durationType = '';
            let stock = 10;

            const originalPriceMatch = line.match(/(?:gốc|cũ|niêm yết|thị trường|km)\s*[:=]?\s*([0-9\.,]+)\s*(k|đ|vnd|triệu|tr)?/i);
            if (originalPriceMatch) {
                let num = parseFloat(originalPriceMatch[1].replace(/\./g, '').replace(/,/g, ''));
                let unit = (originalPriceMatch[2] || '').toLowerCase();
                if (unit === 'k') num *= 1000;
                else if (unit === 'tr' || unit === 'triệu') num *= 1000000;
                salePrice = Math.round(num);
            }

            const priceMatches = [...line.matchAll(/(?:giá|bán|chỉ)?\s*([0-9\.,]+)\s*(k|đ|vnd|triệu|tr)\b/gi)];
            if (priceMatches.length > 0) {
                let bestMatch = priceMatches[0];
                let num = parseFloat(bestMatch[1].replace(/\./g, '').replace(/,/g, ''));
                let unit = (bestMatch[2] || '').toLowerCase();
                if (unit === 'k') num *= 1000;
                else if (unit === 'tr' || unit === 'triệu') num *= 1000000;
                price = Math.round(num);
            } else {
                const plainNum = line.match(/\b([1-9][0-9]{3,7})\b/);
                if (plainNum) {
                    price = parseInt(plainNum[1], 10);
                }
            }

            const durMatch = line.match(/\b([0-9]+)\s*(ngày|tháng|năm|day|days|month|months|year|years)\b/i);
            if (durMatch) {
                durationValue = parseInt(durMatch[1], 10);
                const u = durMatch[2].toLowerCase();
                if (u.includes('ngày') || u.includes('day')) durationType = 'days';
                else if (u.includes('tháng') || u.includes('month')) durationType = 'months';
                else if (u.includes('năm') || u.includes('year')) durationType = 'years';
            }

            const stockMatch = line.match(/(?:kho|sl|stock)\s*[:=]?\s*([0-9]+)/i);
            if (stockMatch) {
                stock = parseInt(stockMatch[1], 10);
            }

            let cleanName = line;
            cleanName = cleanName.replace(/[:=]\s*[0-9\.,]+\s*(?:k|đ|vnd|triệu|tr)?/gi, '');
            cleanName = cleanName.replace(/\([^\)]*(?:gốc|cũ|km)[^\)]*\)/gi, '');
            cleanName = cleanName.replace(/(?:kho|sl|stock)\s*[:=]?\s*[0-9]+/gi, '');
            cleanName = cleanName.replace(/\s{2,}/g, ' ').trim();
            if (cleanName.endsWith('-') || cleanName.endsWith(':')) {
                cleanName = cleanName.slice(0, -1).trim();
            }

            results.push({
                name: cleanName || line,
                price: price || 0,
                sale_price: salePrice || null,
                stock: stock,
                duration_value: durationValue || (durationType === 'months' ? 1 : null),
                duration_type: durationType || 'months'
            });
        });

        return results;
    }

    function parseVariantsFast() {
        const textInput = document.getElementById('aiVariantsInput');
        const rawText = textInput ? textInput.value.trim() : '';

        if (!rawText) {
            Swal.fire({
                icon: 'warning',
                title: 'Chưa có nội dung',
                text: 'Vui lòng dán danh sách các gói hoặc mô tả của Leader vào ô văn bản!'
            });
            return;
        }

        const variants = runFastRegexParser(rawText);
        populateVariantsFromList(variants);
    }
</script>
@endpush

