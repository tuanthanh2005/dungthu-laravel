@extends('layouts.app')

@section('title', 'Đăng Ký - DungThu.com')

@push('styles')
    <style>
        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            font-family: 'Inter', sans-serif;
        }

        .auth-card {
            background: white;
            border-radius: 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 950px;
            display: flex;
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-left {
            flex: 1;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .avatar-circle {
            width: 250px;
            height: 250px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border: 8px solid #f1f3f5;
        }

        .avatar-circle i {
            font-size: 120px;
            color: #dee2e6;
        }

        .auth-right {
            flex: 1.2;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-title {
            font-size: 32px;
            font-weight: 700;
            color: #2d3436;
            margin-bottom: 40px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-input-wrapper i {
            position: absolute;
            left: 20px;
            color: #adb5bd;
            font-size: 18px;
        }

        .auth-input {
            width: 100%;
            padding: 14px 20px 14px 55px;
            background: #f1f3f5;
            border: 2px solid transparent;
            border-radius: 50px;
            outline: none;
            transition: all 0.3s;
            font-size: 15px;
            font-weight: 500;
            color: #495057;
        }

        .auth-input:focus {
            background: white;
            border-color: #667eea;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
        }

        .auth-btn {
            width: 100%;
            padding: 16px;
            background: #57b846;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            box-shadow: 0 10px 20px rgba(87, 184, 70, 0.2);
        }

        .auth-btn:hover {
            background: #4cae4c;
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(87, 184, 70, 0.3);
        }

        .login-text {
            text-align: center;
            margin-top: 30px;
            font-size: 15px;
            color: #6c757d;
        }

        .login-text a {
            color: #667eea;
            font-weight: 700;
            text-decoration: none;
        }

        @media (max-width: 991.98px) {
            .auth-card {
                flex-direction: column;
                max-width: 450px;
            }
            .auth-left {
                padding: 40px 20px;
            }
            .avatar-circle {
                width: 150px;
                height: 150px;
            }
            .avatar-circle i {
                font-size: 70px;
            }
            .auth-right {
                padding: 40px 30px;
            }
        }
    </style>
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <!-- Left Side: Icon -->
        <div class="auth-left d-none d-md-flex">
            <div class="avatar-circle">
                <i class="fas fa-user-plus"></i>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="auth-right">
            <h2 class="auth-title">Đăng Ký</h2>

            @if($errors->any())
                <div class="alert alert-danger border-0 rounded-4 mb-4 shadow-sm" role="alert">
                    <ul class="mb-0 list-unstyled">
                        @foreach($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST">
                @csrf

                <div class="form-group">
                    <div class="form-input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" class="auth-input" placeholder="Họ và tên" value="{{ old('name') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="auth-input" placeholder="Email của bạn" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="auth-input" placeholder="Mật khẩu" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-input-wrapper">
                        <i class="fas fa-shield-alt"></i>
                        <input type="password" name="password_confirmation" class="auth-input" placeholder="Xác nhận mật khẩu" required>
                    </div>
                </div>

                <div class="form-check mb-4 px-4">
                    <input class="form-check-input" type="checkbox" id="terms" required>
                    <label class="form-check-label text-muted small" for="terms">
                        Tôi đồng ý với <a href="javascript:void(0)" class="text-primary fw-bold" data-bs-toggle="modal" data-bs-target="#termsModal">Điều khoản</a> 
                        và <a href="javascript:void(0)" class="text-primary fw-bold" data-bs-toggle="modal" data-bs-target="#privacyRegisterModal">Bảo mật</a>
                    </label>
                </div>

                <button type="submit" class="auth-btn">
                    Tạo Tài Khoản
                </button>
            </form>

            <p class="login-text">
                Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập ngay</a>
            </p>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0" style="border-radius: 25px;">
            <div class="modal-header border-0 p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 25px 25px 0 0;">
                <h5 class="modal-title fw-bold">Điều Khoản Dịch Vụ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <h6>1. Quy định chung</h6>
                <p>Khi sử dụng dịch vụ của chúng tôi, bạn đồng ý với các quy định này...</p>
                <!-- Thêm nội dung rút gọn hoặc giữ nguyên -->
            </div>
        </div>
    </div>
</div>

<!-- Privacy Modal -->
<div class="modal fade" id="privacyRegisterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0" style="border-radius: 25px;">
            <div class="modal-header border-0 p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 25px 25px 0 0;">
                <h5 class="modal-title fw-bold">Chính Sách Bảo Mật</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p>Chúng tôi cam kết bảo mật thông tin cá nhân của bạn...</p>
            </div>
        </div>
    </div>
</div>
@endsection

                <ul class="ms-3">
                    <li>Truy cập và xem thông tin cá nhân</li>
                    <li>Chỉnh sửa hoặc cập nhật thông tin</li>
                    <li>Yêu cầu xóa tài khoản</li>
                </ul>

                <div class="alert alert-info mt-4" style="border-radius: 12px;">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Cần hỗ trợ?</strong> Liên hệ email: tranthanhtuanfix@gmail.com
                </div>
            </div>
        </div>
    </div>
</div>