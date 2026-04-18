@extends('layouts.app')

@section('title', 'Chi tiết Cộng tác viên | Admin')

@push('styles')
<style>
    .admin-wrapper { padding: 40px 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; margin-top: 70px; }
    .admin-card { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); margin-bottom: 30px; }
    .cccd-img { border-radius: 12px; border: 1px solid #e2e8f0; width: 100%; transition: transform 0.3s; cursor: pointer; }
    .cccd-img:hover { transform: scale(1.02); }
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="container">
        <div class="mb-4 d-flex align-items-center">
            <a href="{{ route('admin.affiliates.index') }}" class="btn btn-light"><i class="fas fa-arrow-left me-2"></i> Quay lại</a>
            <h4 class="fw-bold mb-0 text-white ms-3">Chi tiết: {{ $affiliate->name }}</h4>
        </div>

        <div class="row">
            <!-- Basic Info -->
            <div class="col-lg-7">
                <div class="admin-card">
                    <h5 class="fw-bold mb-4 border-bottom pb-2">Thông tin tài khoản</h5>
                    <div class="row g-4">
                        <div class="col-md-6 text-start">
                            <label class="small text-muted d-block">Họ và tên</label>
                            <span class="fw-bold">{{ $affiliate->name }}</span>
                        </div>
                        <div class="col-md-6 text-start">
                            <label class="small text-muted d-block">Trạng thái hồ sơ</label>
                            <span class="badge {{ $affiliate->status == 'approved' ? 'bg-success' : ($affiliate->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                {{ $affiliate->status == 'approved' ? 'Đã duyệt' : ($affiliate->status == 'pending' ? 'Chờ duyệt' : 'Từ chối') }}
                            </span>
                        </div>
                        <div class="col-md-6 text-start">
                            <label class="small text-muted d-block">Email</label>
                            <span class="fw-bold">{{ $affiliate->email }}</span>
                        </div>
                        <div class="col-md-6 text-start">
                            <label class="small text-muted d-block">Số điện thoại</label>
                            <span class="fw-bold">{{ $affiliate->phone }}</span>
                        </div>
                        <div class="col-12 text-start">
                            <label class="small text-muted d-block">Địa chỉ</label>
                            <span class="fw-bold">{{ $affiliate->address }}</span>
                        </div>
                        
                        <div class="col-12 text-start pt-3 border-top">
                            <h6 class="fw-bold text-primary mb-3">Thông tin ngân hàng</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="small text-muted d-block">Ngân hàng</label>
                                    <span class="fw-bold">{{ $affiliate->bank_name ?? 'Chưa cập nhật' }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="small text-muted d-block">Số tài khoản</label>
                                    <span class="fw-bold">{{ $affiliate->bank_account_number ?? 'Chưa cập nhật' }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="small text-muted d-block">Chủ tài khoản</label>
                                    <span class="fw-bold">{{ $affiliate->bank_account_name ?? 'Chưa cập nhật' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 text-start pt-3 border-top">
                            <h6 class="fw-bold text-success mb-3">Thống kê thu nhập</h6>
                            <div class="row text-center">
                                <div class="col-md-6 border-end">
                                    <label class="small text-muted d-block">Số dư hiện tại</label>
                                    <h4 class="fw-bold text-success mb-0">{{ number_format($affiliate->balance, 0, ',', '.') }}đ</h4>
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted d-block">Tổng thu nhập tích lũy</label>
                                    <h4 class="fw-bold text-primary mb-0">{{ number_format($affiliate->fresh()->total_earned, 0, ',', '.') }}đ</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($affiliate->status == 'pending')
                    <div class="mt-5 d-flex gap-3 justify-content-center">
                        <form action="{{ route('admin.affiliates.approve', $affiliate->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success px-4 py-2 fw-bold" onclick="return confirm('Duyệt CTV này?')">
                                <i class="fas fa-check me-2"></i> PHÊ DUYỆT HỒ SƠ
                            </button>
                        </form>
                        <button class="btn btn-danger px-4 py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fas fa-times me-2"></i> TỪ CHỐI HỒ SƠ
                        </button>
                    </div>
                    @endif

                    <div class="mt-4 pt-4 border-top">
                        <form action="{{ route('admin.affiliates.reset-password', $affiliate->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn reset mật khẩu của CTV này về 123456789?')">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-key me-1"></i> Reset mật khẩu về 123456789
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="col-lg-5">
                <div class="admin-card">
                    <h5 class="fw-bold mb-4 border-bottom pb-2">Hồ sơ định danh (CCCD)</h5>
                    <div class="mb-3 text-start">
                        <label class="small text-muted">Số CCCD: </label>
                        <span class="fw-bold font-monospace ms-2">{{ $affiliate->cccd_number }}</span>
                    </div>

                    <div class="mb-4 text-start">
                        <label class="small text-muted mb-2">Mặt trước CCCD</label>
                        <a href="{{ asset($affiliate->cccd_front) }}" target="_blank">
                            <img src="{{ asset($affiliate->cccd_front) }}" class="cccd-img" alt="Front">
                        </a>
                    </div>

                    <div class="mb-2 text-start">
                        <label class="small text-muted mb-2">Mặt sau CCCD</label>
                        <a href="{{ asset($affiliate->cccd_back) }}" target="_blank">
                            <img src="{{ asset($affiliate->cccd_back) }}" class="cccd-img" alt="Back">
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.affiliates.reject', $affiliate->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Từ chối hồ sơ CTV</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-start">
                            <label class="form-label fw-bold">Lý do từ chối:</label>
                            <textarea name="reject_reason" class="form-control" rows="4" required placeholder="Nêu rõ lý do (VD: Ảnh mờ, Thông tin không đúng...)"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-danger px-4">Xác nhận từ chối</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
