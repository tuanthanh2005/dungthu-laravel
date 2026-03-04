@extends('layouts.app')

@section('title', 'Chi Tiết Đơn Buff - DungThu.com')

@push('styles')
<style>
    .detail-header {
        background: white;
        border-bottom: 2px solid #e0e0e0;
        padding: 2rem 0;
        margin-bottom: 2rem;
    }

    .detail-header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .detail-header h1 {
        font-size: 1.8rem;
        font-weight: 800;
        color: #2d3436;
    }

    .status-badge {
        display: inline-block;
        padding: 0.6rem 1.2rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.9rem;
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

    .section-box {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .section-box h3 {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .info-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-row:last-child {
        margin-bottom: 0;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 0.85rem;
        color: #999;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        font-weight: 600;
    }

    .info-value {
        font-size: 1rem;
        color: #2d3436;
        font-weight: 600;
        word-break: break-all;
    }

    .info-value.price {
        color: #dc3545;
        font-size: 1.3rem;
    }

    .timeline {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .timeline-item {
        display: flex;
        gap: 1.5rem;
        position: relative;
        padding-left: 0;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 30px;
        bottom: -50px;
        width: 2px;
        background: #e0e0e0;
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-marker {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #6c5ce7;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        flex-shrink: 0;
        z-index: 1;
        position: relative;
    }

    .timeline-item.completed .timeline-marker {
        background: #28a745;
    }

    .timeline-item.pending .timeline-marker {
        background: #ffc107;
    }

    .timeline-content {
        flex: 1;
        padding-top: 2px;
    }

    .timeline-title {
        font-weight: 700;
        color: #2d3436;
        margin-bottom: 0.25rem;
    }

    .timeline-time {
        font-size: 0.85rem;
        color: #999;
    }

    .timeline-text {
        font-size: 0.95rem;
        color: #555;
        margin-top: 0.5rem;
    }

    .price-breakdown {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }

    .price-row.total {
        border-top: 2px solid #ddd;
        padding-top: 0.75rem;
        margin-top: 0.75rem;
        font-size: 1.2rem;
        font-weight: 800;
        color: #dc3545;
    }

    .admin-notes {
        background: #fff8e1;
        border-left: 4px solid #ffc107;
        padding: 1rem;
        border-radius: 6px;
        margin-top: 1rem;
        font-size: 0.95rem;
        color: #664d03;
    }

    .btn-group {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 1.5rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-block;
    }

    .btn-primary {
        background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #5f4ec7 0%, #9080d8 100%);
    }

    .btn-secondary {
        background: #e0e0e0;
        color: #2d3436;
    }

    .btn-secondary:hover {
        background: #d0d0d0;
    }

    .warning-box {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #842029;
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
    }

    .emotion-display {
        font-size: 2rem;
        margin: 0.5rem 0;
    }

    @media (max-width: 768px) {
        .detail-header-top {
            flex-direction: column;
            align-items: flex-start;
        }

        .detail-header h1 {
            font-size: 1.3rem;
        }

        .info-row {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .btn-group {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<main style="margin-top: 100px; margin-bottom: 3rem;">
    <div class="detail-header">
        <div class="container">
            <div class="detail-header-top">
                <div>
                    <h1>{{ $buffOrder->order_code }}</h1>
                    <p class="text-muted" style="margin-top: 0.5rem;">
                        Tạo lúc: {{ $buffOrder->created_at->format('d/m/Y H:i:s') }}
                    </p>
                </div>
                <span class="status-badge status-{{ $buffOrder->status }}">
                    {{ $buffOrder->getStatusText() }}
                </span>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!-- Service Details -->
                <div class="section-box">
                    <h3>📦 Chi Tiết Dịch Vụ</h3>
                    <div class="info-row">
                        <div class="info-item">
                            <span class="info-label">Dịch Vụ</span>
                            <span class="info-value">{{ $buffOrder->buffService->name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Nền Tảng</span>
                            <span class="info-value">{{ ucfirst($buffOrder->buffService->platform) }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Loại</span>
                            <span class="info-value">{{ ucfirst($buffOrder->buffService->service_type) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Social Account Details -->
                <div class="section-box">
                    <h3>👤 Thông Tin Tài Khoản</h3>
                    <div class="info-row">
                        <div class="info-item">
                            <span class="info-label">Liên Kết</span>
                            <span class="info-value">
                                <a href="{{ $buffOrder->social_link }}" target="_blank" rel="noopener noreferrer">
                                    {{ substr($buffOrder->social_link, 0, 50) }}...
                                </a>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Số Lượng</span>
                            <span class="info-value">{{ number_format($buffOrder->quantity) }}</span>
                        </div>
                    </div>

                    @if($buffOrder->emotion_type)
                        <div class="info-row">
                            <div class="info-item">
                                <span class="info-label">Loại Cảm Xúc</span>
                                <div class="emotion-display">
                                    @switch($buffOrder->emotion_type)
                                        @case('like') 👍 @break
                                        @case('love') ❤️ @break
                                        @case('haha') 😂 @break
                                        @case('wow') 😮 @break
                                        @case('sad') 😢 @break
                                        @case('angry') 😠 @break
                                        @default 👍
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($buffOrder->notes)
                        <div class="info-row">
                            <div class="info-item">
                                <span class="info-label">Ghi Chú</span>
                                <span class="info-value">{{ $buffOrder->notes }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Price Details -->
                <div class="section-box">
                    <h3>💰 Chi Tiết Giá Tiền</h3>
                    <div class="price-breakdown">
                        <div class="price-row">
                            <span>Giá cơ bản:</span>
                            <span>{{ number_format($buffOrder->base_price, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="price-row">
                            <span>Giá/{{ strtolower($buffOrder->buffService->service_type) }}:</span>
                            <span>{{ number_format($buffOrder->unit_price, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="price-row">
                            <span>Số {{ strtolower($buffOrder->buffService->service_type) }} x {{ number_format($buffOrder->quantity) }}:</span>
                            <span>{{ number_format($buffOrder->unit_price * $buffOrder->quantity, 0, ',', '.') }}đ</span>
                        </div>

                        @if($buffOrder->actual_price && $buffOrder->actual_price != $buffOrder->total_price)
                            <div class="price-row" style="color: #dc3545; font-weight: 700;">
                                <span>Giá gốc:</span>
                                <span>{{ number_format($buffOrder->total_price, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="price-row" style="color: #28a745; font-weight: 700;">
                                <span>Giá đã điều chỉnh:</span>
                                <span>-{{ number_format($buffOrder->total_price - $buffOrder->actual_price, 0, ',', '.') }}đ</span>
                            </div>
                        @endif

                        <div class="price-row total">
                            <span>TỔNG CỘNG:</span>
                            <span>{{ number_format($buffOrder->getPriceToShow(), 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="section-box">
                    <h3>⏱️ Lịch Sử Đơn Hàng</h3>
                    <div class="timeline">
                        <!-- Created -->
                        <div class="timeline-item completed">
                            <div class="timeline-marker">✓</div>
                            <div class="timeline-content">
                                <div class="timeline-title">Đơn Hàng Được Tạo</div>
                                <div class="timeline-time">{{ $buffOrder->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>

                        <!-- Paid -->
                        @if($buffOrder->paid_at)
                            <div class="timeline-item completed">
                                <div class="timeline-marker">✓</div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Thanh Toán Đã Được Xác Nhận</div>
                                    <div class="timeline-time">{{ $buffOrder->paid_at->format('d/m/Y H:i') }}</div>
                                    <div class="timeline-text">
                                        Phương thức: <strong>{{ ucfirst(str_replace('_', ' ', $buffOrder->payment_method)) }}</strong>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="timeline-item pending">
                                <div class="timeline-marker">⏳</div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Chờ Thanh Toán</div>
                                    <div class="timeline-text">Bạn cần hoàn tất thanh toán để bắt đầu buff</div>
                                </div>
                            </div>
                        @endif

                        <!-- Processing -->
                        @if($buffOrder->started_at)
                            <div class="timeline-item completed">
                                <div class="timeline-marker">⚙️</div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Bắt Đầu Buff</div>
                                    <div class="timeline-time">{{ $buffOrder->started_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        @elseif($buffOrder->status === 'processing')
                            <div class="timeline-item">
                                <div class="timeline-marker">⚙️</div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Đang Buff</div>
                                    <div class="timeline-text">Dịch vụ đang được xử lý...</div>
                                </div>
                            </div>
                        @endif

                        <!-- Completed -->
                        @if($buffOrder->completed_at)
                            <div class="timeline-item completed">
                                <div class="timeline-marker">✓</div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Hoàn Thành</div>
                                    <div class="timeline-time">{{ $buffOrder->completed_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Admin Notes -->
                @if($buffOrder->admin_notes)
                    <div class="admin-notes">
                        <strong>📋 Ghi Chú Từ Admin:</strong><br>
                        {{ $buffOrder->admin_notes }}
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Status -->
                <div class="section-box">
                    <h3>ℹ️ Thông Tin Server</h3>
                    <div class="info-row">
                        <div class="info-item">
                            <span class="info-label">Server</span>
                            <span class="info-value">{{ $buffOrder->buffServer->name }}</span>
                        </div>
                    </div>
                    @if($buffOrder->buffServer->description)
                        <div class="info-row">
                            <div class="info-item">
                                <span class="info-label">Mô Tả</span>
                                <span class="info-value">{{ $buffOrder->buffServer->description }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="section-box">
                    <h3>⚡ Thao Tác</h3>
                    <div class="btn-group">
                        @if($buffOrder->status === 'pending')
                            <a href="{{ route('buff.payment', $buffOrder) }}" class="btn btn-primary">
                                💰 Thanh Toán Ngay
                            </a>
                        @endif
                        <a href="{{ route('buff.history') }}" class="btn btn-secondary">
                            ← Quay Lại
                        </a>
                    </div>
                </div>

                <!-- Support -->
                <div class="section-box">
                    <h3>🆘 Hỗ Trợ</h3>
                    <p style="margin-bottom: 1rem; font-size: 0.95rem;">
                        Nếu bạn có bất kỳ câu hỏi nào về đơn hàng này, vui lòng liên hệ với chúng tôi qua:
                    </p>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.9rem;">
                        @if($supportInfo['live_chat'] && $supportInfo['live_chat'] !== '#')
                            <a href="{{ $supportInfo['live_chat'] }}" class="text-decoration-none" target="_blank">
                                💬 Live Chat
                            </a>
                        @else
                            <span>💬 Live Chat (Không khả dụng)</span>
                        @endif
                        
                        <a href="mailto:{{ $supportInfo['email'] }}" class="text-decoration-none">
                            📧 Email: {{ $supportInfo['email'] }}
                        </a>
                        
                        <a href="https://zalo.me/{{ str_replace([' ', '-', '(', ')'], '', $supportInfo['zalo']) }}" class="text-decoration-none" target="_blank">
                            📱 Zalo: {{ $supportInfo['zalo'] }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
