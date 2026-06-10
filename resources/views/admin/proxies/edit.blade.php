@extends('layouts.app')

@section('title', 'Cập nhật Proxy - Admin')

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
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="container">
        <div class="admin-card">
            <div class="mb-4">
                <a href="{{ route('admin.proxies') }}" class="btn btn-outline-secondary rounded-pill mb-3">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
                <h3 class="fw-bold mb-0">
                    <i class="fas fa-edit text-primary me-3"></i>Cập nhật Proxy
                </h3>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.proxies.update', $proxy) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">Tên gói Proxy <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $proxy->name) }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">IP</label>
                        <input type="text" name="ip" class="form-control" value="{{ old('ip', $proxy->ip) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Port</label>
                        <input type="text" name="port" class="form-control" value="{{ old('port', $proxy->port) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" name="username" class="form-control" value="{{ old('username', $proxy->username) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Password</label>
                        <input type="text" name="password" class="form-control" value="{{ old('password', $proxy->password) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Giao thức</label>
                        <input type="text" name="protocol" class="form-control" value="{{ old('protocol', $proxy->protocol) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Quốc gia</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location', $proxy->location) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Thời hạn</label>
                        <input type="text" name="duration" class="form-control" value="{{ old('duration', $proxy->duration) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Giá (VNĐ) <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control" value="{{ old('price', $proxy->price) }}" min="0" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Mô tả thêm</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $proxy->description) }}</textarea>
                </div>

                <div class="mb-4 form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $proxy->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold" for="is_active">Hoạt động (Cho phép hiển thị)</label>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4 rounded-pill">
                        <i class="fas fa-save me-2"></i>Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
