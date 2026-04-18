@extends('layouts.app')
@section('title', 'Chờ duyệt hồ sơ | CTV DungThu')
@section('content')
<div style="min-height:100vh;background:linear-gradient(135deg,#1a1a2e 0%,#16213e 50%,#0f3460 100%);display:flex;align-items:center;justify-content:center;padding:100px 20px 60px;">
    <div style="background:rgba(255,255,255,0.06);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.1);border-radius:24px;padding:56px 40px;max-width:520px;width:100%;text-align:center;box-shadow:0 25px 50px rgba(0,0,0,0.4);" data-aos="fade-up">

        <div style="width:90px;height:90px;background:linear-gradient(135deg,#f59e0b,#fbbf24);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;box-shadow:0 15px 35px rgba(245,158,11,0.3);">
            <i class="fas fa-clock" style="font-size:40px;color:white;"></i>
        </div>

        <h2 style="color:white;font-weight:800;font-size:26px;margin-bottom:12px;">Hồ sơ đang chờ duyệt</h2>
        <p style="color:rgba(255,255,255,0.65);font-size:15px;line-height:1.7;margin-bottom:28px;">
            Xin chào <strong style="color:#fbbf24;">{{ $affiliate->name }}</strong>!<br>
            Hồ sơ cộng tác viên của bạn đã được gửi thành công.<br>
            Admin sẽ xem xét và phê duyệt trong vòng <strong style="color:#48cfad;">24 giờ</strong>.
        </p>

        <div style="background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.25);border-radius:16px;padding:20px;margin-bottom:28px;text-align:left;">
            <div style="color:#fbbf24;font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;margin-bottom:12px;">Thông tin đăng ký</div>
            <div style="color:rgba(255,255,255,0.7);font-size:14px;">
                <div class="d-flex justify-content-between mb-2">
                    <span>Họ tên:</span> <strong style="color:white;">{{ $affiliate->name }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Email:</span> <strong style="color:white;">{{ $affiliate->email }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Ngày đăng ký:</span> <strong style="color:white;">{{ $affiliate->created_at->format('d/m/Y H:i') }}</strong>
                </div>
            </div>
        </div>

        <div style="color:rgba(255,255,255,0.5);font-size:13px;margin-bottom:24px;">
            <i class="fas fa-info-circle me-1"></i>
            Bạn sẽ nhận thông báo qua email khi hồ sơ được duyệt.
        </div>

        <form method="POST" action="{{ route('affiliate.logout') }}">
            @csrf
            <button type="submit" style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:white;padding:12px 28px;border-radius:12px;font-size:14px;cursor:pointer;transition:all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.15)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
            </button>
        </form>
    </div>
</div>
@endsection
