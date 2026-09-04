@extends('layouts.admin')

@section('title', 'Quản lý Mã Voucher / Khuyến mãi')

@section('content')
<div class="container-fluid px-4 py-3">
    
    {{-- Header Page --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
                <i class="fas fa-ticket-alt text-warning"></i> Quản lý Mã Voucher & Khuyến Mãi
            </h4>
            <p class="text-muted mb-0 small">Quyền hạn độc quyền dành riêng cho Sieusuperadmin</p>
        </div>
        <button type="button" class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#createCouponModal">
            <i class="fas fa-plus-circle me-1.5"></i> Tạo Voucher Mới
        </button>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="p-3 bg-white rounded-3 shadow-sm border border-primary border-opacity-25 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-medium">Tổng Voucher</div>
                    <div class="fs-4 fw-extrabold text-primary">{{ number_format($totalCoupons) }}</div>
                </div>
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary">
                    <i class="fas fa-tickets-all fs-4"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 bg-white rounded-3 shadow-sm border border-success border-opacity-25 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-medium">Khả Dụng (Chưa Dùng)</div>
                    <div class="fs-4 fw-extrabold text-success">{{ number_format($unusedCoupons) }}</div>
                </div>
                <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success">
                    <i class="fas fa-check-circle fs-4"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 bg-white rounded-3 shadow-sm border border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-medium">Đã Sử Dụng</div>
                    <div class="fs-4 fw-extrabold text-secondary">{{ number_format($usedCoupons) }}</div>
                </div>
                <div class="rounded-circle bg-secondary bg-opacity-10 p-3 text-secondary">
                    <i class="fas fa-history fs-4"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 bg-white rounded-3 shadow-sm border border-warning border-opacity-25 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-medium">Tổng Giá Trị Phát Hành</div>
                    <div class="fs-5 fw-extrabold text-danger">{{ number_format($totalValue, 0, ',', '.') }}đ</div>
                </div>
                <div class="rounded-circle bg-warning bg-opacity-10 p-3 text-warning">
                    <i class="fas fa-coins fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Nav Tabs --}}
    <ul class="nav nav-pills mb-3 gap-2" id="couponTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold rounded-pill px-4" id="coupons-list-tab" data-bs-toggle="tab" data-bs-target="#coupons-list-pane" type="button" role="tab" aria-controls="coupons-list-pane" aria-selected="true">
                <i class="fas fa-list me-1.5"></i> Danh Sách Mã Voucher
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold rounded-pill px-4 text-danger border border-danger border-opacity-25 bg-white position-relative" id="top-customers-tab" data-bs-toggle="tab" data-bs-target="#top-customers-pane" type="button" role="tab" aria-controls="top-customers-pane" aria-selected="false">
                <i class="fas fa-crown text-warning me-1.5"></i> Top Khách Đặt Đơn Nhiều (Gán Lộc)
                @if(isset($topCustomers) && $topCustomers->count() > 0)
                    <span class="badge bg-danger rounded-pill ms-1.5">{{ $topCustomers->count() }}</span>
                @endif
            </button>
        </li>
    </ul>

    {{-- Alert Success / Errors --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> 
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="tab-content" id="couponTabsContent">
        {{-- Tab 1: Danh sách Mã Voucher --}}
        <div class="tab-pane fade show active" id="coupons-list-pane" role="tabpanel" aria-labelledby="coupons-list-tab">
            {{-- Filter & Search Bar --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-3">
                    <form action="{{ route('admin.coupons.index') }}" method="GET" class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Tìm theo mã voucher, tên người dùng, email..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select name="status" class="form-select bg-light" onchange="this.form.submit()">
                                <option value="">-- Tất cả trạng thái --</option>
                                <option value="unused" {{ request('status') === 'unused' ? 'selected' : '' }}>Chưa sử dụng (Khả dụng)</option>
                                <option value="used" {{ request('status') === 'used' ? 'selected' : '' }}>Đã sử dụng</option>
                                <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Đã gán riêng người dùng</option>
                                <option value="unassigned" {{ request('status') === 'unassigned' ? 'selected' : '' }}>Chưa gán (Áp dụng chung)</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary fw-bold flex-grow-1"><i class="fas fa-filter me-1"></i> Lọc</button>
                            @if(request()->hasAny(['search', 'status']))
                                <a href="{{ route('admin.coupons.index') }}" class="btn btn-light border" title="Xóa lọc"><i class="fas fa-undo"></i></a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Main Table --}}
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width: 60px;">ID</th>
                                <th>Mã Voucher</th>
                                <th>Giá Trị Giảm</th>
                                <th>Người Được Gán (User)</th>
                                <th>Trạng Thái</th>
                                <th>Ngày Tạo</th>
                                <th>Ngày Sử Dụng</th>
                                <th class="text-end pe-3" style="width: 140px;">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($coupons as $coupon)
                                <tr>
                                    <td class="ps-3 fw-bold text-muted">#{{ $coupon->id }}</td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1.5 fs-6 font-monospace fw-bold user-select-all cursor-pointer" 
                                              onclick="navigator.clipboard.writeText('{{ $coupon->code }}'); alert('Đã sao chép: {{ $coupon->code }}');"
                                              title="Bấm để sao chép mã">
                                            <i class="fas fa-copy me-1 opacity-75"></i>{{ $coupon->code }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-extrabold text-danger fs-6">-{{ number_format($coupon->value, 0, ',', '.') }}đ</span>
                                    </td>
                                    <td>
                                        @if($coupon->user)
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 13px;">
                                                    {{ strtoupper(substr($coupon->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark mb-0 style-sm">{{ $coupon->user->name }}</div>
                                                    <div class="text-muted" style="font-size: 11.5px;">{{ $coupon->user->email }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="badge bg-light text-secondary border px-2 py-1" style="font-weight: 500;">
                                                <i class="fas fa-globe me-1"></i> Áp dụng tất cả
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($coupon->is_used)
                                            <span class="badge bg-secondary px-2.5 py-1.5"><i class="fas fa-check-double me-1"></i> Đã sử dụng</span>
                                        @else
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 fw-bold"><i class="fas fa-bolt me-1"></i> Khả dụng</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">
                                        {{ $coupon->created_at ? $coupon->created_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="small text-muted">
                                        @if($coupon->is_used && $coupon->used_at)
                                            {{ $coupon->used_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}
                                            @if($coupon->order)
                                                <div style="font-size: 11px;"><a href="{{ route('admin.orders') }}?search={{ $coupon->order->order_code }}" target="_blank">Đơn #{{ $coupon->order->order_code }}</a></div>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-inline-flex gap-1">
                                            {{-- Button Gán User --}}
                                            <button type="button" class="btn btn-sm btn-light border text-info" 
                                                    onclick="openAssignUserModal({{ $coupon->id }}, '{{ $coupon->code }}', '{{ $coupon->user ? $coupon->user->id : '' }}', '{{ $coupon->user ? addslashes($coupon->user->name . ' (' . $coupon->user->email . ')') : '' }}')" 
                                                    title="Gán người dùng">
                                                <i class="fas fa-user-tag"></i>
                                            </button>
                                            {{-- Button Xóa --}}
                                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa mã voucher {{ $coupon->code }} không?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Xóa voucher">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fas fa-ticket-alt fa-3x mb-3 text-secondary opacity-50"></i>
                                        <div>Chưa có mã voucher nào phù hợp.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($coupons->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $coupons->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Tab 2: Top Khách Hàng Đặt Đơn Nhiều (Gán Voucher Lộc) --}}
        <div class="tab-pane fade" id="top-customers-pane" role="tabpanel" aria-labelledby="top-customers-tab">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 px-4 d-flex align-items-center justify-content-between border-0">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                            <i class="fas fa-trophy text-warning"></i> Top Khách Hàng Đặt Đơn Nhiều Nhất
                        </h5>
                        <p class="text-muted small mb-0">Danh sách tự động xếp hạng khách hàng theo số đơn hoàn thành để Admin tặng Lộc Voucher</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 70px;">Hạng</th>
                                <th>Khách Hàng</th>
                                <th>Email & SĐT</th>
                                <th class="text-center">Đơn Hoàn Thành</th>
                                <th class="text-end">Tổng Chi Tiêu</th>
                                <th class="text-end pe-4" style="width: 170px;">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topCustomers as $index => $customer)
                                <tr>
                                    <td class="ps-4 fw-bold">
                                        @if($index == 0)
                                            <span class="badge bg-warning text-dark px-2.5 py-1.5 fs-6"><i class="fas fa-crown me-1"></i> #1</span>
                                        @elseif($index == 1)
                                            <span class="badge bg-secondary text-white px-2.5 py-1.5 fs-6"><i class="fas fa-medal me-1"></i> #2</span>
                                        @elseif($index == 2)
                                            <span class="badge bg-danger bg-opacity-75 text-white px-2.5 py-1.5 fs-6"><i class="fas fa-award me-1"></i> #3</span>
                                        @else
                                            <span class="badge bg-light text-muted border px-2.5 py-1.5 fs-6">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="rounded-circle bg-danger bg-opacity-10 text-danger fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 14px;">
                                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark mb-0">{{ $customer->name }}</div>
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25" style="font-size: 10px;">ID: #{{ $customer->id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-medium text-dark small">{{ $customer->email }}</div>
                                        <div class="text-muted" style="font-size: 11.5px;">{{ $customer->phone ?? 'Chưa có SĐT' }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1.5 fs-6 fw-bold">
                                            <i class="fas fa-shopping-bag me-1"></i> {{ number_format($customer->orders_count) }} đơn
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <strong class="text-danger fs-6">{{ number_format($customer->orders_sum_total_amount ?? 0, 0, ',', '.') }}đ</strong>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button type="button" class="btn btn-sm btn-danger fw-bold rounded-pill px-3 shadow-sm"
                                                onclick="giftCouponToUser({{ $customer->id }}, '{{ addslashes($customer->name) }}', '{{ addslashes($customer->email) }}')"
                                                title="Tặng voucher cho khách hàng này">
                                            <i class="fas fa-gift me-1"></i> Tặng Voucher
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-users-slash fa-3x mb-3 text-secondary opacity-50"></i>
                                        <div>Chưa có dữ liệu khách hàng hoàn thành đơn hàng.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TẠO VOUCHER MỚI --}}
<div class="modal fade" id="createCouponModal" tabindex="-1" aria-labelledby="createCouponModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
            <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);">
                <h5 class="modal-title fw-bold" id="createCouponModalLabel">
                    <i class="fas fa-plus-circle me-1.5"></i> Tạo Mã Voucher Khuyến Mãi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-light">
                    
                    {{-- Mã Voucher --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Mã Voucher (Code)</label>
                        <div class="input-group">
                            <input type="text" name="code" id="input_coupon_code" class="form-control fw-bold font-monospace text-uppercase" placeholder="VD: TRIAN50K, GIFT100K..." style="letter-spacing: 1px;">
                            <button type="button" class="btn btn-outline-secondary" onclick="autoGenerateCode()" title="Tự động tạo mã ngẫu nhiên">
                                <i class="fas fa-random me-1"></i> Tự sinh
                            </button>
                        </div>
                        <div class="form-text small">Để trống hệ thống sẽ tự động tạo mã dạng <code>VOUCHER-XXXXXX</code>.</div>
                    </div>

                    {{-- Giá trị giảm --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Giá Trị Giảm (VND) <span class="text-danger">*</span></label>
                        <input type="number" name="value" id="input_coupon_value" class="form-control fw-bold text-danger fs-5" placeholder="VD: 50000" min="1000" step="1000" required>
                        
                        {{-- Presets --}}
                        <div class="d-flex gap-1.5 mt-2 flex-wrap">
                            <button type="button" class="btn btn-xs btn-outline-danger px-2 py-1" onclick="setPresetValue(10000)">10.000đ</button>
                            <button type="button" class="btn btn-xs btn-outline-danger px-2 py-1" onclick="setPresetValue(20000)">20.000đ</button>
                            <button type="button" class="btn btn-xs btn-outline-danger px-2 py-1" onclick="setPresetValue(50000)">50.000đ</button>
                            <button type="button" class="btn btn-xs btn-outline-danger px-2 py-1" onclick="setPresetValue(100000)">100.000đ</button>
                            <button type="button" class="btn btn-xs btn-outline-danger px-2 py-1" onclick="setPresetValue(200000)">200.000đ</button>
                        </div>
                    </div>

                    {{-- Số lượng phát hành --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Số Lượng Mã Cần Tạo</label>
                        <input type="number" name="quantity" class="form-control" value="1" min="1" max="50">
                        <div class="form-text small">Nếu tạo số lượng > 1, các mã sẽ được tự động sinh ngẫu nhiên.</div>
                    </div>

                    {{-- Gán người dùng (Searchable Dropdown) --}}
                    <div class="mb-3 position-relative">
                        <label class="form-label fw-bold small text-dark">Gán Riêng Người Dùng (Tùy chọn)</label>
                        <input type="hidden" name="user_id" id="create_selected_user_id" value="">
                        
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" id="create_user_search_input" class="form-control" placeholder="Gõ tên, email hoặc SĐT để tìm người dùng..." autocomplete="off" oninput="debounceUserSearch('create')">
                            <button type="button" class="btn btn-outline-secondary" onclick="clearSelectedUser('create')" title="Bỏ chọn">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        {{-- Dropdown Search Results --}}
                        <div id="create_user_search_results" class="list-group position-absolute w-100 shadow-lg rounded-3 mt-1 d-none" style="z-index: 1050; max-height: 220px; overflow-y: auto;"></div>
                        <div id="create_selected_user_badge" class="mt-2 d-none">
                            <span class="badge bg-info text-white px-2.5 py-1.5 font-normal fs-6">
                                <i class="fas fa-user me-1"></i> <span id="create_selected_user_text"></span>
                            </span>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-white border-0 px-4 py-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4 shadow-sm">
                        <i class="fas fa-check me-1"></i> Xác Nhận Tạo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL GÁN / ĐỔI NGƯỜI DÙNG --}}
<div class="modal fade" id="assignUserModal" tabindex="-1" aria-labelledby="assignUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
            <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                <h5 class="modal-title fw-bold" id="assignUserModalLabel">
                    <i class="fas fa-user-tag me-1.5"></i> Gán Người Dùng Cho Voucher: <span id="assign_coupon_code_title"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="assignUserForm" action="" method="POST">
                @csrf
                <div class="modal-body p-4 bg-light">
                    
                    <div class="mb-3 position-relative">
                        <label class="form-label fw-bold small text-dark">Chọn Người Dùng Được Gán Voucher</label>
                        <input type="hidden" name="user_id" id="assign_selected_user_id" value="">
                        
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" id="assign_user_search_input" class="form-control" placeholder="Gõ tên, email hoặc SĐT..." autocomplete="off" oninput="debounceUserSearch('assign')">
                            <button type="button" class="btn btn-outline-secondary" onclick="clearSelectedUser('assign')" title="Bỏ chọn (Tất cả người dùng)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        {{-- Dropdown Search Results --}}
                        <div id="assign_user_search_results" class="list-group position-absolute w-100 shadow-lg rounded-3 mt-1 d-none" style="z-index: 1050; max-height: 220px; overflow-y: auto;"></div>
                        
                        <div id="assign_selected_user_badge" class="mt-2">
                            <span class="badge bg-success text-white px-2.5 py-1.5 font-normal fs-6">
                                <i class="fas fa-user me-1"></i> <span id="assign_selected_user_text">Áp dụng cho tất cả người dùng</span>
                            </span>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-white border-0 px-4 py-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-info text-white fw-bold px-4 shadow-sm">
                        <i class="fas fa-save me-1"></i> Lưu Gán User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let searchDebounceTimer = null;

    function setPresetValue(val) {
        document.getElementById('input_coupon_value').value = val;
    }

    function autoGenerateCode() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let result = 'GIFT';
        for (let i = 0; i < 6; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('input_coupon_code').value = result;
    }

    function debounceUserSearch(prefix) {
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(() => {
            fetchUsers(prefix);
        }, 300);
    }

    function fetchUsers(prefix) {
        const inputEl = document.getElementById(`${prefix}_user_search_input`);
        const resultsEl = document.getElementById(`${prefix}_user_search_results`);
        const q = inputEl.value.trim();

        fetch(`{{ route('admin.coupons.users-search') }}?q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(users => {
                resultsEl.innerHTML = '';
                if (users.length === 0) {
                    resultsEl.innerHTML = '<div class="list-group-item disabled text-muted small p-2">Không tìm thấy người dùng phù hợp</div>';
                } else {
                    users.forEach(u => {
                        const a = document.createElement('a');
                        a.href = 'javascript:void(0)';
                        a.className = 'list-group-item list-group-item-action p-2 small';
                        a.innerHTML = `<strong>${u.name}</strong> <span class="text-muted">(${u.email}${u.phone ? ' - ' + u.phone : ''})</span>`;
                        a.onclick = function() {
                            selectUser(prefix, u.id, `${u.name} (${u.email})`);
                        };
                        resultsEl.appendChild(a);
                    });
                }
                resultsEl.classList.remove('d-none');
            })
            .catch(() => {
                resultsEl.classList.add('d-none');
            });
    }

    function selectUser(prefix, userId, userDisplay) {
        document.getElementById(`${prefix}_selected_user_id`).value = userId;
        document.getElementById(`${prefix}_selected_user_text`).textContent = userDisplay;
        document.getElementById(`${prefix}_selected_user_badge`).classList.remove('d-none');
        document.getElementById(`${prefix}_user_search_results`).classList.add('d-none');
        document.getElementById(`${prefix}_user_search_input`).value = '';
    }

    function clearSelectedUser(prefix) {
        document.getElementById(`${prefix}_selected_user_id`).value = '';
        document.getElementById(`${prefix}_selected_user_text`).textContent = 'Áp dụng tất cả người dùng';
        document.getElementById(`${prefix}_selected_user_input`) ? document.getElementById(`${prefix}_selected_user_input`).value = '' : null;
        if (prefix === 'create') {
            document.getElementById(`${prefix}_selected_user_badge`).classList.add('d-none');
        } else {
            document.getElementById(`${prefix}_selected_user_text`).textContent = 'Áp dụng cho tất cả người dùng';
        }
        document.getElementById(`${prefix}_user_search_results`).classList.add('d-none');
    }

    function openAssignUserModal(couponId, code, userId, userText) {
        document.getElementById('assign_coupon_code_title').textContent = code;
        document.getElementById('assignUserForm').action = `/admin/coupons/${couponId}/assign`;
        
        if (userId) {
            selectUser('assign', userId, userText);
        } else {
            clearSelectedUser('assign');
        }

        const modalEl = document.getElementById('assignUserModal');
        const bsModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        bsModal.show();
    }

    function giftCouponToUser(userId, userName, userEmail) {
        selectUser('create', userId, `${userName} (${userEmail})`);
        const modalEl = document.getElementById('createCouponModal');
        const bsModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        bsModal.show();
    }

    // Close search dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#create_user_search_input') && !e.target.closest('#create_user_search_results')) {
            const el = document.getElementById('create_user_search_results');
            if (el) el.classList.add('d-none');
        }
        if (!e.target.closest('#assign_user_search_input') && !e.target.closest('#assign_user_search_results')) {
            const el = document.getElementById('assign_user_search_results');
            if (el) el.classList.add('d-none');
        }
    });
</script>
@endsection
