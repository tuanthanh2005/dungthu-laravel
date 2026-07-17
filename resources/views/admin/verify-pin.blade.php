@extends('layouts.app')

@section('title', 'Xác thực bảo mật')

@push('styles')
<style>
    .verify-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px;
    }

    .verify-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        text-align: center;
        animation: fadeInUp 0.6s ease-out;
    }

    .lock-icon {
        width: 80px;
        height: 80px;
        background: #f7fafc;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: #667eea;
        margin: 0 auto 25px;
        border: 2px solid #edf2f7;
    }

    .pin-input {
        letter-spacing: 15px;
        font-size: 24px;
        text-align: center;
        font-weight: 700;
        border-radius: 15px;
        border: 2px solid #e2e8f0;
        padding: 15px;
        margin-bottom: 20px;
    }

    .pin-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn-verify {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 15px;
        border-radius: 15px;
        width: 100%;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s;
    }

    .btn-verify:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        color: white;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="verify-wrapper">
    <div class="verify-card">
        <div class="lock-icon">
            <i class="fas fa-lock"></i>
        </div>
        <h3 class="fw-bold mb-2">Yêu cầu xác thực</h3>
        <p class="text-muted mb-4">Đây là khu vực bảo mật. Vui lòng nhập mã PIN để tiếp tục.</p>

        @if(session('error'))
            <div class="alert alert-danger border-0 rounded-3 mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.verify-pin.post') }}" method="POST">
            @csrf
            <div class="mb-4">
                <input type="password" name="pin" class="form-control pin-input" placeholder="···" autofocus maxlength="10">
            </div>
            <button type="submit" class="btn-verify">
                Xác nhận truy cập
            </button>
        </form>

        <div class="mt-4">
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted small">
                <i class="fas fa-arrow-left me-1"></i> Quay lại Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
