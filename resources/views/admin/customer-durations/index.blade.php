@extends('layouts.admin')

@section('title', 'Quản lý Thời hạn Khách hàng - Admin')
@section('page_title', 'Thời hạn Dịch vụ')

@push('styles')
<style>
    .stat-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }
    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .bg-purple-light { background-color: rgba(139, 92, 246, 0.15); color: #7c3aed; }
    .bg-success-light { background-color: rgba(16, 185, 129, 0.15); color: #10b981; }
    .bg-warning-light { background-color: rgba(245, 158, 11, 0.15); color: #f59e0b; }
    .bg-danger-light { background-color: rgba(239, 68, 68, 0.15); color: #ef4444; }
    .bg-secondary-light { background-color: rgba(108, 117, 125, 0.15); color: #4b5563; }
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
    .nav-pills .nav-link {
        font-size: 0.88rem;
        transition: all 0.2s ease;
    }
    .nav-pills .nav-link.active {
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    
    <!-- Top Action Info -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Thời hạn Gói dịch vụ Khách hàng</h4>
            <p class="text-muted mb-0">Theo dõi trạng thái, ẩn/hiện và thời gian còn lại của gói dịch vụ đã bán.</p>
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
    <div class="row g-3 mb-4">
        <!-- Tổng giao dịch -->
        <div class="col-xl col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-purple-light me-3">
                        <i class="fas fa-history"></i>
                    </div>
                    <div>
                        <div class="value fs-4 fw-bold">{{ $totalCount }}</div>
                        <div class="label text-uppercase text-muted fs-7 fw-semibold">TỔNG BẢN GHI</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Đang hoạt động -->
        <div class="col-xl col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-success-light me-3">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="value fs-4 fw-bold">{{ $activeCount }}</div>
                        <div class="label text-uppercase text-muted fs-7 fw-semibold">ĐANG HOẠT ĐỘNG</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sắp hết hạn -->
        <div class="col-xl col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-warning-light me-3">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <div class="value fs-4 fw-bold">{{ $expiringCount }}</div>
                        <div class="label text-uppercase text-muted fs-7 fw-semibold">SẮP HẾT HẠN (<=3N)</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Đã hết hạn -->
        <div class="col-xl col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-danger-light me-3">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <div class="value fs-4 fw-bold">{{ $expiredCount }}</div>
                        <div class="label text-uppercase text-muted fs-7 fw-semibold">ĐÃ HẾT HẠN</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Lịch sử hết hạn (Đã ẩn) -->
        <div class="col-xl col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-secondary-light me-3">
                        <i class="fas fa-archive"></i>
                    </div>
                    <div>
                        <div class="value fs-4 fw-bold text-secondary">{{ $expiredHistoryCount }}</div>
                        <div class="label text-uppercase text-muted fs-7 fw-semibold">LỊCH SỬ HẾT HẠN</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main List Card -->
    <div class="admin-card">
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-list-ul text-primary me-2"></i>Danh Sách Chi Tiết Thời Hạn Đơn Hàng
            </h5>
        </div>

        <!-- Status Navigation Tabs -->
        <ul class="nav nav-pills mb-4 gap-2 flex-wrap" id="statusTabs">
            <li class="nav-item">
                <a class="nav-link rounded-pill px-3 py-2 {{ !request('status') ? 'active fw-bold' : 'bg-light text-dark' }}" 
                   href="{{ route('admin.customer-durations', array_merge(request()->except('status', 'page'))) }}">
                   <i class="fas fa-list me-1"></i> Chưa ẩn (Theo dõi)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill px-3 py-2 {{ request('status') === 'active' ? 'active fw-bold' : 'bg-light text-dark' }}" 
                   href="{{ route('admin.customer-durations', array_merge(request()->except('status', 'page'), ['status' => 'active'])) }}">
                   <i class="fas fa-check-circle text-success me-1"></i> Đang hoạt động 
                   <span class="badge bg-success rounded-pill ms-1">{{ $activeCount }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill px-3 py-2 {{ request('status') === 'expiring' ? 'active fw-bold' : 'bg-light text-dark' }}" 
                   href="{{ route('admin.customer-durations', array_merge(request()->except('status', 'page'), ['status' => 'expiring'])) }}">
                   <i class="fas fa-exclamation-triangle text-warning me-1"></i> Sắp hết hạn 
                   <span class="badge bg-warning text-dark rounded-pill ms-1">{{ $expiringCount }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill px-3 py-2 {{ request('status') === 'expired' ? 'active fw-bold' : 'bg-light text-dark' }}" 
                   href="{{ route('admin.customer-durations', array_merge(request()->except('status', 'page'), ['status' => 'expired'])) }}">
                   <i class="fas fa-times-circle text-danger me-1"></i> Đã hết hạn 
                   <span class="badge bg-danger rounded-pill ms-1">{{ $expiredCount }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill px-3 py-2 {{ request('status') === 'expired_history' ? 'active bg-danger text-white fw-bold' : 'bg-light text-dark border border-danger-subtle' }}" 
                   href="{{ route('admin.customer-durations', array_merge(request()->except('status', 'page'), ['status' => 'expired_history'])) }}">
                   <i class="fas fa-history me-1"></i> Lịch Sử Hết Hạn (Đã ẩn)
                   <span class="badge bg-secondary rounded-pill ms-1">{{ $expiredHistoryCount }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill px-3 py-2 {{ request('status') === 'completed' ? 'active fw-bold' : 'bg-light text-dark' }}" 
                   href="{{ route('admin.customer-durations', array_merge(request()->except('status', 'page'), ['status' => 'completed'])) }}">
                   <i class="fas fa-archive me-1"></i> Tất cả đã ẩn
                   <span class="badge bg-secondary rounded-pill ms-1">{{ $completedCount }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill px-3 py-2 {{ request('status') === 'all' ? 'active fw-bold' : 'bg-light text-dark' }}" 
                   href="{{ route('admin.customer-durations', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}">
                   Tất cả bản ghi
                   <span class="badge bg-dark rounded-pill ms-1">{{ $totalCount }}</span>
                </a>
            </li>
        </ul>

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
                        <option value="">Chưa Hoàn Thành (Mặc định)</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                        <option value="expiring" {{ request('status') === 'expiring' ? 'selected' : '' }}>Sắp hết hạn (<= 3 ngày)</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Đã hết hạn</option>
                        <option value="expired_history" {{ request('status') === 'expired_history' ? 'selected' : '' }}>Lịch sử hết hạn (Đã ẩn)</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Tất cả đã ẩn (Đã hoàn thành)</option>
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Tất cả (Bao gồm Đã hoàn thành)</option>
                    </select>
                </div>
                <div class="col-md-3 col-lg-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill">
                        <i class="fas fa-filter me-2"></i>Tìm kiếm / Lọc
                    </button>
                </div>
            </div>
        </form>

        <!-- Form chọn nhanh tác vụ hàng loạt -->
        <form id="bulkActionForm" action="{{ route('admin.customer-durations.bulk-action') }}" method="POST">
            @csrf
            <input type="hidden" name="action" id="bulkActionType" value="hide">
            
            <!-- Bulk Action Floating Bar -->
            <div id="bulkActionBar" class="alert alert-primary align-items-center justify-content-between mb-3 py-2 px-3 rounded-3 shadow-sm" style="display: none;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-square fs-5 me-2 text-primary"></i>
                    <span class="fw-medium">Đã chọn <strong id="selectedCount" class="text-primary fs-5">0</strong> mục</span>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" onclick="submitBulkAction('hide')" class="btn btn-sm btn-warning rounded-pill px-3 fw-semibold">
                        <i class="fas fa-eye-slash me-1"></i>Ẩn các mục đã chọn
                    </button>
                    <button type="button" onclick="submitBulkAction('show')" class="btn btn-sm btn-success rounded-pill px-3 fw-semibold">
                        <i class="fas fa-eye me-1"></i>Hiện lại các mục đã chọn
                    </button>
                    <button type="button" onclick="submitBulkAction('delete')" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold">
                        <i class="fas fa-trash me-1"></i>Xóa đã chọn
                    </button>
                </div>
            </div>

            <!-- Table Grid -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-muted fw-semibold">
                            <th style="width: 40px;" class="text-center">
                                <input type="checkbox" id="selectAll" class="form-check-input" title="Chọn tất cả">
                            </th>
                            <th style="min-width: 170px;">KHÁCH HÀNG</th>
                            <th>MÃ ĐƠN</th>
                            <th>SẢN PHẨM</th>
                            <th>TỔNG THỜI HẠN</th>
                            <th>NGÀY BẮT ĐẦU</th>
                            <th>NGÀY HẾT HẠN</th>
                            <th>TRẠNG THÁI / CÒN LẠI</th>
                            <th class="text-center" style="width: 170px;">TÁC VỤ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($durations as $duration)
                            <tr class="{{ $duration->is_completed ? 'table-light opacity-75' : '' }}">
                                <!-- Checkbox chọn -->
                                <td class="text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $duration->id }}" class="form-check-input duration-checkbox">
                                </td>
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
                                <!-- Trạng thái / Thời gian còn lại -->
                                <td>
                                    @php
                                        $status = $duration->status;
                                        $badgeClass = 'bg-success-light text-success';
                                        if ($status === 'completed') {
                                            $badgeClass = 'bg-secondary text-white';
                                        } elseif ($status === 'expired') {
                                            $badgeClass = 'bg-danger-light text-danger';
                                        } elseif ($status === 'expiring') {
                                            $badgeClass = 'bg-warning-light text-warning';
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill fw-semibold fs-7">
                                        @if($status === 'completed')
                                            <i class="fas fa-check-circle me-1"></i>Đã hoàn thành (Đã ẩn)
                                        @else
                                            <i class="far fa-clock me-1"></i>{{ $duration->remaining_time }}
                                        @endif
                                    </span>
                                    @if($duration->admin_note)
                                        <div class="mt-2">
                                            <small class="text-muted bg-light border rounded px-2 py-1 d-inline-block" style="font-size: 0.75rem; max-width: 220px; white-space: normal; word-break: break-word;">
                                                <i class="fas fa-sticky-note text-warning me-1"></i>{{ $duration->admin_note }}
                                            </small>
                                        </div>
                                    @endif
                                </td>
                                <!-- Tác vụ -->
                                <td class="text-center">
                                    <div class="btn-group">
                                        <!-- Nút Nhanh: Ẩn / Hiện -->
                                        @if($duration->is_completed)
                                            <button type="button" onclick="submitSingleToggle({{ $duration->id }})" class="btn btn-sm btn-outline-success" title="Hiện lại vào danh sách theo dõi">
                                                <i class="fas fa-eye me-1"></i>Hiện
                                            </button>
                                        @else
                                            <button type="button" onclick="submitSingleToggle({{ $duration->id }})" class="btn btn-sm btn-outline-warning" title="Ẩn khỏi danh sách theo dõi">
                                                <i class="fas fa-eye-slash me-1"></i>Ẩn
                                            </button>
                                        @endif

                                        @if($duration->order_id)
                                            <a href="{{ route('admin.orders.show', $duration->order_id) }}" class="btn btn-sm btn-outline-info" title="Xem chi tiết đơn hàng">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.customer-durations.edit', $duration->id) }}" class="btn btn-sm btn-outline-primary" title="Chỉnh sửa thời hạn">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" onclick="submitSingleDelete({{ $duration->id }})" class="btn btn-sm btn-outline-danger" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
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
        </form>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $durations->appends(request()->input())->links() }}
        </div>

    </div>
</div>

<!-- Forms phụ trợ cho nút tác vụ đơn lẻ -->
<form id="singleToggleForm" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<form id="singleDeleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.duration-checkbox');
    const bulkActionBar = document.getElementById('bulkActionBar');
    const selectedCount = document.getElementById('selectedCount');

    function updateBulkActionBar() {
        const checked = document.querySelectorAll('.duration-checkbox:checked');
        const count = checked.length;
        if (count > 0) {
            bulkActionBar.style.display = 'flex';
            selectedCount.textContent = count;
        } else {
            bulkActionBar.style.display = 'none';
            selectedCount.textContent = '0';
        }
        if (selectAll) {
            selectAll.checked = checkboxes.length > 0 && count === checkboxes.length;
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            updateBulkActionBar();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkActionBar);
    });
});

function submitBulkAction(action) {
    const checked = document.querySelectorAll('.duration-checkbox:checked');
    if (checked.length === 0) {
        alert('Vui lòng chọn ít nhất 1 mục để thực hiện tác vụ!');
        return;
    }

    let confirmMsg = '';
    if (action === 'hide') {
        confirmMsg = `Bạn có chắc chắn muốn ẨN ${checked.length} mục đã chọn không?`;
    } else if (action === 'show') {
        confirmMsg = `Bạn có chắc chắn muốn HIỆN LẠI ${checked.length} mục đã chọn không?`;
    } else if (action === 'delete') {
        confirmMsg = `Bạn có chắc chắn muốn XÓA VĨNH VIỄN ${checked.length} mục đã chọn không? Tác vụ này không thể hoàn tác!`;
    }

    if (confirm(confirmMsg)) {
        document.getElementById('bulkActionType').value = action;
        document.getElementById('bulkActionForm').submit();
    }
}

function submitSingleToggle(id) {
    const form = document.getElementById('singleToggleForm');
    form.action = `/admin/customer-durations/${id}/toggle-hide`;
    form.submit();
}

function submitSingleDelete(id) {
    if (confirm('Bạn có chắc chắn muốn xóa bản ghi thời hạn này không?')) {
        const form = document.getElementById('singleDeleteForm');
        form.action = `/admin/customer-durations/${id}`;
        form.submit();
    }
}
</script>
@endpush
