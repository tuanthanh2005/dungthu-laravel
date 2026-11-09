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
            padding: 20px 15px;
            font-family: 'Inter', sans-serif;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 720px;
            display: flex;
            overflow: hidden;
            animation: slideUp 0.4s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-left {
            flex: 0.75;
            background: #f8faff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px;
        }

        .avatar-circle {
            width: 130px;
            height: 130px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 15px rgba(0,0,0,0.05);
            border: 5px solid #f1f4f9;
        }

        .avatar-circle i {
            font-size: 55px;
            color: #dee2e6;
        }

        .auth-right {
            flex: 1.25;
            padding: 25px 25px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-title {
            font-size: 22px;
            font-weight: 800;
            color: #2d3436;
            margin-bottom: 15px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 10px;
            position: relative;
        }

        .form-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-input-wrapper > i {
            position: absolute;
            left: 15px;
            color: #adb5bd;
            font-size: 14px;
            pointer-events: none;
            z-index: 2;
        }

        .auth-input {
            width: 100%;
            padding: 9px 15px 9px 42px;
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

        .auth-input.is-invalid {
            border-color: #dc3545;
            background-color: #fff8f8;
        }

        /* Email with OTP button */
        .email-input-wrapper {
            position: relative;
        }

        .email-input-wrapper .auth-input {
            padding-right: 95px;
        }

        .btn-send-otp {
            position: absolute;
            right: 5px;
            top: 5px;
            bottom: 5px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 0 14px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            z-index: 3;
            box-shadow: 0 2px 6px rgba(102, 126, 234, 0.3);
        }

        .btn-send-otp:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.4);
        }

        .btn-send-otp:disabled {
            background: #cbd5e1;
            color: #64748b;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        .otp-notice {
            font-size: 11px;
            margin-top: 3px;
            padding-left: 14px;
            display: none;
        }

        .otp-notice.success {
            display: block;
            color: #16a34a;
            font-weight: 600;
        }

        .otp-notice.error {
            display: block;
            color: #dc2626;
            font-weight: 600;
        }

        /* Captcha Box */
        .captcha-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            padding: 6px 12px;
            margin-bottom: 8px;
        }

        .captcha-img-holder {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .captcha-img-holder img {
            height: 38px;
            border-radius: 6px;
            cursor: pointer;
            display: block;
            border: 1px solid #e2e8f0;
        }

        .btn-refresh-captcha {
            background: white;
            border: 1px solid #cbd5e1;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #667eea;
            transition: all 0.2s;
        }

        .btn-refresh-captcha:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
            transform: rotate(90deg);
        }

        .auth-btn {
            width: 100%;
            padding: 11px;
            background: #57b846;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 5px;
            box-shadow: 0 6px 12px rgba(87, 184, 70, 0.2);
        }

        .auth-btn:hover:not(:disabled) {
            background: #4cae4c;
            transform: translateY(-1px);
            box-shadow: 0 10px 18px rgba(87, 184, 70, 0.25);
        }

        .auth-btn:disabled {
            opacity: 0.6;
            background: #94a3b8;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        .login-text {
            text-align: center;
            margin-top: 12px;
            font-size: 13px;
            color: #8c98a4;
            margin-bottom: 0;
        }

        .login-text a {
            color: #667eea;
            font-weight: 700;
            text-decoration: none;
        }

        @media (max-width: 991.98px) {
            .auth-card {
                flex-direction: column;
                max-width: 420px;
            }
            .auth-left {
                padding: 20px 15px;
            }
            .avatar-circle {
                width: 80px;
                height: 80px;
            }
            .avatar-circle i {
                font-size: 35px;
            }
            .auth-right {
                padding: 20px 18px;
            }
        }
        .grecaptcha-badge {
            left: 15px !important;
            right: auto !important;
            bottom: 15px !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15) !important;
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
                <div class="alert alert-danger border-0 rounded-4 mb-2 py-2 px-3 small shadow-sm" role="alert" style="font-size: 11px;">
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

                <!-- Họ và tên -->
                <div class="form-group">
                    <div class="form-input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" class="auth-input" placeholder="{{ __('Họ và tên') }}" value="{{ old('name') }}" required>
                    </div>
                </div>

                <!-- Email + Nút gửi mã OTP -->
                <div class="form-group">
                    <div class="form-input-wrapper email-input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" id="registerEmail" class="auth-input" placeholder="{{ __('Email của bạn (@gmail.com)') }}" value="{{ old('email') }}" autocomplete="email" required>
                        <button type="button" id="btnSendOtp" class="btn-send-otp" title="{{ __('Bấm để nhận mã xác thực qua Email') }}">
                            <span id="btnSendOtpText"><i class="fas fa-paper-plane me-1"></i>{{ __('Gửi mã') }}</span>
                            <span id="btnSendOtpSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                    <div id="emailNotice" class="otp-notice"></div>
                    <small class="form-text text-muted ps-3 d-block" style="font-size: 10px; margin-top: 2px;">
                        <i class="fas fa-shield-alt text-primary me-1"></i>{{ __('Chỉ chấp nhận tài khoản có đuôi @gmail.com') }}
                    </small>
                </div>

                <!-- Mã xác thực Email (OTP) -->
                <div class="form-group">
                    <div class="form-input-wrapper">
                        <i class="fas fa-key"></i>
                        <input type="text" name="verification_code" id="verificationCode" class="auth-input" placeholder="{{ __('Nhập mã xác thực 6 số từ Gmail') }}" maxlength="6" value="{{ old('verification_code') }}" autocomplete="one-time-code" required>
                    </div>
                    <small class="form-text text-muted ps-3 d-block" style="font-size: 10px; margin-top: 2px;">
                        <i class="fas fa-info-circle text-primary me-1"></i>{{ __('Bấm "Gửi mã" rồi sao chép mã 6 số trong email dán vào đây để mở khóa nút đăng ký') }}
                    </small>
                </div>

                <!-- Mật khẩu -->
                <div class="form-group">
                    <div class="form-input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="auth-input" placeholder="{{ __('Mật khẩu') }}" required>
                    </div>
                </div>

                <!-- Xác nhận mật khẩu -->
                <div class="form-group">
                    <div class="form-input-wrapper">
                        <i class="fas fa-shield-alt"></i>
                        <input type="password" name="password_confirmation" class="auth-input" placeholder="{{ __('Xác nhận mật khẩu') }}" required>
                    </div>
                </div>

                <!-- Captcha chống Hacker & Bot -->
                <div class="form-group">
                    <div class="captcha-row">
                        <div class="captcha-img-holder">
                            <img src="{{ route('captcha.image') }}?t={{ time() }}" id="captchaImage" alt="Captcha" title="{{ __('Bấm vào ảnh để đổi mã khác') }}">
                            <button type="button" id="btnRefreshCaptcha" class="btn-refresh-captcha" title="{{ __('Đổi mã khác') }}">
                                <i class="fas fa-sync-alt" id="refreshIcon"></i>
                            </button>
                        </div>
                        <small class="text-muted" style="font-size: 10px;">
                            <i class="fas fa-shield-virus text-danger me-1"></i>{{ __('Mã bảo vệ Captcha') }}
                        </small>
                    </div>
                    <div class="form-input-wrapper">
                        <i class="fas fa-robot"></i>
                        <input type="text" name="captcha" id="captchaInput" class="auth-input" placeholder="{{ __('Nhập chính xác mã captcha ở trên') }}" maxlength="6" autocomplete="off" required>
                    </div>
                </div>

                <!-- Điều khoản & Bảo mật -->
                <div class="form-check mb-2 px-4">
                    <input class="form-check-input" type="checkbox" id="terms" required style="width: 0.8rem; height: 0.8rem;">
                    <label class="form-check-label text-muted" for="terms" style="font-size: 10px;">
                        {{ __('Tôi đồng ý với') }} <a href="javascript:void(0)" class="text-primary fw-bold" data-bs-toggle="modal" data-bs-target="#termsModal">{{ __('Điều khoản') }}</a> 
                        {{ __('và') }} <a href="javascript:void(0)" class="text-primary fw-bold" data-bs-toggle="modal" data-bs-target="#privacyRegisterModal">{{ __('Bảo mật') }}</a>
                    </label>
                </div>

                <!-- Nút Tạo Tài Khoản -->
                <button type="submit" class="auth-btn" id="btnSubmitRegister" disabled>
                    <i class="fas fa-user-check me-1"></i>{{ __('Tạo Tài Khoản') }}
                </button>
            </form>

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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnSendOtp = document.getElementById('btnSendOtp');
        const btnSendOtpText = document.getElementById('btnSendOtpText');
        const btnSendOtpSpinner = document.getElementById('btnSendOtpSpinner');
        const emailInput = document.getElementById('registerEmail');
        const emailNotice = document.getElementById('emailNotice');
        const verificationCodeInput = document.getElementById('verificationCode');
        const btnSubmitRegister = document.getElementById('btnSubmitRegister');
        const captchaImage = document.getElementById('captchaImage');
        const btnRefreshCaptcha = document.getElementById('btnRefreshCaptcha');
        const refreshIcon = document.getElementById('refreshIcon');

        // Hàm reload captcha
        function reloadCaptcha() {
            if (refreshIcon) refreshIcon.classList.add('fa-spin');
            captchaImage.src = '{{ route("captcha.image") }}?' + new Date().getTime();
            setTimeout(function() {
                if (refreshIcon) refreshIcon.classList.remove('fa-spin');
            }, 500);
        }

        if (captchaImage) {
            captchaImage.addEventListener('click', reloadCaptcha);
            captchaImage.addEventListener('error', function() {
                setTimeout(reloadCaptcha, 500);
            }, { once: true });
        }
        if (btnRefreshCaptcha) {
            btnRefreshCaptcha.addEventListener('click', reloadCaptcha);
        }

        // Bật nút đăng ký khi đã dán mã xác thực 6 số
        function checkSubmitButtonStatus() {
            const codeVal = (verificationCodeInput.value || '').trim();
            if (codeVal.length === 6) {
                btnSubmitRegister.disabled = false;
                btnSubmitRegister.style.opacity = '1';
                btnSubmitRegister.style.cursor = 'pointer';
            } else {
                btnSubmitRegister.disabled = true;
                btnSubmitRegister.style.opacity = '0.6';
                btnSubmitRegister.style.cursor = 'not-allowed';
            }
        }

        verificationCodeInput.addEventListener('input', checkSubmitButtonStatus);
        verificationCodeInput.addEventListener('paste', function() {
            setTimeout(checkSubmitButtonStatus, 50);
        });

        // Kiểm tra ngay lúc tải trang (nếu có old value)
        checkSubmitButtonStatus();

        // Xử lý gửi OTP qua AJAX
        let countdownTimer = null;
        function startCountdown(seconds) {
            clearInterval(countdownTimer);
            btnSendOtp.disabled = true;
            let current = seconds;

            countdownTimer = setInterval(function() {
                current--;
                if (current <= 0) {
                    clearInterval(countdownTimer);
                    btnSendOtp.disabled = false;
                    btnSendOtpText.innerHTML = '<i class="fas fa-paper-plane me-1"></i>{{ __("Gửi lại") }}';
                } else {
                    btnSendOtpText.textContent = '{{ __("Gửi lại") }} (' + current + 's)';
                }
            }, 1000);
        }

        btnSendOtp.addEventListener('click', function() {
            const email = (emailInput.value || '').trim();

            if (!email) {
                emailNotice.className = 'otp-notice error';
                emailNotice.textContent = 'Vui lòng nhập địa chỉ email trước.';
                emailInput.focus();
                return;
            }

            // Kiểm tra định dạng @gmail.com
            const gmailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/i;
            if (!gmailRegex.test(email)) {
                emailNotice.className = 'otp-notice error';
                emailNotice.textContent = 'Hệ thống chỉ chấp nhận tài khoản có đuôi @gmail.com.';
                emailInput.focus();
                return;
            }

            // Hiển thị trạng thái đang gửi
            btnSendOtp.disabled = true;
            btnSendOtpText.classList.add('d-none');
            btnSendOtpSpinner.classList.remove('d-none');
            emailNotice.className = 'otp-notice';
            emailNotice.textContent = '';

            const csrfToken = document.querySelector('meta[name="csrf-token"]') 
                ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
                : '{{ csrf_token() }}';

            fetch('{{ route("auth.send-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ email: email })
            })
            .then(async function(response) {
                const data = await response.json();
                return { status: response.status, data: data };
            })
            .then(function(result) {
                btnSendOtpSpinner.classList.add('d-none');
                btnSendOtpText.classList.remove('d-none');

                if (result.status === 200 && result.data.success) {
                    emailNotice.className = 'otp-notice success';
                    emailNotice.innerHTML = '<i class="fas fa-check-circle me-1"></i>' + result.data.message;
                    startCountdown(60);
                    verificationCodeInput.focus();
                } else {
                    btnSendOtp.disabled = false;
                    emailNotice.className = 'otp-notice error';
                    emailNotice.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>' + (result.data.message || 'Lỗi gửi mã xác thực.');
                }
            })
            .catch(function(err) {
                btnSendOtpSpinner.classList.add('d-none');
                btnSendOtpText.classList.remove('d-none');
                btnSendOtp.disabled = false;
                emailNotice.className = 'otp-notice error';
                emailNotice.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>Không thể kết nối đến máy chủ. Vui lòng thử lại sau.';
            });
        });
    });
</script>
@endpush

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
