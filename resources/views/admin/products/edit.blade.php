@extends('layouts.app')

@section('title', 'Chỉnh sửa Sản phẩm - Admin')

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

    .category-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .category-option {
        position: relative;
    }

    .category-option input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    .category-option label {
        display: block;
        padding: 20px;
        border: 2px solid #e2e8f0;
        border-radius: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
    }

    .category-option label i {
        font-size: 2rem;
        display: block;
        margin-bottom: 10px;
    }

    .category-option input[type="radio"]:checked + label {
        border-color: #667eea;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(102,126,234,0.3);
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

    .image-preview {
        max-width: 200px;
        border-radius: 15px;
        margin-top: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
                <h3 class="fw-bold mb-0">
                    <i class="fas fa-edit text-primary me-3"></i>Chỉnh sửa Sản phẩm
                </h3>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Error Messages -->
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

            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Product Name -->
                <div class="mb-4">
                    <label for="name" class="form-label">
                        <i class="fas fa-tag me-2 text-primary"></i>Tên sản phẩm <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $product->name) }}"
                           placeholder="Nhập tên sản phẩm..."
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
                              placeholder="Nhập mô tả chi tiết sản phẩm..."
                              required>{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Category -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="fas fa-list me-2 text-primary"></i>Danh mục <span class="text-danger">*</span>
                    </label>
                    <div class="category-options">
                        <div class="category-option">
                            <input type="radio" name="category" value="tech" id="cat-tech" 
                                   {{ old('category', $product->category) === 'tech' ? 'checked' : '' }} required>
                            <label for="cat-tech">
                                <i class="fas fa-laptop" style="color: #00d4ff;"></i>
                                Công nghệ
                            </label>
                        </div>
                        <div class="category-option">
                            <input type="radio" name="category" value="ebooks" id="cat-ebooks" 
                                   {{ old('category', $product->category) === 'ebooks' ? 'checked' : '' }}>
                            <label for="cat-ebooks">
                                <i class="fas fa-file-pdf" style="color: #00bcd4;"></i>
                                Tài liệu kiếm tiền
                            </label>
                        </div>
                    </div>
                    @error('category')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
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
                               value="{{ old('price', $product->price) }}"
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
                                   {{ old('is_on_sale', (bool) $product->sale_price) ? 'checked' : '' }}
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
                               value="{{ old('sale_price', $product->sale_price) }}"
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
                               value="{{ old('stock', $product->stock) }}"
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
                               {{ old('is_flash_sale', $product->is_flash_sale ?? false) ? 'checked' : '' }}
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
                               {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
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
                               {{ old('is_exclusive', $product->is_exclusive ?? false) ? 'checked' : '' }}
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
                               {{ old('is_combo_ai', $product->is_combo_ai ?? false) ? 'checked' : '' }}
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
                        <i class="fas fa-shipping-fast me-2 text-primary"></i>Loại giao hàng <span class="text-danger">*</span>
                    </label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="delivery_type" id="digital" value="digital" {{ old('delivery_type', $product->delivery_type) == 'digital' ? 'checked' : '' }} required>
                            <label class="form-check-label" for="digital">
                                <i class="fas fa-download me-1"></i>Sản phẩm số (Digital)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="delivery_type" id="physical" value="physical" {{ old('delivery_type', $product->delivery_type) == 'physical' ? 'checked' : '' }}>
                            <label class="form-check-label" for="physical">
                                <i class="fas fa-box me-1"></i>Giao hàng vật lý (Physical)
                            </label>
                        </div>
                    </div>
                    @error('delivery_type')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div class="mb-4">
                    <label for="image" class="form-label">
                        <i class="fas fa-image me-2 text-primary"></i>Hình ảnh sản phẩm
                    </label>
                    
                    @if($product->image)
                        <div class="mb-3">
                            <label class="form-label">Hình ảnh hiện tại:</label><br>
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" id="currentImage" class="image-preview">
                        </div>
                    @endif
                    
                    <input type="file" 
                           class="form-control @error('image') is-invalid @enderror" 
                           id="image" 
                           name="image"
                           accept="image/*"
                           onchange="previewImage(event)">
                    <small class="text-muted">Chọn file ảnh mới (JPEG, PNG, JPG, GIF - tối đa 2MB). Ảnh sẽ tự động crop về 500x334 pixels. Để trống nếu không muốn thay đổi.</small>
                    @error('image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    
                    <!-- New Image Preview -->
                    <div id="imagePreview" class="mt-3" style="display: none;">
                        <div class="card" style="max-width: 300px;">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">Ảnh mới (Preview):</small>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeImage()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <img id="preview" src="" alt="Preview" class="img-fluid rounded" style="width: 100%; height: auto;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Upload Section for Ebooks -->
                <div id="fileUploadSection" class="mb-4" style="display: none;">
                    <label class="form-label">
                        <i class="fas fa-file-upload me-2"></i>File tải về
                    </label>
                    
                    @if($product->file_path)
                        <div class="mb-3">
                            <label class="form-label">File hiện tại:</label><br>
                            <div class="alert alert-info">
                                <i class="fas fa-file-{{ $product->file_type }} me-2"></i>
                                <strong>{{ basename($product->file_path) }}</strong>
                                <span class="badge bg-primary ms-2">{{ $product->formatted_file_size }}</span>
                            </div>
                        </div>
                    @endif
                    
                    <input type="file" 
                           class="form-control @error('file') is-invalid @enderror" 
                           id="file" 
                           name="file"
                           accept=".pdf,.doc,.docx,.txt,.zip,.rar"
                           onchange="previewFile(event)">
                    <small class="text-muted">Chọn file PDF, DOC, DOCX, TXT, ZIP, hoặc RAR (tối đa 50MB). Để trống nếu không muốn thay đổi.</small>
                    @error('file')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    
                    <!-- File Preview -->
                    <div id="filePreview" class="mt-3" style="display: none;">
                        <div class="alert alert-success">
                            <strong>File mới:</strong> <span id="fileName"></span>
                            <span class="badge bg-dark ms-2" id="fileSize"></span>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="d-flex gap-3 justify-content-end mt-5">
                    <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-times me-2"></i>Hủy
                    </a>
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-save me-2"></i>Cập nhật sản phẩm
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

    // Show/hide file upload section based on category
    document.addEventListener('DOMContentLoaded', function() {
        const categoryInputs = document.querySelectorAll('input[name="category"]');
        const fileUploadSection = document.getElementById('fileUploadSection');
        
        // Check initial state
        const selectedCategory = document.querySelector('input[name="category"]:checked');
        if (selectedCategory && selectedCategory.value === 'ebooks') {
            fileUploadSection.style.display = 'block';
        }
        
        categoryInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.value === 'ebooks') {
                    fileUploadSection.style.display = 'block';
                } else {
                    fileUploadSection.style.display = 'none';
                }
            });
        });
    });

    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
                // Hide current image when new one is selected
                const currentImg = document.getElementById('currentImage');
                if (currentImg) {
                    currentImg.style.opacity = '0.5';
                }
            }
            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        document.getElementById('image').value = '';
        document.getElementById('imagePreview').style.display = 'none';
        document.getElementById('preview').src = '';
        // Restore current image opacity
        const currentImg = document.getElementById('currentImage');
        if (currentImg) {
            currentImg.style.opacity = '1';
        }
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
        if (bytes < 1024) return bytes + ' bytes';
        else if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        else return (bytes / 1048576).toFixed(1) + ' MB';
    }
</script>
@endpush
