@extends('layouts.admin')

@section('title', 'Thêm Giá Mới - Admin')

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

    .info-box {
        background: #e3f2fd;
        border-left: 4px solid #2196f3;
        padding: 1rem;
        border-radius: 4px;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
        color: #1565c0;
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
    }
</style>
@endpush

@section('content')
<div class="container-admin">
    <div class="form-box">
        <h1>💰 Thêm Giá Mới</h1>

        @if ($errors->any())
            <div style="background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 6px; padding: 1rem; margin-bottom: 1.5rem; color: #721c24;">
                <strong>Có lỗi xảy ra:</strong>
                <ul style="margin: 0.5rem 0 0 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="info-box">
            ℹ️ Gán giá tùy chỉnh cho một dịch vụ và server cụ thể
        </div>

        <form method="POST" action="{{ route('admin.buff.prices.store') }}">
            @csrf

            <div class="form-group @error('buff_service_id') has-error @enderror">
                <label class="form-label">Dịch Vụ <span style="color: #dc3545;">*</span></label>
                <select name="buff_service_id" class="form-control" required>
                    <option value="">-- Chọn dịch vụ --</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" @selected(old('buff_service_id') == $service->id)>
                            {{ $service->name }} ({{ $service->platform }})
                        </option>
                    @endforeach
                </select>
                @error('buff_service_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                <div class="help-text">Chọn dịch vụ cần gán giá</div>
            </div>

            <div class="form-group @error('buff_server_id') has-error @enderror">
                <label class="form-label">Server <span style="color: #dc3545;">*</span></label>
                <select name="buff_server_id" class="form-control" required>
                    <option value="">-- Chọn server --</option>
                    @foreach($servers as $server)
                        <option value="{{ $server->id }}" @selected(old('buff_server_id') == $server->id)>
                            {{ $server->name }}
                            @if($server->is_maintenance)
                                (🔧 Bảo trì)
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('buff_server_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                <div class="help-text">Chọn server để gán giá</div>
            </div>

            <div class="form-group @error('price') has-error @enderror">
                <label class="form-label">Giá <span style="color: #dc3545;">*</span></label>
                <div class="price-input-group">
                    <input type="number" name="price" class="form-control"
                        step="1" min="0"
                        placeholder="Nhập giá"
                        value="{{ old('price') }}" required>
                    <span class="currency">đ</span>
                </div>
                @error('price')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                <div class="help-text">Nhập giá tùy chỉnh cho kết hợp này</div>
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
                    ✓ Thêm Giá
                </button>
                <a href="{{ route('admin.buff.prices.index') }}" class="btn btn-secondary">
                    ← Quay Lại
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
