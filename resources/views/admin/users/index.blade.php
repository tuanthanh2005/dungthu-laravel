@extends('layouts.app')

@section('title', 'Quản Lý Người Dùng')

@push('styles')
<style>
    .admin-wrapper {
        padding: 40px 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        margin-top: 70px;
    }

    .admin-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }

    .admin-nav {
        background: rgba(255,255,255,0.1);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
    }

    .admin-nav a {
        color: white;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 10px;
        transition: all 0.3s;
        margin-right: 10px;
        display: inline-block;
    }

    .admin-nav a:hover {
        background: rgba(255,255,255,0.2);
    }

    .admin-nav a.active {
        background: white;
        color: #667eea;
    }

    .user-table {
        width: 100%;
        margin-top: 20px;
    }

    .user-table th {
        background: #f7fafc;
        padding: 15px;
        font-weight: 600;
        color: #2d3748;
        border-bottom: 2px solid #e2e8f0;
    }

    .user-table td {
        padding: 15px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .user-table tr:hover {
        background: #f7fafc;
    }

    .badge-orders {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
    }

    .total-spent {
        color: #38a169;
        font-weight: 700;
        font-size: 16px;
    }

    .view-btn {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .view-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
    }

    .stats-mini {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .stats-mini-item {
        background: #f7fafc;
        padding: 8px 15px;
        border-radius: 10px;
        font-size: 13px;
    }
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="container">
        <!-- Navigation -->
        <div class="admin-nav">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fas fa-chart-line me-2"></i>Dashboard
            </a>
            <a href="{{ route('admin.products') }}">
                <i class="fas fa-box me-2"></i>Sản phẩm
            </a>
            <a href="{{ route('admin.orders') }}">
                <i class="fas fa-shopping-cart me-2"></i>Đơn hàng
            </a>
            <a href="{{ route('admin.users') }}" class="active">
                <i class="fas fa-users me-2"></i>Người dùng
            </a>
        </div>

        <!-- User Management Card -->
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">
                    <i class="fas fa-users text-primary me-2"></i>Quản Lý Người Dùng
                </h3>
                <div class="stats-mini">
                    <div class="stats-mini-item">
                        <i class="fas fa-user text-primary me-1"></i>
                        <strong>{{ $users->total() }}</strong> người dùng
                    </div>
                </div>
            </div>

            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th style="width: 5%">#</th>
                                <th style="width: 20%">Họ tên</th>
                                <th style="width: 20%">Email</th>
                                <th style="width: 12%">Số ĐT</th>
                                <th style="width: 10%" class="text-center">Số đơn</th>
                                <th style="width: 13%" class="text-end">Tổng chi tiêu</th>
                                <th style="width: 12%">Ngày đăng ký</th>
                                <th style="width: 8%" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                                <tr>
                                    <td class="fw-bold">{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        @if($user->phone)
                                            <small class="text-muted">{{ $user->phone }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone ?? 'Chưa cập nhật' }}</td>
                                    <td class="text-center">
                                        <span class="badge-orders">
                                            <i class="fas fa-shopping-cart me-1"></i>{{ $user->orders_count }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="total-spent">
                                            {{ number_format($user->orders_sum_total_amount ?? 0, 0, ',', '.') }}đ
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $user->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.users.history', $user->id) }}" class="view-btn" title="Xem lịch sử mua hàng">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Chưa có người dùng nào</h5>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
