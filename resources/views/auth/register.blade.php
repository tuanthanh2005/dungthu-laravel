@extends('layouts.app')

@section('title', __('Đăng Ký') . ' - DungThu.com')

@push('styles')
    <style>
        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 15px;
            font-family: 'Inter', sans-serif;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 680px;
            display: flex;
            overflow: hidden;
            animation: slideUp 0.4s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-left {
            flex: 0.8;
            background: #f8faff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px;
        }

        .avatar-circle {
            width: 140px;
            height: 140px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 15px rgba(0,0,0,0.05);
            border: 5px solid #f1f4f9;
        }

        .avatar-circle i {
            font-size: 60px;
            color: #dee2e6;
        }

        .auth-right {
            flex: 1.2;
            padding: 30px 25px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-title {
            font-size: 22px;
            font-weight: 800;
            color: #2d3436;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 12px;
            position: relative;
        }

        .form-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-input-wrapper i {
            position: absolute;
            left: 15px;
            color: #adb5bd;
            font-size: 14px;
        }

        .auth-input {
            width: 100%;
            padding: 10px 15px 10px 42px;
            background: #f1f3f5;
            border: 2px solid transparent;
            border-radius: 50px;
            outline: none;
            transition: all 0.3s;
            font-size: 13px;
            font-weight: 500;
            color: #495057;
        }

        .auth-input:focus {
            background: white;
            border-color: #667eea;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.1);
        }

        .auth-btn {
            width: 100%;
            padding: 12px;
            background: #57b846;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 3px;
            box-shadow: 0 6px 12px rgba(87, 184, 70, 0.2);
        }

        .auth-btn:hover {
            background: #4cae4c;
            transform: translateY(-1px);
            box-shadow: 0 10px 18px rgba(87, 184, 70, 0.25);
        }

        .login-text {
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
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
                max-width: 380px;
            }
            .auth-left {
                padding: 25px 15px;
            }
            .avatar-circle {
                width: 100px;
                height: 100px;
            }
            .avatar-circle i {
                font-size: 45px;
            }
            .auth-right {
                padding: 25px 20px;
            }
        }
        .grecaptcha-badge {
            visibility: hidden;
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
            <h2 class="auth-title">{{ __('Đăng Ký') }}</h2>

            @if($errors->any())
                <div class="alert alert-danger border-0 rounded-4 mb-2 py-1 px-3 small shadow-sm" role="alert" style="font-size: 11px;">
                    <ul class="mb-0 list-unstyled">
                        @foreach($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle me-1"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" id="registerForm">
                @csrf
                @if(config('services.recaptcha.site_key'))
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                @endif

                <div class="form-group">
                    <div class="form-input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" class="auth-input" placeholder="{{ __('Họ và tên') }}" value="{{ old('name') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="auth-input" placeholder="{{ __('Email của bạn') }}" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="auth-input" placeholder="{{ __('Mật khẩu') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-input-wrapper">
                        <i class="fas fa-shield-alt"></i>
                        <input type="password" name="password_confirmation" class="auth-input" placeholder="{{ __('Xác nhận mật khẩu') }}" required>
                    </div>
                </div>

                <div class="form-check mb-2 px-4">
                    <input class="form-check-input" type="checkbox" id="terms" required style="width: 0.8rem; height: 0.8rem;">
                    <label class="form-check-label text-muted" for="terms" style="font-size: 10px;">
                        {{ __('Tôi đồng ý với') }} <a href="javascript:void(0)" class="text-primary fw-bold" data-bs-toggle="modal" data-bs-target="#termsModal">{{ __('Điều khoản') }}</a> 
                        {{ __('và') }} <a href="javascript:void(0)" class="text-primary fw-bold" data-bs-toggle="modal" data-bs-target="#privacyRegisterModal">{{ __('Bảo mật') }}</a>
                    </label>
                </div>

                <button type="submit" class="auth-btn">
                    {{ __('Tạo Tài Khoản') }}
                </button>
            </form>

            @if(config('services.recaptcha.site_key'))
                <div class="text-center mt-2 text-muted" style="font-size: 9px; line-height: 1.2;">
                    Trang web được bảo vệ bởi reCAPTCHA và <a href="https://policies.google.com/privacy" target="_blank" class="text-muted text-decoration-underline">Chính sách bảo mật</a> & <a href="https://policies.google.com/terms" target="_blank" class="text-muted text-decoration-underline">Điều khoản dịch vụ</a> của Google.
                </div>
            @endif

            <p class="login-text">
                {{ __('Đã có tài khoản?') }} <a href="{{ route('login') }}">{{ __('Đăng nhập ngay') }}</a>
            </p>
        </div>
    </div>
</div>

<!-- Modals -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 p-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0;">
                <h6 class="modal-title fw-bold">{{ __('Điều Khoản Dịch Vụ') }}</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 small text-muted">
                <p>{{ __('Chào mừng bạn đến với DungThu.com. Khi đăng ký tài khoản, bạn đồng ý tuân thủ các điều khoản sau:') }}</p>
                <ul class="ps-3">
                    <li>{{ __('Sử dụng dịch vụ đúng mục đích, không vi phạm pháp luật.') }}</li>
                    <li>{{ __('Bảo mật thông tin tài khoản cá nhân.') }}</li>
                    <li>{{ __('Không chia sẻ tài khoản cho người khác.') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="privacyRegisterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 p-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0;">
                <h6 class="modal-title fw-bold">{{ __('Chính Sách Bảo Mật') }}</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 small text-muted">
                <p>{{ __('Chúng tôi cam kết bảo vệ dữ liệu của bạn:') }}</p>
                <ul class="ps-3">
                    <li>{{ __('Thông tin cá nhân chỉ được dùng để cung cấp dịch vụ.') }}</li>
                    <li>{{ __('Không bán hoặc chia sẻ thông tin cho bên thứ ba.') }}</li>
                    <li>{{ __('Sử dụng các biện pháp bảo mật hiện đại nhất.') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@if(config('services.recaptcha.site_key'))
    @push('scripts')
        <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
        <script>
            document.getElementById('registerForm').addEventListener('submit', function(e) {
                e.preventDefault();
                grecaptcha.ready(function() {
                    grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'register'}).then(function(token) {
                        document.getElementById('g-recaptcha-response').value = token;
                        document.getElementById('registerForm').submit();
                    });
                });
            });
        </script>
    @endpush
@endif
@endsection