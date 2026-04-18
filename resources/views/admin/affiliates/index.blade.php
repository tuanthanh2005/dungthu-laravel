@extends('layouts.app')

@section('title', 'Quản lý Cộng tác viên | Admin')

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
                <a href="{{ route('admin.affiliates.index') }}" class="btn btn-light active">Duyệt CTV ({{ $pendingCount }})</a>
                <a href="{{ route('admin.affiliates.invoices') }}" class="btn btn-light">Duyệt Hóa đơn</a>
                <a href="{{ route('admin.affiliates.withdrawals') }}" class="btn btn-light">Duyệt Rút tiền</a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light ms-auto"><i class="fas fa-arrow-left"></i> Dashboard</a>
            </div>
        </div>

        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Danh sách Cộng tác viên</h4>
                
                <form action="{{ route('admin.affiliates.index') }}" method="GET" class="d-flex gap-2">
                    <select name="status" class="form-select" style="width: 150px;" onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                    </select>
                    <input type="text" name="search" class="form-control" placeholder="Tìm tên/email/sdt..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tên & Liên hệ</th>
                            <th>CCCD</th>
                            <th>Trạng thái</th>
                            <th>Ngày đăng ký</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($affiliates as $aff)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $aff->name }}</div>
                                <div class="small text-muted">{{ $aff->email }}</div>
                                <div class="small text-muted">{{ $aff->phone }}</div>
                            </td>
                            <td>{{ $aff->cccd_number }}</td>
                            <td>
                                <span class="badge-status badge-{{ $aff->status }}">
                                    {{ $aff->status == 'pending' ? 'Chờ duyệt' : ($aff->status == 'approved' ? 'Đã duyệt' : 'Từ chối') }}
                                </span>
                            </td>
                            <td>{{ $aff->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.affiliates.show', $aff->id) }}" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-eye"></i> Chi tiết
                                </a>
                                @if($aff->status == 'pending')
                                    <form action="{{ route('admin.affiliates.approve', $aff->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Duyệt hồ sơ này?')">
                                            <i class="fas fa-check"></i> Duyệt
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $aff->id }}">
                                        <i class="fas fa-times"></i> Từ chối
                                    </button>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $aff->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.affiliates.reject', $aff->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Từ chối hồ sơ CTV</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        <label class="form-label">Lý do từ chối:</label>
                                                        <textarea name="reject_reason" class="form-control" rows="3" required placeholder="Nêu rõ lý do để CTV biết..."></textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                        <button type="submit" class="btn btn-danger">Xác nhận từ chối</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <form action="{{ route('admin.affiliates.reset-password', $aff->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Reset mật khẩu về 123456789?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Reset mật khẩu">
                                        <i class="fas fa-key"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Không tìm thấy cộng tác viên nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $affiliates->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
