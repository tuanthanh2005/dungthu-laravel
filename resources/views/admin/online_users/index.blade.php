@extends('layouts.admin')

@section('title', 'Khách Hàng Đang Xem Hàng (Live)')

@push('styles')
<style>
    .live-dot-indicator {
        width: 10px;
        height: 10px;
        background-color: #10b981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: liveDotPulse 1.6s infinite ease-in-out;
    }

    @keyframes liveDotPulse {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }
        70% {
            transform: scale(1.15);
            box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
        }
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }

    .stat-card-custom {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        display: flex;
        align-items: center;
        gap: 16px;
        height: 100%;
    }

    .stat-icon-wrapper {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .bg-icon-green { background: rgba(16, 185, 129, 0.12); color: #059669; }
    .bg-icon-blue { background: rgba(59, 130, 246, 0.12); color: #2563eb; }
    .bg-icon-purple { background: rgba(139, 92, 246, 0.12); color: #7c3aed; }
    .bg-icon-orange { background: rgba(249, 115, 22, 0.12); color: #ea580c; }

    .stat-info .stat-value {
        font-size: 1.6rem;
        font-weight: 800;
        color: #111827;
        line-height: 1.2;
    }

    .stat-info .stat-label {
        font-size: 0.82rem;
        color: #6b7280;
        font-weight: 600;
    }

    .user-avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: #ffffff;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .guest-avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #f3f4f6;
        color: #6b7280;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        border: 1px solid #e5e7eb;
        flex-shrink: 0;
    }

    .url-badge {
        max-width: 320px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
        color: #2563eb;
        background: #eff6ff;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
    }

    .url-badge:hover {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .badge-guest {
        background: #f3f4f6;
        color: #4b5563;
        font-weight: 600;
        font-size: 0.72rem;
        padding: 2px 8px;
        border-radius: 6px;
    }

    .badge-member {
        background: #ecfdf5;
        color: #047857;
        font-weight: 700;
        font-size: 0.72rem;
        padding: 2px 8px;
        border-radius: 6px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-3">

    <!-- Header & Auto Refresh Controls -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-bold m-0 d-flex align-items-center gap-2">
                <span class="live-dot-indicator"></span> Khách Hàng Đang Xem Hàng (Live)
            </h4>
            <p class="text-muted small m-0 mt-1">Theo dõi thời gian thực tất cả lượt khách truy cập và thành viên đang xem sản phẩm trên website</p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.location.reload();">
                <i class="fas fa-sync-alt me-1"></i> Làm mới
            </button>

            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-primary active" id="btnAutoRefresh" onclick="toggleAutoRefresh();">
                    <i class="fas fa-play me-1"></i> Tự động làm mới (<span id="refreshTimerCount">5</span>s)
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card-custom">
                <div class="stat-icon-wrapper bg-icon-green">
                    <i class="fas fa-users-line"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value text-success">{{ number_format($totalOnline) }}</div>
                    <div class="stat-label">Tổng khách online real-time</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card-custom">
                <div class="stat-icon-wrapper bg-icon-blue">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value text-primary">{{ number_format($loggedInCount) }}</div>
                    <div class="stat-label">Thành viên đã đăng nhập</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card-custom">
                <div class="stat-icon-wrapper bg-icon-purple">
                    <i class="fas fa-user-secret"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value text-purple" style="color:#7c3aed;">{{ number_format($guestCount) }}</div>
                    <div class="stat-label">Khách vãng lai (Chưa đăng nhập)</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card-custom">
                <div class="stat-icon-wrapper bg-icon-orange">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value text-warning fs-6">
                        @if($topPages->count() > 0)
                            @php
                                $topPath = parse_url($topPages->first()->current_url, PHP_URL_PATH) ?: '/';
                            @endphp
                            {{ Str::limit($topPath, 20) }}
                            <span class="badge bg-warning text-dark ms-1">{{ $topPages->first()->count }} lượt</span>
                        @else
                            Trang chủ
                        @endif
                    </div>
                    <div class="stat-label">Trang được xem nhiều nhất</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.online-users.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Tìm theo tên, email, IP, đường dẫn URL..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Tất cả loại khách --</option>
                        <option value="logged_in" {{ request('type') == 'logged_in' ? 'selected' : '' }}>Thành viên đã đăng nhập</option>
                        <option value="guests" {{ request('type') == 'guests' ? 'selected' : '' }}>Khách chưa đăng nhập (Vãng lai)</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary px-3"><i class="fas fa-filter me-1"></i> Lọc</button>
                    @if(request()->hasAny(['search', 'type']))
                        <a href="{{ route('admin.online-users.index') }}" class="btn btn-sm btn-light border px-3">Xóa lọc</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Active Users Table -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width: 130px;">Trạng thái</th>
                            <th>Người dùng / Khách hàng</th>
                            <th>Địa chỉ IP & Thiết bị</th>
                            <th>Trang đang xem (URL)</th>
                            <th>Tương tác gần nhất</th>
                            <th class="text-end pe-4">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                            @php
                                $diffInSeconds = $session->last_activity->diffInSeconds(now());
                                $isLive = $diffInSeconds <= 300;
                            @endphp
                            <tr>
                                <!-- Status -->
                                <td class="ps-4">
                                    @if($isLive)
                                        <span class="d-inline-flex align-items-center gap-1 text-success fw-bold small">
                                            <span class="live-dot-indicator"></span> Online
                                        </span>
                                    @else
                                        <span class="d-inline-flex align-items-center gap-1 text-muted small">
                                            <i class="fas fa-circle text-secondary" style="font-size: 8px;"></i> Vừa rời
                                        </span>
                                    @endif
                                </td>

                                <!-- User Info -->
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($session->user)
                                            <div class="user-avatar-circle">
                                                {{ strtoupper(substr($session->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark mb-0">
                                                    {{ $session->user->name }}
                                                    <span class="badge-member ms-1">Thành viên</span>
                                                </div>
                                                <div class="small text-muted">{{ $session->user->email }}</div>
                                            </div>
                                        @else
                                            <div class="guest-avatar-circle">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark mb-0">
                                                    Khách vãng lai
                                                    <span class="badge-guest ms-1">Guest</span>
                                                </div>
                                                <div class="small text-muted font-monospace">Session: {{ substr($session->session_id, 0, 12) }}...</div>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- IP & Device -->
                                <td>
                                    <div class="fw-bold font-monospace text-dark small">
                                        <i class="fas fa-network-wired me-1 text-muted"></i>{{ $session->ip_address ?: 'Unknown IP' }}
                                    </div>
                                    <div class="small text-muted mt-1">
                                        @if($session->device_type === 'mobile')
                                            <i class="fas fa-mobile-alt text-primary me-1" title="Điện thoại"></i> Mobile
                                        @elseif($session->device_type === 'tablet')
                                            <i class="fas fa-tablet-alt text-info me-1" title="Máy tính bảng"></i> Tablet
                                        @else
                                            <i class="fas fa-desktop text-secondary me-1" title="Máy tính"></i> Desktop
                                        @endif
                                        <span class="ms-1 text-truncate d-inline-block align-middle" style="max-width: 140px;" title="{{ $session->user_agent }}">
                                            • {{ Str::limit($session->user_agent, 20) }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Current Page URL -->
                                <td>
                                    @php
                                        $parsedPath = parse_url($session->current_url, PHP_URL_PATH) ?: '/';
                                    @endphp
                                    <a href="{{ $session->current_url }}" target="_blank" class="url-badge" title="{{ $session->current_url }}">
                                        <i class="fas fa-external-link-alt me-1 fs-xs"></i>{{ $parsedPath }}
                                    </a>
                                </td>

                                <!-- Last Activity -->
                                <td>
                                    <div class="fw-bold text-dark small">
                                        {{ $session->last_activity->diffForHumans() }}
                                    </div>
                                    <div class="small text-muted">
                                        {{ $session->last_activity->format('H:i:s d/m/Y') }}
                                    </div>
                                </td>

                                <!-- Action -->
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-1 align-items-center">
                                        @if($session->user_id)
                                            <a href="{{ route('admin.users.history', $session->user_id) }}" class="btn btn-sm btn-outline-info" title="Xem lịch sử user">
                                                <i class="fas fa-user-gear me-1"></i> Hồ sơ
                                            </a>
                                        @endif
                                        <a href="{{ $session->current_url }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Mở trang trong tab mới">
                                            <i class="fas fa-external-link-alt me-1"></i> Xem trang
                                        </a>
                                        @php
                                            $kickRoute = \Illuminate\Support\Facades\Route::has('admin.online-users.kick')
                                                ? route('admin.online-users.kick', $session->id)
                                                : (\Illuminate\Support\Facades\Route::has('admin.online-users.delete')
                                                    ? route('admin.online-users.delete', $session->id)
                                                    : '#');
                                        @endphp
                                        <form action="{{ $kickRoute }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn ngắt phiên làm việc của khách hàng này?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-warning" title="Ngắt kết nối phiên">
                                                <i class="fas fa-power-off me-1"></i> Ngắt phiên
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-users-slash fs-2 mb-3 d-block text-secondary opacity-50"></i>
                                    Không có khách hàng nào đang hoạt động trong khoảng thời gian vừa qua.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sessions->hasPages())
                <div class="p-3 border-top">
                    {{ $sessions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let autoRefresh = true;
    let timerSeconds = 5;
    let timerInterval = null;

    function startTimer() {
        if (timerInterval) clearInterval(timerInterval);
        
        timerInterval = setInterval(() => {
            if (!autoRefresh) return;
            
            timerSeconds--;
            document.getElementById('refreshTimerCount').textContent = timerSeconds;
            
            if (timerSeconds <= 0) {
                window.location.reload();
            }
        }, 1000);
    }

    function toggleAutoRefresh() {
        autoRefresh = !autoRefresh;
        const btn = document.getElementById('btnAutoRefresh');
        
        if (autoRefresh) {
            btn.classList.add('active', 'btn-outline-primary');
            btn.classList.remove('btn-outline-secondary');
            btn.innerHTML = '<i class="fas fa-play me-1"></i> Tự động làm mới (<span id="refreshTimerCount">5</span>s)';
            timerSeconds = 5;
            startTimer();
        } else {
            btn.classList.remove('active', 'btn-outline-primary');
            btn.classList.add('btn-outline-secondary');
            btn.innerHTML = '<i class="fas fa-pause me-1"></i> Tự động làm mới (Đã dừng)';
            if (timerInterval) clearInterval(timerInterval);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        startTimer();
    });
</script>
@endpush
