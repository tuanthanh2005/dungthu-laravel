@extends('layouts.admin')

@section('title', 'Quản Lý Người Dùng')

@section('page_title', 'Người dùng')

@push('styles')
<style>

    .admin-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    .admin-nav a {
        color: white;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 10px;
        transition: all 0.3s;
        margin-right: 10px;
        display: inline-block;
    }

    .admin-nav a:hover {
        background: rgba(255,255,255,0.2);
    }

    .admin-nav a.active {
        background: white;
        color: #667eea;
    }

    .user-table {
        width: 100%;
        margin-top: 20px;
    }

    .user-table th {
        background: #f7fafc;
        padding: 15px;
        font-weight: 600;
        color: #2d3748;
        border-bottom: 2px solid #e2e8f0;
    }

    .user-table td {
        padding: 15px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .user-table tr:hover {
        background: #f7fafc;
    }

    .badge-orders {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
    }

    .total-spent {
        color: #38a169;
        font-weight: 700;
        font-size: 16px;
    }

    .view-btn {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .view-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
    }

    .view-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
    }

    .stats-mini {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .stats-mini-item {
        background: #f7fafc;
        padding: 8px 15px;
        border-radius: 10px;
        font-size: 13px;
    }

    .role-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        text-align: center;
    }

    .role-badge-user {
        background: #e2e8f0;
        color: #2d3748;
    }

    .role-badge-admin {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .role-badge-moderator {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .role-dropdown {
        min-width: 120px;
        padding: 6px 10px;
        border: 1px solid #cbd5e0;
        border-radius: 8px;
        background: white;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .role-dropdown:hover {
        border-color: #667eea;
        background: #f7fafc;
    }

    .role-dropdown:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .role-update-loading {
        opacity: 0.6;
        pointer-events: none;
    }

    /* Compact Search Form */
    .compact-search-form {
        display: flex;
        align-items: center;
        background: #f1f2f6;
        border-radius: 30px;
        padding: 5px 15px;
        border: 1px solid transparent;
        transition: all 0.3s ease;
        width: 250px;
    }
    .compact-search-form:focus-within {
        background: #fff;
        border-color: #667eea;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1);
        width: 320px;
    }
    .compact-search-input {
        border: none;
        outline: none;
        background: transparent;
        font-size: 0.9rem;
        color: #2d3436;
        width: 100%;
        font-weight: 500;
    }
    .compact-search-icon {
        color: #667eea;
        font-size: 0.85rem;
        margin-right: 10px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
        <!-- Navigation -->
        <!-- Voucher Generator Card -->
        <div class="admin-card mb-4">
            <h4 class="fw-bold mb-4">
                <i class="fas fa-ticket-alt text-warning me-2"></i>Tạo mã Voucher nhanh
            </h4>
            
            <div class="row align-items-end g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-muted">Chọn mệnh giá</label>
                    <select id="voucher-preset-select" class="form-select rounded-3" onchange="toggleCustomVoucherInput(this)">
                        <option value="2000">2.000đ</option>
                        <option value="5000">5.000đ</option>
                        <option value="10000" selected>10.000đ</option>
                        <option value="15000">15.000đ</option>
                        <option value="25000">25.000đ</option>
                        <option value="50000">50.000đ</option>
                        <option value="custom">Tùy chọn khác...</option>
                    </select>
                </div>
                
                <div class="col-md-4" id="custom-voucher-value-container" style="display: none;">
                    <label class="form-label fw-bold small text-muted">Nhập số tiền giảm (VNĐ)</label>
                    <input type="number" id="voucher-custom-value" class="form-control rounded-3" placeholder="Ví dụ: 100000" min="1000">
                </div>
                
                <div class="col-md-4">
                    <button type="button" class="view-btn w-100 py-2 rounded-3 text-center" onclick="generateVoucher()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-magic me-2"></i>Tạo mã Voucher
                    </button>
                </div>
            </div>
            
            <!-- Result display -->
            <div id="voucher-result-container" class="mt-4 p-3 bg-light rounded-3 d-none">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <span class="text-muted small d-block">Mã Voucher vừa tạo:</span>
                        <strong class="fs-4 text-danger font-monospace" id="generated-voucher-code">ADMIN10K-XXXXXX</strong>
                        <span class="badge bg-success ms-2" id="generated-voucher-value">10.000đ</span>
                    </div>
                    <button class="view-btn py-2 px-4" onclick="copyGeneratedVoucher()" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <i class="fas fa-copy me-1"></i> Sao chép mã
                    </button>
                </div>
            </div>
        </div>

        <!-- User Management Card -->
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <h3 class="fw-bold mb-0">
                        <i class="fas fa-users text-primary me-2"></i>Quản Lý Người Dùng
                    </h3>
                    
                    <form action="{{ route('admin.users') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
                        <div class="compact-search-form">
                            <i class="fas fa-search compact-search-icon"></i>
                            <input type="text" name="search" class="compact-search-input" placeholder="Tìm tên hoặc email..." value="{{ request('search') }}">
                        </div>
                        
                        <div>
                            <select name="sort" class="form-select rounded-pill px-3" style="border: 1px solid #cbd5e0; min-width: 180px; font-size: 0.9rem; font-weight: 500; cursor: pointer; color: #4a5568; background-color: #fff; height: 38px;" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort') === 'newest' || !request('sort') ? 'selected' : '' }}>Mới nhất (Mới -> Cũ)</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Cũ nhất (Cũ -> Mới)</option>
                                <option value="most_orders" {{ request('sort') === 'most_orders' ? 'selected' : '' }}>Nhiều đơn hàng nhất</option>
                                <option value="most_spent" {{ request('sort') === 'most_spent' ? 'selected' : '' }}>Tổng chi tiêu nhiều nhất</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="stats-mini">
                    <div class="stats-mini-item">
                        <i class="fas fa-user text-primary me-1"></i>
                        <strong>{{ $users->total() }}</strong> người dùng
                    </div>
                </div>
            </div>

            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th style="width: 4%">#</th>
                                <th style="width: 15%">Họ tên</th>
                                <th style="width: 15%">Email</th>
                                <th style="width: 9%">Số ĐT</th>
                                <th style="width: 6%" class="text-center">Số đơn</th>
                                <th style="width: 12%" class="text-center">Lịch sử mua</th>
                                <th style="width: 7%" class="text-center">Lượt quay</th>
                                <th style="width: 11%" class="text-end">Tổng chi tiêu</th>
                                <th style="width: 9%">Ngày đăng ký</th>
                                <th style="width: 6%" class="text-center">Quyền</th>
                                <th style="width: 6%" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                                <tr>
                                    <td class="fw-bold">{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        @if($user->phone)
                                            <small class="text-muted">{{ $user->phone }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone ?? 'Chưa cập nhật' }}</td>
                                    <td class="text-center">
                                        <span class="badge-orders">
                                            <i class="fas fa-shopping-cart me-1"></i>{{ $user->orders_count }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" 
                                                class="view-btn px-3 py-1 text-white border-0 shadow-sm"
                                                style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); font-size: 0.8rem; font-weight: 600; border-radius: 20px; white-space: nowrap; cursor: pointer; transition: all 0.25s;"
                                                onclick="viewUserOrdersDetail({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                                title="Xem chi tiết đơn hàng & form thông tin nhập">
                                            <i class="fas fa-receipt me-1"></i>Xem chi tiết
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary text-white px-2 py-1" id="tickets-count-{{ $user->id }}">
                                            {{ $user->spin_tickets }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="total-spent">
                                            {{ number_format($user->orders_sum_total_amount ?? 0, 0, ',', '.') }}đ
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $user->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <select 
                                            class="role-dropdown role-select" 
                                            data-user-id="{{ $user->id }}"
                                            data-current-role="{{ $user->role }}"
                                            onchange="updateUserRole(this)">
                                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                            <option value="moderator" {{ $user->role === 'moderator' ? 'selected' : '' }}>Moderator</option>
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <button onclick="awardTickets({{ $user->id }}, '{{ $user->name }}', {{ $user->spin_tickets }})" class="view-btn me-1" style="background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); padding: 6px 12px; font-size: 0.85rem;" title="Cấp lượt quay">
                                            <i class="fas fa-ticket-alt"></i>
                                        </button>
                                        <a href="{{ route('admin.users.history', $user->id) }}" class="view-btn" style="padding: 6px 12px; font-size: 0.85rem;" title="Xem trang lịch sử đầy đủ">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Chưa có người dùng nào</h5>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Xem Chi Tiết Lịch Sử Mua Hàng & Form Nhập -->
<div class="modal fade" id="userOrdersDetailModal" tabindex="-1" aria-labelledby="userOrdersDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15); overflow: hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 18px 25px;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px; font-size: 1.2rem;">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="userOrdersDetailModalLabel">Lịch Sử Đơn Hàng & Thông Tin Form Khách Nhập</h5>
                        <small class="opacity-75" id="userOrdersDetailUserSubtitle">Khách hàng: ...</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background: #f8fafc; min-height: 400px;">
                <!-- Loading State -->
                <div id="userOrdersLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Đang tải...</span>
                    </div>
                    <div class="mt-3 text-muted fw-bold">Đang tải toàn bộ lịch sử đơn hàng và dữ liệu form...</div>
                </div>

                <!-- Empty State -->
                <div id="userOrdersEmpty" class="text-center py-5 d-none">
                    <div class="mb-3 text-muted" style="font-size: 3.5rem; opacity: 0.4;">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <h5 class="text-muted fw-bold" id="userOrdersEmptyTitle">Khách hàng chưa có đơn hàng nào</h5>
                    <p class="text-muted small" id="userOrdersEmptyDesc">Người dùng này chưa có đơn hàng nào được ghi nhận trong hệ thống.</p>
                </div>

                <!-- Content State -->
                <div id="userOrdersContent" class="d-none">
                    <!-- Quick Stats Header -->
                    <div class="p-3 mb-4 rounded-4 bg-white shadow-sm border d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 48px; height: 48px; font-size: 1.3rem; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);" id="userOrdersAvatar">
                                U
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold text-dark" id="userOrdersSummaryName">Họ tên</h6>
                                <div class="text-muted small" id="userOrdersSummaryContact">Email | SĐT</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge bg-light text-primary border px-3 py-2 fs-6 rounded-pill">
                                <i class="fas fa-shopping-cart me-1"></i> <span id="userOrdersSummaryCount">0</span> đơn hàng
                            </span>
                            <span class="badge bg-light text-success border px-3 py-2 fs-6 fw-bold rounded-pill">
                                <i class="fas fa-money-bill-wave me-1"></i> <span id="userOrdersSummarySpent">0đ</span>
                            </span>
                        </div>
                    </div>

                    <!-- Orders List Container -->
                    <div id="userOrdersListContainer" class="d-flex flex-column gap-4">
                        <!-- Cards injected via JS -->
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-white border-top">
                <a href="#" id="userOrdersFullHistoryLink" class="btn btn-outline-primary rounded-pill px-4" target="_blank">
                    <i class="fas fa-external-link-alt me-1"></i>Xem trang lịch sử đầy đủ
                </a>
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function toggleCustomVoucherInput(select) {
    const container = document.getElementById('custom-voucher-value-container');
    if (select.value === 'custom') {
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
    }
}

function generateVoucher() {
    const select = document.getElementById('voucher-preset-select');
    let value = select.value;
    
    if (value === 'custom') {
        const customInput = document.getElementById('voucher-custom-value');
        value = customInput.value;
        if (!value || isNaN(value) || parseInt(value) < 1000) {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Vui lòng nhập mệnh giá hợp lệ (tối thiểu 1.000đ)!'
            });
            return;
        }
    }
    
    const doFetch = (pinVal = null) => {
        const bodyObj = { value: value };
        if (pinVal) bodyObj.admin_pin = pinVal;

        // AJAX request
        fetch('{{ route('admin.coupons.generate') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(bodyObj)
        })
        .then(async response => {
            if (response.status === 403) {
                const err = await response.json();
                const pin = window.prompt(err.message || 'Nhập mã xác nhận (8 số) để tiếp tục:');
                if (pin !== null) {
                    doFetch(pin);
                }
                return;
            }
            if (!response.ok) {
                const err = await response.json();
                throw err;
            }
            return response.json();
        })
        .then(data => {
            if (!data) return; // handled in 403
            // Display result
            document.getElementById('generated-voucher-code').textContent = data.coupon.code;
            document.getElementById('generated-voucher-value').textContent = data.coupon.formatted_value;
            document.getElementById('voucher-result-container').classList.remove('d-none');
            
            Swal.fire({
                icon: 'success',
                title: 'Đã tạo xong!',
                text: 'Mã voucher mới: ' + data.coupon.code,
                timer: 2000,
                showConfirmButton: false
            });
        })
        .catch(err => {
            if (err && err.message !== 'Bị hủy') {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: err.message || 'Có lỗi xảy ra khi tạo voucher!'
                });
            }
        });
    };

    doFetch();
}

function copyGeneratedVoucher() {
    const code = document.getElementById('generated-voucher-code').textContent;
    navigator.clipboard.writeText(code).then(() => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Đã sao chép mã voucher!',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
        });
    }).catch(err => {
        console.error('Không thể sao chép: ', err);
    });
}

function awardTickets(userId, userName, currentTickets) {
    Swal.fire({
        title: 'Cấp lượt quay',
        html: `Nhập số lượt quay muốn cộng thêm cho <strong>${userName}</strong> (nhập số âm để trừ):`,
        input: 'number',
        inputValue: 1,
        showCancelButton: true,
        confirmButtonText: 'Xác nhận',
        cancelButtonText: 'Hủy',
        confirmButtonColor: '#667eea',
        inputValidator: (value) => {
            if (value === '' || isNaN(value)) {
                return 'Vui lòng nhập một số hợp lệ!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const ticketChange = parseInt(result.value);
            
            const doFetch = (pinVal = null) => {
                const bodyObj = { tickets: ticketChange };
                if (pinVal) bodyObj.admin_pin = pinVal;

                // Send AJAX post to award tickets
                fetch(`{{ route('admin.users.award-tickets', ':userId') }}`.replace(':userId', userId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(bodyObj)
                })
                .then(async response => {
                    if (response.status === 403) {
                        const err = await response.json();
                        const pin = window.prompt(err.message || 'Nhập mã xác nhận (8 số) để tiếp tục:');
                        if (pin !== null) {
                            doFetch(pin);
                        }
                        return;
                    }
                    if (!response.ok) {
                        const err = await response.json();
                        throw err;
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data) return; // handled in 403
                    // Update tickets on UI
                    document.getElementById(`tickets-count-${userId}`).textContent = data.spin_tickets;
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                })
                .catch(err => {
                    if (err && err.message !== 'Bị hủy') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: err.message || 'Có lỗi xảy ra, vui lòng thử lại sau!'
                        });
                    }
                });
            };

            doFetch();
        }
    });
}

function updateUserRole(selectElement) {
    const userId = selectElement.dataset.userId;
    const newRole = selectElement.value;
    const currentRole = selectElement.dataset.currentRole;

    // Prevent default change if same role
    if (currentRole === newRole) {
        return;
    }

    // Show loading state
    selectElement.disabled = true;
    selectElement.classList.add('role-update-loading');
    const originalHTML = selectElement.innerHTML;

    // Add loading spinner
    selectElement.innerHTML = '<option>Đang cập nhật...</option>';

    const doFetch = (pinVal = null) => {
        const bodyObj = { role: newRole };
        if (pinVal) bodyObj.admin_pin = pinVal;

        // Send AJAX request
        fetch(`{{ route('admin.users.update-role', ':userId') }}`.replace(':userId', userId), {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify(bodyObj)
        })
        .then(async response => {
            if (response.status === 403) {
                const err = await response.json();
                const pin = window.prompt(err.message || 'Nhập mã xác nhận (8 số) để tiếp tục:');
                if (pin !== null) {
                    doFetch(pin);
                } else {
                    // Revert selection
                    selectElement.value = currentRole;
                    selectElement.innerHTML = originalHTML;
                    selectElement.disabled = false;
                    selectElement.classList.remove('role-update-loading');
                }
                return;
            }
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (!data) return; // handled in 403
            // Update the data attribute
            selectElement.dataset.currentRole = newRole;

            // Show success message
            showNotification(data.message, 'success');
            
            // Remove loading state
            selectElement.disabled = false;
            selectElement.classList.remove('role-update-loading');
            selectElement.innerHTML = originalHTML;
        })
        .catch(error => {
            console.error('Error:', error);
            // Revert the selection
            selectElement.value = currentRole;
            selectElement.innerHTML = originalHTML;
            selectElement.disabled = false;
            selectElement.classList.remove('role-update-loading');
            
            let errorMessage = 'Không thể cập nhật quyền. Vui lòng thử lại!';
            if (error.message && error.message.includes('403')) {
                errorMessage = 'Không thể thay đổi quyền của chính mình!';
            }
            showNotification(errorMessage, 'error');
        });
    };

    doFetch();
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideIn 0.3s ease-in-out;
    `;
    notification.innerHTML = `
        ${type === 'success' ? '<i class="fas fa-check-circle me-2"></i>' : '<i class="fas fa-exclamation-circle me-2"></i>'}
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    document.body.appendChild(notification);

    // Auto remove after 4 seconds
    setTimeout(() => {
        notification.remove();
    }, 4000);
}

