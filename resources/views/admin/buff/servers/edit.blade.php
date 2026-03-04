@extends('layouts.admin')

@section('title', 'Sửa Server - Admin')

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
        min-height: 100px;
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

    .warning-box {
        background: #fff3cd;
        border: 1px solid #ffc107;
        color: #856404;
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
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
        <h1>✎ Sửa Server</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.buff.servers.update', $buffServer) }}">
            @csrf
            @method('PUT')

            <div class="form-group @error('name') has-error @enderror">
                <label class="form-label">Tên Server <span style="color: #dc3545;">*</span></label>
                <input type="text" name="name" class="form-control"
                    placeholder="VD: Server 1310, Server 1129, ..."
                    value="{{ old('name', $buffServer->name) }}" required>
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group @error('description') has-error @enderror">
                <label class="form-label">Mô Tả</label>
                <textarea name="description" class="form-control"
                    placeholder="Mô tả chi tiết về server này...">{{ old('description', $buffServer->description) }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" id="isActive"
                    @checked(old('is_active', $buffServer->is_active))>
                <label for="isActive">
                    Server này đang hoạt động
                </label>
            </div>

            <div class="form-check">
                <input type="hidden" name="is_maintenance" value="0">
                <input type="checkbox" name="is_maintenance" value="1" id="isMaintenance"
                    @checked(old('is_maintenance', $buffServer->is_maintenance))>
                <label for="isMaintenance">
                    Server đang bảo trì (vô hiệu hóa cho khách hàng)
                </label>
            </div>

            @if($buffServer->is_maintenance)
                <div class="warning-box">
                    ⚠️ Server này đang bảo trì, khách hàng không thể chọn server này khi tạo đơn mới.
                </div>
            @endif

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
                    ✓ Cập Nhật Server
                </button>
                <a href="{{ route('admin.buff.servers.index') }}" class="btn btn-secondary">
                    ← Quay Lại
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
