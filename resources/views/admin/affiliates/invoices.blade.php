@extends('layouts.app')

@section('title', 'Duyệt hóa đơn CTV | Admin')

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
                <a href="{{ route('admin.affiliates.invoices') }}" class="btn btn-light active">Duyệt Hóa đơn ({{ $pendingCount }})</a>
                <a href="{{ route('admin.affiliates.withdrawals') }}" class="btn btn-light">Duyệt Rút tiền</a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light ms-auto"><i class="fas fa-arrow-left"></i> Dashboard</a>
            </div>
        </div>

        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Hóa đơn Cộng tác viên gửi</h4>
                
                <form action="{{ route('admin.affiliates.invoices') }}" method="GET" class="d-flex gap-2">
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
                            <th>Sản phẩm</th>
                            <th>Số tiền khách trả</th>
                            <th>Hoa hồng (10%)</th>
                            <th>Ghi chú CTV</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $invoice->affiliate->name }}</div>
                                <div class="small text-muted">{{ $invoice->affiliate->email }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $invoice->product_name }}</div>
                                <div class="small text-muted mt-1">
                                    <i class="fas fa-user-tag me-1"></i> {{ $invoice->customer_name }}
                                </div>
                                @if($invoice->customer_email || $invoice->customer_phone)
                                <div class="small text-muted" style="font-size: 11px;">
                                    {{ implode(' | ', array_filter([$invoice->customer_email, $invoice->customer_phone])) }}
                                </div>
                                @endif
                            </td>
                            <td><span class="fw-bold">{{ number_format($invoice->amount, 0, ',', '.') }}đ</span></td>
                            <td><span class="fw-bold text-success">+{{ number_format($invoice->commission, 0, ',', '.') }}đ</span></td>
                            <td style="max-width: 200px;"><small class="text-muted">{{ $invoice->note ?? '-' }}</small></td>
                            <td>
                                <span class="badge-status badge-{{ $invoice->status }}">
                                    {{ $invoice->status_label }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#viewBill{{ $invoice->id }}">
                                    <i class="fas fa-image"></i> Xem Bill
                                </button>
                                
                                @if($invoice->status == 'pending')
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $invoice->id }}">
                                        <i class="fas fa-check"></i> Duyệt
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $invoice->id }}">
                                        <i class="fas fa-times"></i> Hủy
                                    </button>
                                @endif

                                <!-- Bill Preview Modal -->
                                <div class="modal fade" id="viewBill{{ $invoice->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Hóa đơn của {{ $invoice->affiliate->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ asset($invoice->bill_image) }}" class="img-fluid" alt="Bill">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Approve Modal -->
                                <div class="modal fade" id="approveModal{{ $invoice->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.affiliates.invoices.approve', $invoice->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-success">Phê duyệt hoa hồng</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    <p class="mb-2">Bạn sắp duyệt hoa hồng <strong>{{ number_format($invoice->commission, 0, ',', '.') }}đ</strong> cho {{ $invoice->affiliate->name }}?</p>
                                                    <label class="form-label small">Ghi chú cho CTV (tùy chọn):</label>
                                                    <input type="text" name="admin_note" class="form-control" placeholder="Tuyệt vời, cảm ơn em...">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                    <button type="submit" class="btn btn-success fw-bold">XÁC NHẬN & CỘNG TIỀN</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $invoice->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.affiliates.invoices.reject', $invoice->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-danger">Từ chối hóa đơn</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    <label class="form-label fw-bold">Lý do từ chối:</label>
                                                    <textarea name="admin_note" class="form-control" rows="3" required placeholder="Nêu rõ lý do từ chối..."></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                    <button type="submit" class="btn btn-danger">XÁC NHẬN TỪ CHỐI</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Không có hóa đơn nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