// Add animation style
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);

function viewUserOrdersDetail(userId, userName) {
    const modalEl = document.getElementById('userOrdersDetailModal');
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    
    document.getElementById('userOrdersDetailUserSubtitle').textContent = `Khách hàng: ${userName} (ID #${userId})`;
    document.getElementById('userOrdersFullHistoryLink').href = `/admin/users/${userId}/history`;
    
    document.getElementById('userOrdersLoading').classList.remove('d-none');
    document.getElementById('userOrdersEmpty').classList.add('d-none');
    document.getElementById('userOrdersContent').classList.add('d-none');
    
    modal.show();

    fetch(`/admin/users/${userId}/orders-detail`)
        .then(res => {
            if (!res.ok) throw new Error('Network error ' + res.status);
            return res.json();
        })
        .then(res => {
            document.getElementById('userOrdersLoading').classList.add('d-none');
            
            if (!res.success || !res.orders || res.orders.length === 0) {
                document.getElementById('userOrdersEmpty').classList.remove('d-none');
                return;
            }

            const user = res.user;
            document.getElementById('userOrdersAvatar').textContent = (user.name || 'U').charAt(0).toUpperCase();
            document.getElementById('userOrdersSummaryName').textContent = user.name;
            document.getElementById('userOrdersSummaryContact').innerHTML = `<i class="fas fa-envelope me-1"></i>${escapeHtml(user.email)} | <i class="fas fa-phone me-1"></i>${escapeHtml(user.phone)} | <i class="fas fa-calendar me-1"></i>Đăng ký: ${user.created_at}`;
            document.getElementById('userOrdersSummaryCount').textContent = user.orders_count;
            document.getElementById('userOrdersSummarySpent').textContent = user.total_spent_formatted;

            const container = document.getElementById('userOrdersListContainer');
            container.innerHTML = '';

            res.orders.forEach(order => {
                const orderCard = document.createElement('div');
                orderCard.className = 'card border shadow-sm rounded-4 overflow-hidden';
                
                // Products rows
                let productsHtml = '';
                if (order.items && order.items.length > 0) {
                    order.items.forEach(item => {
                        productsHtml += `
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        ${item.product_image ? `<img src="${item.product_image}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0;">` : `<div style="width: 40px; height: 40px; background: #e2e8f0; border-radius: 8px;"></div>`}
                                        <div>
                                            <div class="fw-bold text-dark small">${escapeHtml(item.product_name)}</div>
                                            ${item.variant_name ? `<span class="badge bg-light text-primary border" style="font-size: 0.75rem;">${escapeHtml(item.variant_name)}</span>` : ''}
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center small">${item.variant_name ? escapeHtml(item.variant_name) : '-'}</td>
                                <td class="text-center fw-bold small">x${item.quantity}</td>
                                <td class="text-end small">${item.formatted_price}</td>
                                <td class="text-end fw-bold text-primary small">${item.formatted_line_total}</td>
                            </tr>
                        `;
                    });
                } else {
                    productsHtml = `<tr><td colspan="5" class="text-center text-muted small py-2">Không có sản phẩm</td></tr>`;
                }

                orderCard.innerHTML = `
                    <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="fs-5 fw-bold text-primary font-monospace">${escapeHtml(order.order_code)}</span>
                            <span class="badge bg-light text-muted border">#${order.id}</span>
                            <span class="badge bg-secondary text-white">${escapeHtml(order.order_type_label)}</span>
                            <span class="badge bg-${order.status_color} px-2 py-1">${escapeHtml(order.status_label)}</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <small class="text-muted"><i class="fas fa-clock me-1"></i>${order.created_at}</small>
                            <span class="fs-5 fw-bold text-danger">${order.formatted_total}</span>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <!-- THÔNG TIN FORM KHÁCH HÀNG NHẬP (Bắt buộc show đầy đủ) -->
                        <div class="p-3 rounded-3 mb-3" style="background: #ffffff; border: 1.5px solid #cbd5e1; border-left: 5px solid #667eea; box-shadow: 0 2px 6px rgba(0,0,0,0.03);">
                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom flex-wrap gap-2">
                                <span class="fw-bold text-primary small text-uppercase">
                                    <i class="fas fa-id-card me-1"></i> Thông Tin Khách Hàng Nhập (Form Chi Tiết)
                                </span>
                                ${order.status === 'cancelled' ? '<span class="badge bg-danger text-white"><i class="fas fa-times-circle me-1"></i>Đơn Chưa Thanh Toán / Đã Hủy</span>' : '<span class="badge bg-success text-white"><i class="fas fa-check-circle me-1"></i>Đơn Đã Ghi Nhận</span>'}
                            </div>
                            <div class="row g-2 small">
                                <div class="col-md-4">
                                    <span class="text-muted d-block">👤 Họ và tên:</span>
                                    <strong class="text-dark">${escapeHtml(order.customer_name || 'Chưa cập nhật')}</strong>
                                </div>
                                <div class="col-md-4">
                                    <span class="text-muted d-block">📞 Số điện thoại:</span>
                                    <strong class="text-dark">${escapeHtml(order.customer_phone || 'Chưa cập nhật')}</strong>
                                </div>
                                <div class="col-md-4">
                                    <span class="text-muted d-block">✉️ Email nhận mã:</span>
                                    <strong class="text-dark">${escapeHtml(order.customer_email || 'Chưa cập nhật')}</strong>
                                </div>
                                <div class="col-md-6">
                                    <span class="text-muted d-block">📝 Email cần nâng cấp / Ghi chú:</span>
                                    <strong class="${order.upgrade_note ? 'text-danger bg-warning-subtle px-1 rounded' : 'text-muted'}">${escapeHtml(order.upgrade_note || 'Không có')}</strong>
                                </div>
                                <div class="col-md-3">
                                    <span class="text-muted d-block">💬 Số Zalo:</span>
                                    <strong class="text-dark">${order.zalo ? `<a href="https://zalo.me/${encodeURIComponent(order.zalo)}" target="_blank" class="text-primary text-decoration-none fw-bold"><i class="fas fa-comment-dots me-1"></i>${escapeHtml(order.zalo)}</a>` : '<span class="text-muted">Không có</span>'}</strong>
                                </div>
                                <div class="col-md-3">
                                    <span class="text-muted d-block">🌐 Link Facebook:</span>
                                    <strong class="text-dark">${order.facebook ? `<a href="${escapeHtml(order.facebook.startsWith('http') ? order.facebook : 'https://' + order.facebook)}" target="_blank" class="text-primary text-decoration-none fw-bold"><i class="fab fa-facebook me-1"></i>Xem Facebook</a>` : '<span class="text-muted">Không có</span>'}</strong>
                                </div>
                                <div class="col-md-4">
                                    <span class="text-muted d-block">💭 Liên hệ Boxchat:</span>
                                    <span class="badge ${order.use_boxchat ? 'bg-info text-white' : 'bg-light text-muted border'}">${order.use_boxchat ? 'Có (Khách tự liên hệ qua Boxchat)' : 'Không'}</span>
                                </div>
                                <div class="col-md-4">
                                    <span class="text-muted d-block">💳 Phương thức thanh toán:</span>
                                    <span class="badge bg-secondary text-white">${escapeHtml(order.payment_method || 'VietQR')}</span>
                                </div>
                                <div class="col-md-4">
                                    <span class="text-muted d-block">ℹ️ Trạng thái & Ghi chú:</span>
                                    <span class="text-dark fw-bold">${escapeHtml(order.status_note || order.status_label)}</span>
                                </div>
                                ${order.raw_address ? `
                                <div class="col-12 mt-1 pt-1 border-top">
                                    <span class="text-muted d-block">📍 Địa chỉ nhận hàng:</span>
                                    <span class="text-dark">${escapeHtml(order.raw_address)}</span>
                                </div>` : ''}
                            </div>
                        </div>

                        <!-- Danh sách sản phẩm mua -->
                        <div class="table-responsive rounded-3 border bg-white mb-2">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="small text-muted">
                                        <th>Sản phẩm</th>
                                        <th class="text-center" style="width: 130px;">Gói / Biến thể</th>
                                        <th class="text-center" style="width: 70px;">SL</th>
                                        <th class="text-end" style="width: 120px;">Đơn giá</th>
                                        <th class="text-end" style="width: 130px;">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${productsHtml}
                                </tbody>
                            </table>
                        </div>

                        <!-- Chi tiết thanh toán & Liên kết -->
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2">
                            <div>
                                ${order.coupon_code ? `<span class="badge bg-success-subtle text-success border border-success me-2"><i class="fas fa-tag me-1"></i>Mã giảm giá: ${escapeHtml(order.coupon_code)} (-${order.formatted_discount})</span>` : ''}
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <a href="${order.admin_url}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fas fa-external-link-alt me-1"></i>Trang quản lý đơn #${order.id}
                                </a>
                            </div>
                        </div>

                        <!-- Bàn giao tài khoản nếu có -->
                        ${(order.delivery_account || order.delivery_key || order.delivery_note) ? `
                        <div class="mt-2 p-2 rounded-3 bg-light border small">
                            <strong class="text-success d-block mb-1"><i class="fas fa-key me-1"></i>Thông tin tài khoản đã bàn giao:</strong>
                            ${order.delivery_account ? `<div>Tài khoản: <code>${escapeHtml(order.delivery_account)}</code></div>` : ''}
                            ${order.delivery_key ? `<div>Key/Mã: <code>${escapeHtml(order.delivery_key)}</code></div>` : ''}
                            ${order.delivery_note ? `<div>Ghi chú giao hàng: ${escapeHtml(order.delivery_note)}</div>` : ''}
                        </div>` : ''}
                    </div>
                `;

                container.appendChild(orderCard);
            });

            document.getElementById('userOrdersContent').classList.remove('d-none');
        })
        .catch(err => {
            console.error('Error fetching user orders:', err);
            document.getElementById('userOrdersLoading').classList.add('d-none');
            document.getElementById('userOrdersEmpty').classList.remove('d-none');
            document.getElementById('userOrdersEmptyTitle').textContent = 'Lỗi tải dữ liệu';
            document.getElementById('userOrdersEmptyDesc').textContent = 'Không thể tải lịch sử đơn hàng. Vui lòng thử lại sau.';
        });
}

function escapeHtml(text) {
    if (!text) return '';
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}
</script>
@endpush

@endsection
