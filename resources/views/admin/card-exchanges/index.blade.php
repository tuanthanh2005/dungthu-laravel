@extends('layouts.app')

@section('title', 'Quản Lý Đổi Thẻ Cào')

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

    .exchange-table {
        width: 100%;
    }

    .exchange-table th {
        background: #f7fafc;
        padding: 15px;
        font-weight: 600;
        color: #2d3748;
        border-bottom: 2px solid #e2e8f0;
    }

    .exchange-table td {
        padding: 15px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .exchange-table tr:hover {
        background: #f7fafc;
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
            <a href="{{ route('admin.users') }}">
                <i class="fas fa-users me-2"></i>Người dùng
            </a>
            <a href="{{ route('admin.blogs') }}">
                <i class="fas fa-blog me-2"></i>Bài viết
            </a>
            <a href="{{ route('admin.card-exchanges') }}" class="active">
                <i class="fas fa-credit-card me-2"></i>Đổi thẻ cào
            </a>
        </div>

        <div class="admin-card">
            <h3 class="fw-bold mb-4">
                <i class="fas fa-credit-card text-primary me-2"></i>Quản Lý Đổi Thẻ Cào
            </h3>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($exchanges->count() > 0)
                <div class="table-responsive">
                    <table class="exchange-table">
                        <thead>
                            <tr>
                                <th style="width: 5%">ID</th>
                                <th style="width: 15%">Khách hàng</th>
                                <th style="width: 12%">Loại thẻ</th>
                                <th style="width: 10%">Mệnh giá</th>
                                <th style="width: 18%">Ngân hàng</th>
                                <th style="width: 12%">Ngày gửi</th>
                                <th style="width: 10%">Trạng thái</th>
                                <th style="width: 18%" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($exchanges as $exchange)
                                <tr>
                                    <td class="fw-bold">#{{ $exchange->id }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $exchange->user->name }}</div>
                                        <small class="text-muted">{{ $exchange->user->email }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $exchange->card_type }}</span>
                                    </td>
                                    <td>{{ number_format($exchange->card_value, 0, ',', '.') }}đ</td>
                                    <td>
                                        <div><strong>{{ $exchange->bank_name }}</strong></div>
                                        <small class="text-muted">{{ $exchange->bank_account_number }}</small>
                                        <div><small>{{ $exchange->bank_account_name }}</small></div>
                                    </td>
                                    <td>
                                        <small>{{ $exchange->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $exchange->status_badge }}">
                                            {{ $exchange->status_text }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#updateModal{{ $exchange->id }}">
                                            <i class="fas fa-edit"></i> Xử lý
                                        </button>
                                    </td>
                                </tr>

                                <!-- Update Modal -->
                                <div class="modal fade" id="updateModal{{ $exchange->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content" style="border-radius: 15px;">
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Xử lý đổi thẻ #{{ $exchange->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.card-exchanges.update-status', $exchange) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <strong>Khách hàng:</strong> {{ $exchange->user->name }}<br>
                                                            <strong>Email:</strong> {{ $exchange->user->email }}<br>
                                                            <strong>SĐT:</strong> {{ $exchange->user->phone ?? 'Chưa có' }}
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Loại thẻ:</strong> {{ $exchange->card_type }}<br>
                                                            <strong>Seri:</strong> {{ $exchange->card_serial }}<br>
                                                            <strong>Mã thẻ:</strong> {{ $exchange->card_code }}
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Trạng thái</label>
                                                        <select class="form-select" name="status" required>
                                                            <option value="pending" {{ $exchange->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                                            <option value="processing" {{ $exchange->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                                            <option value="success" {{ $exchange->status == 'success' ? 'selected' : '' }}>Thành công</option>
                                                            <option value="failed" {{ $exchange->status == 'failed' ? 'selected' : '' }}>Thất bại</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Số tiền thực nhận</label>
                                                        <input type="number" class="form-control" name="exchange_amount" 
                                                               value="{{ $exchange->exchange_amount }}" 
                                                               placeholder="Nhập số tiền chuyển cho khách">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Ghi chú</label>
                                                        <textarea class="form-control" name="admin_note" rows="3" 
                                                                  placeholder="Ghi chú của admin...">{{ $exchange->admin_note }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-save me-2"></i>Cập nhật
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $exchanges->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-credit-card fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Chưa có yêu cầu đổi thẻ nào</h5>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
