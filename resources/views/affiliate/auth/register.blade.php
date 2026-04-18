@extends('layouts.app')

@section('title', 'Đăng ký Cộng tác viên | DungThu')
@section('meta_description', 'Đăng ký trở thành cộng tác viên DungThu và kiếm thu nhập hấp dẫn.')

@push('styles')
<style>
:root { --aff-primary: #6c63ff; --aff-secondary: #48cfad; }

.aff-register-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    padding: 100px 20px 60px;
    position: relative;
    overflow: hidden;
}

.aff-register-wrapper::before {
    content: '';
    position: absolute;
    width: 700px; height: 700px;
    background: radial-gradient(circle, rgba(108,99,255,0.12) 0%, transparent 70%);
    top: -200px; right: -200px;
    border-radius: 50%;
    pointer-events: none;
}

.reg-card {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 24px;
    padding: 48px 40px;
    max-width: 700px;
    margin: 0 auto;
    box-shadow: 0 25px 50px rgba(0,0,0,0.4);
    position: relative;
    z-index: 2;
}

.reg-header {
    text-align: center;
    margin-bottom: 36px;
}

.reg-header .ctv-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #6c63ff, #48cfad);
    color: white;
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1.5px;
    margin-bottom: 16px;
    text-transform: uppercase;
}

.reg-header h1 {
    color: white;
    font-size: 28px;
    font-weight: 800;
}

.reg-header p {
    color: rgba(255,255,255,0.6);
    font-size: 14px;
}

