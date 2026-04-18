@extends('layouts.app')

@section('title', 'Cài đặt tài khoản | DungThu')

@push('styles')
<style>
    .aff-wrapper { background-color: #f8fafc; min-height: 100vh; padding-top: 100px; padding-bottom: 50px; }
    .aff-sidebar { background: white; border-radius: 20px; padding: 25px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); height: fit-content; }
    .aff-nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 20px; color: #64748b; text-decoration: none !important; border-radius: 12px; font-weight: 500; transition: all 0.3s; margin-bottom: 8px; }
    .aff-nav-link:hover, .aff-nav-link.active { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
    .content-card { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); margin-bottom: 30px; }
    .section-title { font-size: 18px; font-weight: 700; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; }
    .form-label { font-weight: 600; font-size: 14px; margin-bottom: 8px; color: #1e293b; }
    .form-control { border-radius: 12px; padding: 10px 16px; border: 1px solid #e2e8f0; font-size: 14px; }
    .form-control:focus { box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); border-color: #6366f1; }
</style>
@endpush

@section('content')
<div class="aff-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="aff-sidebar">
                    <div class="text-center mb-4">
                        <div style="width: 80px; height: 80px; background: #e2e8f0; border-radius: 20px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 32px; color: #64748b;">
                            {{ strtoupper(substr($affiliate->name, 0, 1)) }}
                        </div>
                        <h6 class="mb-1 fw-bold">{{ $affiliate->name }}</h6>
                        <small class="text-muted">{{ $affiliate->email }}</small>
                    </div>
                    
                    <nav>
                        <a href="{{ route('affiliate.dashboard') }}" class="aff-nav-link">
                            <i class="fas fa-th-large"></i> Thống kê
                        </a>
                        <a href="{{ route('affiliate.invoices') }}" class="aff-nav-link">
                            <i class="fas fa-file-invoice-dollar"></i> Gửi hóa đơn
                        </a>
                        <a href="{{ route('affiliate.withdrawals') }}" class="aff-nav-link">
                            <i class="fas fa-wallet"></i> Rút tiền
                        </a>
                        <a href="{{ route('affiliate.account') }}" class="aff-nav-link active">
                            <i class="fas fa-user-cog"></i> Tài khoản
                        </a>
                        <hr class="my-3">
                        <form action="{{ route('affiliate.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="aff-nav-link text-danger border-0 bg-transparent w-100 text-start">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <div class="col-lg-9">
                <!-- Profile Settings -->
                <div class="content-card">
                    <div class="section-title">
                        <i class="fas fa-user-edit text-primary"></i> Thông tin cá nhân
                    </div>
                    
                    <form action="{{ route('affiliate.account.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ tên</label>
                                <input type="text" name="name" class="form-control" value="{{ $affiliate->name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" name="phone" class="form-control" value="{{ $affiliate->phone }}" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Địa chỉ</label>
                                <input type="text" name="address" class="form-control" value="{{ $affiliate->address }}" required>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tên ngân hàng</label>
                                <input type="text" name="bank_name" class="form-control" value="{{ $affiliate->bank_name }}" placeholder="Vietcombank...">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Số tài khoản</label>
                                <input type="text" name="bank_account_number" class="form-control" value="{{ $affiliate->bank_account_number }}" placeholder="0123...">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Chủ tài khoản</label>
                                <input type="text" name="bank_account_name" class="form-control" value="{{ $affiliate->bank_account_name }}" style="text-transform: uppercase;">
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary" style="border-radius: 10px; padding: 10px 25px;">
                                Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Settings -->
                <div class="content-card">
                    <div class="section-title">
                        <i class="fas fa-lock text-warning"></i> Đổi mật khẩu
                    </div>
                    
                    <form action="{{ route('affiliate.account.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Mật khẩu hiện tại</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Mật khẩu mới</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Xác nhận mật khẩu</label>
                                <input type="password" name="new_password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning fw-bold text-white" style="border-radius: 10px; padding: 10px 25px;">
                                Cập nhật mật khẩu
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ID Card Info (Read-only) -->
                <div class="content-card border-start border-4 border-info">
                    <div class="section-title">
                        <i class="fas fa-id-card text-info"></i> Thông tin định danh (CCCD)
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="small text-muted mb-1">Số CCCD</div>
                            <div class="fw-bold">{{ $affiliate->cccd_number }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="small text-muted mb-1">Trạng thái tài khoản</div>
                            <span class="badge bg-success">Đã xác minh (Approved)</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="small text-muted mb-2">Mặt trước CCCD</div>
                            <img src="{{ asset($affiliate->cccd_front) }}" class="img-fluid rounded" style="max-height: 150px; border: 1px solid #e2e8f0;">
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted mb-2">Mặt sau CCCD</div>
                            <img src="{{ asset($affiliate->cccd_back) }}" class="img-fluid rounded" style="max-height: 150px; border: 1px solid #e2e8f0;">
                        </div>
                    </div>
                    <div class="mt-3 p-3 bg-light rounded small text-muted">
                        <i class="fas fa-info-circle"></i> Thông tin định danh không thể tự thay đổi. Vui lòng liên hệ Admin nếu có sai sót.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
