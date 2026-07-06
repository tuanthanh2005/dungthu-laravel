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
                                <th style="width: 5%">#</th>
                                <th style="width: 18%">Họ tên</th>
                                <th style="width: 18%">Email</th>
                                <th style="width: 10%">Số ĐT</th>
                                <th style="width: 8%" class="text-center">Số đơn</th>
                                <th style="width: 10%" class="text-center">Lượt quay</th>
                                <th style="width: 12%" class="text-end">Tổng chi tiêu</th>
                                <th style="width: 10%">Ngày đăng ký</th>
                                <th style="width: 8%" class="text-center">Quyền</th>
                                <th style="width: 11%" class="text-center">Thao tác</th>
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
                                        <a href="{{ route('admin.users.history', $user->id) }}" class="view-btn" style="padding: 6px 12px; font-size: 0.85rem;" title="Xem lịch sử mua hàng">
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
                const pin = window.prompt(err.message || 'Nhập mã xác nhận (3 số) để tiếp tục:');
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
                        const pin = window.prompt(err.message || 'Nhập mã xác nhận (3 số) để tiếp tục:');
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
                const pin = window.prompt(err.message || 'Nhập mã xác nhận (3 số) để tiếp tục:');
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
</script>
@endpush

@endsection
