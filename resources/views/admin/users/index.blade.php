@extends('layouts.app')

@section('title', 'Quản Lý Người Dùng')

@push('styles')
<style>
    .admin-wrapper {
        padding: 40px 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        margin-top: 70px;
    }

    .admin-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }

    .admin-nav {
        background: rgba(255,255,255,0.1);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
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
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="container">
        <!-- Navigation -->
        <div class="admin-nav">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fas fa-chart-line me-2"></i>Dashboard
            </a>
            <a href="{{ route('admin.products') }}">
                <i class="fas fa-box me-2"></i>Sản phẩm
            </a>
            <a href="{{ route('admin.orders') }}">
                <i class="fas fa-shopping-cart me-2"></i>Đơn hàng
            </a>
            <a href="{{ route('admin.users') }}" class="active">
                <i class="fas fa-users me-2"></i>Người dùng
            </a>
            <a href="{{ route('admin.abandoned-carts') }}">
                <i class="fas fa-shopping-basket me-2"></i>Gio bo quen
            </a>
        </div>

        <!-- User Management Card -->
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-3">
                        <i class="fas fa-users text-primary me-2"></i>Quản Lý Người Dùng
                    </h3>
                    <!-- Search Bar -->
                    <form method="GET" action="{{ route('admin.users') }}" class="d-flex gap-2">
                        <div class="flex-grow-1" style="max-width: 300px;">
                            <input 
                                type="text" 
                                name="search" 
                                class="form-control form-control-sm" 
                                placeholder="Tìm theo tên hoặc email..." 
                                value="{{ request('search') }}"
                                style="border-radius: 8px; padding: 8px 12px;">
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary" style="border-radius: 8px;">
                            <i class="fas fa-search me-1"></i>Tìm kiếm
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.users') }}" class="btn btn-sm btn-secondary" style="border-radius: 8px;">
                                <i class="fas fa-times me-1"></i>Xóa
                            </a>
                        @endif
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
                                <th style="width: 20%">Họ tên</th>
                                <th style="width: 20%">Email</th>
                                <th style="width: 12%">Số ĐT</th>
                                <th style="width: 10%" class="text-center">Số đơn</th>
                                <th style="width: 13%" class="text-end">Tổng chi tiêu</th>
                                <th style="width: 10%">Ngày đăng ký</th>
                                <th style="width: 10%" class="text-center">Quyền</th>
                                <th style="width: 8%" class="text-center">Thao tác</th>
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
                                        <a href="{{ route('admin.users.history', $user->id) }}" class="view-btn" title="Xem lịch sử mua hàng">
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
<script>
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
    const originalValue = selectElement.value;
    selectElement.innerHTML = '<option>Đang cập nhật...</option>';

    // Send AJAX request
    fetch(`{{ route('admin.users.update-role', '') }}/${userId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({ role: newRole })
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        // Update the data attribute
        selectElement.dataset.currentRole = newRole;

        // Show success message
        showNotification(data.message, 'success');
    })
    .catch(error => {
        console.error('Error:', error);
        // Revert the selection
        selectElement.value = currentRole;
        selectElement.innerHTML = originalHTML;
        
        let errorMessage = 'Không thể cập nhật quyền. Vui lòng thử lại!';
        if (error.message.includes('403')) {
            errorMessage = 'Không thể thay đổi quyền của chính mình!';
        }
        showNotification(errorMessage, 'error');
    })
    .finally(() => {
        // Remove loading state
        selectElement.disabled = false;
        selectElement.classList.remove('role-update-loading');
        selectElement.innerHTML = originalHTML;
    });
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
