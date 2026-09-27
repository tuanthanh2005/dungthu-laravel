@extends('layouts.admin')

@section('title', 'Thêm Sản phẩm - Admin')

@section('page_title', 'Thêm sản phẩm')

@push('styles')
<style>
    .form-container-wide {
        max-width: 1480px;
        margin: 0 auto;
    }

    .form-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .form-card-header {
        background: #f8fafc;
        border-bottom: 1px solid #eef2f6;
        padding: 14px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .form-card-title {
        font-size: 0.98rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-card-body {
        padding: 20px;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.88rem;
        color: #334155;
        margin-bottom: 6px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 9px 14px;
        font-size: 0.92rem;
        transition: all 0.25s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.12);
    }

    .category-hint {
        font-size: 0.82rem;
        color: #64748b;
        margin-top: 5px;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 11px 28px;
        border-radius: 25px;
        color: white;
        font-weight: 600;
        transition: all 0.25s ease;
        box-shadow: 0 4px 15px rgba(102,126,234,0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102,126,234,0.45);
        color: white;
    }

    .setting-switch-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .setting-switch-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .setting-switch-item:first-child {
        padding-top: 0;
    }

    .sticky-sidebar-card {
        position: sticky;
        top: 80px;
        z-index: 10;
    }

    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="form-container-wide" data-aos="fade-up">

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

        <div class="row g-4">
            <!-- LEFT / MAIN COLUMN -->
            <div class="col-xl-8 col-lg-7">

                <!-- 1. Basic Info Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="form-card-title">
                            <i class="fas fa-cube text-primary"></i> Thông tin sản phẩm
                        </div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1" style="font-size: 0.75rem;">Cơ bản</span>
                    </div>
                    <div class="form-card-body">
                        <!-- Product Names (VI & EN side-by-side) -->
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

                        <!-- Descriptions (VI & EN side-by-side) -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-1 text-primary"></i>Mô tả chi tiết <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4"
                                          placeholder="Nhập mô tả chi tiết sản phẩm..."
                                          required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="description_en" class="form-label text-success">
                                    <i class="fas fa-align-left me-1"></i>Mô tả (Tiếng Anh)
                                </label>
                                <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                          id="description_en" 
                                          name="description_en" 
                                          rows="4"
                                          placeholder="Enter detailed description in English...">{{ old('description_en') }}</textarea>
                                @error('description_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Pricing & Inventory Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="form-card-title">
                            <i class="fas fa-dollar-sign text-primary"></i> Giá bán & Tồn kho
                        </div>
                    </div>
                    <div class="form-card-body">
                        <!-- 4 Columns for Price & Stock -->
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6 col-md-3">
                                <label for="price" class="form-label">
                                    <i class="fas fa-money-bill-wave me-1 text-primary"></i>Giá (VNĐ) <span class="text-danger">*</span>
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
                            <div class="col-sm-6 col-md-3">
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
                            <div class="col-sm-6 col-md-3">
                                <label for="stock" class="form-label">
                                    <i class="fas fa-warehouse me-1 text-primary"></i>Tồn kho <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('stock') is-invalid @enderror" 
                                       id="stock" 
                                       name="stock" 
                                       value="{{ old('stock') }}"
                                       min="0"
                                       placeholder="0"
                                       required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label for="fake_sold" class="form-label text-warning">
                                    <i class="fas fa-shopping-bag me-1"></i>Đã Bán (Ảo)
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

                        <!-- Sale Price Section -->
                        <div class="p-3 rounded-3" style="background: #fff8f8; border: 1.5px dashed #fca5a5;">
                            <div class="d-flex align-items-center mb-2">
                                <div class="form-check form-switch m-0" style="padding-left: 2.5rem;">
                                    <input class="form-check-input cursor-pointer"
                                           type="checkbox"
                                           role="switch"
                                           id="is_on_sale"
                                           name="is_on_sale"
                                           value="1"
                                           {{ old('is_on_sale') ? 'checked' : '' }}
                                           style="width: 44px; height: 22px;">
                                    <label class="form-check-label fw-bold text-danger ms-2 cursor-pointer" for="is_on_sale">
                                        <i class="fas fa-tags me-1"></i>Bật giảm giá (Khuyến mãi)
                                    </label>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="sale_price" class="form-label text-danger small">
                                        Giá giảm (VNĐ)
                                    </label>
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
                                    <small class="text-muted" style="font-size: 0.78rem;">Giá giảm phải nhỏ hơn giá gốc</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="sale_price_usd" class="form-label text-success small">
                                        Giá giảm (USD)
                                    </label>
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

                <!-- 3. Variants Card -->
                @php
                    $oldHasVariants = old('has_variants', false);
                    $oldVariantNames = old('variant_names', []);
                    $oldVariantPrices = old('variant_prices', []);
                    $oldVariantSalePrices = old('variant_sale_prices', []);
                    $oldVariantStocks = old('variant_stocks', []);
                    $oldVariantDurValues = old('variant_duration_values', []);
                    $oldVariantDurTypes = old('variant_duration_types', []);
                @endphp
                <div class="form-card">
                    <div class="form-card-header">
                        <div>
                            <div class="form-card-title">
                                <i class="fas fa-layer-group text-primary"></i> Các loại / Gói dịch vụ (Variants)
                            </div>
                            <small class="text-muted">Bật khi sản phẩm có nhiều gói thời hạn, phiên bản hoặc mức giá khác nhau</small>
                        </div>
                        <div class="form-check form-switch m-0" style="padding-left: 2.5rem;">
                            <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="has_variants" name="has_variants" value="1" {{ $oldHasVariants ? 'checked' : '' }} onchange="toggleVariantsSection()" style="width: 44px; height: 22px;">
                        </div>
                    </div>
                    <div class="form-card-body" id="variantsContainer" style="{{ $oldHasVariants ? '' : 'display: none;' }}">
                        <!-- AI & Quick Text Input for Variants -->
                        <div class="p-3 mb-3 rounded-4" style="background: linear-gradient(135deg, #f0f7ff 0%, #f5f3ff 100%); border: 1.5px dashed #a5b4fc;">
                            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                <label class="fw-bold text-dark d-flex align-items-center gap-2 mb-0" style="font-size: 0.92rem;">
                                    <i class="fas fa-wand-magic-sparkles text-primary"></i> Nhập nhanh nhiều gói bằng AI (Theo mô tả của Leader)
                                </label>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                    <i class="fas fa-robot me-1"></i>Tự động nhận diện mô tả ở cuối dòng
                                </span>
                            </div>
                            <p class="text-muted small mb-2" style="font-size: 0.84rem;">
                                Dán danh sách các gói (mỗi dòng 1 gói) hoặc đoạn chat mô tả của Leader. AI sẽ tự động phân tách, nghe theo mô tả/ghi chú ở cuối các dòng và tạo đủ các ô:
                            </p>
                            <textarea class="form-control mb-2" id="aiVariantsInput" rows="3" 
                                      placeholder="Ví dụ dán vào đây:
Gói 1 tháng: 35k (giá gốc 50k) - 1 profile, cấp sẵn, kho 50
Gói 3 tháng: 90k - bảo hành 90 ngày, kho 30
Gói 12 tháng: 350k - tặng thêm 1 tháng, kho 20"></textarea>
                            <div class="d-flex gap-2 justify-content-between align-items-center flex-wrap">
                                <small class="text-muted" style="font-size: 0.8rem;">
                                    <i class="fas fa-info-circle text-info me-1"></i>Hệ thống sẽ giữ trọn vẹn các yêu cầu/ghi chú ở cuối dòng vào tên gói và thiết lập đúng giá, kho, hạn dùng.
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

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-2">
                                <thead class="table-light">
                                    <tr class="text-center text-nowrap" style="font-size: 0.85rem;">
                                        <th style="min-width: 220px;">Tên gói / Phiên bản <span class="text-danger">*</span></th>
                                        <th style="min-width: 140px;">Giá bán (VNĐ) <span class="text-danger">*</span></th>
                                        <th style="min-width: 140px;">Giá gốc (VNĐ)</th>
                                        <th style="min-width: 90px;">Kho</th>
                                        <th style="min-width: 190px;">Thời hạn bảo hành</th>
                                        <th style="width: 50px;">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody id="variantRows">
                                    @if(!empty($oldVariantNames))
                                        @foreach($oldVariantNames as $vIdx => $vName)
                                            <tr class="variant-row">
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" name="variant_names[]" value="{{ $vName }}" placeholder="VD: Gói 1 tháng, 10M Token..." required>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm text-end" name="variant_prices[]" value="{{ $oldVariantPrices[$vIdx] ?? '' }}" placeholder="Giá bán" min="0" step="1000" required>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm text-end" name="variant_sale_prices[]" value="{{ $oldVariantSalePrices[$vIdx] ?? '' }}" placeholder="Để trống nếu ko giảm" min="0" step="1000">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm text-center" name="variant_stocks[]" value="{{ $oldVariantStocks[$vIdx] ?? '10' }}" min="0">
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" class="form-control" name="variant_duration_values[]" value="{{ $oldVariantDurValues[$vIdx] ?? '' }}" placeholder="Số" min="1">
                                                        <select class="form-select" name="variant_duration_types[]">
                                                            <option value="" {{ ($oldVariantDurTypes[$vIdx] ?? '') == '' ? 'selected' : '' }}>Không</option>
                                                            <option value="days" {{ ($oldVariantDurTypes[$vIdx] ?? '') == 'days' ? 'selected' : '' }}>Ngày</option>
                                                            <option value="months" {{ ($oldVariantDurTypes[$vIdx] ?? '') == 'months' ? 'selected' : '' }}>Tháng</option>
                                                            <option value="years" {{ ($oldVariantDurTypes[$vIdx] ?? '') == 'years' ? 'selected' : '' }}>Năm</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeVariantRow(this)">
                                                        <i class="fas fa-trash"></i>
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

                <!-- 4. Specifications Card (Side-by-side VI & EN) -->
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
                            <i class="fas fa-cogs text-primary"></i> Thông Số Kỹ Thuật (Side-by-side)
                        </div>
                        <small class="text-muted">Nhập thông số song ngữ tiện lợi, tiết kiệm không gian</small>
                    </div>
                    <div class="form-card-body">
                        <div class="row g-4">
                            <!-- Vietnamese Specs Column -->
                            <div class="col-md-6 border-end-md">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                    <label class="form-label mb-0 fw-bold">
                                        <i class="fas fa-flag text-primary me-1"></i>Tiếng Việt
                                    </label>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <input type="radio" class="btn-check" name="spec_type" id="spec_type_table" value="table" autocomplete="off" {{ $specType === 'table' ? 'checked' : '' }} onchange="switchSpecMode('table')">
                                        <label class="btn btn-outline-primary btn-sm" for="spec_type_table" style="font-size: 0.78rem;">
                                            <i class="fas fa-table me-1"></i>Từng dòng
                                        </label>

                                        <input type="radio" class="btn-check" name="spec_type" id="spec_type_text" value="text" autocomplete="off" {{ $specType === 'text' ? 'checked' : '' }} onchange="switchSpecMode('text')">
                                        <label class="btn btn-outline-primary btn-sm" for="spec_type_text" style="font-size: 0.78rem;">
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
                                    <button type="button" class="btn btn-outline-primary btn-sm mt-1" onclick="addSpecRow()">
                                        <i class="fas fa-plus me-1"></i>Thêm dòng
                                    </button>
                                </div>

                                <!-- Mode Text (Textarea) -->
                                <div id="specTextContainer" style="{{ $specType === 'text' ? '' : 'display: none;' }}">
                                    <textarea class="form-control form-control-sm" name="spec_text" id="spec_text" rows="6" placeholder="Nhập thông số dạng mô tả (xuống dòng, gạch đầu dòng)...">{{ $specText }}</textarea>
                                    <div class="form-text text-muted" style="font-size: 0.78rem;">
                                        <i class="fas fa-info-circle me-1"></i>Nhập văn bản tự do, hỗ trợ xuống dòng.
                                    </div>
                                </div>
                            </div>

                            <!-- English Specs Column -->
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                    <label class="form-label mb-0 fw-bold text-success">
                                        <i class="fas fa-globe me-1"></i>English Specs
                                    </label>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <input type="radio" class="btn-check" name="spec_type_en" id="spec_type_en_table" value="table" autocomplete="off" {{ $specTypeEn === 'table' ? 'checked' : '' }} onchange="switchSpecEnMode('table')">
                                        <label class="btn btn-outline-success btn-sm" for="spec_type_en_table" style="font-size: 0.78rem;">
                                            <i class="fas fa-table me-1"></i>Key - Value
                                        </label>

                                        <input type="radio" class="btn-check" name="spec_type_en" id="spec_type_en_text" value="text" autocomplete="off" {{ $specTypeEn === 'text' ? 'checked' : '' }} onchange="switchSpecEnMode('text')">
                                        <label class="btn btn-outline-success btn-sm" for="spec_type_en_text" style="font-size: 0.78rem;">
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
                                    <button type="button" class="btn btn-outline-success btn-sm mt-1" onclick="addSpecRowEn()">
                                        <i class="fas fa-plus me-1"></i>Add row (EN)
                                    </button>
                                </div>

                                <!-- Mode Text EN -->
                                <div id="specTextContainerEn" style="{{ $specTypeEn === 'text' ? '' : 'display: none;' }}">
                                    <textarea class="form-control form-control-sm" name="spec_text_en" id="spec_text_en" rows="6" placeholder="Enter specifications in description/text format...">{{ $specTextEn }}</textarea>
                                    <div class="form-text text-muted" style="font-size: 0.78rem;">
                                        <i class="fas fa-info-circle me-1"></i>Free text format in English.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Quick Submit -->
                <div class="d-flex gap-3 justify-content-end align-items-center mb-5">
                    <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-times me-2"></i>Hủy
                    </a>
                    <button type="submit" class="btn btn-submit px-4">
                        <i class="fas fa-save me-2"></i>Lưu sản phẩm
                    </button>
                </div>

            </div> <!-- /col-xl-8 -->

            <!-- RIGHT / SIDEBAR COLUMN -->
            <div class="col-xl-4 col-lg-5">

                <!-- 1. Actions Card (Sticky) -->
                <div class="form-card sticky-sidebar-card shadow-sm">
                    <div class="form-card-header bg-white">
                        <div class="form-card-title">
                            <i class="fas fa-paper-plane text-primary"></i> Hành động
                        </div>
                    </div>
                    <div class="form-card-body">
                        <button type="submit" class="btn btn-submit w-100 mb-2 py-2">
                            <i class="fas fa-save me-2"></i>Lưu sản phẩm
                        </button>
                        <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary w-100 rounded-pill py-2">
                            <i class="fas fa-times me-1"></i>Hủy & Quay lại
                        </a>
                    </div>
                </div>

                <!-- 2. Category & Delivery Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="form-card-title">
                            <i class="fas fa-folder-open text-primary"></i> Phân loại & Giao hàng
                        </div>
                    </div>
                    <div class="form-card-body">
                        <!-- Category -->
                        <div class="mb-3">
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
                                <div class="category-hint">Quyết định loại sản phẩm (ebooks / tài liệu / tech).</div>
                            @endif
                        </div>

                        <!-- Delivery Type -->
                        <div class="mb-3">
                            <label class="form-label d-block">
                                <i class="fas fa-shipping-fast me-1 text-primary"></i>Loại giao hàng <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex flex-column gap-2">
                                <div class="form-check border rounded-3 p-2 px-3">
                                    <input class="form-check-input" type="radio" name="delivery_type" id="digital" value="digital" {{ old('delivery_type', 'digital') == 'digital' ? 'checked' : '' }} required>
                                    <label class="form-check-label ms-1 cursor-pointer" for="digital">
                                        <i class="fas fa-download text-primary me-1"></i>Sản phẩm số (Digital)
                                    </label>
                                </div>
                                <div class="form-check border rounded-3 p-2 px-3">
                                    <input class="form-check-input" type="radio" name="delivery_type" id="physical" value="physical" {{ old('delivery_type') == 'physical' ? 'checked' : '' }}>
                                    <label class="form-check-label ms-1 cursor-pointer" for="physical">
                                        <i class="fas fa-box text-secondary me-1"></i>Giao hàng vật lý (Physical)
                                    </label>
                                </div>
                            </div>
                            @error('delivery_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Duration -->
                        <div>
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
                            <small class="text-muted d-block mt-1" style="font-size: 0.78rem;">Để trống hoặc chọn "Không giới hạn" nếu dùng vĩnh viễn.</small>
                            @error('duration_value')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('duration_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- 3. Image & Download File Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="form-card-title">
                            <i class="fas fa-image text-primary"></i> Hình ảnh & Tệp tải
                        </div>
                    </div>
                    <div class="form-card-body">
                        <!-- Image Upload -->
                        <div class="mb-3">
                            <label for="image" class="form-label">
                                <i class="fas fa-camera me-1 text-primary"></i>Ảnh sản phẩm
                            </label>
                            <input type="file" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   id="image" 
                                   name="image"
                                   accept="image/*"
                                   onchange="previewImage(event)">
                            <small class="text-muted d-block mt-1" style="font-size: 0.78rem;">JPG, PNG, GIF (tối đa 2MB). Crop về 500x334.</small>
                            @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            
                            <!-- Image Preview -->
                            <div id="imagePreview" class="mt-2" style="display: none;">
                                <div class="position-relative border rounded-3 p-1 bg-light text-center">
                                    <img id="preview" src="" alt="Preview" class="img-fluid rounded" style="max-height: 180px; object-fit: contain;">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle" style="width: 28px; height: 28px; padding: 0;" onclick="removeImage()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- File Upload (ebooks) -->
                        <div id="fileUploadSection" style="display: none;">
                            <label for="file" class="form-label">
                                <i class="fas fa-file-upload me-1 text-primary"></i>File tải về (PDF, DOCX, ZIP)
                            </label>
                            <input type="file" 
                                   class="form-control @error('file') is-invalid @enderror" 
                                   id="file" 
                                   name="file"
                                   accept=".pdf,.doc,.docx,.txt,.zip,.rar"
                                   onchange="previewFile(event)">
                            <small class="text-muted d-block mt-1" style="font-size: 0.78rem;">File tài liệu để khách tải về sau mua (tối đa 50MB).</small>
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

                <!-- 4. Display Settings Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="form-card-title">
                            <i class="fas fa-sliders-h text-primary"></i> Cài đặt hiển thị & Vị trí
                        </div>
                    </div>
                    <div class="form-card-body p-3">
                        <!-- is_active -->
                        <div class="setting-switch-item">
                            <div class="pe-2">
                                <label class="form-check-label fw-bold d-block mb-0 cursor-pointer" for="is_active">
                                    <i class="fas fa-eye text-success me-1"></i> Hiển thị sản phẩm
                                </label>
                                <small class="text-muted" style="font-size: 0.78rem;">Bật để hiện trên web / Tắt để ẩn</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', '1') === '1' ? 'checked' : '' }} style="width: 44px; height: 22px;">
                            </div>
                        </div>

                        <!-- is_flash_sale -->
                        <div class="setting-switch-item">
                            <div class="pe-2">
                                <label class="form-check-label fw-bold d-block mb-0 cursor-pointer" for="is_flash_sale">
                                    <i class="fas fa-bolt text-danger me-1"></i> Ưu tiên Flash Sale
                                </label>
                                <small class="text-muted" style="font-size: 0.78rem;">Đưa lên 4 ô giảm giá trên trang chủ</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="is_flash_sale" name="is_flash_sale" value="1" {{ old('is_flash_sale') ? 'checked' : '' }} style="width: 44px; height: 22px;">
                            </div>
                        </div>

                        <!-- is_vpn -->
                        <div class="setting-switch-item">
                            <div class="pe-2">
                                <label class="form-check-label fw-bold d-block mb-0 cursor-pointer" for="is_vpn">
                                    <i class="fas fa-network-wired text-info me-1"></i> Sản phẩm VPN
                                </label>
                                <small class="text-muted" style="font-size: 0.78rem;">Hiển thị trong trang VPN</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="is_vpn" name="is_vpn" value="1" {{ old('is_vpn') ? 'checked' : '' }} style="width: 44px; height: 22px;">
                            </div>
                        </div>

                        <!-- is_featured -->
                        <div class="setting-switch-item">
                            <div class="pe-2">
                                <label class="form-check-label fw-bold d-block mb-0 cursor-pointer" for="is_featured">
                                    <i class="fas fa-star text-warning me-1"></i> Sản phẩm nổi bật
                                </label>
                                <small class="text-muted" style="font-size: 0.78rem;">Hiển thị ở trang chủ - Hàng 1</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} style="width: 44px; height: 22px;">
                            </div>
                        </div>

                        <!-- is_exclusive -->
                        <div class="setting-switch-item">
                            <div class="pe-2">
                                <label class="form-check-label fw-bold d-block mb-0 cursor-pointer" for="is_exclusive">
                                    <i class="fas fa-gem text-success me-1"></i> Sản phẩm độc quyền
                                </label>
                                <small class="text-muted" style="font-size: 0.78rem;">Hiển thị ở trang chủ - Hàng 2</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="is_exclusive" name="is_exclusive" value="1" {{ old('is_exclusive') ? 'checked' : '' }} style="width: 44px; height: 22px;">
                            </div>
                        </div>

                        <!-- show_on_banner -->
                        <div class="setting-switch-item">
                            <div class="pe-2">
                                <label class="form-check-label fw-bold d-block mb-0 cursor-pointer" for="show_on_banner">
                                    <i class="fas fa-desktop text-primary me-1"></i> Nổi bật Banner Hero
                                </label>
                                <small class="text-muted" style="font-size: 0.78rem;">4 thẻ sản phẩm ở banner chính</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="show_on_banner" name="show_on_banner" value="1" {{ old('show_on_banner') ? 'checked' : '' }} style="width: 44px; height: 22px;">
                            </div>
                        </div>

                        <!-- is_combo_ai -->
                        <div class="setting-switch-item">
                            <div class="pe-2">
                                <label class="form-check-label fw-bold d-block mb-0 cursor-pointer" for="is_combo_ai">
                                    <i class="fas fa-robot text-primary me-1"></i> Combo AI giá rẻ
                                </label>
                                <small class="text-muted" style="font-size: 0.78rem;">Hiển thị ở mục Combo AI trang chủ</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input cursor-pointer" type="checkbox" role="switch" id="is_combo_ai" name="is_combo_ai" value="1" {{ old('is_combo_ai') ? 'checked' : '' }} style="width: 44px; height: 22px;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. Features Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="form-card-title">
                            <i class="fas fa-star text-warning"></i> Tính Năng SP
                        </div>
                    </div>
                    <div class="form-card-body p-3">
                        @if(isset($features) && $features->count() > 0)
                            <div class="d-flex flex-column gap-2" style="max-height: 280px; overflow-y: auto;">
                                @foreach($features as $feature)
                                    <div class="form-check border rounded-3 p-2 px-3 mb-0" style="padding-left: 2.2rem !important;">
                                        <input class="form-check-input" type="checkbox" name="features[]" value="{{ $feature->id }}" id="feature_{{ $feature->id }}" {{ in_array($feature->id, old('features', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label cursor-pointer" for="feature_{{ $feature->id }}">
                                            <i class="{{ $feature->icon }} me-1" style="color: {{ $feature->color }}"></i>
                                            <strong>{{ $feature->name }}</strong>
                                            @if($feature->description)
                                                <small class="text-muted d-block" style="font-size: 0.78rem;">{{ $feature->description }}</small>
                                            @endif
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

            </div> <!-- /col-xl-4 -->
        </div> <!-- /row -->

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
                <input type="number" class="form-control form-control-sm text-end" name="variant_prices[]" value="${data.price || ''}" placeholder="Giá bán" min="0" step="1000" required>
            </td>
            <td>
                <input type="number" class="form-control form-control-sm text-end" name="variant_sale_prices[]" value="${data.sale_price || ''}" placeholder="Để trống nếu ko giảm" min="0" step="1000">
            </td>
            <td>
                <input type="number" class="form-control form-control-sm text-center" name="variant_stocks[]" value="${data.stock !== undefined ? data.stock : '10'}" min="0">
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <input type="number" class="form-control" name="variant_duration_values[]" value="${data.duration_value || ''}" placeholder="Số" min="1">
                    <select class="form-select" name="variant_duration_types[]">
                        <option value="" ${!data.duration_type ? 'selected' : ''}>Không</option>
                        <option value="days" ${data.duration_type === 'days' ? 'selected' : ''}>Ngày</option>
                        <option value="months" ${data.duration_type === 'months' || !data.duration_type ? 'selected' : ''}>Tháng</option>
                        <option value="years" ${data.duration_type === 'years' ? 'selected' : ''}>Năm</option>
                    </select>
                </div>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeVariantRow(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    function removeVariantRow(btn) {
        const rows = document.querySelectorAll('.variant-row');
        if (rows.length > 1) {
            btn.closest('.variant-row').remove();
        } else {
            const tr = btn.closest('.variant-row');
            tr.querySelectorAll('input').forEach(i => i.value = '');
        }
    }

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
