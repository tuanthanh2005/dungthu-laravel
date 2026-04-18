@extends('layouts.app')

@section('title', 'Duyệt rút tiền CTV | Admin')

@push('styles')
<style>
    .admin-wrapper { padding: 40px 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; margin-top: 70px; }
    .admin-card { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
    .table th { background: #f8fafc; border: none; color: #4a5568; font-weight: 700; text-transform: uppercase; font-size: 13px; }
    .badge-status { padding: 6px 12px; border-radius: 50px; font-size: 12px; font-weight: 600; }
    .badge-pending { background: #fef3c7; color: #d97706; }
    .badge-approved { background: #dcfce7; color: #16a34a; }
    .badge-rejected { background: #fee2e2; color: #dc2626; }
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="container">
        <!-- Sub Nav -->
        <div class="mb-4">
            <div class="d-flex gap-2">
                <a href="{{ route('admin.affiliates.index') }}" class="btn btn-light">Duyệt CTV</a>
                <a href="{{ route('admin.affiliates.invoices') }}" class="btn btn-light">Duyệt Hóa đơn</a>
                <a href="{{ route('admin.affiliates.withdrawals') }}" class="btn btn-light active">Duyệt Rút tiền ({{ $pendingCount }})</a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light ms-auto"><i class="fas fa-arrow-left"></i> Dashboard</a>
            </div>
        </div>

        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Yêu cầu rút tiền từ CTV</h4>
                
                <form action="{{ route('admin.affiliates.withdrawals') }}" method="GET" class="d-flex gap-2">
                    <select name="status" class="form-select" style="width: 150px;" onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                    </select>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Cộng tác viên</th>
                            <th>Số tiền rút</th>
                            <th>Thông tin ngân hàng</th>
                            <th>Số dư còn lại</th>
                            <th>Trạng thái</th>
                            <th>Ngày yêu cầu</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $withdrawal)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $withdrawal->affiliate->name }}</div>
                                <div class="small text-muted">{{ $withdrawal->affiliate->email }}</div>
                            </td>
                            <td><span class="fw-bold text-danger">{{ number_format($withdrawal->amount, 0, ',', '.') }}đ</span></td>
                            <td>
                                <div class="fw-bold" style="font-size: 13px;">{{ $withdrawal->bank_name }}</div>
                                <div class="small text-muted">{{ $withdrawal->bank_account_number }} - {{ $withdrawal->bank_account_name }}</div>
                            </td>
                            <td><span class="text-muted">{{ number_format($withdrawal->affiliate->balance, 0, ',', '.') }}đ</span></td>
                            <td>
                                <span class="badge-status badge-{{ $withdrawal->status }}">
                                    {{ $withdrawal->status_label }}
                                </span>
                            </td>
                            <td>{{ $withdrawal->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($withdrawal->status == 'pending')
                                    <button class="btn btn-sm btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#approveModal{{ $withdrawal->id }}">
                                        <i class="fas fa-check"></i> DUYỆT & TRỪ TIỀN
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $withdrawal->id }}">
                                        <i class="fas fa-times"></i> TỪ CHỐI
                                    </button>

                                    <!-- Approve Modal -->
                                    <div class="modal fade" id="approveModal{{ $withdrawal->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.affiliates.withdrawals.approve', $withdrawal->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title font-bold">Xác nhận đã chuyển khoản</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        <div class="alert alert-warning py-2 mb-3 small">
                                                            <i class="fas fa-exclamation-triangle me-1"></i> Lưu ý: Bạn cần chuyển khoản thực tế cho CTV trước khi bấm duyệt.
                                                        </div>
                                                        <div class="bg-light p-3 rounded mb-3">
                                                            <div class="text-muted small">Số tiền: <strong class="text-danger">{{ number_format($withdrawal->amount, 0, ',', '.') }}đ</strong></div>
                                                            <div class="text-muted small">Ngân hàng: <strong>{{ $withdrawal->bank_name }}</strong></div>
                                                            <div class="text-muted small">STK: <strong class="text-primary">{{ $withdrawal->bank_account_number }}</strong></div>
                                                            <div class="text-muted small">Tên: <strong class="text-uppercase">{{ $withdrawal->bank_account_name }}</strong></div>
                                                        </div>
                                                        <label class="form-label small">Ghi chú cho CTV (Mã giao dịch...):</label>
                                                        <input type="text" name="admin_note" class="form-control" placeholder="Đã chuyển thành công...">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                        <button type="submit" class="btn btn-success fw-bold">XÁC NHẬN ĐÃ CHUYỂN & DUYỆT</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $withdrawal->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.affiliates.withdrawals.reject', $withdrawal->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-danger">Từ chối rút tiền</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        <label class="form-label fw-bold">Lý do từ chối:</label>
                                                        <textarea name="admin_note" class="form-control" rows="3" required placeholder="Ví dụ: STK không chính xác, Số dư không hợp lệ..."></textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                        <button type="submit" class="btn btn-danger">XÁC NHẬN TỪ CHỐI</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">{{ $withdrawal->admin_note ?? '-' }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Không có yêu cầu rút tiền nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $withdrawals->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