.section-title {
    color: rgba(255,255,255,0.5);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 16px;
    margin-top: 28px;
    padding-bottom: 8px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-title i { color: #6c63ff; }

.aff-label {
    color: rgba(255,255,255,0.8);
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
}

.aff-required { color: #ff6b6b; }

.aff-input {
    background: rgba(255,255,255,0.07) !important;
    border: 1px solid rgba(255,255,255,0.12) !important;
    color: white !important;
    border-radius: 12px !important;
    padding: 13px 16px !important;
    font-size: 15px !important;
    width: 100%;
    transition: all 0.3s;
    margin-bottom: 18px;
}

.aff-input:focus {
    background: rgba(255,255,255,0.1) !important;
    border-color: #6c63ff !important;
    box-shadow: 0 0 0 3px rgba(108,99,255,0.2) !important;
    outline: none;
}

.aff-input::placeholder { color: rgba(255,255,255,0.3) !important; }
.aff-input.is-invalid { border-color: #ff6b6b !important; }
.invalid-feedback { color: #ff9999; font-size: 12px; margin-top: -14px; margin-bottom: 12px; }

/* CCCD upload area */
.cccd-upload-area {
    background: rgba(255,255,255,0.05);
    border: 2px dashed rgba(108,99,255,0.4);
    border-radius: 16px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    margin-bottom: 18px;
    position: relative;
    overflow: hidden;
}

.cccd-upload-area:hover {
    border-color: #6c63ff;
    background: rgba(108,99,255,0.08);
}

.cccd-upload-area .upload-icon {
    font-size: 32px;
    margin-bottom: 8px;
    background: linear-gradient(135deg, #6c63ff, #48cfad);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.cccd-upload-area .upload-label {
    color: rgba(255,255,255,0.7);
    font-size: 13px;
}

.cccd-upload-area .upload-hint {
    color: rgba(255,255,255,0.35);
    font-size: 11px;
    margin-top: 4px;
}

.cccd-upload-area input[type="file"] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
}

.cccd-preview {
    display: none;
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 8px;
    margin-top: 10px;
}

.benefit-list {
    background: rgba(72,207,173,0.08);
    border: 1px solid rgba(72,207,173,0.2);
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 28px;
}

.benefit-list h6 {
    color: #48cfad;
    font-weight: 700;
    margin-bottom: 12px;
    font-size: 14px;
}

.benefit-item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: rgba(255,255,255,0.75);
    font-size: 13px;
    margin-bottom: 8px;
}

.benefit-item i {
    color: #48cfad;
    width: 16px;
    flex-shrink: 0;
}

.aff-btn-register {
    background: linear-gradient(135deg, #6c63ff, #48cfad);
    border: none;
    color: white;
    font-weight: 700;
    padding: 15px;
    border-radius: 12px;
    font-size: 16px;
    width: 100%;
    cursor: pointer;
    transition: all 0.3s;
    margin-top: 8px;
    letter-spacing: 0.5px;
}

.aff-btn-register:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(108,99,255,0.4);
}

.aff-alert-error {
    background: rgba(255,75,75,0.12);
    border: 1px solid rgba(255,75,75,0.3);
    color: #ff9999;
    border-radius: 12px;
    padding: 14px 18px;
    font-size: 13px;
    margin-bottom: 24px;
}

.aff-link { color: #48cfad; text-decoration: none; font-weight: 600; }
.aff-link:hover { color: #6c63ff; }
</style>
@endpush

@section('content')
<div class="aff-register-wrapper">
    <div class="reg-card" data-aos="fade-up">
        <div class="reg-header">
            <div class="ctv-badge"><i class="fas fa-star"></i>Cộng Tác Viên</div>
            <h1>Đăng ký tham gia</h1>
            <p>Điền đầy đủ thông tin bên dưới. Admin sẽ duyệt hồ sơ trong 24 giờ.</p>
        </div>

        <!-- Benefits -->
        <div class="benefit-list">
            <h6><i class="fas fa-gift me-2"></i>Quyền lợi khi tham gia</h6>
            <div class="benefit-item"><i class="fas fa-check-circle"></i>Hoa hồng <strong>10%</strong> trên mỗi đơn hàng giới thiệu thành công</div>
            <div class="benefit-item"><i class="fas fa-check-circle"></i>Rút tiền linh hoạt qua chuyển khoản ngân hàng</div>
            <div class="benefit-item"><i class="fas fa-check-circle"></i>Dashboard quản lý thu nhập chuyên nghiệp</div>
            <div class="benefit-item"><i class="fas fa-check-circle"></i>Hỗ trợ 24/7 qua Zalo & Telegram</div>
        </div>

        @if($errors->any())
            <div class="aff-alert-error">
                @foreach($errors->all() as $err)
                    <div><i class="fas fa-times-circle me-2"></i>{{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('affiliate.register.post') }}" enctype="multipart/form-data">
            @csrf

            <!-- Thông tin cá nhân -->
            <div class="section-title"><i class="fas fa-user"></i>Thông tin cá nhân</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="aff-label">Họ và tên <span class="aff-required">*</span></label>
                    <input type="text" name="name" class="aff-input @error('name') is-invalid @enderror"
                           placeholder="Nguyễn Văn A" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="aff-label">Số điện thoại <span class="aff-required">*</span></label>
                    <input type="tel" name="phone" class="aff-input @error('phone') is-invalid @enderror"
                           placeholder="0912 345 678" value="{{ old('phone') }}" required>
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="aff-label">Địa chỉ <span class="aff-required">*</span></label>
                    <input type="text" name="address" class="aff-input @error('address') is-invalid @enderror"
                           placeholder="Số nhà, Đường, Phường/Xã, Quận/Huyện, Tỉnh/TP" value="{{ old('address') }}" required>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Tài khoản đăng nhập -->
            <div class="section-title"><i class="fas fa-key"></i>Tài khoản đăng nhập</div>
            <div class="row g-3">
                <div class="col-12">
                    <label class="aff-label">Email <span class="aff-required">*</span></label>
                    <input type="email" name="email" class="aff-input @error('email') is-invalid @enderror"
                           placeholder="email@example.com" value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="aff-label">Mật khẩu <span class="aff-required">*</span></label>
                    <input type="password" name="password" class="aff-input @error('password') is-invalid @enderror"
                           placeholder="Ít nhất 8 ký tự" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="aff-label">Xác nhận mật khẩu <span class="aff-required">*</span></label>
                    <input type="password" name="password_confirmation" class="aff-input"
                           placeholder="Nhập lại mật khẩu" required>
                </div>
            </div>

            <!-- CCCD -->
            <div class="section-title"><i class="fas fa-id-card"></i>Căn cước công dân (CCCD)</div>
            <div class="row g-3">
                <div class="col-12">
                    <label class="aff-label">Số CCCD / CMND <span class="aff-required">*</span></label>
                    <input type="text" name="cccd_number" class="aff-input @error('cccd_number') is-invalid @enderror"
                           placeholder="012345678901" value="{{ old('cccd_number') }}" required>
                    @error('cccd_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="aff-label">Ảnh CCCD mặt trước <span class="aff-required">*</span></label>
                    <div class="cccd-upload-area" id="front-upload-area">
                        <input type="file" name="cccd_front" id="cccd_front" accept="image/*"
                               onchange="previewImage(this, 'front-preview', 'front-upload-area')" required>
                        <div id="front-placeholder">
                            <div class="upload-icon"><i class="fas fa-id-card"></i></div>
                            <div class="upload-label">Click để upload ảnh mặt trước</div>
                            <div class="upload-hint">JPG, PNG tối đa 5MB</div>
                        </div>
                        <img id="front-preview" class="cccd-preview" alt="CCCD mặt trước">
                    </div>
                    @error('cccd_front')<div class="invalid-feedback d-block mt-n3">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="aff-label">Ảnh CCCD mặt sau <span class="aff-required">*</span></label>
                    <div class="cccd-upload-area" id="back-upload-area">
                        <input type="file" name="cccd_back" id="cccd_back" accept="image/*"
                               onchange="previewImage(this, 'back-preview', 'back-upload-area')" required>
                        <div id="back-placeholder">
                            <div class="upload-icon"><i class="fas fa-id-card-alt"></i></div>
                            <div class="upload-label">Click để upload ảnh mặt sau</div>
                            <div class="upload-hint">JPG, PNG tối đa 5MB</div>
                        </div>
                        <img id="back-preview" class="cccd-preview" alt="CCCD mặt sau">
                    </div>
                    @error('cccd_back')<div class="invalid-feedback d-block mt-n3">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Ngân hàng (tùy chọn) -->
            <div class="section-title"><i class="fas fa-university"></i>Thông tin ngân hàng <small style="font-size:10px;color:rgba(255,255,255,0.4);font-weight:normal;">(có thể bổ sung sau)</small></div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="aff-label">Tên ngân hàng</label>
                    <input type="text" name="bank_name" class="aff-input"
                           placeholder="Vietcombank, MB, TP..." value="{{ old('bank_name') }}">
                </div>
                <div class="col-md-4">
                    <label class="aff-label">Số tài khoản</label>
                    <input type="text" name="bank_account_number" class="aff-input"
                           placeholder="9012345678901" value="{{ old('bank_account_number') }}">
                </div>
                <div class="col-md-4">
                    <label class="aff-label">Tên chủ tài khoản</label>
                    <input type="text" name="bank_account_name" class="aff-input"
                           placeholder="NGUYEN VAN A" value="{{ old('bank_account_name') }}"
                           style="text-transform:uppercase;">
                </div>
            </div>

            <button type="submit" class="aff-btn-register" id="submitBtn">
                <i class="fas fa-paper-plane me-2"></i>Gửi đăng ký — Chờ duyệt trong 24h
            </button>
        </form>

        <div class="text-center mt-4" style="color:rgba(255,255,255,0.6);font-size:14px;">
            Đã có tài khoản?
            <a href="{{ route('affiliate.login') }}" class="aff-link ms-1">Đăng nhập</a>
        </div>
        <div class="text-center mt-2">
            <a href="{{ route('home') }}" class="aff-link" style="font-size:13px;opacity:0.7;">
                <i class="fas fa-arrow-left me-1"></i>Về trang chủ
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(input, previewId, areaId) {
    const preview = document.getElementById(previewId);
    const area    = document.getElementById(areaId);
    const placeholder = area.querySelector('[id$="-placeholder"]');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

document.getElementById('submitBtn').addEventListener('click', function() {
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang gửi...';
    this.disabled = true;
    setTimeout(() => { this.form.submit(); }, 100);
});
</script>
@endpush
