@extends('layouts.app')

@section('title', 'Dịch Vụ Tăng Tương Tác MXH - DungThu.com')
@section('meta_description', 'Hệ thống tăng tương tác mạng xã hội tự động: Facebook, TikTok, Instagram. Tốc độ nhanh, an toàn, giá rẻ nhất.')

@push('styles')
<style>
/* ============================================================
   BUFF PAGE - PREMIUM REDESIGN
   ============================================================ */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

.buff-page { font-family: 'Inter', sans-serif; background: #f8fafc; }

/* ---- HERO ---- */
.buff-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);
    padding: 80px 0 60px;
    margin-top: 64px;
    position: relative;
    overflow: hidden;
}
.buff-hero::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 80% 60% at 20% 50%, rgba(99,102,241,.25) 0%, transparent 60%),
        radial-gradient(ellipse 60% 80% at 80% 30%, rgba(139,92,246,.2) 0%, transparent 60%);
}
.buff-hero-particles {
    position: absolute; inset: 0; overflow: hidden; pointer-events: none;
}
.buff-hero-particles span {
    position: absolute;
    width: 3px; height: 3px;
    border-radius: 50%;
    background: rgba(255,255,255,.4);
    animation: floatDot 6s ease-in-out infinite;
}
.buff-hero-particles span:nth-child(1)  { top:15%; left:10%; animation-delay:0s; }
.buff-hero-particles span:nth-child(2)  { top:30%; left:25%; animation-delay:.8s; width:5px;height:5px; }
.buff-hero-particles span:nth-child(3)  { top:60%; left:15%; animation-delay:1.5s; }
.buff-hero-particles span:nth-child(4)  { top:20%; left:70%; animation-delay:2s; width:4px;height:4px; }
.buff-hero-particles span:nth-child(5)  { top:70%; left:80%; animation-delay:.4s; }
.buff-hero-particles span:nth-child(6)  { top:45%; left:90%; animation-delay:2.5s; width:5px;height:5px; }
@keyframes floatDot {
    0%,100% { transform: translateY(0) scale(1); opacity:.4; }
    50%      { transform: translateY(-20px) scale(1.5); opacity:.9; }
}
.buff-hero-inner {
    position: relative; z-index: 2; text-align: center; color: white;
}
.buff-hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(99,102,241,.25);
    border: 1px solid rgba(99,102,241,.5);
    padding: 6px 18px; border-radius: 50px;
    font-size: .82rem; font-weight: 600; letter-spacing: .5px;
    text-transform: uppercase; color: #a5b4fc;
    margin-bottom: 20px;
    backdrop-filter: blur(10px);
}
.buff-hero-badge .dot { width:8px;height:8px;border-radius:50%;background:#6ee7b7;animation:pulseDot 2s ease-in-out infinite; }
@keyframes pulseDot { 0%,100%{box-shadow:0 0 0 0 rgba(110,231,183,.6);} 50%{box-shadow:0 0 0 8px rgba(110,231,183,0);} }
.buff-hero h1 {
    font-size: clamp(2rem, 5vw, 3.2rem);
    font-weight: 900; line-height: 1.15;
    background: linear-gradient(135deg, #fff 30%, #a5b4fc 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    margin-bottom: 16px;
}
.buff-hero p {
    font-size: 1.05rem; color: rgba(255,255,255,.75);
    max-width: 560px; margin: 0 auto 32px; line-height: 1.7;
}
.buff-hero-actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }
.btn-hero-primary {
    display: inline-flex; align-items: center; gap: 9px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white; padding: 13px 28px; border-radius: 14px;
    font-weight: 700; font-size: .95rem; text-decoration: none;
    box-shadow: 0 8px 30px rgba(99,102,241,.45);
    transition: all .3s; border: none;
}
.btn-hero-primary:hover { transform: translateY(-3px); box-shadow: 0 14px 40px rgba(99,102,241,.55); color: white; }
.btn-hero-ghost {
    display: inline-flex; align-items: center; gap: 9px;
    background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.25);
    color: white; padding: 13px 28px; border-radius: 14px;
    font-weight: 600; font-size: .95rem; text-decoration: none;
    backdrop-filter: blur(10px); transition: all .3s;
}
.btn-hero-ghost:hover { background: rgba(255,255,255,.2); color: white; transform: translateY(-2px); }

