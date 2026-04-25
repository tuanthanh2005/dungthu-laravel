@extends('layouts.app')

@section('title', 'Dashboard Cộng tác viên | DungThu')

@push('styles')
<style>
    :root {
        --aff-bg: #f8fafc;
        --aff-card-bg: #ffffff;
        --aff-accent: #6366f1;
        --aff-text: #1e293b;
        --aff-text-light: #64748b;
    }

    .aff-wrapper {
        background-color: var(--aff-bg);
        min-height: 100vh;
        padding-top: 100px;
        padding-bottom: 50px;
    }

    .aff-sidebar {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        height: fit-content;
    }

    .aff-nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 20px;
        color: var(--aff-text-light);
        text-decoration: none !important;
        border-radius: 12px;
        font-weight: 500;
        transition: all 0.3s;
        margin-bottom: 8px;
    }

    .aff-nav-link:hover, .aff-nav-link.active {
        background: rgba(99, 102, 241, 0.1);
        color: var(--aff-accent);
    }

    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        transition: transform 0.3s;
        height: 100%;
    }

    .stats-card:hover {
        transform: translateY(-5px);
    }

    .stats-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 15px;
    }

    .stats-val {
        font-size: 24px;
        font-weight: 700;
        color: var(--aff-text);
        margin-bottom: 5px;
    }

    .stats-label {
        font-size: 14px;
        color: var(--aff-text-light);
    }

    .content-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table th {
        background: #f8fafc;
        border: none;
        color: var(--aff-text-light);
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        padding: 15px;
    }

    .table td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-pending { background: #fef3c7; color: #d97706; }
    .badge-approved { background: #dcfce7; color: #16a34a; }
    .badge-rejected { background: #fee2e2; color: #dc2626; }

    .btn-aff {
        background: var(--aff-accent);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-aff:hover {
        background: #4f46e5;
        color: white;
        transform: translateY(-2px);
    }

    .profile-banner {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        border-radius: 20px;
        padding: 30px;
        color: white;
        margin-bottom: 30px;
    }
</style>
@endpush

@section('content')
<div class="aff-wrapper">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
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
                        <a href="{{ route('affiliate.dashboard') }}" class="aff-nav-link active">
                            <i class="fas fa-th-large"></i> Thống kê
                        </a>
                        <a href="{{ route('affiliate.invoices') }}" class="aff-nav-link">
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

            <!-- Content -->
            <div class="col-lg-9">
                <!-- Welcome Banner -->
                <div class="profile-banner d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-2">Chào mừng, {{ explode(' ', $affiliate->name)[0] }}! 👋</h4>
                        <p class="mb-0 opacity-75">Hôm nay bạn đã có giao dịch mới chưa? Gửi hóa đơn để nhận hoa hồng 5% ngay nhé.</p>
                    </div>
                    <a href="{{ route('affiliate.invoices.create') }}" class="btn btn-light fw-bold" style="border-radius: 12px; padding: 10px 20px;">
                        + Gửi hóa đơn mới
                    </a>
                </div>

                <!-- Stats -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                                <i class="fas fa-hand-holding-usd"></i>
                            </div>
                            <div class="stats-val">{{ number_format($balance, 0, ',', '.') }}đ</div>
                            <div class="stats-label">Số dư khả dụng</div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: rgba(34, 197, 94, 0.1); color: #22c55e;">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stats-val">{{ number_format($totalEarned, 0, ',', '.') }}đ</div>
                            <div class="stats-label">Tổng thu nhập</div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stats-val">{{ number_format($pendingEarned, 0, ',', '.') }}đ</div>
                            <div class="stats-label">Chờ xử lý</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="row">
                    <!-- Recent Invoices -->
                    <div class="col-12 mb-4">
                        <div class="content-card">
                            <div class="section-title">
                                <i class="fas fa-history text-primary"></i> Giao dịch gần đây
                                <a href="{{ route('affiliate.invoices') }}" class="ms-auto btn btn-link text-decoration-none p-0" style="font-size: 13px;">Xem tất cả</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th>Số tiền</th>
                                            <th>Hoa hồng (5%)</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentInvoices as $invoice)
                                        <tr>
                                            <td class="fw-bold">{{ $invoice->product_name }}</td>
                                            <td>{{ number_format($invoice->amount, 0, ',', '.') }}đ</td>
                                            <td class="text-success fw-bold">+{{ number_format($invoice->commission, 0, ',', '.') }}đ</td>
                                            <td>
                                                <span class="badge-status badge-{{ $invoice->status }}">
                                                    {{ $invoice->status_label }}
                                                </span>
                                            </td>
                                            <td class="text-muted">{{ $invoice->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">Chưa có giao dịch nào</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Withdrawals -->
                    <div class="col-12">
                        <div class="content-card">
                            <div class="section-title">
                                <i class="fas fa-wallet text-warning"></i> Lịch sử rút tiền
                                <a href="{{ route('affiliate.withdrawals') }}" class="ms-auto btn btn-link text-decoration-none p-0" style="font-size: 13px;">Xem tất cả</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Số tiền</th>
                                            <th>Ngân hàng</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentWithdrawals as $withdrawal)
                                        <tr>
                                            <td class="fw-bold text-danger">-{{ number_format($withdrawal->amount, 0, ',', '.') }}đ</td>
                                            <td>
                                                <div style="font-size: 13px;">{{ $withdrawal->bank_name }}</div>
                                                <div class="text-muted" style="font-size: 11px;">{{ $withdrawal->bank_account_number }}</div>
                                            </td>
                                            <td>
                                                <span class="badge-status badge-{{ $withdrawal->status }}">
                                                    {{ $withdrawal->status_label }}
                                                </span>
                                            </td>
                                            <td class="text-muted">{{ $withdrawal->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">Chưa có yêu cầu rút tiền nào</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
