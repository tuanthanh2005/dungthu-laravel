@extends('layouts.app')

@section('title', 'Gửi hóa đơn mới | DungThu')

@push('styles')
<style>
    .aff-wrapper { background-color: #f8fafc; min-height: 100vh; padding-top: 100px; padding-bottom: 50px; }
    .content-card { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); max-width: 600px; margin: 0 auto; }
    .form-label { font-weight: 600; font-size: 14px; margin-bottom: 8px; color: #1e293b; }
    .form-control { border-radius: 12px; padding: 12px 16px; border: 1px solid #e2e8f0; }
    .form-control:focus { box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); border-color: #6366f1; }
    .image-preview { width: 100%; height: 250px; border: 2px dashed #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; cursor: pointer; position: relative; overflow: hidden; }
    .image-preview img { width: 100%; height: 100%; object-fit: contain; }
    .commission-box { background: #f0f9ff; border-radius: 12px; padding: 15px; border: 1px solid #e0f2fe; margin-bottom: 25px; }
</style>
@endpush

@section('content')
<div class="aff-wrapper">
    <div class="container">
        <div class="content-card">
            <h4 class="fw-bold mb-4 text-center">Gửi hóa đơn thanh toán</h4>
            
            <form action="{{ route('affiliate.invoices.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                @if($errors->any())
                    <div class="alert alert-danger mb-4" style="border-radius: 12px;">
                        <ul class="mb-0" style="padding-left: 15px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="mb-4">
                    <label class="form-label">Tên sản phẩm / Dịch vụ đã bán</label>
                    <input type="text" name="product_name" class="form-control @error('product_name') is-invalid @enderror" placeholder="Ví dụ: Tài khoản ChatGPT Plus" value="{{ old('product_name') }}" required>
                    @error('product_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Tên khách hàng (Bắt buộc)</label>
                    <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" placeholder="Nhập tên khách hàng..." value="{{ old('customer_name') }}" required>
                    @error('customer_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Email khách hàng (Hoặc SĐT)</label>
                        <input type="text" name="customer_email" class="form-control @error('customer_email') is-invalid @enderror" placeholder="Email hoặc SĐT..." value="{{ old('customer_email') }}">
                        @error('customer_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Số điện thoại khách hàng (Hoặc Email)</label>
                        <input type="text" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" placeholder="SĐT hoặc Email..." value="{{ old('customer_phone') }}">
                        @error('customer_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Số tiền khách hàng đã trả (VNĐ)</label>
                    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="Nhập số tiền..." value="{{ old('amount') }}" required oninput="calcCommission()">
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="commission-box">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Hoa hồng của bạn (5%)</span>
                        <span class="fw-bold text-primary" id="commission-val">0đ</span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Ảnh hóa đơn / Bill chuyển khoản</label>
                    <div class="image-preview @error('bill_image') border-danger @enderror" onclick="document.getElementById('bill_image').click()">
                        <div id="preview-placeholder">
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                            <div class="text-muted small">Click để tải ảnh lên</div>
                        </div>
                        <img id="preview-img" src="#" style="display: none;">
                    </div>
                    <input type="file" name="bill_image" id="bill_image" class="d-none" accept="image/*" required onchange="previewImage(this)">
                    @error('bill_image')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    @if($errors->any())
                        <div class="text-warning small mt-1"><i class="fas fa-exclamation-triangle"></i> Vui lòng chọn lại ảnh nếu form có lỗi nhé!</div>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="form-label">Ghi chú (nếu có)</label>
                    <textarea name="note" class="form-control @error('note') is-invalid @enderror" rows="3" placeholder="Thông tin thêm cho Admin...">{{ old('note') }}</textarea>
                    @error('note')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold" style="border-radius: 12px; background: #6366f1;">
                    Gửi yêu cầu phê duyệt
                </button>
                
                <a href="{{ route('affiliate.invoices') }}" class="btn btn-link w-100 mt-3 text-muted text-decoration-none">
                    Quay về danh sách
                </a>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function calcCommission() {
        const amount = document.getElementById('amount').value;
        const commission = Math.round(amount * 0.05);
        document.getElementById('commission-val').innerText = commission.toLocaleString() + 'đ';
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('preview-img').style.display = 'block';
                document.getElementById('preview-placeholder').style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Trigger calc on load to restore commission if amount exists
    document.addEventListener('DOMContentLoaded', function() {
        if(document.getElementById('amount').value) {
            calcCommission();
        }
    });
</script>
@endpush
