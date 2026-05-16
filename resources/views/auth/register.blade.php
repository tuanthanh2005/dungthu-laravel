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
            padding: 20px;
            font-family: 'Inter', sans-serif;
        }

        .auth-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 820px;
            display: flex;
            overflow: hidden;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-left {
            flex: 1;
            background: #f8faff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
        }

        .avatar-circle {
            width: 180px;
            height: 180px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
            border: 6px solid #f1f4f9;
        }

        .avatar-circle i {
            font-size: 80px;
            color: #dee2e6;
        }

        .auth-right {
            flex: 1.2;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-title {
            font-size: 26px;
            font-weight: 800;
            color: #2d3436;
            margin-bottom: 25px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
            position: relative;
        }

        .form-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-input-wrapper i {
            position: absolute;
            left: 18px;
            color: #adb5bd;
            font-size: 16px;
        }

        .auth-input {
            width: 100%;
            padding: 12px 20px 12px 50px;
            background: #f1f3f5;
            border: 2px solid transparent;
            border-radius: 50px;
            outline: none;
            transition: all 0.3s;
            font-size: 14px;
            font-weight: 500;
            color: #495057;
        }

        .auth-input:focus {
            background: white;
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.12);
        }

        .auth-btn {
            width: 100%;
            padding: 14px;
            background: #57b846;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 17px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 5px;
            box-shadow: 0 8px 16px rgba(87, 184, 70, 0.2);
        }

        .auth-btn:hover {
            background: #4cae4c;
            transform: translateY(-1px);
            box-shadow: 0 12px 20px rgba(87, 184, 70, 0.25);
        }

        .login-text {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #8c98a4;
        }

        .login-text a {
            color: #667eea;
            font-weight: 700;
            text-decoration: none;
        }

        @media (max-width: 991.98px) {
            .auth-card {
                flex-direction: column;
                max-width: 400px;
            }
            .auth-left {
                padding: 30px 20px;
            }
            .avatar-circle {
                width: 140px;
                height: 140px;
            }
            .avatar-circle i {
                font-size: 60px;
            }
            .auth-right {
                padding: 30px 25px;
            }
        }
    </style>
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <!-- Left Side -->
        <div class="auth-left d-none d-md-flex">
            <div class="avatar-circle">
                <i class="fas fa-user-plus"></i>
            </div>
        </div>

        <!-- Right Side -->
        <div class="auth-right">
            <h2 class="auth-title">Đăng Ký</h2>

            @if($errors->any())
                <div class="alert alert-danger border-0 rounded-4 mb-3 py-2 px-3 small shadow-sm" role="alert">
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

                <div class="form-check mb-3 px-4">
                    <input class="form-check-input" type="checkbox" id="terms" required>
                    <label class="form-check-label text-muted" for="terms" style="font-size: 11px;">
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
<div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 p-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0;">
                <h6 class="modal-title fw-bold">Điều Khoản Dịch Vụ</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 small text-muted">
                <p>Chào mừng bạn đến với DungThu.com. Khi đăng ký tài khoản, bạn đồng ý tuân thủ các điều khoản sau:</p>
                <ul class="ps-3">
                    <li>Sử dụng dịch vụ đúng mục đích, không vi phạm pháp luật.</li>
                    <li>Bảo mật thông tin tài khoản cá nhân.</li>
                    <li>Không chia sẻ tài khoản cho người khác.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="privacyRegisterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 p-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0;">
                <h6 class="modal-title fw-bold">Chính Sách Bảo Mật</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 small text-muted">
                <p>Chúng tôi cam kết bảo vệ dữ liệu của bạn:</p>
                <ul class="ps-3">
                    <li>Thông tin cá nhân chỉ được dùng để cung cấp dịch vụ.</li>
                    <li>Không bán hoặc chia sẻ thông tin cho bên thứ ba.</li>
                    <li>Sử dụng các biện pháp bảo mật hiện đại nhất.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection