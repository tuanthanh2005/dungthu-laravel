@extends('layouts.admin')

@section('title', 'Quản lý Thời hạn Khách hàng - Admin')
@section('page_title', 'Thời hạn Dịch vụ')

@push('styles')
<style>
    .stat-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .bg-purple-light { background-color: rgba(139, 92, 246, 0.15); color: #7c3aed; }
    .bg-success-light { background-color: rgba(16, 185, 129, 0.15); color: #10b981; }
    .bg-warning-light { background-color: rgba(245, 158, 11, 0.15); color: #f59e0b; }
    .bg-danger-light { background-color: rgba(239, 68, 68, 0.15); color: #ef4444; }
    .fs-7 { font-size: 0.8rem; }

    .admin-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,0,0,0.02);
    }
    .filter-wrapper {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 25px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    
    <!-- Top Action Info -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Thời hạn Gói dịch vụ Khách hàng</h4>
            <p class="text-muted mb-0">Theo dõi trạng thái và thời gian còn lại của gói dịch vụ đã bán.</p>
        </div>
        <a href="{{ route('admin.customer-durations.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus me-2"></i>Cấp Phát Thủ Công
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Cards Summary -->
    <div class="row g-4 mb-4">
        <!-- Tổng giao dịch -->
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-purple-light me-3">
                        <i class="fas fa-history"></i>
                    </div>
                    <div>
                        <div class="value fs-3 fw-bold">{{ $totalCount }}</div>
                        <div class="label text-uppercase text-muted fs-7 fw-semibold">TỔNG GIAO DỊCH</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Đang hoạt động -->
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-success-light me-3">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="value fs-3 fw-bold">{{ $activeCount }}</div>
                        <div class="label text-uppercase text-muted fs-7 fw-semibold">ĐANG HOẠT ĐỘNG</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sắp hết hạn -->
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-warning-light me-3">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <div class="value fs-3 fw-bold">{{ $expiringCount }}</div>
                        <div class="label text-uppercase text-muted fs-7 fw-semibold">SẮP HẾT HẠN (<=3 NGÀY)</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Đã hết hạn -->
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-danger-light me-3">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <div class="value fs-3 fw-bold">{{ $expiredCount }}</div>
                        <div class="label text-uppercase text-muted fs-7 fw-semibold">ĐÃ HẾT HẠN</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main List Card -->
    <div class="admin-card">
        
        <h5 class="fw-bold mb-4">
            <i class="fas fa-list-ul text-primary me-2"></i>Danh Sách Chi Tiết Thời Hạn Đơn Hàng
        </h5>

        <!-- Filter Form -->
        <form action="{{ route('admin.customer-durations') }}" method="GET" class="filter-wrapper">
            <div class="row g-3">
                <div class="col-md-6 col-lg-7">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Tìm tên khách hàng, email, mã đơn, sản phẩm..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 col-lg-3">
                    <select name="status" class="form-select">
                        <option value="">Tất Cả Trạng Thái</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                        <option value="expiring" {{ request('status') === 'expiring' ? 'selected' : '' }}>Sắp hết hạn (<= 3 ngày)</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Đã hết hạn</option>
                    </select>
                </div>
                <div class="col-md-3 col-lg-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill">
                        <i class="fas fa-filter me-2"></i>Tìm kiếm / Lọc
                    </button>
                </div>
            </div>
        </form>

        <!-- Table Grid -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="text-muted fw-semibold">
                        <th style="min-width: 180px;">KHÁCH HÀNG</th>
                        <th>MÃ ĐƠN</th>
                        <th>SẢN PHẨM</th>
                        <th>TỔNG THỜI HẠN</th>
                        <th>NGÀY BẮT ĐẦU</th>
                        <th>NGÀY HẾT HẠN</th>
                        <th>THỜI GIAN CÒN LẠI</th>
                        <th class="text-center" style="width: 140px;">TÁC VỤ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($durations as $duration)
                        <tr>
                            <!-- Khách hàng -->
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark">{{ $duration->customer_name }}</span>
                                    <span class="text-muted fs-7">{{ $duration->customer_email }}</span>
                                    @if($duration->customer_phone)
                                        <span class="text-muted fs-7">{{ $duration->customer_phone }}</span>
                                    @endif
                                </div>
                            </td>
                            <!-- Mã đơn -->
                            <td>
                                @if($duration->order_id)
                                    <a href="{{ route('admin.orders.show', $duration->order_id) }}" class="fw-semibold text-primary text-decoration-none">
                                        <i class="fas fa-shopping-bag me-1" style="font-size: 11px;"></i>{{ $duration->order_code }}
                                    </a>
                                @else
                                    <span class="text-secondary fw-semibold">{{ $duration->order_code ?? 'Thủ công' }}</span>
                                @endif
                            </td>
                            <!-- Sản phẩm -->
                            <td>
                                <span class="fw-semibold text-dark">{{ $duration->product_name }}</span>
                            </td>
                            <!-- Tổng thời hạn -->
                            <td>
                                <span>{{ $duration->total_duration ?? 'Chưa thiết lập' }}</span>
                            </td>
                            <!-- Ngày bắt đầu -->
                            <td>
                                <span class="text-secondary">{{ $duration->start_date ? $duration->start_date->format('d/m/Y H:i') : '-' }}</span>
                            </td>
                            <!-- Ngày hết hạn -->
                            <td>
                                @if($duration->expiry_date)
                                    <span class="text-dark fw-medium">{{ $duration->expiry_date->format('d/m/Y H:i') }}</span>
                                @else
                                    <span class="text-muted italic">Chưa thiết lập</span>
                                @endif
                            </td>
                            <!-- Thời gian còn lại -->
                            <td>
                                @php
                                    $status = $duration->status;
                                    $badgeClass = 'bg-success-light text-success';
                                    if ($status === 'expired') {
                                        $badgeClass = 'bg-danger-light text-danger';
                                    } elseif ($status === 'expiring') {
                                        $badgeClass = 'bg-warning-light text-warning';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill fw-semibold fs-7">
                                    <i class="far fa-clock me-1"></i>{{ $duration->remaining_time }}
                                </span>
                            </td>
                            <!-- Tác vụ -->
                            <td class="text-center">
                                <div class="btn-group">
                                    @if($duration->order_id)
                                        <a href="{{ route('admin.orders.show', $duration->order_id) }}" class="btn btn-sm btn-outline-info" title="Xem chi tiết đơn hàng">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.customer-durations.edit', $duration->id) }}" class="btn btn-sm btn-outline-primary" title="Chỉnh sửa thời hạn">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.customer-durations.destroy', $duration->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bản ghi thời hạn này không?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="far fa-folder-open mb-3 fs-2 text-secondary"></i>
                                    <span>Không có dữ liệu thời hạn tài khoản</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $durations->appends(request()->input())->links() }}
        </div>

    </div>
</div>
@endsection
