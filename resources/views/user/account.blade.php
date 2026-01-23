@extends('layouts.app')

@section('title', 'Tài khoản của tôi')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<style>
    .account-wrapper {
        padding: 100px 0 60px;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .account-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
    }

    .nav-tabs {
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 30px;
    }

    .nav-tabs .nav-link {
        border: none;
        color: #718096;
        font-weight: 600;
        padding: 15px 25px;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link:hover {
        color: #667eea;
    }

    .nav-tabs .nav-link.active {
        color: #667eea;
        border-bottom: 3px solid #667eea;
        background: transparent;
    }

    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
    }

    .form-control {
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        padding: 12px 16px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
    }

    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px 40px;
        border-radius: 25px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(102,126,234,0.4);
    }

    .info-box {
        background: #f7fafc;
        border-left: 4px solid #667eea;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="account-wrapper">
    <div class="container">
        <div class="account-card" data-aos="fade-up">
            <h3 class="fw-bold mb-4">
                <i class="fas fa-user-circle text-primary me-3"></i>Tài khoản của tôi
            </h3>

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

            <!-- Tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#profile">
                        <i class="fas fa-user me-2"></i>Thông tin cá nhân
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#password">
                        <i class="fas fa-lock me-2"></i>Đổi mật khẩu
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Profile Tab -->
                <div class="tab-pane fade show active" id="profile">
                    <form action="{{ route('user.account.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user text-primary me-2"></i>Họ và tên <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope text-primary me-2"></i>Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone text-primary me-2"></i>Số điện thoại
                                </label>
                                <input type="text" class="form-control" id="phone" name="phone" 
                                       value="{{ old('phone', $user->phone) }}">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="address" class="form-label">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>Địa chỉ
                                </label>
                                <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-save">
                                <i class="fas fa-save me-2"></i>Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Tab -->
                <div class="tab-pane fade" id="password">
                    <div class="info-box">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Mật khẩu phải có ít nhất 8 ký tự để đảm bảo bảo mật
                    </div>

                    <form action="{{ route('user.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="current_password" class="form-label">
                                    <i class="fas fa-key text-primary me-2"></i>Mật khẩu hiện tại <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control" id="current_password" 
                                       name="current_password" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock text-primary me-2"></i>Mật khẩu mới <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control" id="password" 
                                       name="password" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock text-primary me-2"></i>Xác nhận mật khẩu mới <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control" id="password_confirmation" 
                                       name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-save">
                                <i class="fas fa-key me-2"></i>Đổi mật khẩu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true });
</script>
@endpush
