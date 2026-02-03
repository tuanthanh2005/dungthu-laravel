@extends('layouts.app')

@section('title', 'Thêm Sản phẩm Công nghệ - Admin')

@push('styles')
<style>
    .admin-wrapper {
        padding: 40px 0;
        background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
        min-height: 100vh;
        margin-top: 70px;
    }

    .admin-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        max-width: 900px;
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
        border-color: #00d4ff;
        box-shadow: 0 0 0 3px rgba(0,212,255,0.1);
    }

    .tech-badge {
        background: linear-gradient(135deg, #00d4ff 0%, #0099cc 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 25px;
        display: inline-block;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .btn-submit {
        background: linear-gradient(135deg, #00d4ff 0%, #0099cc 100%);
        border: none;
        padding: 14px 40px;
        border-radius: 25px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,212,255,0.4);
    }

    .spec-section {
        background: #f8f9fa;
        border-left: 4px solid #00d4ff;
        padding: 20px;
        border-radius: 10px;
        margin: 20px 0;
    }

    .image-preview {
        max-width: 300px;
        border-radius: 15px;
        margin-top: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .feature-checkbox {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px;
        transition: all 0.3s ease;
    }

    .feature-checkbox:hover {
        border-color: #00d4ff;
        background: #f8fcff;
    }

    .feature-checkbox input:checked ~ label {
        color: #00d4ff;
    }

    .feature-icon-small {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="container">
        <div class="admin-card" data-aos="fade-up">
            <div class="mb-4">
                <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary rounded-pill mb-3">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
                <span class="tech-badge">
                    <i class="fas fa-microchip me-2"></i>TECH - Công nghệ
                </span>
                <h3 class="fw-bold mb-0">
                    <i class="fas fa-plus-circle text-info me-3"></i>Thêm Sản phẩm Công nghệ
                </h3>
            </div>

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Product Name -->
                <div class="mb-4">
                    <label for="name" class="form-label">
                        <i class="fas fa-tag me-2 text-primary"></i>Tên sản phẩm <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           placeholder="VD: Laptop Dell XPS 15"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="form-label">
                        <i class="fas fa-align-left me-2 text-primary"></i>Mô tả <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="4"
                              placeholder="Nhập mô tả chi tiết về sản phẩm..."
                              required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="category_id" class="form-label">
                        <i class="fas fa-list me-2 text-primary"></i>Danh mục <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" id="category_id" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($categories->isEmpty())
                        <small class="text-danger d-block mt-2">Chưa có danh mục công nghệ. Vui lòng thêm danh mục trước.</small>
                    @endif
                </div>

                <!-- Thông số kỹ thuật -->
                <div class="spec-section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-cogs me-2 text-info"></i>Thông Số Kỹ Thuật / Thông Tin
                        </h5>
                        <button type="button" class="btn btn-sm btn-info" onclick="addSpecRow()">
                            <i class="fas fa-plus me-1"></i>Thêm trường
                        </button>
                    </div>
                    
                    <div id="specsContainer">
                        <!-- Default row -->
                        <div class="spec-row-item mb-3">
                            <div class="row g-2">
                                <div class="col-md-4">
<input type="text" 
                                           class="form-control" 
                                           name="spec_keys[]" 
                                           placeholder="Tên trường (VD: CPU, Thời hạn...)">
                                </div>
                                <div class="col-md-7">
                                    <input type="text" 
                                           class="form-control" 
                                           name="spec_values[]" 
                                           placeholder="Giá trị (VD: Intel i7, 1 năm...)">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeSpecRow(this)" disabled>
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>Gợi ý thông số phổ biến:</strong>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong>Hardware (Laptop/Phone):</strong>
                                <ul class="mb-0 small">
                                    <li>CPU → Intel Core i7 Gen 12</li>
                                    <li>RAM → 16GB DDR5</li>
                                    <li>Ổ cứng → 512GB NVMe SSD</li>
                                    <li>Màn hình → 15.6" FHD IPS</li>
                                    <li>Bảo hành → 24 tháng</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <strong>Service (Copilot/GPT Plus):</strong>
                                <ul class="mb-0 small">
                                    <li>Thời hạn → 1 năm</li>
                                    <li>Số thiết bị → 5 thiết bị</li>
                                    <li>Dung lượng → Unlimited</li>
                                    <li>Tính năng → AI Assistant</li>
                                    <li>Hỗ trợ → 24/7 Support</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price and Stock -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="price" class="form-label">
                            <i class="fas fa-dollar-sign me-2 text-primary"></i>Giá (VNĐ) <span class="text-danger">*</span>
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
                    <div class="col-md-4">
                        <div class="form-check form-switch mb-2" style="padding-left: 2.5rem;">
                            <input class="form-check-input"
                                   type="checkbox"
                                   role="switch"
                                   id="is_on_sale"
                                   name="is_on_sale"
                                   value="1"
                                   {{ old('is_on_sale') ? 'checked' : '' }}
                                   style="width: 46px; height: 22px; cursor: pointer;">
                            <label class="form-check-label fw-bold" for="is_on_sale" style="margin-left: 8px; cursor: pointer;">
                                <i class="fas fa-tags text-danger me-1"></i>Bat giam gia
                            </label>
                        </div>
                        <label for="sale_price" class="form-label">
                            <i class="fas fa-tags me-2 text-danger"></i>Giá giảm (VNĐ)
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
                        <small class="text-muted">Giá giảm phải nhỏ hơn giá gốc</small>
                    </div>
                    <div class="col-md-4">
                        <label for="stock" class="form-label">
                            <i class="fas fa-warehouse me-2 text-primary"></i>Tồn kho <span class="text-danger">*</span>
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
                </div>

                <div class="mb-4">
                    <div class="form-check form-switch" style="padding-left: 2.5rem;">
                        <input class="form-check-input"
                               type="checkbox"
                               role="switch"
                               id="is_flash_sale"
                               name="is_flash_sale"
                               value="1"
                               {{ old('is_flash_sale') ? 'checked' : '' }}
                               style="width: 50px; height: 25px; cursor: pointer;">
                        <label class="form-check-label fw-bold" for="is_flash_sale" style="margin-left: 10px; cursor: pointer;">
                            <i class="fas fa-bolt text-danger me-2"></i>Uu tien Flash Sale
                            <small class="text-muted d-block">Dua len 4 o giam gia tren trang chu</small>
                        </label>
                    </div>
                </div>
                <!-- Featured Product -->
                <div class="mb-4">
                    <div class="form-check form-switch" style="padding-left: 2.5rem;">
                        <input class="form-check-input" 
                               type="checkbox" 
                               role="switch" 
                               id="is_featured" 
                               name="is_featured" 
                               value="1"
                               {{ old('is_featured') ? 'checked' : '' }}
                               style="width: 50px; height: 25px; cursor: pointer;">
                        <label class="form-check-label fw-bold" for="is_featured" style="margin-left: 10px; cursor: pointer;">
                            <i class="fas fa-star text-warning me-2"></i>Sản phẩm nổi bật
                            <small class="text-muted d-block">Hiển thị trên trang chủ - Hàng đầu tiên</small>
                        </label>
                    </div>
                </div>

                <!-- Exclusive Product -->
                <div class="mb-4">
                    <div class="form-check form-switch" style="padding-left: 2.5rem;">
                        <input class="form-check-input" 
                               type="checkbox" 
                               role="switch" 
                               id="is_exclusive" 
                               name="is_exclusive" 
                               value="1"
                               {{ old('is_exclusive') ? 'checked' : '' }}
                               style="width: 50px; height: 25px; cursor: pointer;">
                        <label class="form-check-label fw-bold" for="is_exclusive" style="margin-left: 10px; cursor: pointer;">
                            <i class="fas fa-gem text-success me-2"></i>Sản phẩm độc quyền
                            <small class="text-muted d-block">Hiển thị trên trang chủ - Hàng thứ 2</small>
                        </label>
                    </div>
                </div>

                <!-- Combo AI Giá Rẻ -->
                <div class="mb-4">
                    <div class="form-check form-switch" style="padding-left: 2.5rem;">
                        <input class="form-check-input" 
                               type="checkbox" 
                               role="switch" 
                               id="is_combo_ai" 
                               name="is_combo_ai" 
                               value="1"
                               {{ old('is_combo_ai') ? 'checked' : '' }}
                               style="width: 50px; height: 25px; cursor: pointer;">
                        <label class="form-check-label fw-bold" for="is_combo_ai" style="margin-left: 10px; cursor: pointer;">
                            <i class="fas fa-robot text-primary me-2"></i>Combo AI giá rẻ
                            <small class="text-muted d-block">Hiển thị ở trang chủ - mục Combo AI giá rẻ</small>
                        </label>
                    </div>
                </div>

                <!-- Delivery Type -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="fas fa-truck me-2 text-primary"></i>Loại giao hàng <span class="text-danger">*</span>
                    </label>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="feature-checkbox">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           name="delivery_type" 
                                           id="delivery_physical" 
                                           value="physical" 
                                           {{ old('delivery_type', 'physical') == 'physical' ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label w-100" for="delivery_physical">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                                <i class="fas fa-box-open text-success fa-2x"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">Sản phẩm vật lý (Ship)</h6>
                                                <small class="text-muted">Cần giao hàng tận nơi</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-checkbox">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           name="delivery_type" 
                                           id="delivery_digital" 
                                           value="digital"
                                           {{ old('delivery_type') == 'digital' ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label w-100" for="delivery_digital">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                                <i class="fas fa-qrcode text-primary fa-2x"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">Sản phẩm số (QR)</h6>
                                                <small class="text-muted">Thanh toán qua QR Code</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Sản phẩm vật lý:</strong> Laptop, điện thoại, phụ kiện... <strong>Sản phẩm số:</strong> License software, tài khoản dịch vụ (Copilot, ChatGPT)...
                    </small>
                </div>

                <!-- Image Upload -->
                <div class="mb-4">
                    <label for="image" class="form-label">
                        <i class="fas fa-image me-2 text-primary"></i>Hình ảnh sản phẩm
                    </label>
                    <input type="file" 
                           class="form-control @error('image') is-invalid @enderror" 
                           id="image" 
                           name="image"
                           accept="image/*"
                           onchange="previewImage(event)">
                    <small class="text-muted">Chọn file ảnh (JPEG, PNG, JPG, GIF - tối đa 2MB). Ảnh sẽ tự động crop về 500x334 pixels.</small>
                    @error('image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    
                    <!-- Image Preview -->
                    <div id="imagePreview" class="mt-3" style="display: none;">
                        <div class="card" style="max-width: 300px;">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">Preview:</small>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeImage()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <img id="preview" src="" alt="Preview" class="img-fluid rounded" style="width: 100%; height: auto;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tính năng nổi bật -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="fas fa-star me-2 text-warning"></i>Tính năng nổi bật
                    </label>
                    <small class="d-block text-muted mb-2">Chọn các tính năng nổi bật cho sản phẩm</small>
                    
                    @if(isset($features) && $features->count() > 0)
                    <div class="row">
                        @foreach($features as $feature)
                        <div class="col-md-6 mb-3">
                            <div class="form-check feature-checkbox">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="features[]" 
                                       value="{{ $feature->id }}" 
                                       id="feature{{ $feature->id }}"
                                       {{ (is_array(old('features')) && in_array($feature->id, old('features'))) ? 'checked' : '' }}>
                                <label class="form-check-label w-100" for="feature{{ $feature->id }}">
                                    <div class="d-flex align-items-center">
                                        <div class="feature-icon-small me-2" style="background: linear-gradient(135deg, {{ $feature->color }}, {{ $feature->color }}dd);">
                                            <i class="{{ $feature->icon }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $feature->name }}</div>
                                            @if($feature->description)
                                            <small class="text-muted">{{ Str::limit($feature->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Chưa có tính năng nào. <a href="{{ route('admin.features.create') }}" target="_blank">Thêm tính năng mới</a>
                    </div>
                    @endif
                </div>

                <!-- Submit Buttons -->
                <div class="d-flex gap-3 justify-content-end mt-5">
                    <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-times me-2"></i>Hủy
                    </a>
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-save me-2"></i>Lưu sản phẩm
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

    function addSpecRow() {
        const container = document.getElementById('specsContainer');
        const newRow = document.createElement('div');
        newRow.className = 'spec-row-item mb-3';
        newRow.innerHTML = `
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" 
                           class="form-control" 
                           name="spec_keys[]" 
                           placeholder="Tên trường (VD: CPU, Thời hạn...)">
                </div>
                <div class="col-md-7">
                    <input type="text" 
                           class="form-control" 
                           name="spec_values[]" 
                           placeholder="Giá trị (VD: Intel i7, 1 năm...)">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeSpecRow(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newRow);
    }

    function removeSpecRow(button) {
        button.closest('.spec-row-item').remove();
    }
</script>
@endpush
