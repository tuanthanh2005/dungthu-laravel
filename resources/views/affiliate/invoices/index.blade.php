@extends('layouts.app')

@section('title', 'Quản lý hóa đơn | DungThu')

@push('styles')
<style>
    .aff-wrapper { background-color: #f8fafc; min-height: 100vh; padding-top: 100px; padding-bottom: 50px; }
    .aff-sidebar { background: white; border-radius: 20px; padding: 25px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); height: fit-content; }
    .aff-nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 20px; color: #64748b; text-decoration: none !important; border-radius: 12px; font-weight: 500; transition: all 0.3s; margin-bottom: 8px; }
    .aff-nav-link:hover, .aff-nav-link.active { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
    .content-card { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); margin-bottom: 30px; }
    .section-title { font-size: 18px; font-weight: 700; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; }
    .table th { background: #f8fafc; border: none; color: #64748b; font-weight: 600; font-size: 13px; text-transform: uppercase; padding: 15px; }
    .table td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
    .badge-status { padding: 6px 12px; border-radius: 50px; font-size: 12px; font-weight: 600; }
    .badge-pending { background: #fef3c7; color: #d97706; }
    .badge-approved { background: #dcfce7; color: #16a34a; }
    .badge-rejected { background: #fee2e2; color: #dc2626; }
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
                        <a href="{{ route('affiliate.invoices') }}" class="aff-nav-link active">
                            <i class="fas fa-file-invoice-dollar"></i> Gửi hóa đơn
                        </a>
                        <a href="{{ route('affiliate.withdrawals') }}" class="aff-nav-link">
                            <i class="fas fa-wallet"></i> Rút tiền
                        </a>
                        <a href="{{ route('affiliate.account') }}" class="aff-nav-link">
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
                <div class="content-card">
                    <div class="section-title">
                        <i class="fas fa-list text-primary"></i> Danh sách hóa đơn đã gửi
                        <a href="{{ route('affiliate.invoices.create') }}" class="ms-auto btn btn-primary btn-sm" style="border-radius: 8px;">
                            + Gửi mới
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Số tiền khách trả</th>
                                    <th>Hoa hồng (10%)</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                <tr>
                                    <td class="fw-bold">{{ $invoice->product_name }}</td>
                                    <td>{{ number_format($invoice->amount, 0, ',', '.') }}đ</td>
                                    <td class="text-success fw-bold">+{{ number_format($invoice->commission, 0, ',', '.') }}đ</td>
                                    <td>
                                        <span class="badge-status badge-{{ $invoice->status }}">
                                            {{ $invoice->status_label }}
                                        </span>
                                        @if($invoice->status === 'rejected' && $invoice->admin_note)
                                            <div class="small text-danger mt-1" title="{{ $invoice->admin_note }}">
                                                <i class="fas fa-info-circle"></i> Lý do từ chối
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $invoice->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#imgModal{{ $invoice->id }}">
                                            <i class="fas fa-image"></i> Bill
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="imgModal{{ $invoice->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Hóa đơn: {{ $invoice->product_name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="{{ asset($invoice->bill_image) }}" class="img-fluid" alt="Bill image">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Chưa có hóa đơn nào được gửi</td>
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
    </div>
</div>
@endsection
