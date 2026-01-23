@extends('layouts.app')

@section('title', 'Sửa Tính Năng Nổi Bật - Admin')

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

    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-secondary {
        border-radius: 10px;
        padding: 12px 30px;
        font-weight: 600;
    }

    .icon-preview {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        margin-top: 10px;
    }

    .icon-suggestions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }

    .icon-suggestion {
        padding: 8px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .icon-suggestion:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .color-suggestions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .color-suggestion {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .color-suggestion:hover {
        transform: scale(1.1);
        border-color: #2d3748;
    }
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="container">
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-edit text-primary me-2"></i>
                    Sửa Tính Năng Nổi Bật
                </h2>
                <a href="{{ route('admin.features') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>

            <form action="{{ route('admin.features.update', $feature) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <!-- Tên tính năng -->
                        <div class="mb-4">
                            <label for="name" class="form-label">Tên tính năng <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $feature->name) }}" 
                                   placeholder="Ví dụ: Hiệu năng cao, Pin lâu dài, Thiết kế sang trọng..." required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mô tả -->
                        <div class="mb-4">
                            <label for="description" class="form-label">Mô tả chi tiết</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Mô tả chi tiết về tính năng này...">{{ old('description', $feature->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Danh mục -->
                        <div class="mb-4">
                            <label for="category" class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" 
                                    id="category" name="category" required>
                                <option value="tech" {{ old('category', $feature->category) == 'tech' ? 'selected' : '' }}>Công nghệ</option>
                                <option value="fashion" {{ old('category', $feature->category) == 'fashion' ? 'selected' : '' }}>Thời trang</option>
                                <option value="doc" {{ old('category', $feature->category) == 'doc' ? 'selected' : '' }}>Văn phòng phẩm</option>
                            </select>
                            @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Icon -->
                        <div class="mb-4">
                            <label for="icon" class="form-label">Icon (FontAwesome)</label>
                            <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                   id="icon" name="icon" value="{{ old('icon', $feature->icon) }}" 
                                   placeholder="fas fa-star">
                            @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <!-- Icon Preview -->
                            <div id="iconPreview" class="icon-preview" style="background: linear-gradient(135deg, {{ $feature->color }}, {{ $feature->color }}dd);">
                                <i class="{{ $feature->icon }}"></i>
                            </div>

                            <!-- Icon Suggestions -->
                            <div class="icon-suggestions">
                                <span class="icon-suggestion" onclick="selectIcon('fas fa-bolt')">
                                    <i class="fas fa-bolt"></i> Bolt
                                </span>
                                <span class="icon-suggestion" onclick="selectIcon('fas fa-rocket')">
                                    <i class="fas fa-rocket"></i> Rocket
                                </span>
                                <span class="icon-suggestion" onclick="selectIcon('fas fa-shield-alt')">
                                    <i class="fas fa-shield-alt"></i> Shield
                                </span>
                                <span class="icon-suggestion" onclick="selectIcon('fas fa-battery-full')">
                                    <i class="fas fa-battery-full"></i> Battery
                                </span>
                                <span class="icon-suggestion" onclick="selectIcon('fas fa-microchip')">
                                    <i class="fas fa-microchip"></i> Chip
                                </span>
                                <span class="icon-suggestion" onclick="selectIcon('fas fa-star')">
                                    <i class="fas fa-star"></i> Star
                                </span>
                            </div>
                        </div>

                        <!-- Màu sắc -->
                        <div class="mb-4">
                            <label for="color" class="form-label">Màu sắc</label>
                            <input type="color" class="form-control @error('color') is-invalid @enderror" 
                                   id="color" name="color" value="{{ old('color', $feature->color) }}">
                            @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <!-- Color Suggestions -->
                            <div class="color-suggestions">
                                <div class="color-suggestion" style="background: linear-gradient(135deg, #667eea, #764ba2);" 
                                     onclick="selectColor('#667eea')"></div>
                                <div class="color-suggestion" style="background: linear-gradient(135deg, #f093fb, #f5576c);" 
                                     onclick="selectColor('#f093fb')"></div>
                                <div class="color-suggestion" style="background: linear-gradient(135deg, #4facfe, #00f2fe);" 
                                     onclick="selectColor('#4facfe')"></div>
                                <div class="color-suggestion" style="background: linear-gradient(135deg, #43e97b, #38f9d7);" 
                                     onclick="selectColor('#43e97b')"></div>
                                <div class="color-suggestion" style="background: linear-gradient(135deg, #fa709a, #fee140);" 
                                     onclick="selectColor('#fa709a')"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn btn-gradient">
                        <i class="fas fa-save me-2"></i>Cập nhật tính năng
                    </button>
                    <a href="{{ route('admin.features') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function selectIcon(iconClass) {
    document.getElementById('icon').value = iconClass;
    updateIconPreview();
}

function selectColor(colorCode) {
    document.getElementById('color').value = colorCode;
    updateIconPreview();
}

function updateIconPreview() {
    const iconClass = document.getElementById('icon').value || 'fas fa-star';
    const color = document.getElementById('color').value || '#667eea';
    
    const preview = document.getElementById('iconPreview');
    preview.style.background = `linear-gradient(135deg, ${color}, ${color}dd)`;
    preview.innerHTML = `<i class="${iconClass}"></i>`;
}

// Update preview on input change
document.getElementById('icon').addEventListener('input', updateIconPreview);
document.getElementById('color').addEventListener('input', updateIconPreview);
</script>
@endpush
@endsection
