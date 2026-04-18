@extends('layouts.app')

@section('title', 'Thông báo hệ thống - Admin')

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
        background: white;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }

    .admin-nav .nav-link {
        color: #4a5568;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 10px;
        transition: all 0.3s ease;
        margin: 0 5px;
    }

    .admin-nav .nav-link:hover,
    .admin-nav .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .custom-table {
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .custom-table thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 12px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1px;
    }

    .custom-table thead th:first-child {
        border-radius: 10px 0 0 10px;
    }

    .custom-table thead th:last-child {
        border-radius: 0 10px 10px 0;
    }

    .custom-table tbody tr {
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .custom-table tbody td {
        padding: 12px;
        vertical-align: top;
        border: none;
    }
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="container">
        <nav class="admin-nav" data-aos="fade-down">
            <ul class="nav nav-pills justify-content-center flex-wrap">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.products') }}">
                        <i class="fas fa-box me-2"></i>Sản phẩm
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.orders') }}">
                        <i class="fas fa-shopping-cart me-2"></i>Đơn hàng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.users') }}">
                        <i class="fas fa-users me-2"></i>Người dùng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.abandoned-carts') }}">
                        <i class="fas fa-shopping-basket me-2"></i>Giỏ bỏ quên
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.system-notifications') }}">
                        <i class="fas fa-bullhorn me-2"></i>Thông báo hệ thống
                    </a>
                </li>
            </ul>
        </nav>

        <div class="admin-card">
            <h4 class="fw-bold mb-4">
                <i class="fas fa-bullhorn text-primary me-2"></i>Gửi thông báo hệ thống
            </h4>

            @if($users->count() === 0)
                <div class="text-center py-5 text-muted">
                    Chưa có người dùng nào.
                </div>
            @else
                <form action="{{ route('admin.system-notifications.send') }}" method="POST" id="notificationForm">
                    @csrf
                    
                    <div class="card bg-light border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3"><i class="fas fa-envelope-open-text text-primary me-2"></i>Nội dung thông báo</h5>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tiêu đề (Subject):</label>
                                <input type="text" name="subject" class="form-control" placeholder="VD: Khuyến mãi khủng nhân ngày lễ..." required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nội dung (Message):</label>
                                <textarea name="message" class="form-control" rows="5" placeholder="Nhập nội dung thông báo. Bạn có thể xuống dòng, nội dung sẽ được định dạng tự động trong email." required></textarea>
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-4 rounded-pill" onclick="return confirm('Bạn có chắc chắn muốn gửi thông báo cho các người dùng đã chọn?');">
                                    <i class="fas fa-paper-plane me-2"></i>Gửi ngay
                                </button>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3"><i class="fas fa-users text-info me-2"></i>Danh sách người dùng (Nhận thông báo)</h5>
                    <div class="table-responsive">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th width="40"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                                    <th>ID</th>
                                    <th>Tên người dùng</th>
                                    <th>Email</th>
                                    <th>Ngày tham gia</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="form-check-input user-checkbox">
                                        </td>
                                        <td><strong>#{{ $user->id }}</strong></td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $users->links() }}
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true });
    
    // Select all functionality
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    // Update select all when individual checkbox changes
    document.querySelectorAll('.user-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allCheckboxes = document.querySelectorAll('.user-checkbox');
            const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
            const selectAll = document.getElementById('selectAll');
            if(selectAll) selectAll.checked = allChecked;
        });
    });

    // Validation before submit
    document.getElementById('notificationForm')?.addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
        if(checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất 1 người dùng để gửi thông báo!');
        }
    });
</script>
@endpush
