@extends('layouts.app')

@section('title', 'Dịch vụ Tăng Tương Tác - DungThu.com')

@push('styles')
<style>
    :root {
        --buff-primary: #4F46E5;
        --buff-gradient: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        --buff-bg: #F8FAFC;
    }

    body {
        background-color: var(--buff-bg);
    }

    .buff-hero {
        background: var(--buff-gradient);
        padding: 60px 0 40px;
        color: white;
        margin-top: 70px;
        position: relative;
        overflow: hidden;
    }

    .buff-hero::after {
        content: '';
        position: absolute;
        top: 0; right: 0; bottom: 0; left: 0;
        background: url('data:image/svg+xml,<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="rgba(255,255,255,0.05)"/></svg>');
    }

    .buff-hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .buff-hero-content h1 {
        font-weight: 800;
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .buff-hero-content p {
        font-size: 1.1rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto 25px;
    }

    .history-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 10px 24px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        backdrop-filter: blur(10px);
        transition: all 0.3s;
    }

    .history-btn:hover {
        background: white;
        color: var(--buff-primary);
    }

    .platform-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 40px 0 20px;
    }

    .platform-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .platform-header h3 {
        margin: 0;
        font-weight: 800;
        color: #1e293b;
        font-size: 1.5rem;
    }

    .service-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .service-card {
        background: white;
        border-radius: 20px;
        padding: 24px;
        border: 1px solid #f1f5f9;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }

    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        border-color: #e2e8f0;
        color: inherit;
    }

    .service-card-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: #f8fafc;
        color: var(--buff-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 16px;
        transition: all 0.3s;
    }

    .service-card:hover .service-card-icon {
        background: var(--buff-primary);
        color: white;
    }

    .service-card h4 {
        font-weight: 700;
        font-size: 1.1rem;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .service-card p {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 20px;
        line-height: 1.5;
        flex-grow: 1;
    }

    .service-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 16px;
        border-top: 1px solid #f1f5f9;
    }

    .price-tag {
        display: flex;
        flex-direction: column;
    }

    .price-tag small {
        color: #94a3b8;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .price-tag span {
        color: #ef4444;
        font-weight: 800;
        font-size: 1.1rem;
    }

    .use-btn {
        background: #f1f5f9;
        color: #334155;
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .service-card:hover .use-btn {
        background: var(--buff-primary);
        color: white;
    }

    .features-section {
        background: white;
        border-radius: 24px;
        padding: 40px;
        margin: 60px 0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    }

    .feature-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    .feature-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: #f0fdf4;
        color: #16a34a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .feature-item h5 {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 4px;
    }

    .feature-item p {
        color: #64748b;
        font-size: 0.9rem;
        margin: 0;
    }

    @media (max-width: 768px) {
        .service-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .service-card {
            padding: 16px;
        }

        .service-card-icon {
            width: 40px;
            height: 40px;
            font-size: 20px;
            margin-bottom: 12px;
            border-radius: 12px;
        }

        .service-card h4 {
            font-size: 0.9rem;
            margin-bottom: 4px;
        }

        .service-card p {
            font-size: 0.75rem;
            margin-bottom: 12px;
        }

        .service-card-footer {
            padding-top: 12px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .price-tag span {
            font-size: 0.95rem;
        }

        .use-btn {
            padding: 6px 12px;
            font-size: 0.8rem;
        }
    }
</style>
@endpush

@section('content')
<main style="min-height: 80vh;">
    <section class="buff-hero">
        <div class="container">
            <div class="buff-hero-content">
                <h1>Hệ Thống Tăng Tương Tác</h1>
                <p>Giải pháp tối ưu giúp tăng cường sự hiện diện của bạn trên mạng xã hội một cách nhanh chóng, an toàn và hoàn toàn tự động.</p>
                
                @auth
                <a href="{{ route('buff.history') }}" class="history-btn">
                    <i class="fas fa-history"></i> Lịch sử đơn hàng
                </a>
                @endauth
            </div>
        </div>
    </section>

    <div class="container pb-5">
        @forelse ($services as $platform => $platformServices)
            @php
                $platformInfo = match($platform) {
                    'facebook' => ['name' => 'Facebook', 'icon' => 'fab fa-facebook', 'color' => '#1877F2'],
                    'tiktok' => ['name' => 'TikTok', 'icon' => 'fab fa-tiktok', 'color' => '#000000'],
                    'instagram' => ['name' => 'Instagram', 'icon' => 'fab fa-instagram', 'color' => '#E4405F'],
                    default => ['name' => ucfirst($platform), 'icon' => 'fas fa-star', 'color' => '#f59e0b']
                };
            @endphp

            <div class="platform-header">
                <div class="platform-icon" style="color: {{ $platformInfo['color'] }};">
                    <i class="{{ $platformInfo['icon'] }}"></i>
                </div>
                <h3>{{ $platformInfo['name'] }}</h3>
            </div>

            <div class="service-grid">
                @foreach ($platformServices as $service)
                    <a href="{{ route('buff.show', $service) }}" class="service-card">
                        <div class="service-card-icon">
                            <i class="{{ $service->getIcon() }}"></i>
                        </div>
                        <h4>{{ $service->name }}</h4>
                        <p>{{ $service->description ?? 'Dịch vụ tăng tương tác tự động tốc độ cao' }}</p>
                        
                        <div class="service-card-footer">
                            <div class="price-tag">
                                <small>Chỉ từ</small>
                                <span>{{ number_format($service->price_per_unit, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="use-btn">Sử dụng ngay</div>
                        </div>
                    </a>
                @endforeach
            </div>
        @empty
            <div class="text-center py-5 mt-5">
                <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #94a3b8; font-size: 32px;">
                    <i class="fas fa-box-open"></i>
                </div>
                <h4 class="fw-bold text-dark">Hệ thống đang cập nhật</h4>
                <p class="text-muted">Các dịch vụ sẽ sớm quay trở lại. Vui lòng quay lại sau!</p>
            </div>
        @endforelse

        @if($services->isNotEmpty())
        <div class="features-section">
            <h4 class="fw-bold mb-4 text-center">Tại sao nên chọn hệ thống của chúng tôi?</h4>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-item">
                        <div class="feature-icon"><i class="fas fa-bolt"></i></div>
                        <div>
                            <h5>Siêu Tốc Độ</h5>
                            <p>Hệ thống tự động xử lý đơn hàng ngay lập tức sau khi tạo.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-item">
                        <div class="feature-icon" style="color: #3b82f6; background: #eff6ff;"><i class="fas fa-shield-alt"></i></div>
                        <div>
                            <h5>An Toàn Tuyệt Đối</h5>
                            <p>Không yêu cầu mật khẩu. Cam kết an toàn cho tài khoản 100%.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-item">
                        <div class="feature-icon" style="color: #eab308; background: #fefce8;"><i class="fas fa-headset"></i></div>
                        <div>
                            <h5>Bảo Hành Dài Hạn</h5>
                            <p>Hỗ trợ bảo hành tuột, hoàn tiền nếu lỗi. Support nhiệt tình 24/7.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</main>
@endsection
