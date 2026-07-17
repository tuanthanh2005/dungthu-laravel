@extends('layouts.admin')

@section('title', 'Chỉnh sửa thời hạn dịch vụ - Admin')
@section('page_title', 'Chỉnh sửa thời hạn')

@section('content')
<div class="container-fluid px-0" style="max-width: 800px;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Chỉnh Sửa Thời Hạn Dịch Vụ</h4>
            <p class="text-muted mb-0">Cập nhật thời gian sử dụng, thông tin gói dịch vụ của khách hàng.</p>
        </div>
        <a href="{{ route('admin.customer-durations') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
        <div class="card-body p-4">
            <form action="{{ route('admin.customer-durations.update', $customerDuration->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Khách hàng đã đăng ký -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="user_id" class="form-label fw-bold">Liên kết tài khoản User (Nếu có)</label>
                        <select name="user_id" id="user_id" class="form-select">
                            <option value="">-- Chọn User (Vãng lai hoặc nhập tay bên dưới) --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-phone="{{ $user->phone }}" {{ old('user_id', $customerDuration->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="order_id" class="form-label fw-bold">Liên kết Đơn hàng (Nếu có)</label>
                        <select name="order_id" id="order_id" class="form-select">
                            <option value="">-- Chọn Đơn hàng --</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}" data-code="{{ $order->order_code ?? ('DH' . $order->id) }}" data-user="{{ $order->user_id }}" data-name="{{ $order->customer_name }}" data-email="{{ $order->customer_email }}" data-phone="{{ $order->customer_phone }}" {{ old('order_id', $customerDuration->order_id) == $order->id ? 'selected' : '' }}>
                                    {{ $order->order_code ?? ('DH' . $order->id) }} - {{ $order->customer_name }} ({{ number_format($order->total_amount) }}đ)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr class="my-4 text-muted">

                <!-- Thông tin khách hàng -->
                <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-user me-2"></i>Thông tin Khách hàng</h5>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="customer_name" class="form-label fw-bold">Tên khách hàng <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="Nhập tên khách hàng" value="{{ old('customer_name', $customerDuration->customer_name) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="customer_email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="customer_email" id="customer_email" class="form-control" placeholder="Nhập email" value="{{ old('customer_email', $customerDuration->customer_email) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="customer_phone" class="form-label fw-bold">Số điện thoại</label>
                        <input type="text" name="customer_phone" id="customer_phone" class="form-control" placeholder="Nhập SĐT" value="{{ old('customer_phone', $customerDuration->customer_phone) }}">
                    </div>
                </div>

                <!-- Thông tin dịch vụ & Đơn hàng -->
                <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-box me-2"></i>Thông tin Dịch vụ & Đơn hàng</h5>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="product_id" class="form-label fw-bold">Sản phẩm (Nếu có trong danh sách)</label>
                        <select name="product_id" id="product_id" class="form-select">
                            <option value="">-- Chọn sản phẩm có sẵn --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-name="{{ $product->name }}" {{ old('product_id', $customerDuration->product_id) == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="product_name" class="form-label fw-bold">Tên sản phẩm hiển thị <span class="text-danger">*</span></label>
                        <input type="text" name="product_name" id="product_name" class="form-control" placeholder="Nhập tên sản phẩm hiển thị" value="{{ old('product_name', $customerDuration->product_name) }}" required>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="order_code" class="form-label fw-bold">Mã đơn hàng</label>
                        <input type="text" name="order_code" id="order_code" class="form-control" placeholder="Ví dụ: DH12345" value="{{ old('order_code', $customerDuration->order_code) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="total_duration" class="form-label fw-bold">Tổng thời hạn</label>
                        <input type="text" name="total_duration" id="total_duration" class="form-control" placeholder="Ví dụ: 30 ngày, 3 tháng, 1 năm..." value="{{ old('total_duration', $customerDuration->total_duration) }}">
                    </div>
                </div>

                <!-- Cấu hình thời gian -->
                <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-calendar-alt me-2"></i>Cấu hình Thời gian</h5>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="start_date" class="form-label fw-bold">Ngày bắt đầu</label>
                        <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $customerDuration->start_date ? $customerDuration->start_date->format('Y-m-d\TH:i') : '') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="expiry_date" class="form-label fw-bold">Ngày hết hạn (Có thể bỏ trống để thiết lập sau)</label>
                        <input type="datetime-local" name="expiry_date" id="expiry_date" class="form-control" value="{{ old('expiry_date', $customerDuration->expiry_date ? $customerDuration->expiry_date->format('Y-m-d\TH:i') : '') }}">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-5">
                    <a href="{{ route('admin.customer-durations') }}" class="btn btn-outline-secondary rounded-pill px-4">Hủy bỏ</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-5">
                        <i class="fas fa-save me-2"></i>Cập nhật Thời hạn
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userSelect = document.getElementById('user_id');
        const orderSelect = document.getElementById('order_id');
        const productSelect = document.getElementById('product_id');

        const customerNameInput = document.getElementById('customer_name');
        const customerEmailInput = document.getElementById('customer_email');
        const customerPhoneInput = document.getElementById('customer_phone');
        const productNameInput = document.getElementById('product_name');
        const orderCodeInput = document.getElementById('order_code');

        // When user is selected
        userSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                customerNameInput.value = selectedOption.getAttribute('data-name') || '';
                customerEmailInput.value = selectedOption.getAttribute('data-email') || '';
                customerPhoneInput.value = selectedOption.getAttribute('data-phone') || '';
            }
        });

        // When order is selected
        orderSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                orderCodeInput.value = selectedOption.getAttribute('data-code') || '';
                customerNameInput.value = selectedOption.getAttribute('data-name') || '';
                customerEmailInput.value = selectedOption.getAttribute('data-email') || '';
                customerPhoneInput.value = selectedOption.getAttribute('data-phone') || '';

                const userId = selectedOption.getAttribute('data-user');
                if (userId) {
                    userSelect.value = userId;
                }
            }
        });

        // When product is selected
        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                productNameInput.value = selectedOption.getAttribute('data-name') || '';
            }
        });
    });
</script>
@endpush
@endsection