/* ---- STATS ---- */
.buff-stats-bar {
    background: white;
    border-bottom: 1px solid #e2e8f0;
    padding: 20px 0;
}
.buff-stat-item { text-align: center; padding: 0 20px; }
.buff-stat-num {
    font-size: 1.6rem; font-weight: 900; color: #0f172a;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}
.buff-stat-label { font-size: .78rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
.buff-stat-divider { width: 1px; background: #e2e8f0; margin: 4px 0; }

/* ---- PLATFORM TABS ---- */
.buff-tabs-wrap { padding: 40px 0 0; }
.buff-tabs {
    display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 36px;
}
.buff-tab {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 22px; border-radius: 12px; font-weight: 700;
    font-size: .9rem; cursor: pointer; border: 2px solid transparent;
    transition: all .25s; background: white;
    color: #64748b; box-shadow: 0 2px 8px rgba(0,0,0,.06);
    text-decoration: none;
}
.buff-tab:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.1); color: #1e293b; }
.buff-tab.active { border-color: #6366f1; color: #4f46e5; background: #eef2ff; }
.buff-tab i { font-size: 1.1rem; }

/* ---- PLATFORM SECTION ---- */
.platform-section { margin-bottom: 48px; }
.platform-section-header {
    display: flex; align-items: center; gap: 14px;
    margin-bottom: 22px; padding-bottom: 16px;
    border-bottom: 2px solid #f1f5f9;
}
.platform-logo {
    width: 50px; height: 50px; border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; box-shadow: 0 4px 16px rgba(0,0,0,.12);
    flex-shrink: 0;
}
.platform-section-header h3 {
    font-weight: 800; font-size: 1.4rem; color: #0f172a; margin: 0;
}
.platform-section-header p { color: #64748b; font-size: .88rem; margin: 2px 0 0; }
.platform-count {
    margin-left: auto;
    background: #f1f5f9; color: #64748b;
    padding: 4px 12px; border-radius: 20px;
    font-size: .8rem; font-weight: 600;
}

/* ---- SERVICE CARDS ---- */
.service-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 18px;
}
.service-card {
    background: white;
    border-radius: 20px;
    padding: 24px;
    border: 1.5px solid #f1f5f9;
    transition: all .3s cubic-bezier(.4,0,.2,1);
    text-decoration: none; color: inherit;
    display: flex; flex-direction: column;
    position: relative; overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,.04);
}
.service-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    opacity: 0; transition: opacity .3s;
}
.service-card:hover { transform: translateY(-6px); box-shadow: 0 20px 50px rgba(0,0,0,.1); border-color: #e0e7ff; color: inherit; }
.service-card:hover::before { opacity: 1; }
.service-card-top { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 14px; }
.service-icon-wrap {
    width: 52px; height: 52px; border-radius: 14px;
    background: #eef2ff; color: #6366f1;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
    transition: all .3s;
}
.service-card:hover .service-icon-wrap { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; box-shadow: 0 8px 20px rgba(99,102,241,.35); }
.service-card-info { flex: 1; min-width: 0; }
.service-card-info h4 { font-weight: 700; font-size: .98rem; color: #0f172a; margin: 0 0 4px; line-height: 1.3; }
.service-badge {
    display: inline-block;
    padding: 2px 9px; border-radius: 6px;
    font-size: .72rem; font-weight: 700;
    background: #f0fdf4; color: #16a34a; letter-spacing: .3px;
}
.service-desc {
    color: #64748b; font-size: .85rem; line-height: 1.55;
    margin-bottom: 18px; flex-grow: 1;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.service-card-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding-top: 14px; border-top: 1px solid #f8fafc;
}
.price-block small { display: block; color: #94a3b8; font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
.price-block strong { color: #ef4444; font-weight: 800; font-size: 1.1rem; }
.btn-use {
    display: inline-flex; align-items: center; gap: 6px;
    background: #f1f5f9; color: #475569;
    border: none; padding: 9px 18px; border-radius: 10px;
    font-weight: 700; font-size: .84rem; transition: all .3s;
    text-decoration: none;
}
.service-card:hover .btn-use { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; box-shadow: 0 4px 14px rgba(99,102,241,.4); }

/* ---- WHY US ---- */
.why-section {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
    border-radius: 28px; padding: 56px 48px;
    margin: 20px 0 60px; position: relative; overflow: hidden;
}
.why-section::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse 70% 70% at 50% 0%, rgba(99,102,241,.3), transparent);
}
.why-section-inner { position: relative; z-index: 1; }
.why-section h2 {
    font-weight: 900; font-size: 1.8rem; color: white;
    text-align: center; margin-bottom: 8px;
}
.why-section .why-sub { color: rgba(255,255,255,.6); text-align: center; margin-bottom: 44px; font-size: .95rem; }
.why-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px; }
.why-item {
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 20px; padding: 28px 24px;
    text-align: center;
    transition: all .3s; backdrop-filter: blur(10px);
}
.why-item:hover { background: rgba(255,255,255,.12); transform: translateY(-4px); }
.why-icon {
    width: 60px; height: 60px; border-radius: 18px;
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; margin: 0 auto 16px;
}
.why-item h5 { color: white; font-weight: 700; font-size: 1rem; margin-bottom: 8px; }
.why-item p { color: rgba(255,255,255,.6); font-size: .85rem; line-height: 1.6; margin: 0; }

/* ---- EMPTY STATE ---- */
.empty-state { text-align: center; padding: 80px 20px; }
.empty-icon {
    width: 100px; height: 100px; border-radius: 50%;
    background: #eef2ff; color: #6366f1;
    display: flex; align-items: center; justify-content: center;
    font-size: 40px; margin: 0 auto 24px;
}

/* ---- RESPONSIVE ---- */
@media (max-width: 768px) {
    .buff-hero { padding: 60px 0 40px; }
    .buff-hero h1 { font-size: 2rem; }
    .buff-stat-item { padding: 0 12px; }
    .buff-stat-num { font-size: 1.25rem; }
    .service-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .service-card { padding: 16px; }
    .service-icon-wrap { width: 42px; height: 42px; font-size: 18px; border-radius: 12px; }
    .service-card-info h4 { font-size: .88rem; }
    .service-desc { -webkit-line-clamp: 1; font-size: .8rem; margin-bottom: 12px; }
    .price-block strong { font-size: .95rem; }
    .btn-use { padding: 7px 13px; font-size: .78rem; }
    .why-section { padding: 36px 20px; border-radius: 20px; }
    .why-section h2 { font-size: 1.4rem; }
    .why-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
    .why-item { padding: 20px 14px; }
    .platform-logo { width: 40px; height: 40px; font-size: 20px; border-radius: 12px; }
}
@media (max-width: 480px) {
    .service-grid { grid-template-columns: 1fr 1fr; }
    .buff-tabs { gap: 8px; }
    .buff-tab { padding: 8px 14px; font-size: .82rem; }
    .why-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="buff-page">

{{-- HERO --}}
<section class="buff-hero">
    <div class="buff-hero-particles">
        <span></span><span></span><span></span><span></span><span></span><span></span>
    </div>
    <div class="container">
        <div class="buff-hero-inner">
            <div class="buff-hero-badge">
                <span class="dot"></span> Hệ Thống Tự Động 24/7
            </div>
            <h1>Tăng Tương Tác<br>Mạng Xã Hội</h1>
            <p>Giải pháp tăng follow, like, view tự động cho Facebook, TikTok, Instagram. Nhanh chóng, an toàn, giá tốt nhất thị trường.</p>
            <div class="buff-hero-actions">
                <a href="#dich-vu" class="btn-hero-primary">
                    <i class="fas fa-rocket"></i> Xem dịch vụ
                </a>
                @auth
                <a href="{{ route('buff.history') }}" class="btn-hero-ghost">
                    <i class="fas fa-history"></i> Lịch sử đơn
                </a>
                @else
                <a href="{{ route('login') }}" class="btn-hero-ghost">
                    <i class="fas fa-sign-in-alt"></i> Đăng nhập để dùng
                </a>
                @endauth
            </div>
        </div>
    </div>
</section>

{{-- STATS BAR --}}
<div class="buff-stats-bar">
    <div class="container">
        <div class="d-flex justify-content-center align-items-center flex-wrap gap-0">
            <div class="buff-stat-item">
                <div class="buff-stat-num">50K+</div>
                <div class="buff-stat-label">Đơn hoàn thành</div>
            </div>
            <div class="buff-stat-divider d-none d-sm-block" style="height:40px;"></div>
            <div class="buff-stat-item">
                <div class="buff-stat-num">5K+</div>
                <div class="buff-stat-label">Khách hàng tin dùng</div>
            </div>
            <div class="buff-stat-divider d-none d-sm-block" style="height:40px;"></div>
            <div class="buff-stat-item">
                <div class="buff-stat-num">99.9%</div>
                <div class="buff-stat-label">Tỷ lệ thành công</div>
            </div>
            <div class="buff-stat-divider d-none d-sm-block" style="height:40px;"></div>
            <div class="buff-stat-item">
                <div class="buff-stat-num">24/7</div>
                <div class="buff-stat-label">Xử lý tự động</div>
            </div>
        </div>
    </div>
</div>

{{-- SERVICES --}}
<div class="container buff-tabs-wrap" id="dich-vu">
    @forelse ($services as $platform => $platformServices)
    @php
        $platformInfo = match($platform) {
            'facebook'  => ['name' => 'Facebook',  'icon' => 'fab fa-facebook',  'color' => '#1877F2', 'bg' => '#e7f3ff', 'label' => 'Tăng follow, like, share & hơn thế nữa'],
            'tiktok'    => ['name' => 'TikTok',    'icon' => 'fab fa-tiktok',    'color' => '#010101', 'bg' => '#f0f0f0', 'label' => 'Tăng tim, view, follower TikTok'],
            'instagram' => ['name' => 'Instagram', 'icon' => 'fab fa-instagram', 'color' => '#E4405F', 'bg' => '#fde8ed', 'label' => 'Tăng follow, like, comment Instagram'],
            'youtube'   => ['name' => 'YouTube',   'icon' => 'fab fa-youtube',   'color' => '#FF0000', 'bg' => '#ffe8e8', 'label' => 'Tăng view, sub, like YouTube'],
            default     => ['name' => ucfirst($platform), 'icon' => 'fas fa-star', 'color' => '#f59e0b', 'bg' => '#fef9e7', 'label' => 'Dịch vụ tăng tương tác'],
        };
    @endphp

    <div class="platform-section">
        <div class="platform-section-header">
            <div class="platform-logo" style="background:{{ $platformInfo['bg'] }}; color:{{ $platformInfo['color'] }};">
                <i class="{{ $platformInfo['icon'] }}"></i>
            </div>
            <div>
                <h3>{{ $platformInfo['name'] }}</h3>
                <p>{{ $platformInfo['label'] }}</p>
            </div>
            <div class="platform-count">{{ $platformServices->count() }} dịch vụ</div>
        </div>

        <div class="service-grid">
            @foreach ($platformServices as $service)
            <a href="{{ route('buff.show', $service) }}" class="service-card">
                <div class="service-card-top">
                    <div class="service-icon-wrap">
                        <i class="{{ $service->getIcon() }}"></i>
                    </div>
                    <div class="service-card-info">
                        <h4>{{ $service->name }}</h4>
                        <span class="service-badge"><i class="fas fa-check-circle me-1"></i>Tự động</span>
                    </div>
                </div>
                <p class="service-desc">{{ $service->description ?? 'Dịch vụ tăng tương tác tự động tốc độ cao, an toàn bảo mật.' }}</p>
                <div class="service-card-footer">
                    <div class="price-block">
                        <small>Chỉ từ</small>
                        <strong>{{ number_format($service->price_per_unit, 0, ',', '.') }}đ</strong>
                    </div>
                    <span class="btn-use">Dùng ngay <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @empty
    <div class="empty-state">
        <div class="empty-icon"><i class="fas fa-box-open"></i></div>
        <h4 class="fw-bold text-dark mb-2">Hệ thống đang cập nhật</h4>
        <p class="text-muted">Các dịch vụ sẽ sớm quay trở lại. Vui lòng quay lại sau!</p>
    </div>
    @endforelse

    {{-- WHY US --}}
    @if($services->isNotEmpty())
    <div class="why-section">
        <div class="why-section-inner">
            <h2>Tại Sao Nên Chọn Chúng Tôi?</h2>
            <p class="why-sub">Hàng nghìn khách hàng đã tin tưởng và sử dụng dịch vụ của chúng tôi</p>
            <div class="why-grid">
                <div class="why-item">
                    <div class="why-icon" style="background:rgba(99,102,241,.2);color:#a5b4fc;">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h5>Siêu Tốc Độ</h5>
                    <p>Xử lý đơn hàng ngay lập tức sau khi tạo, hệ thống tự động 24/7</p>
                </div>
                <div class="why-item">
                    <div class="why-icon" style="background:rgba(16,185,129,.2);color:#6ee7b7;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5>An Toàn Tuyệt Đối</h5>
                    <p>Không yêu cầu mật khẩu. Cam kết bảo mật tài khoản 100%</p>
                </div>
                <div class="why-item">
                    <div class="why-icon" style="background:rgba(245,158,11,.2);color:#fcd34d;">
                        <i class="fas fa-undo-alt"></i>
                    </div>
                    <h5>Bảo Hành Dài Hạn</h5>
                    <p>Bảo hành tụt số, hoàn tiền nếu lỗi. Không rủi ro cho khách hàng</p>
                </div>
                <div class="why-item">
                    <div class="why-icon" style="background:rgba(236,72,153,.2);color:#f9a8d4;">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h5>Hỗ Trợ 24/7</h5>
                    <p>Đội ngũ support nhiệt tình, phản hồi nhanh qua chat và Zalo</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

</div>
@endsection
