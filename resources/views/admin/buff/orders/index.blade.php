@extends('layouts.admin')

@section('title', 'Quản Lý Đơn Hàng - Admin')

@push('styles')
<style>
    .container-admin {
        padding: 2rem;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header h1 {
        font-size: 1.8rem;
        font-weight: 800;
        color: #2d3436;
        margin: 0;
    }

    .search-filter {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .search-filter input,
    .search-filter select {
        padding: 0.6rem 0.75rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.9rem;
    }

    .search-filter input:focus,
    .search-filter select:focus {
        border-color: #6c5ce7;
        outline: none;
        box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.1);
    }

    .search-filter button {
        background: #6c5ce7;
        color: white;
        padding: 0.6rem 1rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .search-filter button:hover {
        background: #5f4ec7;
    }

    .success-message {
        background: #d4edda;
        color: #155724;
        padding: 1rem 1.5rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
        border-left: 4px solid #28a745;
    }

    .success-message button {
        float: right;
        background: none;
        border: none;
        color: #155724;
        cursor: pointer;
        font-size: 1.5rem;
        line-height: 1;
    }

    .table-responsive {
        overflow-x: auto;
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    thead {
        background: #f5f5f5;
    }

    th {
        padding: 1rem;
        text-align: left;
        font-weight: 700;
        color: #2d3436;
        border-bottom: 2px solid #e0e0e0;
    }

    td {
        padding: 1rem;
        border-bottom: 1px solid #e0e0e0;
    }

    tbody tr:hover {
        background: #fafafa;
    }

    .order-code {
        font-weight: 700;
        color: #6c5ce7;
        font-family: monospace;
        font-size: 0.9rem;
    }

    .user-info {
        font-weight: 600;
        color: #2d3436;
        font-size: 0.95rem;
    }

    .user-email {
        font-size: 0.85rem;
        color: #636e72;
    }

    .price-value {
        font-weight: 700;
        color: #00b894;
    }

    .status-badge {
        display: inline-block;
        padding: 0.4rem 0.75rem;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-paid {
        background: #cfe2ff;
        color: #084298;
    }

    .status-processing {
        background: #e7d4f5;
        color: #6c5ce7;
    }

    .status-completed {
        background: #d4edda;
        color: #155724;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-view {
        background: #a29bfe;
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .btn-view:hover {
        background: #6c5ce7;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #636e72;
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .empty-state p {
        margin: 0.5rem 0;
        font-size: 0.95rem;
    }

    @media (max-width: 768px) {
        .container-admin {
            padding: 1rem;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .search-filter {
            width: 100%;
        }

        .search-filter input,
        .search-filter select,
        .search-filter button {
            flex: 1;
            min-width: 120px;
        }

        th, td {
            padding: 0.75rem;
            font-size: 0.9rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-view {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="container-admin">
    <div class="page-header">
        <h1>📋 Quản Lý Đơn Hàng</h1>
    </div>

    <div class="search-filter">
        <form method="GET" action="{{ route('admin.buff.orders.index') }}" style="display: flex; gap: 1rem; width: 100%; flex-wrap: wrap;">
            <input type="text" name="search" placeholder="Tìm mã đơn hoặc email..."
                value="{{ request('search') }}" style="flex: 1; min-width: 200px;">
            
            <select name="status" style="min-width: 150px;">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="pending" @selected(request('status') === 'pending')>Chờ Thanh Toán</option>
                <option value="paid" @selected(request('status') === 'paid')>Đã Thanh Toán</option>
                <option value="processing" @selected(request('status') === 'processing')>Đang Xử Lý</option>
                <option value="completed" @selected(request('status') === 'completed')>Hoàn Thành</option>
                <option value="cancelled" @selected(request('status') === 'cancelled')>Hủy</option>
            </select>

            <button type="submit">🔍 Tìm Kiếm</button>
        </form>
    </div>

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
            <button onclick="this.parentElement.style.display='none';">×</button>
        </div>
    @endif

    @if($orders->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Mã Đơn</th>
                        <th>Khách Hàng</th>
                        <th>Dịch Vụ</th>
                        <th>Giá</th>
                        <th>Trạng Thái</th>
                        <th>Ngày Tạo</th>
                        <th style="width: 100px;">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>
                                <div class="order-code">#{{ $order->order_code }}</div>
                            </td>
                            <td>
                                <div class="user-info">{{ $order->user->name }}</div>
                                <div class="user-email">{{ $order->user->email }}</div>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #2d3436;">{{ $order->buffService->name }}</div>
                                <div style="font-size: 0.85rem; color: #636e72;">
                                    SL: {{ $order->amount }}
                                </div>
                            </td>
                            <td>
                                <div class="price-value">{{ number_format($order->total_price) }} đ</div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    @switch($order->status)
                                        @case('pending')
                                            ⏳ Chờ Thanh Toán
                                            @break
                                        @case('paid')
                                            ✓ Đã Thanh Toán
                                            @break
                                        @case('processing')
                                            🔄 Đang Xử Lý
                                            @break
                                        @case('completed')
                                            ✓ Hoàn Thành
                                            @break
                                        @case('cancelled')
                                            ✗ Hủy
                                            @break
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                <small>{{ $order->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.buff.orders.edit', $order) }}" class="btn-view">
                                        ✏️ Chi Tiết
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div style="margin-top: 2rem; display: flex; justify-content: center;">
                {{ $orders->links() }}
            </div>
        @endif
    @else
        <div style="background: white; border: 1px solid #e0e0e0; border-radius: 8px;">
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <p style="font-weight: 600; color: #2d3436;">Chưa có đơn hàng nào</p>
                <p>Các đơn hàng sẽ xuất hiện ở đây</p>
            </div>
        </div>
    @endif
</div>
@endsection
