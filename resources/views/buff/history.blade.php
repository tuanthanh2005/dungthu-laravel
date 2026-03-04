@extends('layouts.app')

@section('title', 'Lịch Sử Đơn Buff - DungThu.com')

@push('styles')
<style>
    .history-header {
        background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
    }

    .history-header h1 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .filter-section {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        border: 1px solid #e0e0e0;
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        align-items: flex-end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .filter-select {
        padding: 0.6rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.95rem;
    }

    .order-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .order-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.5rem;
        transition: all 0.2s ease;
    }

    .order-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: #6c5ce7;
    }

    .order-card-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .order-code-section {
        flex: 1;
    }

    .order-code {
        font-family: 'Courier New', monospace;
        font-weight: 700;
        color: #6c5ce7;
        font-size: 0.95rem;
    }

    .order-date {
        font-size: 0.85rem;
        color: #999;
        margin-top: 0.25rem;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
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

    .status-cancelled {
        background: #f8d7da;
        color: #842029;
    }

    .status-refunded {
        background: #e2e3e5;
        color: #383d41;
    }

    .order-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
    }

    .detail-label {
        font-size: 0.85rem;
        color: #999;
        margin-bottom: 0.25rem;
        text-transform: uppercase;
    }

    .detail-value {
        font-weight: 600;
        color: #2d3436;
        word-break: break-all;
    }

    .detail-value.price {
        color: #dc3545;
        font-size: 1.1rem;
    }

    .order-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        border: none;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-view {
        background: #6c5ce7;
        color: white;
    }

    .btn-view:hover {
        background: #5f4ec7;
    }

    .btn-pay {
        background: #dc3545;
        color: white;
    }

    .btn-pay:hover {
        background: #c82333;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        background: white;
        border-radius: 8px;
        border: 1px dashed #e0e0e0;
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state h3 {
        color: #2d3436;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #999;
        margin-bottom: 1.5rem;
    }

    .pagination {
        margin-top: 2rem;
        justify-content: center;
    }

    .progress-bar {
        height: 4px;
        background: #e0e0e0;
        border-radius: 2px;
        overflow: hidden;
        margin-top: 0.75rem;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #6c5ce7, #a29bfe);
        border-radius: 2px;
    }

    .progress-fill.complete {
        width: 100%;
    }

    .progress-fill.processing {
        width: 50%;
    }

    .progress-fill.paid {
        width: 33%;
    }

    .progress-fill.pending {
        width: 10%;
    }

    @media (max-width: 768px) {
        .history-header {
            padding: 1.5rem 0;
        }

        .history-header h1 {
            font-size: 1.5rem;
        }

        .order-card-header {
            flex-direction: column;
        }

        .order-details {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .order-actions {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<main style="margin-top: 100px; margin-bottom: 3rem;">
    <div class="history-header">
        <div class="container">
            <h1>📝 Lịch Sử Đơn Buff</h1>
            <p>Quản lý và theo dõi trạng thái tất cả đơn hàng của bạn</p>
        </div>
    </div>

    <div class="container">
        <!-- Filter -->
        @if($orders->count() > 0)
            <form method="GET" action="{{ route('buff.history') }}" class="filter-section">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Lọc theo trạng thái</label>
                        <select name="status" class="filter-select" onchange="this.form.submit()">
                            <option value="">-- Tất cả đơn --</option>
                            <option value="pending" @selected(request('status') === 'pending')>⏳ Chờ thanh toán</option>
                            <option value="paid" @selected(request('status') === 'paid')>✅ Đã thanh toán</option>
                            <option value="processing" @selected(request('status') === 'processing')>⚙️ Đang buff</option>
                            <option value="completed" @selected(request('status') === 'completed')>🎉 Hoàn thành</option>
                            <option value="cancelled" @selected(request('status') === 'cancelled')>❌ Đã hủy</option>
                        </select>
                    </div>
                </div>
            </form>
        @endif

        @if($orders->count() > 0)
            <div class="order-list">
                @foreach($orders as $order)
                    <div class="order-card">
                        <div class="order-card-header">
                            <div class="order-code-section">
                                <div class="order-code">{{ $order->order_code }}</div>
                                <div class="order-date">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            <span class="status-badge status-{{ $order->status }}">
                                {{ $order->getStatusText() }}
                            </span>
                        </div>

                        <div class="order-details">
                            <div class="detail-item">
                                <span class="detail-label">Dịch Vụ</span>
                                <span class="detail-value">{{ $order->buffService->name }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Server</span>
                                <span class="detail-value">{{ $order->buffServer->name }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Số Lượng</span>
                                <span class="detail-value">{{ number_format($order->quantity) }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Giá Tiền</span>
                                <span class="detail-value price">{{ number_format($order->getPriceToShow(), 0, ',', '.') }}đ</span>
                            </div>
                        </div>

                        <div class="progress-bar">
                            <div class="progress-fill {{ $order->status }}"></div>
                        </div>

                        <div class="order-actions" style="margin-top: 1rem;">
                            <a href="{{ route('buff.detail', $order) }}" class="btn-action btn-view">
                                👁️ Xem Chi Tiết
                            </a>
                            @if($order->status === 'pending')
                                <a href="{{ route('buff.payment', $order) }}" class="btn-action btn-pay">
                                    💰 Thanh Toán
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $orders->links('pagination::bootstrap-4') }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h3>Chưa có đơn hàng</h3>
                <p>Bạn chưa tạo đơn hàng buff nào. Hãy bắt đầu ngay!</p>
                <a href="{{ route('buff.index') }}" class="btn" 
                    style="background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 600;">
                    Chọn Dịch Vụ →
                </a>
            </div>
        @endif
    </div>
</main>
@endsection
