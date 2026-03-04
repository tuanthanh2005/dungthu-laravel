@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Dịch Vụ - Admin')

@push('styles')
<style>
    .container-admin {
        padding: 2rem;
        max-width: 600px;
        margin: 0 auto;
    }

    .form-box {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 2rem;
    }

    .form-box h1 {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        color: #2d3436;
    }

    .service-badge {
        display: inline-block;
        background: #e8f5e9;
        color: #2e7d32;
        padding: 0.5rem 0.75rem;
        border-radius: 4px;
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #2d3436;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #6c5ce7;
        box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.1);
        outline: none;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }

    .form-check {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .form-check input {
        width: 20px;
        height: 20px;
        margin-right: 10px;
        cursor: pointer;
    }

    .form-check label {
        margin: 0;
        cursor: pointer;
        font-weight: 500;
    }

    .error-message {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    .form-group.has-error .form-control {
        border-color: #dc3545;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .button-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background: #6c5ce7;
        color: white;
        flex: 1;
    }

    .btn-primary:hover {
        background: #5f4ec7;
    }

    .btn-secondary {
        background: #e0e0e0;
        color: #2d3436;
        flex: 1;
    }

    .btn-secondary:hover {
        background: #d0d0d0;
    }

    .btn-danger {
        background: #ff7675;
        color: white;
        flex: 1;
    }

    .btn-danger:hover {
        background: #d63031;
    }

    @media (max-width: 768px) {
        .container-admin {
            padding: 1rem;
        }

        .form-box {
            padding: 1.5rem;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .button-group {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="container-admin">
    <div class="form-box">
        <h1>✏️ Chỉnh Sửa Dịch Vụ</h1>
        
        <span class="service-badge">
            {{ $buffService->platform }} → {{ $buffService->service_type }}
        </span>

        <form method="POST" action="{{ route('admin.buff.services.update', $buffService) }}">
            @csrf
            @method('PUT')

            <div class="form-group @error('name') has-error @enderror">
                <label class="form-label">Tên Dịch Vụ <span style="color: #dc3545;">*</span></label>
                <input type="text" name="name" class="form-control"
                    placeholder="VD: Tăng like Facebook"
                    value="{{ old('name', $buffService->name) }}" required>
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group @error('platform') has-error @enderror">
                    <label class="form-label">Nền Tảng <span style="color: #dc3545;">*</span></label>
                    <select name="platform" class="form-control" required>
                        <option value="facebook" @selected(old('platform', $buffService->platform) === 'facebook')>Facebook</option>
                        <option value="tiktok" @selected(old('platform', $buffService->platform) === 'tiktok')>TikTok</option>
                        <option value="instagram" @selected(old('platform', $buffService->platform) === 'instagram')>Instagram</option>
                    </select>
                    @error('platform')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group @error('service_type') has-error @enderror">
                    <label class="form-label">Loại Dịch Vụ <span style="color: #dc3545;">*</span></label>
                    <select name="service_type" class="form-control" required>
                        <option value="like" @selected(old('service_type', $buffService->service_type) === 'like')>Like</option>
                        <option value="follow" @selected(old('service_type', $buffService->service_type) === 'follow')>Follow</option>
                        <option value="comment" @selected(old('service_type', $buffService->service_type) === 'comment')>Comment</option>
                        <option value="view" @selected(old('service_type', $buffService->service_type) === 'view')>View</option>
                    </select>
                    @error('service_type')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group @error('description') has-error @enderror">
                <label class="form-label">Mô Tả</label>
                <textarea name="description" class="form-control"
                    placeholder="Mô tả chi tiết về dịch vụ...">{{ old('description', $buffService->description) }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group @error('base_price') has-error @enderror">
                    <label class="form-label">Giá Cơ Bản (đ) <span style="color: #dc3545;">*</span></label>
                    <input type="number" name="base_price" class="form-control" 
                        step="1000" min="0"
                        placeholder="0"
                        value="{{ old('base_price', $buffService->base_price) }}" required>
                    @error('base_price')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group @error('price_per_unit') has-error @enderror">
                    <label class="form-label">Giá/Đơn Vị (đ) <span style="color: #dc3545;">*</span></label>
                    <input type="number" name="price_per_unit" class="form-control"
                        step="1" min="0"
                        placeholder="0"
                        value="{{ old('price_per_unit', $buffService->price_per_unit) }}" required>
                    @error('price_per_unit')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group @error('min_amount') has-error @enderror">
                    <label class="form-label">Số Lượng Tối Thiểu <span style="color: #dc3545;">*</span></label>
                    <input type="number" name="min_amount" class="form-control"
                        min="1"
                        placeholder="10"
                        value="{{ old('min_amount', $buffService->min_amount) }}" required>
                    @error('min_amount')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group @error('max_amount') has-error @enderror">
                    <label class="form-label">Số Lượng Tối Đa <span style="color: #dc3545;">*</span></label>
                    <input type="number" name="max_amount" class="form-control"
                        min="1"
                        placeholder="1000000"
                        value="{{ old('max_amount', $buffService->max_amount) }}" required>
                    @error('max_amount')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" id="isActive"
                    @checked(old('is_active', $buffService->is_active))>
                <label for="isActive">
                    Dịch vụ này đang hoạt động
                </label>
            </div>

            <div class="form-group @error('admin_pin') has-error @enderror">
                <label class="form-label">Mã Xác Nhận <span style="color: #dc3545;">*</span></label>
                <input type="text" name="admin_pin" class="form-control"
                    placeholder="Nhập 3 chữ số xác nhận"
                    maxlength="3" pattern="\d{3}" required
                    style="font-size: 1.2rem; letter-spacing: 0.5rem; text-align: center;">
                @error('admin_pin')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">
                    ✓ Cập Nhật
                </button>
                <a href="{{ route('admin.buff.services.index') }}" class="btn btn-secondary">
                    ← Quay Lại
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
