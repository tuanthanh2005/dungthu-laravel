@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Giá - Admin')

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

    .price-info {
        background: #f0f0f0;
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
        border-left: 4px solid #6c5ce7;
    }

    .price-info-item {
        margin: 0.5rem 0;
        font-size: 0.95rem;
    }

    .price-info-label {
        font-weight: 600;
        color: #2d3436;
        display: inline-block;
        width: 120px;
    }

    .price-info-value {
        color: #636e72;
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

    .form-control:disabled {
        background: #f5f5f5;
        color: #636e72;
        cursor: not-allowed;
    }

    .error-message {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    .form-group.has-error .form-control {
        border-color: #dc3545;
    }

    .help-text {
        font-size: 0.85rem;
        color: #636e72;
        margin-top: 0.25rem;
    }

    .price-input-group {
        display: flex;
        align-items: center;
    }

    .price-input-group .form-control {
        flex: 1;
    }

    .currency {
        margin-left: 0.5rem;
        font-weight: 600;
        color: #636e72;
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

        .button-group {
            flex-direction: column;
        }

        .price-info-label {
            display: block;
            margin-bottom: 0.25rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-admin">
    <div class="form-box">
        <h1>✏️ Chỉnh Sửa Giá</h1>

        <div class="price-info">
            <div class="price-info-item">
                <span class="price-info-label">Dịch Vụ:</span>
                <span class="price-info-value">{{ $buffServerPrice->buffService->name }}</span>
            </div>
            <div class="price-info-item">
                <span class="price-info-label">Server:</span>
                <span class="price-info-value">{{ $buffServerPrice->buffServer->name }}</span>
            </div>
            <div class="price-info-item">
                <span class="price-info-label">Giá Hiện Tại:</span>
                <span class="price-info-value" style="font-weight: 700; color: #00b894;">
                    {{ number_format($buffServerPrice->price) }} đ
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.buff.prices.update', $buffServerPrice) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Dịch Vụ</label>
                <input type="text" class="form-control" value="{{ $buffServerPrice->buffService->name }}" disabled>
                <div class="help-text">Không thể thay đổi - tạo mới nếu cần dịch vụ khác</div>
            </div>

            <div class="form-group">
                <label class="form-label">Server</label>
                <input type="text" class="form-control" value="{{ $buffServerPrice->buffServer->name }}" disabled>
                <div class="help-text">Không thể thay đổi - tạo mới nếu cần server khác</div>
            </div>

            <div class="form-group @error('price') has-error @enderror">
                <label class="form-label">Giá Mới <span style="color: #dc3545;">*</span></label>
                <div class="price-input-group">
                    <input type="number" name="price" class="form-control"
                        step="1" min="0"
                        placeholder="Nhập giá"
                        value="{{ old('price', $buffServerPrice->price) }}" required>
                    <span class="currency">đ</span>
                </div>
                @error('price')
                    <div class="error-message">{{ $message }}</div>
                @enderror
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
                <a href="{{ route('admin.buff.prices.index') }}" class="btn btn-secondary">
                    ← Quay Lại
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
