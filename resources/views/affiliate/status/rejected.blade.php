@extends('layouts.app')
@section('title', 'Hồ sơ bị từ chối | CTV DungThu')
@section('content')
<div style="min-height:100vh;background:linear-gradient(135deg,#1a1a2e 0%,#16213e 50%,#0f3460 100%);display:flex;align-items:center;justify-content:center;padding:100px 20px 60px;">
    <div style="background:rgba(255,255,255,0.06);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.1);border-radius:24px;padding:56px 40px;max-width:520px;width:100%;text-align:center;box-shadow:0 25px 50px rgba(0,0,0,0.4);" data-aos="fade-up">

        <div style="width:90px;height:90px;background:linear-gradient(135deg,#ef4444,#f87171);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;box-shadow:0 15px 35px rgba(239,68,68,0.3);">
            <i class="fas fa-times" style="font-size:40px;color:white;"></i>
        </div>

        <h2 style="color:white;font-weight:800;font-size:26px;margin-bottom:12px;">Hồ sơ bị từ chối</h2>
        <p style="color:rgba(255,255,255,0.65);font-size:15px;line-height:1.7;margin-bottom:28px;">
            Rất tiếc! Hồ sơ cộng tác viên của bạn chưa được chấp thuận.
        </p>

        @if($affiliate->reject_reason)
        <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);border-radius:16px;padding:20px;margin-bottom:28px;text-align:left;">
            <div style="color:#f87171;font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;margin-bottom:10px;">
                <i class="fas fa-exclamation-triangle me-1"></i>Lý do từ chối
            </div>
            <div style="color:rgba(255,255,255,0.8);font-size:14px;">{{ $affiliate->reject_reason }}</div>
        </div>
        @endif

        <div style="color:rgba(255,255,255,0.5);font-size:13px;margin-bottom:28px;">
            Vui lòng liên hệ admin để được hỗ trợ hoặc đăng ký lại với thông tin chính xác hơn.
        </div>

        <form method="POST" action="{{ route('affiliate.logout') }}">
            @csrf
            <button type="submit" style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:white;padding:12px 28px;border-radius:12px;font-size:14px;cursor:pointer;">
                <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
            </button>
        </form>

        <div class="mt-3">
            <a href="{{ route('home') }}" style="color:#48cfad;text-decoration:none;font-size:13px;">
                <i class="fas fa-arrow-left me-1"></i>Về trang chủ
            </a>
        </div>
    </div>
</div>
@endsection
