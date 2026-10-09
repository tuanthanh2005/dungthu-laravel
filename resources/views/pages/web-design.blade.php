@extends('layouts.app')

@section('title', $heroTitle ?? __('Thiết kế website giá rẻ'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<style>
    .web-design-card {
        background: #fff;
        border: 1px solid rgba(0,0,0,0.06);
        border-radius: 16px;
        padding: 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
        box-shadow: 0 10px 24px rgba(0,0,0,0.06);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .web-design-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 30px rgba(0,0,0,0.1);
    }
    .web-design-card .price {
        font-weight: 800;
        font-size: 1.3rem;
        color: #1d4ed8;
    }
    .web-design-card ul {
        padding-left: 18px;
        margin: 12px 0 0;
        flex-grow: 1;
    }
    .web-design-card li {
        margin-bottom: 7px;
        line-height: 1.45;
    }
    .pagination .page-link {
        border-radius: 8px;
        margin: 0 3px;
        color: #1d4ed8;
        font-weight: 600;
    }
    .pagination .page-item.active .page-link {
        background-color: #ff5e00;
        border-color: #ff5e00;
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="container py-2" style="margin-top: 50px;">
    {{-- Hero Section --}}
    <div class="row mb-4" data-aos="fade-down">
        <div class="col-12 text-center">
            <h1 class="fw-bold mb-3">{{ $heroTitle }}</h1>
            @if(!empty($heroSubtitle))
                <p class="text-muted fs-6 mb-0">{{ $heroSubtitle }}</p>
            @endif
        </div>
    </div>

    {{-- Service Intro Card --}}
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
                        <div class="flex-grow-1">
                            @if(!empty($serviceTag))
                                <span class="text-primary fw-bold text-uppercase ls-1" style="font-size: 13px;">{{ $serviceTag }}</span>
                            @endif
                            <h3 class="fw-bold section-title mb-2 mt-1">{{ $serviceTitle }}</h3>
                            @if(!empty($serviceDesc))
                                <p class="text-muted mb-0" style="line-height: 1.6;">{{ $serviceDesc }}</p>
                            @endif
                        </div>
                        <a href="javascript:void(0)" class="btn btn-primary rounded-pill px-4 py-2 fw-bold text-nowrap" data-bs-toggle="modal" data-bs-target="#quickContactModal">
                            <i class="fa-solid fa-headset me-1"></i> {{ $btnText ?: __('Nhận tư vấn') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Packages Grid: 1 hàng 3 gói, thêm gói thứ 4 sẽ tự động xuống hàng, phân trang 6 gói/trang --}}
    <div class="row row-cols-1 row-cols-md-3 g-3 g-md-4 mb-4" data-aos="fade-up">
        @forelse($packages as $package)
            <div class="col">
                <div class="web-design-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold mb-0 text-dark fs-5">{{ $package->name }}</h6>
                        @if($package->badge)
                            <span class="badge bg-{{ $package->badge_color ?? 'primary' }} rounded-pill px-2.5 py-1">
                                {{ $package->badge }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="price mb-2">
                        @if(app()->getLocale() === 'en')
                            ${{ number_format($package->price / \App\Models\SiteSetting::getValue('usd_exchange_rate', 25000), 2) }}
                        @else
                            {{ number_format($package->price, 0, ',', '.') }}đ
                        @endif
                    </div>

                    @php
                        $features = is_array($package->features) ? $package->features : [];
                    @endphp

                    @if(!empty($features))
                        <ul class="text-muted small">
                            @foreach($features as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="mt-3 pt-3 border-top text-center">
                        <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary rounded-pill w-100 fw-bold py-1.5" data-bs-toggle="modal" data-bs-target="#quickContactModal">
                            {{ __('Đăng ký gói này') }}
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">{{ __('Hiện chưa có gói dịch vụ nào được công khai.') }}</p>
            </div>
        @endforelse
    </div>

    {{-- Phân trang (Từ gói thứ 7 trở đi sẽ hiển thị phân trang) --}}
    @if($packages->hasPages())
        <div class="d-flex justify-content-center mt-4 mb-3">
            {{ $packages->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    if (typeof AOS !== 'undefined') {
        AOS.init({ duration: 800, once: true });
    }
</script>
@endpush
