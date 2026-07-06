@extends('layouts.admin')

@section('title', 'Tổng quan Admin')
@section('page_title', 'Dashboard')

@push('styles')
<style>
    /* Premium Stats Deck */
    .stat-card-premium {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(0, 0, 0, 0.03);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .stat-card-premium:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    }

    .stat-icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 20px;
        transition: all 0.3s;
    }

    .stat-card-premium:hover .stat-icon-wrapper {
        transform: scale(1.1) rotate(5deg);
    }

    .stat-card-premium.products .stat-icon-wrapper {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
    }
    .stat-card-premium.orders .stat-icon-wrapper {
        background: rgba(245, 87, 108, 0.1);
        color: #f5576c;
    }
    .stat-card-premium.users .stat-icon-wrapper {
        background: rgba(79, 172, 254, 0.1);
        color: #4facfe;
    }
    .stat-card-premium.blogs .stat-icon-wrapper {
        background: rgba(67, 233, 123, 0.1);
        color: #2ecc71;
    }

    .stat-card-premium .value {
        font-size: 32px;
        font-weight: 800;
        color: #1a1a2e;
        margin-bottom: 4px;
        letter-spacing: -0.5px;
    }

    .stat-card-premium .label {
        font-size: 13.5px;
        font-weight: 600;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-card-premium .sub-badge {
        position: absolute;
        top: 24px;
        right: 24px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 700;
        border-radius: 20px;
    }

    /* Revenue & Chart Section */
    .revenue-card-premium {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(0, 0, 0, 0.03);
        position: relative;
        min-height: 400px;
    }

    .revenue-locked-overlay-new {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        z-index: 100;
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .revenue-locked-overlay-new.unlocked {
        opacity: 0;
        pointer-events: none;
        transform: scale(0.95);
    }

    .lock-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(102, 126, 234, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: #667eea;
        margin-bottom: 20px;
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.15);
        animation: pulseLock 2s infinite;
    }

    @keyframes pulseLock {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.4); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(102, 126, 234, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(102, 126, 234, 0); }
    }

    /* Quick Actions */
    .action-grid-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 16px;
        border: 1px solid rgba(0,0,0,0.04);
        display: flex;
        align-items: center;
        gap: 16px;
        text-decoration: none;
        color: #374151;
        transition: all 0.25s;
    }

    .action-grid-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        border-color: #667eea;
        color: #667eea;
    }

    .action-grid-card .icon-box {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        background: rgba(102,126,234,0.08);
        color: #667eea;
        flex-shrink: 0;
    }

    /* Pending Tasks / Notification Center */
    .pending-feed {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .pending-feed-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 14px 0;
        border-bottom: 1px solid #f0f2f5;
    }

    .pending-feed-item:last-child {
        border-bottom: none;
    }

    .pending-feed-item .icon-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }

    /* Responsive adjustments */
    .chart-container {
        position: relative;
        height: 280px;
        width: 100%;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <!-- Section Title -->
    <div class="admin-page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Dashboard</h1>
            <p>Xin chào, {{ auth()->user()->name ?? 'Admin' }}. Dưới đây là thống kê vận hành hệ thống.</p>
        </div>
        <div>
            <button class="btn btn-outline-secondary btn-sm" onclick="location.reload()">
                <i class="fas fa-sync-alt me-1"></i> Làm mới dữ liệu
            </button>
        </div>
    </div>

    <!-- Stats summary deck -->
    <div class="row mb-4">
        <!-- Products -->
        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="stat-card-premium products">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-box"></i>
                </div>
                <div class="value">{{ number_format($stats['products']) }}</div>
                <div class="label">Sản phẩm</div>
                <span class="sub-badge bg-primary-subtle text-primary">Active</span>
            </div>
        </div>

        <!-- Orders -->
        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="stat-card-premium orders">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="value">{{ number_format($stats['orders']) }}</div>
                <div class="label">Đơn hàng</div>
                @if(isset($pendingOrdersCount) && $pendingOrdersCount > 0)
                    <span class="sub-badge bg-warning text-dark">{{ $pendingOrdersCount }} Chờ xử lý</span>
                @else
                    <span class="sub-badge bg-success-subtle text-success">Hoàn tất</span>
                @endif
            </div>
        </div>

        <!-- Users -->
        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="stat-card-premium users">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-users"></i>
                </div>
                <div class="value">{{ number_format($stats['users']) }}</div>
                <div class="label">Người dùng</div>
                @if(isset($pendingAffCount) && $pendingAffCount > 0)
                    <span class="sub-badge bg-info-subtle text-info">{{ $pendingAffCount }} CTV mới</span>
                @else
                    <span class="sub-badge bg-secondary-subtle text-muted">Thành viên</span>
                @endif
            </div>
        </div>

        <!-- Blogs -->
        <div class="col-xl-3 col-lg-6 mb-4">
            <div class="stat-card-premium blogs">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-blog"></i>
                </div>
                <div class="value">{{ number_format($stats['blogs']) }}</div>
                <div class="label">Bài viết</div>
                <span class="sub-badge bg-success-subtle text-success">Blogs</span>
            </div>
        </div>
    </div>

    <!-- Main Chart & Tasks Grid -->
    <div class="row mb-4">
        <!-- Revenue Line Chart -->
        <div class="col-lg-8 mb-4">
            <div class="revenue-card-premium">
                <!-- Locked Overlay -->
                <div id="revenue-overlay" class="revenue-locked-overlay-new">
                    <div class="lock-circle">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Thông tin tài chính</h5>
                    <p class="text-muted text-center mb-4" style="max-width: 320px;">Vui lòng nhập mã bảo mật để xem báo cáo doanh thu và biểu đồ tăng trưởng.</p>
                    <div class="input-group" style="max-width: 280px; box-shadow: 0 4px 12px rgba(102,126,234,0.15); border-radius: 30px; overflow: hidden;">
                        <input type="password" id="revenue-pass" class="form-control border-0 px-3" placeholder="Mã bảo mật (PIN)..." style="height: 46px;">
                        <button class="btn btn-primary border-0 px-4" onclick="checkRevenuePass()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-key"></i>
                        </button>
                    </div>
                </div>

                <!-- Unlocked Chart Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div>
                        <h4 class="admin-card-title mb-1">
                            <i class="fas fa-chart-line text-primary me-2"></i>Doanh thu & Xu hướng bán hàng
                        </h4>
                        <p class="text-muted mb-0" style="font-size:12.5px;">Tổng tiền thực thu từ các đơn hàng thành công</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="text-end">
                            <span class="text-muted d-block" style="font-size: 11px; text-transform: uppercase;">Doanh thu lọc</span>
                            <span class="fw-bold text-primary" style="font-size: 18px;"><span id="revenue-val">{{ number_format($revenue30Days, 0, ',', '.') }}</span>đ</span>
                        </div>
                        <div id="revenue-filter-box" class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm filter-btn active" onclick="filterRevenue(30, this)">30 Ngày</button>
                            <button type="button" class="btn btn-outline-primary btn-sm filter-btn" onclick="filterRevenue(10, this)">10 Ngày</button>
                            <button type="button" class="btn btn-outline-primary btn-sm filter-btn" onclick="filterRevenue(5, this)">5 Ngày</button>
                            <button type="button" class="btn btn-outline-primary btn-sm filter-btn" onclick="filterRevenue(60, this)">60 Ngày</button>
                            <button type="button" class="btn btn-outline-primary btn-sm filter-btn" onclick="filterRevenue(90, this)">90 Ngày</button>
                        </div>
                    </div>
                </div>

                <!-- Chart Canvas -->
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Pending Operations / Tasks Center -->
        <div class="col-lg-4 mb-4">
            <div class="admin-card" style="height: 100%; display: flex; flex-direction: column;">
                <div class="admin-card-header mb-3">
                    <h4 class="admin-card-title">
                        <i class="fas fa-bell text-danger me-2"></i>Yêu cầu cần xử lý
                    </h4>
                    <span class="badge bg-danger-subtle text-danger rounded-pill px-2">Live</span>
                </div>
                <div style="flex: 1;">
                    <ul class="pending-feed">
                        <!-- Chat messages -->
                        <li class="pending-feed-item">
                            <div class="icon-circle bg-primary-subtle text-primary">
                                <i class="fas fa-comments"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-semibold" style="font-size:14px;">Tin nhắn khách hàng</h6>
                                <p class="text-muted mb-0" style="font-size:12px;">Đang chờ admin trả lời</p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-danger rounded-pill" id="unread-chat-badge">{{ $unreadChatCount ?? 0 }}</span>
                            </div>
                        </li>

                        <!-- Pending Orders -->
                        <li class="pending-feed-item">
                            <div class="icon-circle bg-warning-subtle text-warning">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-semibold" style="font-size:14px;">Đơn hàng chờ duyệt</h6>
                                <p class="text-muted mb-0" style="font-size:12px;">Cần xác nhận giao hàng</p>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('admin.orders') }}" class="badge bg-warning text-dark rounded-pill text-decoration-none">
                                    {{ $pendingOrdersCount ?? 0 }} duyệt
                                </a>
                            </div>
                        </li>

                        <!-- Card Exchanges -->
                        <li class="pending-feed-item">
                            <div class="icon-circle bg-info-subtle text-info">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-semibold" style="font-size:14px;">Đổi thẻ cào chờ duyệt</h6>
                                <p class="text-muted mb-0" style="font-size:12px;">Yêu cầu rút tiền/đổi thẻ</p>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('admin.card-exchanges') }}" class="badge bg-info text-white rounded-pill text-decoration-none">
                                    {{ $pendingCardExchangeCount ?? 0 }} thẻ
                                </a>
                            </div>
                        </li>

                        <!-- Abandoned Carts -->
                        <li class="pending-feed-item">
                            <div class="icon-circle bg-secondary-subtle text-muted">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-semibold" style="font-size:14px;">Giỏ hàng bị bỏ quên</h6>
                                <p class="text-muted mb-0" style="font-size:12px;">Khách chưa hoàn tất thanh toán</p>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('admin.abandoned-carts') }}" class="badge bg-secondary text-dark rounded-pill text-decoration-none">
                                    {{ $abandonedCartsCount ?? 0 }} giỏ
                                </a>
                            </div>
                        </li>

                        <!-- Affiliate Invoices -->
                        <li class="pending-feed-item">
                            <div class="icon-circle bg-success-subtle text-success">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-semibold" style="font-size:14px;">Rút tiền CTV chờ duyệt</h6>
                                <p class="text-muted mb-0" style="font-size:12px;">Yêu cầu thanh toán hoa hồng</p>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('admin.affiliates.index') }}" class="badge bg-success text-white rounded-pill text-decoration-none">
                                    {{ $pendingAffWithdrawCount ?? 0 }} duyệt
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Deck -->
    <div class="admin-card mb-4">
        <h4 class="admin-card-title mb-4">
            <i class="fas fa-bolt text-warning me-2"></i>Thao tác nhanh hệ thống
        </h4>
        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <a href="javascript:void(0)" data-url="{{ route('admin.products.create') }}" class="action-grid-card protected-link">
                    <div class="icon-box">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="font-size:14px;">Thêm sản phẩm</div>
                        <small class="text-muted" style="font-size:11px;">Đăng bán mặt hàng mới</small>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="javascript:void(0)" data-url="{{ route('admin.products') }}" class="action-grid-card protected-link">
                    <div class="icon-box">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="font-size:14px;">Quản lý kho hàng</div>
                        <small class="text-muted" style="font-size:11px;">Sửa giá, số lượng</small>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('admin.preorders') }}" class="action-grid-card">
                    <div class="icon-box">
                        <i class="fas fa-hourglass"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="font-size:14px;">Xem Pre-orders</div>
                        <small class="text-muted" style="font-size:11px;">Người dùng chờ hàng</small>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('admin.menu-settings') }}" class="action-grid-card">
                    <div class="icon-box">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="font-size:14px;">Cài đặt Menu</div>
                        <small class="text-muted" style="font-size:11px;">Tùy chỉnh danh mục</small>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Latest Orders Table -->
    @if(isset($latestOrders) && $latestOrders->count() > 0)
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header mb-4">
            <h4 class="admin-card-title">
                <i class="fas fa-shopping-bag text-primary me-2"></i>Đơn hàng mới nhận
            </h4>
            <a href="{{ route('admin.orders') }}" class="btn btn-link btn-sm text-decoration-none">Xem toàn bộ</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle admin-table">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Thời gian</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($latestOrders as $order)
                    <tr>
                        <td>
                            <strong>#{{ $order->id }}</strong>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle" style="width:28px; height:28px; border-radius:50%; background: #e0e6ed; color:#475569; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700;">
                                    {{ strtoupper(substr($order->user->name ?? 'K', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $order->user->name ?? 'Khách vãng lai' }}</div>
                                    <small class="text-muted" style="font-size:11px;">{{ $order->user->email ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-size:13px;">{{ $order->created_at->format('d/m/Y') }}</div>
                            <small class="text-muted" style="font-size:11px;">{{ $order->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <strong class="text-primary">{{ number_format($order->total_amount, 0, ',', '.') }}đ</strong>
                        </td>
                        <td>
                            @if($order->status === 'pending')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1" style="border-radius:12px;">Chờ xử lý</span>
                            @elseif($order->status === 'completed')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="border-radius:12px;">Hoàn thành</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1" style="border-radius:12px;">Đã hủy</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3" style="font-size:12px;">Chi tiết</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="admin-card text-center py-5" data-aos="fade-up">
        <div class="mb-3">
            <i class="fas fa-shopping-cart fa-3x text-muted opacity-40"></i>
        </div>
        <h5 class="text-muted fw-semibold">Chưa có đơn hàng nào</h5>
        <p class="text-muted" style="font-size:13px;">Hệ thống chưa ghi nhận đơn hàng phát sinh mới.</p>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<!-- Load Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const revenues = {
        5: '{{ number_format($revenue5Days, 0, ",", ".") }}',
        10: '{{ number_format($revenue10Days, 0, ",", ".") }}',
        30: '{{ number_format($revenue30Days, 0, ",", ".") }}',
        60: '{{ number_format($revenue60Days, 0, ",", ".") }}',
        90: '{{ number_format($revenue90Days, 0, ",", ".") }}'
    };

    const chartData = {
        5: {
            labels: @json($chart5Days['labels']),
            values: @json($chart5Days['values'])
        },
        10: {
            labels: @json($chart10Days['labels']),
            values: @json($chart10Days['values'])
        },
        30: {
            labels: @json($chart30Days['labels']),
            values: @json($chart30Days['values'])
        },
        60: {
            labels: @json($chart60Days['labels']),
            values: @json($chart60Days['values'])
        },
        90: {
            labels: @json($chart90Days['labels']),
            values: @json($chart90Days['values'])
        }
    };

    let salesChart = null;
    let currentDays = 30; // default

    function initChart() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Gradient fill
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(102, 126, 234, 0.4)');
        gradient.addColorStop(1, 'rgba(102, 126, 234, 0.01)');

        salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData[currentDays].labels,
                datasets: [{
                    label: 'Doanh thu (đ)',
                    data: chartData[currentDays].values,
                    backgroundColor: gradient,
                    borderColor: '#667eea',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#667eea',
                    pointBorderWidth: 2,
                    pointRadius: currentDays > 30 ? 2 : 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], color: '#f0f2f5' },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return (value / 1000000).toFixed(1) + 'M';
                                } else if (value >= 1000) {
                                    return (value / 1000).toFixed(0) + 'k';
                                }
                                return value;
                            },
                            font: { size: 10 }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { 
                            font: { size: 10 },
                            maxRotation: 45,
                            minRotation: 0,
                            callback: function(val, index) {
                                const labels = chartData[currentDays].labels;
                                if (currentDays === 90) {
                                    return index % 10 === 0 ? labels[index] : '';
                                } else if (currentDays === 60) {
                                    return index % 5 === 0 ? labels[index] : '';
                                } else if (currentDays === 30) {
                                    return index % 3 === 0 ? labels[index] : '';
                                }
                                return labels[index];
                            }
                        }
                    }
                }
            }
        });
    }

    function checkRevenuePass() {
        const pass = document.getElementById('revenue-pass').value;
        if (pass === '113') {
            unlockEverything();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi bảo mật',
                text: 'Mật khẩu PIN không chính xác!',
                confirmButtonColor: '#e74c3c'
            });
            document.getElementById('revenue-pass').value = '';
        }
    }

    function unlockEverything() {
        document.getElementById('revenue-overlay').classList.add('unlocked');
        sessionStorage.setItem('revenue_unlocked', 'true');
        sessionStorage.setItem('admin_unlocked', 'true');
        
        // Render chart when unlocked
        setTimeout(initChart, 200);
    }

    // Protection for other links
    document.querySelectorAll('.protected-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-url');
            
            if (sessionStorage.getItem('admin_unlocked') === 'true') {
                window.location.href = url;
            } else {
                Swal.fire({
                    title: 'Xác thực tài chính',
                    text: 'Khu vực bảo mật. Vui lòng nhập mã PIN bảo mật:',
                    input: 'password',
                    inputAttributes: { maxlength: 3, pattern: '[0-9]{3}' },
                    showCancelButton: true,
                    confirmButtonText: 'Xác thực',
                    cancelButtonText: 'Hủy',
                    confirmButtonColor: '#667eea',
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (result.value === '113') {
                            unlockEverything();
                            window.location.href = url;
                        } else {
                            Swal.fire({ icon: 'error', title: 'Lỗi', text: 'PIN không chính xác.' });
                        }
                    }
                });
            }
        });
    });

    // Enter to submit password
    document.getElementById('revenue-pass').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            checkRevenuePass();
        }
    });

    function filterRevenue(days, btn) {
        currentDays = days;

        // Update display value
        document.getElementById('revenue-val').innerText = revenues[days];
        
        // Update active button classes
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // Update chart dynamically
        if (salesChart) {
            salesChart.data.labels = chartData[days].labels;
            salesChart.data.datasets[0].data = chartData[days].values;
            salesChart.data.datasets[0].pointRadius = days > 30 ? 2 : 4;
            salesChart.update();
        }
    }

    // Check if previously unlocked
    if (sessionStorage.getItem('revenue_unlocked') === 'true' || sessionStorage.getItem('admin_unlocked') === 'true') {
        document.getElementById('revenue-overlay').classList.add('unlocked');
        setTimeout(initChart, 100);
    }
</script>
@endpush
