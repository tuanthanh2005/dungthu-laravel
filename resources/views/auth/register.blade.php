@extends('layouts.app')

@section('title', 'Đăng Ký - DungThu.com')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .auth-container {
            min-height: calc(100vh - 80px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 20px 80px;
        }
        .auth-container > .container {
            display: flex;
            justify-content: center;
        }
        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 960px;
            margin: 0 auto;
        }
        .auth-left {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .auth-right {
            padding: 60px 40px;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(108, 92, 231, 0.25);
        }
        @media (max-width: 991.98px) {
            .auth-container {
                padding: 100px 16px 60px;
                min-height: calc(100vh - 80px);
            }
        }
    </style>
@endpush

@section('content')
<div class="auth-container">
    <div class="container">
        <div class="auth-card" data-aos="zoom-in">
            <div class="row g-0">
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="auth-left h-100">
                        <div>
                            <h2 class="fw-bold mb-3">Tham gia cùng chúng tôi!</h2>
                            <p class="mb-4">Tạo tài khoản để nhận những ưu đãi độc quyền và trải nghiệm mua sắm tuyệt vời.</p>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-gift fs-5 me-3"></i>
                                <span>Voucher 100.000đ cho đơn đầu tiên</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-shipping-fast fs-5 me-3"></i>
                                <span>Miễn phí vận chuyển đơn từ 300k</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-star fs-5 me-3"></i>
                                <span>Tích điểm đổi quà hấp dẫn</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="auth-right">
                        <h3 class="fw-bold mb-4">Đăng Ký Tài Khoản</h3>
                        
                        @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <form action="{{ route('register.post') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Họ và tên</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" 
                                           name="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           placeholder="Nguyễn Văn A"
                                           value="{{ old('name') }}"
                                           required>
                                </div>
                                @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" 
                                           name="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           placeholder="email@example.com"
                                           value="{{ old('email') }}"
                                           required>
                                </div>
                                @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" 
                                           name="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           placeholder="Tối thiểu 6 ký tự"
                                           required>
                                </div>
                                @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Xác nhận mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" 
                                           name="password_confirmation" 
                                           class="form-control" 
                                           placeholder="Nhập lại mật khẩu"
                                           required>
                                </div>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Tôi đồng ý với <a href="javascript:void(0)" class="text-primary" data-bs-toggle="modal" data-bs-target="#termsModal">Điều khoản dịch vụ</a> 
                                    và <a href="javascript:void(0)" class="text-primary" data-bs-toggle="modal" data-bs-target="#privacyRegisterModal">Chính sách bảo mật</a>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow">
                                <i class="fas fa-user-plus me-2"></i> Đăng Ký
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p class="text-muted mb-0">Đã có tài khoản? 
                                <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Đăng nhập ngay</a>
                            </p>
                        </div>
                    </div>
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

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0; border: none; padding: 25px 30px;">
                <h5 class="modal-title fw-bold" id="termsModalLabel">
                    <i class="fas fa-file-contract me-2"></i>Điều Khoản Dịch Vụ
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
            </div>
            <div class="modal-body p-4" style="font-size: 15px; line-height: 1.8; color: #4a5568;">
                <h6 class="fw-bold mb-3" style="color: #667eea;">1. Quyền và Trách Vụ Người Dùng</h6>
                <p>Người dùng đồng ý rằng:</p>
                <ul class="ms-3">
                    <li>Sẽ cung cấp thông tin chính xác khi đăng ký</li>
                    <li>Chịu trách nhiệm bảo mật tài khoản của mình</li>
                    <li>Sẽ không sử dụng dịch vụ cho mục đích bất hợp pháp</li>
                    <li>Sẽ tuân thủ tất cả các quy định hiện hành</li>
                </ul>

                <h6 class="fw-bold mb-3 mt-4" style="color: #667eea;">2. Quyền và Trách Vụ DungThu</h6>
                <p>DungThu.com có quyền:</p>
                <ul class="ms-3">
                    <li>Cung cấp các dịch vụ với chất lượng tốt nhất</li>
                    <li>Thay đổi hoặc cập nhật dịch vụ</li>
                    <li>Vô hiệu hóa tài khoản vi phạm điều khoản</li>
                </ul>

                <h6 class="fw-bold mb-3 mt-4" style="color: #667eea;">3. Giới Hạn Trách Nhiệm</h6>
                <p>DungThu.com không chịu trách nhiệm về:</p>
                <ul class="ms-3">
                    <li>Mất dữ liệu do người dùng không cập nhật backup</li>
                    <li>Thiệt hại do sử dụng không đúng cách</li>
                    <li>Các vấn đề liên quan đến kết nối internet</li>
                </ul>

                <h6 class="fw-bold mb-3 mt-4" style="color: #667eea;">4. Thanh Toán và Hoàn Tiền</h6>
                <ul class="ms-3">
                    <li>Tất cả giá trị được liệt kê đã bao gồm thuế</li>
                    <li>Chính sách hoàn tiền được áp dụng trong 7 ngày</li>
                    <li>Hoàn tiền sẽ được xử lý trong 5-7 ngày làm việc</li>
                </ul>

                <h6 class="fw-bold mb-3 mt-4" style="color: #667eea;">5. Thay Đổi Điều Khoản</h6>
                <p>DungThu.com có quyền thay đổi điều khoản này bất kỳ lúc nào. Người dùng sẽ được thông báo về những thay đổi quan trọng.</p>

                <div class="alert alert-success mt-4" style="border-radius: 12px;">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Cảm ơn</strong> vì đã đồng ý với điều khoản dịch vụ của chúng tôi!
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Privacy Register Modal -->
<div class="modal fade" id="privacyRegisterModal" tabindex="-1" aria-labelledby="privacyRegisterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0; border: none; padding: 25px 30px;">
                <h5 class="modal-title fw-bold" id="privacyRegisterModalLabel">
                    <i class="fas fa-shield-alt me-2"></i>Chính Sách Bảo Mật
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
            </div>
            <div class="modal-body p-4" style="font-size: 15px; line-height: 1.8; color: #4a5568;">
                <h6 class="fw-bold mb-3" style="color: #667eea;">📋 Thông Tin Chúng Tôi Thu Thập</h6>
                <p>Chúng tôi thu thập những thông tin sau:</p>
                <ul class="ms-3">
                    <li>Tên, email, số điện thoại khi bạn đăng ký</li>
                    <li>Địa chỉ giao hàng để xử lý đơn hàng</li>
                    <li>Lịch sử mua hàng và sở thích sản phẩm</li>
                </ul>

                <h6 class="fw-bold mb-3 mt-4" style="color: #667eea;">🔒 Bảo Vệ Thông Tin</h6>
                <p>Chúng tôi sử dụng mã hóa SSL/TLS cho tất cả giao tiếp và không chia sẻ thông tin cá nhân với bên thứ ba.</p>

                <h6 class="fw-bold mb-3 mt-4" style="color: #667eea;">💾 Cách Sử Dụng Thông Tin</h6>
                <ul class="ms-3">
                    <li>Xử lý và giao hàng đơn hàng</li>
                    <li>Gửi thông báo về tình trạng đơn hàng</li>
                    <li>Cải thiện dịch vụ và sản phẩm</li>
                    <li>Tuân thủ pháp luật</li>
                </ul>

                <h6 class="fw-bold mb-3 mt-4" style="color: #667eea;">👥 Quyền Của Bạn</h6>
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