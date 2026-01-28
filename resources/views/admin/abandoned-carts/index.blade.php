@extends('layouts.app')

@section('title', 'Giỏ hàng bỏ quên - Admin')

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

    .items-list {
        color: #6b7280;
        font-size: 0.85rem;
        margin-top: 4px;
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
                    <a class="nav-link" href="{{ route('admin.tiktok-deals.index') }}">
                        <i class="fab fa-tiktok me-2"></i>Săn Sale TikTok
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
                    <a class="nav-link" href="{{ route('admin.blogs') }}">
                        <i class="fas fa-blog me-2"></i>Bài viết
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.card-exchanges') }}">
                        <i class="fas fa-credit-card me-2"></i>Đổi thẻ cào
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.chat.index') }}">
                        <i class="fas fa-comments me-2"></i>Chat
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.abandoned-carts') }}">
                        <i class="fas fa-shopping-basket me-2"></i>Giỏ bỏ quên
                    </a>
                </li>
            </ul>
        </nav>

        <div class="admin-card">
            <h4 class="fw-bold mb-4">
                <i class="fas fa-shopping-basket text-danger me-2"></i>Giỏ hàng bỏ quên
            </h4>

            @if($carts->count() === 0)
                <div class="text-center py-5 text-muted">
                    Chưa có giỏ hàng bỏ quên.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>Khách hàng</th>
                                <th>Email</th>
                                <th>Sản phẩm</th>
                                <th>Tổng</th>
                                <th>Hoạt động cuối</th>
                                <th>Nhắc lần</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carts as $cart)
                                <tr>
                                    <td>
                                        <strong>{{ $cart->user->name ?? 'User' }}</strong>
                                        <div class="items-list">ID: {{ $cart->user_id }}</div>
                                    </td>
                                    <td>{{ $cart->email }}</td>
                                    <td>
                                        <strong>{{ $cart->items_count }} SP</strong>
                                        <div class="items-list">
                                            @foreach($cart->cart_data as $item)
                                                <div>- {{ $item['name'] ?? 'Sản phẩm' }} x {{ $item['quantity'] ?? 1 }}</div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td><strong>{{ number_format((float) $cart->total_amount, 0, ',', '.') }}đ</strong></td>
                                    <td>
                                        {{ optional($cart->last_activity_at)->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        {{ $cart->reminder_stage }}/3
                                        <div class="items-list">
                                            Lần cuối: {{ optional($cart->last_reminder_at)->format('d/m/Y H:i') ?? 'Chưa gửi' }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $carts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true });
</script>
@endpush
