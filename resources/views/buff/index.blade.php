@extends('layouts.app')

@section('title', 'Dịch vụ Buff - DungThu.com')

@push('styles')
<style>
    .buff-hero {
        background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
        padding: 1.5rem 0;
        color: white;
        margin-bottom: 2rem;
    }

    .buff-hero h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .buff-hero p {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    .platform-section {
        margin-bottom: 2.5rem;
    }

    .platform-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #6c5ce7;
    }

    .platform-title i {
        font-size: 1.5rem;
    }

    .service-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.25rem;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .service-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        border-color: #6c5ce7;
    }

    .service-icon {
        font-size: 2rem;
        margin-bottom: 0.75rem;
    }

    .service-name {
        font-size: 1rem;
        font-weight: 700;
        color: #2d3436;
        margin-bottom: 0.4rem;
    }

    .service-description {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 1rem;
        flex-grow: 1;
    }

    .service-price {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 0.75rem;
    }

    .price-label {
        font-size: 0.85rem;
        color: #999;
    }

    .price-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: #dc3545;
    }

    .btn-select {
        background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: inline-block;
        text-align: center;
    }

    .btn-select:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(108, 92, 231, 0.4);
        color: white;
    }

    .info-box {
        background: white;
        border: 1px solid #e0e0e0;
        border-left: 4px solid #6c5ce7;
        border-radius: 8px;
        padding: 1.5rem;
    }

    .info-box h4 {
        color: #2d3436;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .info-box ul {
        margin: 0;
        padding-left: 1.5rem;
    }

    .info-box li {
        margin-bottom: 0.5rem;
        color: #555;
    }


{
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        display: block;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-select:hover {
        background: linear-gradient(135deg, #5f4ec7 0%, #9080d8 100%);
        color: white;
        transform: scale(1.02);
    }

    /* Info box */
    .info-box {
        background: #f8f9fa;
        border-left: 4px solid #6c5ce7;
        padding: 1.5rem;
        border-radius: 6px;
        margin-bottom: 2rem;
    }

    .info-box h4 {
        color: #2d3436;
        margin-bottom: 0.5rem;
    }

    .info-box ul {
        margin: 0;
        padding-left: 1.5rem;
        color: #555;
    }

    .info-box li {
        margin-bottom: 0.5rem;
    }
</style>
@endpush

@section('content')
<main style="margin-top: 100px; min-height: 70vh;">
    <div class="buff-hero">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1>💎 Dịch vụ Buff</h1>
                    <p>Tăng tương tác - An toàn - Giá tốt</p>
                </div>
                @auth
                    <a href="{{ route('buff.history') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 0.6rem 1.2rem; border-radius: 6px; text-decoration: none; border: 1px solid rgba(255,255,255,0.3); font-weight: 600; font-size: 0.9rem;">
                        📋 Lịch sử đơn
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <div class="container">


        @forelse ($services as $platform => $platformServices)
            <div class="platform-section">
                @php
                    $platformName = match($platform) {
                        'facebook' => 'Facebook',
                        'tiktok' => 'TikTok',
                        'instagram' => 'Instagram',
                        default => $platform
                    };
                    $platformIcon = match($platform) {
                        'facebook' => 'fab fa-facebook',
                        'tiktok' => 'fab fa-tiktok',
                        'instagram' => 'fab fa-instagram',
                        default => 'fas fa-star'
                    };
                @endphp

                <div class="platform-title">
                    <i class="{{ $platformIcon }}"></i>
                    {{ $platformName }}
                </div>

                <div class="row g-3">
                    @foreach ($platformServices as $service)
                        <div class="col-md-6 col-lg-4">
                            <div class="service-card">
                                <div class="service-icon">
                                    <i class="{{ $service->getIcon() }}"></i>
                                </div>

                                <div class="service-name">
                                    {{ $service->name }}
                                </div>

                                <div class="service-description">
                                    {{ $service->description ?? 'Tăng ' . strtolower($service->service_type) }}
                                </div>

                                <div class="service-price">
                                    <span class="price-label">Từ:</span>
                                    <span class="price-value">
                                        {{ number_format($service->base_price + $service->price_per_unit, 0, ',', '.') }}đ
                                    </span>
                                </div>

                                <a href="{{ route('buff.show', $service) }}" class="btn-select">
                                    Chọn →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="alert alert-info text-center py-5">
                <h4>Chưa có dịch vụ</h4>
                <p class="mt-2">Admin đang cập nhật...</p>
            </div>
        @endforelse
                <!-- Quick Info -->
        <div class="info-box" style="margin-bottom: 2.5rem;">
            <h4>⚡ Tại sao chọn dịch vụ buff của chúng tôi?</h4>
            <ul style="margin-bottom: 0;">
                <li>✅ Tăng tương tác thực từ những tài khoản thực</li>
                <li>✅ Hỗ trợ đa nền tảng: Facebook, TikTok, Instagram</li>
                <li>✅ Giá cạnh tranh, không giá ẩn</li>
                <li>✅ Hoàn tiền 100% nếu không hài lòng</li>
            </ul>
        </div>
    </div>
</main>
@endsection
