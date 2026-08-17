@extends('layouts.admin')

@section('title', 'Nhật ký IP Nghi ngờ & Bảo mật')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">
                <i class="fas fa-shield-virus text-danger me-2"></i>Nhật ký IP Nghi ngờ & Bảo mật
            </h1>
            <p class="text-muted small mb-0">Quản lý các địa chỉ IP phát sinh Cảnh báo đỏ (Tấn công SQLi/XSS, spam > 10 session...) và điều chỉnh trạng thái khóa/mở khóa.</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Thống kê Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-bold">TỔNG NHẬT KÝ ĐÃ GHI</div>
                        <div class="h4 mb-0 fw-bold text-dark">{{ number_format($totalLogs) }}</div>
                    </div>
                    <div class="rounded-circle bg-light p-3 text-primary">
                        <i class="fas fa-history fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white border-start border-warning border-4">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-warning small fw-bold">TỰ ĐỘNG KHÓA 24H</div>
                        <div class="h4 mb-0 fw-bold text-warning">{{ number_format($autoBannedCount) }}</div>
                    </div>
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 text-warning">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white border-start border-danger border-4">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-danger small fw-bold">ĐÃ KHÓA VĨNH VIỄN</div>
                        <div class="h4 mb-0 fw-bold text-danger">{{ number_format($permanentlyBannedCount) }}</div>
                    </div>
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3 text-danger">
                        <i class="fas fa-user-lock fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white border-start border-success border-4">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-success small fw-bold">ĐÃ XÁC NHẬN AN TOÀN</div>
                        <div class="h4 mb-0 fw-bold text-success">{{ number_format($safeCount) }}</div>
                    </div>
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success">
                        <i class="fas fa-check-shield fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Table Card -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <!-- Status Tabs -->
                <ul class="nav nav-pills card-header-pills me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.suspicious-ips.index') }}">Tất cả ({{ $totalLogs }})</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') === 'auto_banned_24h' ? 'active bg-warning text-dark' : '' }}" href="{{ route('admin.suspicious-ips.index', ['status' => 'auto_banned_24h']) }}">Đã khóa 24h ({{ $autoBannedCount }})</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') === 'permanently_banned' ? 'active bg-danger text-white' : '' }}" href="{{ route('admin.suspicious-ips.index', ['status' => 'permanently_banned']) }}">Khóa vĩnh viễn ({{ $permanentlyBannedCount }})</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') === 'safe' ? 'active bg-success text-white' : '' }}" href="{{ route('admin.suspicious-ips.index', ['status' => 'safe']) }}">Đã xác nhận an toàn ({{ $safeCount }})</a>
                    </li>
                </ul>

                <!-- Search -->
                <form action="{{ route('admin.suspicious-ips.index') }}" method="GET" class="d-flex gap-2">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" 
                               name="search" 
                               class="form-field form-field-sm" 
                               placeholder="Tìm IP, lý do, URL..." 
                               value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('admin.suspicious-ips.index', ['status' => request('status')]) }}" class="btn btn-sm btn-light border" title="Xóa lọc">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 50px;">#</th>
                            <th>Địa chỉ IP</th>
                            <th>Lý do Cảnh báo đỏ</th>
                            <th>URL & User-Agent</th>
                            <th>Trạng thái hiện tại</th>
                            <th>Phát hiện lúc</th>
                            <th class="text-end pe-3">Thao tác Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $item)
                            <tr>
                                <td class="ps-3 text-muted">{{ $logs->firstItem() + $index }}</td>
                                <td>
                                    <span class="font-monospace fw-bold text-dark fs-6">
                                        <i class="fas fa-network-wired text-muted me-1 small"></i>{{ $item->ip_address }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-danger fw-bold small">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $item->reason }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small text-truncate" style="max-width: 260px;" title="{{ $item->url }}">
                                        <i class="fas fa-link me-1 text-muted"></i><code>{{ $item->url ?: 'N/A' }}</code>
                                    </div>
                                    <div class="small text-muted text-truncate" style="max-width: 260px;" title="{{ $item->user_agent }}">
                                        <i class="fas fa-laptop me-1"></i>{{ $item->user_agent ?: 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    @if($item->status === 'safe')
                                        <span class="badge bg-success px-2 py-1">
                                            <i class="fas fa-check-circle me-1"></i>Đã xác nhận An toàn
                                        </span>
                                    @elseif($item->status === 'permanently_banned')
                                        <span class="badge bg-danger px-2 py-1">
                                            <i class="fas fa-lock me-1"></i>Khóa vĩnh viễn
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark px-2 py-1" title="{{ $item->banned_until ? 'Hết hạn ' . $item->banned_until->diffForHumans() : '' }}">
                                            <i class="fas fa-clock me-1"></i>Tự động khóa 24h
                                        </span>
                                    @endif
                                </td>
                                <td class="small text-muted">
                                    <div>{{ $item->created_at->format('H:i:s d/m/Y') }}</div>
                                    <div class="text-muted small">{{ $item->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <!-- Form 🟢 Xác nhận An toàn (Gỡ khóa) -->
                                        @if($item->status !== 'safe')
                                            <form action="{{ route('admin.suspicious-ips.update-status', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="safe">
                                                <button type="submit" class="btn btn-outline-success btn-sm px-2" title="Xác nhận IP này an toàn (Mở khóa lập tức)">
                                                    <i class="fas fa-check-circle me-1"></i>An toàn
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Form 🔒 Chuyển sang Khóa Vĩnh Viễn -->
                                        @if($item->status !== 'permanently_banned')
                                            <form action="{{ route('admin.suspicious-ips.update-status', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="permanently_banned">
                                                <button type="submit" class="btn btn-outline-danger btn-sm px-2" title="Khóa IP vĩnh viễn">
                                                    <i class="fas fa-lock me-1"></i>Khóa vĩnh viễn
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Form ⏱️ Gia hạn Khóa 24h -->
                                        @if($item->status === 'safe')
                                            <form action="{{ route('admin.suspicious-ips.update-status', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="24h_banned">
                                                <button type="submit" class="btn btn-outline-warning text-dark btn-sm px-2" title="Khóa lại 24 giờ">
                                                    <i class="fas fa-clock me-1"></i>Khóa 24h
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Delete Log -->
                                        <form action="{{ route('admin.suspicious-ips.destroy', $item->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Xóa nhật ký này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-secondary btn-sm px-2" title="Xóa nhật ký">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-shield-alt fa-2x mb-2 d-block text-secondary"></i>
                                    Chưa có nhật ký IP nghi ngờ nào được ghi nhận.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($logs->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
