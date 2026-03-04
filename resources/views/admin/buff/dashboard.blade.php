@extends('layouts.admin')

@section('title', 'Buff Dashboard - Admin')

@push('styles')
<style>
    .dashboard-container {
        padding: 2rem;
    }

    .dashboard-header {
        margin-bottom: 2rem;
    }

    .dashboard-header h1 {
        font-size: 2rem;
        font-weight: 800;
        color: #2d3436;
        margin-bottom: 0.5rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.2s ease;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: #6c5ce7;
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: #2d3436;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #999;
        text-transform: uppercase;
        font-weight: 600;
    }

    .recent-orders {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .recent-orders h2 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #2d3436;
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }

    .orders-table th {
        background: #f8f9fa;
        padding: 0.75rem;
        text-align: left;
        font-weight: 700;
        font-size: 0.9rem;
        color: #666;
        border-bottom: 2px solid #e0e0e0;
    }

    .orders-table td {
        padding: 0.75rem;
        border-bottom: 1px solid #e0e0e0;
        font-size: 0.95rem;
    }

    .orders-table tr:hover {
        background: #f8f9fa;
    }

    .status-badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-paid {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-processing {
        background: #cfe2ff;
        color: #084298;
    }

    .status-completed {
        background: #d1e7dd;
        color: #0f5132;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .action-btn {
        background: white;
        border: 1px solid #e0e0e0;
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
        text-decoration: none;
        color: #6c5ce7;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        background: #f8f9fa;
        border-color: #6c5ce7;
        color: #6c5ce7;
    }

    .action-icon {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .stat-value {
            font-size: 1.5rem;
        }

        .orders-table {
            font-size: 0.85rem;
        }

        .orders-table th,
        .orders-table td {
            padding: 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>💎 Buff Service Dashboard</h1>
        <p class="text-muted">Quản lý dịch vụ buff và đơn hàng</p>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-value">{{ $totalOrders }}</div>
            <div class="stat-label">Tổng Đơn Hàng</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-value">{{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="stat-label">Doanh Thu</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">⚙️</div>
            <div class="stat-value">{{ $processingOrders }}</div>
            <div class="stat-label">Đang Xử Lý</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-value">{{ $completedOrders }}</div>
            <div class="stat-label">Hoàn Thành</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="recent-orders" style="background: #f8f9fa; padding: 1.5rem;">
        <h2>⚡ Quản Lý Nhanh</h2>
        <div class="quick-actions">
            <a href="{{ route('admin.buff.servers.index') }}" class="action-btn">
                <div class="action-icon">🖥️</div>
                <div>Quản Lý Server</div>
            </a>
            <a href="{{ route('admin.buff.services.index') }}" class="action-btn">
                <div class="action-icon">🎯</div>
                <div>Dịch Vụ</div>
            </a>
            <a href="{{ route('admin.buff.prices.index') }}" class="action-btn">
                <div class="action-icon">💵</div>
                <div>Giá Server</div>
            </a>
            <a href="{{ route('admin.buff.orders.index') }}" class="action-btn">
                <div class="action-icon">📋</div>
                <div>Quản Lý Đơn</div>
            </a>
        </div>
    </div>

    <!-- Recent Orders -->
    @if($recentOrders->count() > 0)
        <div class="recent-orders">
            <h2>📊 Đơn Hàng Gần Đây</h2>
            <div style="overflow-x: auto;">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Mã Đơn</th>
                            <th>Khách Hàng</th>
                            <th>Dịch Vụ</th>
                            <th>Giá</th>
                            <th>Trạng Thái</th>
                            <th style="text-align: center;">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr>
                                <td>{{ $order->order_code }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->buffService->name }}</td>
                                <td>{{ number_format($order->getPriceToShow(), 0, ',', '.') }}đ</td>
                                <td>
                                    <span class="status-badge status-{{ $order->status }}">
                                        {{ $order->getStatusText() }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <a href="{{ route('admin.buff.orders.show', $order) }}" 
                                        class="btn btn-sm btn-primary" style="font-size: 0.8rem;">
                                        Xem
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
