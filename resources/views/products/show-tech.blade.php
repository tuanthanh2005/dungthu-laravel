@extends('layouts.app')

@section('title', $product->name . ' - DungThu.com')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .tech-wrapper {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 50%, #80deea 100%);
            padding: 16px 0 40px;
            min-height: 100vh;
        }
        .description-content {
            font-size: 1rem;
        }
        .tech-card {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.3);
        }
        .product-detail-image {
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            transition: transform 0.3s;
            border: 3px solid #00d4ff;
        }
        .product-detail-image:hover {
            transform: scale(1.05) rotateY(5deg);
        }
        .tech-badge {
            background: linear-gradient(135deg, #00d4ff 0%, #0099cc 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            box-shadow: 0 5px 20px rgba(0,212,255,0.3);
            cursor: default;
            pointer-events: none;
        }
        .tech-badge i {
            font-size: 2rem;
            margin-right: 20px;
        }
        .spec-table {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
        }
        .spec-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .spec-row:last-child {
            border-bottom: none;
        }
        /* Variant Option Selector - Clean 1-column Stack List */
        .variant-options-grid {
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-height: 250px;
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 4px;
        }
        .variant-options-grid::-webkit-scrollbar {
            width: 4px;
        }
        .variant-options-grid::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .variant-option-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 9px 12px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            position: relative;
            user-select: none;
            gap: 10px;
            min-height: 48px;
        }
        .variant-option-card.active {
            border-color: #0099cc !important;
            background: #f0faff !important;
            box-shadow: 0 3px 12px rgba(0, 153, 204, 0.15);
        }
        .variant-option-card.disabled {
            opacity: 0.55;
            background: #f8f9fa;
            cursor: not-allowed;
            border-color: #e9ecef;
        }
        .variant-radio {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #0099cc;
            flex-shrink: 0;
        }
        .variant-name {
            font-size: 0.85rem;
            font-weight: 600;
            line-height: 1.35;
            color: #1e293b;
            word-break: break-word;
        }
        .variant-price-display {
            font-size: 0.9rem;
            font-weight: 700;
            color: #0099cc;
            white-space: nowrap;
        }
        .variant-option-card.active .variant-price-display {
            color: #0077aa !important;
            font-weight: 800;
        }
        .product-info-compact {
            padding: 22px 24px;
        }
        @media (max-width: 768px) {
            .product-info-compact {
                padding: 18px 16px;
            }
        }
        .tech-tab.nav-link {
            border: none;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            margin: 0 5px;
            border-radius: 15px;
            padding: 18px 35px;
            color: white;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .tech-tab.nav-link i {
            font-size: 28px;
            margin: 0 !important;
            filter: drop-shadow(0 2px 3px rgba(0,0,0,0.3));
        }
        .tech-tab.nav-link span {
            font-size: 15px;
            letter-spacing: 0.5px;
        }
        
        /* Tab Tính Năng - Xanh cyan sáng */
        #features-tab {
            background: linear-gradient(135deg, #06d6a0 0%, #1b9aaa 100%);
            border: 2px solid rgba(255,255,255,0.5);
        }
        #features-tab:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(6,214,160,0.5), 0 0 20px rgba(6,214,160,0.4);
            border-color: rgba(255,255,255,0.7);
        }
        #features-tab.active {
            box-shadow: 0 12px 35px rgba(6,214,160,0.6), 0 0 25px rgba(6,214,160,0.5);
            border-color: rgba(255,255,255,0.8);
        }
        
        /* Tab Mô Tả - Vàng cam sáng */
        #description-tab {
            background: linear-gradient(135deg, #ffa502 0%, #ff6348 100%);
            border: 2px solid rgba(255,255,255,0.5);
        }
        #description-tab:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(255,165,2,0.5), 0 0 20px rgba(255,165,2,0.4);
            border-color: rgba(255,255,255,0.7);
        }
        #description-tab.active {
            box-shadow: 0 12px 35px rgba(255,165,2,0.6), 0 0 25px rgba(255,165,2,0.5);
            border-color: rgba(255,255,255,0.8);
        }
        
        /* Tab Đánh Giá - Hồng tím sáng */
        #reviews-tab {
            background: linear-gradient(135deg, #ee5a6f 0%, #c44569 100%);
            border: 2px solid rgba(255,255,255,0.5);
        }
        #reviews-tab:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(238,90,111,0.5), 0 0 20px rgba(238,90,111,0.4);
            border-color: rgba(255,255,255,0.7);
        }
        #reviews-tab.active {
            box-shadow: 0 12px 35px rgba(238,90,111,0.6), 0 0 25px rgba(238,90,111,0.5);
            border-color: rgba(255,255,255,0.8);
        }
        
        .rating-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 5px;
        }
        .rating-input input {
            display: none;
        }
        .rating-input label {
            cursor: pointer;
            font-size: 28px;
            color: #ddd;
            transition: color 0.2s;
        }
        .rating-input label:hover,
        .rating-input label:hover ~ label,
        .rating-input input:checked ~ label {
            color: #ffc107;
        }

        /* --- MOBILE RESPONSIVE TWEAKS --- */
        @media (max-width: 768px) {
            .tech-wrapper {
                padding: 15px 0;
            }
            .tech-card {
                padding: 15px;
                border-radius: 16px;
            }
            /* Variant Options on Mobile: Expands naturally without scroll constraint */
            .variant-options-grid {
                max-height: none !important;
                overflow-y: visible !important;
                padding-right: 0 !important;
                gap: 10px !important;
            }
            .variant-option-card {
                padding: 10px 12px !important;
                min-height: 52px !important;
                border-radius: 12px !important;
                gap: 10px !important;
            }
            .variant-name {
                font-size: 0.86rem !important;
                line-height: 1.4 !important;
            }
            .variant-price-display {
                font-size: 0.92rem !important;
            }
            .product-detail-image {
                border-radius: 12px;
                border-width: 2px;
            }
            h1.fw-bold {
                font-size: 1.4rem;
                line-height: 1.4;
            }
            .lead.text-muted {
                font-size: 0.95rem;
                margin-bottom: 1rem !important;
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            h2.text-info {
                font-size: 1.6rem;
            }
            
            /* Buttons layout */
            .d-flex.gap-3.mb-3 {
                gap: 10px !important;
                flex-direction: column;
            }
            .btn-lg {
                padding: 12px 15px !important;
                font-size: 1rem;
                width: 100%;
                border-radius: 12px !important;
            }
            
            /* Tabs responsive */
            .nav-tabs.nav-fill {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 5px;
                -webkit-overflow-scrolling: touch;
                border-radius: 12px;
            }
            .nav-tabs.nav-fill::-webkit-scrollbar {
                display: none;
            }
            .tech-tab.nav-link {
                padding: 10px 5px;
                margin: 0 4px;
                border-radius: 12px;
                min-width: 90px;
                gap: 4px;
            }
            .tech-tab.nav-link i {
                font-size: 20px;
            }
            .tech-tab.nav-link span {
                font-size: 11px;
                white-space: nowrap;
            }
            
            /* Features Badges */
            .tech-badge {
                padding: 15px;
                border-radius: 12px;
                flex-direction: row;
                text-align: left;
                gap: 15px;
            }
            .tech-badge i {
                margin-right: 0;
                font-size: 1.8rem;
            }
            
            /* Typography & Spacing inside cards */
            .description-content {
                font-size: 0.95rem;
            }
            .alert {
                padding: 12px;
                font-size: 0.9rem;
            }
            .bg-light.p-4 {
                padding: 15px !important;
                border-radius: 12px !important;
            }
        }
    </style>
@endpush

@section('content')
<div class="tech-wrapper">
    <div class="container py-1">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3" data-aos="fade-down">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #06d6a0; font-weight: 600;">{{ __('Trang chủ') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}" style="color: #06d6a0; font-weight: 600;">{{ __('Cửa hàng') }}</a></li>
                <li class="breadcrumb-item active" style="color: #ff6348; font-weight: 700;">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-4 col-md-5 mb-4" data-aos="fade-right">
                <div class="tech-card">
                    <img src="{{ $product->image ?? 'https://via.placeholder.com/600' }}" 
                         class="img-fluid product-detail-image w-100" 
                         alt="{{ $product->name }}">
                    @include('products.partials.desktop_banners')
                </div>
            </div>
            
            <div class="col-lg-8 col-md-7" data-aos="fade-left">
                <div class="tech-card product-info-compact">
                    @php
                        $hasVariants = $product->hasVariants();
                        $firstVariant = $hasVariants ? $product->activeVariants->first() : null;
                        $displayPrice = $firstVariant ? $firstVariant->formatted_price : $product->formatted_price;
                        $displayOriginalPrice = $firstVariant ? ($firstVariant->is_on_sale ? $firstVariant->formatted_original_price : '') : ($product->is_on_sale ? $product->formatted_original_price : '');
                        $displayIsOnSale = $firstVariant ? $firstVariant->is_on_sale : $product->is_on_sale;
                        $displayDiscountPercent = $firstVariant ? $firstVariant->discount_percent : $product->discount_percent;
                        $displayStock = $firstVariant ? $firstVariant->stock : $product->stock;
                        $canOrder = $hasVariants ? ($product->activeVariants->sum('stock') > 0 || $firstVariant->stock > 0) : ($product->stock > 0);
                    @endphp

                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2 py-1" style="font-size: 11px;">
                            <i class="fas fa-microchip me-1"></i>{{ strtoupper($product->category) }}
                        </span>
                        <div id="stockStatusContainer">
                            <div class="d-flex align-items-center gap-1" id="stockAlertSuccess" style="{{ $displayStock > 0 ? '' : 'display: none !important;' }}">
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1" style="font-size: 0.76rem;">
                                    <i class="fas fa-check-circle me-1"></i><span id="stockText">{{ __('Còn hàng') }} ({{ $displayStock }})</span>
                                </span>
                            </div>
                            <div class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1" id="stockAlertDanger" style="{{ $displayStock <= 0 ? 'display: inline-block;' : 'display: none !important;' }}; font-size: 0.76rem;">
                                <i class="fas fa-times-circle me-1"></i> {{ __('Hết hàng') }}
                            </div>
                        </div>
                    </div>

                    <h1 class="fw-bold {{ $hasVariants ? 'mb-3' : 'mb-2' }}" style="color: #0f2027; font-size: 1.35rem; line-height: 1.3;">{{ $product->name }}</h1>
                    
                    @if(!$hasVariants)
                    <p class="text-muted mb-2" style="font-size: 0.86rem; line-height: 1.45;">{{ Str::limit($product->description, 130, '...') }}</p>

                    <!-- Compact Price Bar -->
                    <div class="mb-3 px-3 py-2 bg-light rounded-3 border" id="mainPriceContainer">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-baseline gap-2 flex-wrap">
                                <h2 class="text-info fw-bold mb-0" style="font-size: 1.55rem; line-height: 1;" id="mainPriceDisplay">{{ $displayPrice }}</h2>
                                <div class="d-flex align-items-center gap-1" id="mainSaleContainer" style="{{ $displayIsOnSale ? '' : 'display: none !important;' }}">
                                    <span class="text-muted text-decoration-line-through small" id="mainOriginalPriceDisplay">{{ $displayOriginalPrice }}</span>
                                    <span class="badge bg-danger" style="font-size: 0.7rem;" id="mainDiscountBadge">-{{ $displayDiscountPercent }}%</span>
                                </div>
                            </div>
                            <small class="text-muted" style="font-size: 0.72rem;"><i class="fas fa-shield-alt text-success me-1"></i>{{ __('Đã gồm VAT & Bảo hành') }}</small>
                        </div>
                    </div>
                    @endif
                    
                    @if($canOrder)
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" id="addToCartForm">
                        @csrf
                        
                        @if($hasVariants)
                        <div class="variants-selector mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="fw-bold text-uppercase d-flex align-items-center gap-1 mb-0" style="font-size: 0.78rem; letter-spacing: 0.5px; color: #334155;">
                                    <i class="fas fa-layer-group text-info"></i> {{ __('Chọn gói / Thời gian') }}
                                </label>
                                <small class="text-muted" style="font-size: 0.72rem;">{{ __('Click để chọn gói') }}</small>
                            </div>
                            <div class="variant-options-grid" id="variantOptionsList">
                                @foreach($product->activeVariants as $index => $variant)
                                    @php
                                        $isAvailable = $variant->stock > 0;
                                    @endphp
                                    <label class="variant-option-card {{ $loop->first ? 'active' : '' }} {{ !$isAvailable ? 'disabled' : '' }}" 
                                           data-variant-id="{{ $variant->id }}">
                                        <div class="d-flex align-items-center gap-2 flex-grow-1" style="min-width: 0;">
                                            <input type="radio" name="variant_id" value="{{ $variant->id }}" 
                                                   class="form-check-input mt-0 variant-radio" 
                                                   {{ $loop->first ? 'checked' : '' }}
                                                   {{ !$isAvailable ? 'disabled' : '' }}
                                                   data-price="{{ $variant->formatted_price }}"
                                                   data-original-price="{{ $variant->is_on_sale ? $variant->formatted_original_price : '' }}"
                                                   data-is-on-sale="{{ $variant->is_on_sale ? '1' : '0' }}"
                                                   data-discount-percent="{{ $variant->discount_percent }}"
                                                   data-stock="{{ $variant->stock }}"
                                                   data-name="{{ $variant->name_localized }}"
                                                   data-specs="{{ $variant->specs }}">
                                            <div class="flex-grow-1" style="min-width: 0;">
                                                <div class="fw-bold variant-name" title="{{ $variant->name_localized }}">{{ $variant->name_localized }}</div>
                                                @if($variant->duration_text)
                                                    <small class="text-muted d-block" style="font-size: 0.7rem; line-height: 1;"><i class="far fa-clock me-1"></i>{{ $variant->duration_text }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-end flex-shrink-0 ms-2">
                                            <div class="fw-bold text-info variant-price-display">{{ $variant->formatted_price }}</div>
                                            @if($variant->is_on_sale)
                                                <small class="text-muted text-decoration-line-through d-block" style="font-size: 0.7rem; line-height: 1;">{{ $variant->formatted_original_price }}</small>
                                            @endif
                                            @if(!$isAvailable)
                                                <span class="badge bg-secondary" style="font-size: 0.65rem;">{{ __('Hết') }}</span>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="d-flex gap-2 mt-3 flex-wrap align-items-center">
                            <button type="submit" id="btnAddToCart" class="btn rounded-pill px-4 py-2 fw-semibold text-white shadow-sm flex-grow-1" 
                                    style="background: linear-gradient(135deg, #00d4ff 0%, #0099cc 100%); border: none; font-size: 0.92rem;">
                                <i class="fas fa-shopping-cart me-1"></i> {{ __('Thêm vào giỏ') }}
                            </button>
                            @if($product->delivery_type === 'digital')
                            <button type="submit" id="btnBuyNow" formaction="{{ route('cart.buy-now', $product->id) }}" data-buy-now class="btn btn-warning rounded-pill px-4 py-2 fw-bold shadow-sm text-dark flex-grow-1" style="font-size: 0.92rem;">
                                <i class="fas fa-bolt me-1"></i> {{ __('Mua ngay') }}
                            </button>
                            @endif
                            <a href="{{ route('shop') }}" class="btn btn-outline-secondary rounded-pill px-3 py-2 text-nowrap" style="font-size: 0.85rem;" title="{{ __('Tiếp tục mua sắm') }}">
                                <i class="fas fa-arrow-left me-1"></i> {{ __('Tiếp tục') }}
                            </a>
                        </div>
                    </form>
                    @else
                    <div class="d-flex gap-2 mt-3 flex-wrap">
                        <button type="button" class="btn btn-secondary rounded-pill px-4 py-2 flex-grow-1" disabled style="font-size: 0.9rem;">
                            <i class="fas fa-ban me-1"></i> {{ __('Hết hàng') }}
                        </button>
                        <a href="{{ route('shop') }}" class="btn btn-outline-secondary rounded-pill px-3 py-2" style="font-size: 0.85rem;">
                            <i class="fas fa-arrow-left me-1"></i> {{ __('Tiếp tục mua') }}
                        </a>
                    </div>
                    @endif

                </div>
            </div>
        </div>

        <!-- Tech Specs Block -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="tech-card">
                    <h5 class="fw-bold mb-4" style="color: #0f2027;"><i class="fas fa-cogs me-2 text-info"></i>{{ __('Thông Số Kỹ Thuật') }}</h5>
                    <div id="variantSpecContentContainer">
                        @php
                            $isText = $product->isTextSpecs();
                            $textContent = $product->getSpecTextContent();
                            $specsData = $product->specs;
                            $hasTableSpecs = is_array($specsData) && !$isText && count(array_filter($specsData, function($v, $k) {
                                return $k !== '_type' && !empty($v);
                            }, ARRAY_FILTER_USE_BOTH)) > 0;
                            $fallbackContent = !empty(trim($textContent)) ? $textContent : $product->description;
                        @endphp

                        @if($isText && !empty(trim($textContent)))
                            <div class="p-4 bg-light rounded-4 border-start border-4 border-info">
                                <div class="text-dark" style="white-space: pre-line; line-height: 1.8; font-size: 0.98rem;">
                                    {!! nl2br(e($textContent)) !!}
                                </div>
                            </div>
                        @elseif($hasTableSpecs)
                            <div class="row g-3">
                                @foreach($specsData as $key => $value)
                                    @if($key !== '_type' && !empty($value))
                                    <div class="col-md-6 col-lg-4">
                                        <div class="p-3 bg-light rounded-4 h-100 d-flex justify-content-between align-items-center">
                                            <span class="text-muted me-2" style="font-weight: 500;">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                            <strong class="text-dark text-end" style="font-weight: 700;">{{ is_array($value) ? implode(', ', $value) : $value }}</strong>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        @elseif(!empty(trim($fallbackContent)))
                            <div class="p-4 bg-light rounded-4 border-start border-4 border-info">
                                <div class="text-dark" style="white-space: pre-line; line-height: 1.8; font-size: 0.98rem;">
                                    {!! nl2br(e($fallbackContent)) !!}
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                {{ __('Chưa có thông tin thông số kỹ thuật cho sản phẩm này.') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabs Section -->
        <div class="row mt-5">
            <div class="col-12">
                <ul class="nav nav-tabs nav-fill border-0 mb-4" id="productTabs" role="tablist" data-aos="fade-up">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link tech-tab active" id="features-tab" data-bs-toggle="tab" 
                                data-bs-target="#features" type="button" role="tab">
                            <i class="fas fa-microchip"></i>
                            <span>{{ __('Tính Năng') }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link tech-tab" id="description-tab" data-bs-toggle="tab" 
                                data-bs-target="#description" type="button" role="tab">
                            <i class="fas fa-list-alt"></i>
                            <span>{{ __('Mô Tả') }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link tech-tab" id="reviews-tab" data-bs-toggle="tab" 
                                data-bs-target="#reviews" type="button" role="tab">
                            <i class="fas fa-comments"></i>
                            <span>{{ __('Đánh Giá') }}</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="productTabsContent">
                    <!-- Features Tab -->
                    <div class="tab-pane fade show active" id="features" role="tabpanel" data-aos="fade-up">
                        <div class="tech-card">
                            <h4 class="fw-bold mb-4">
                                <i class="fas fa-star text-warning me-2"></i>{{ __('Tính Năng Nổi Bật') }}
                            </h4>
                            @if($product->features && $product->features->count() > 0)
                            <div class="row g-3">
                                @php
                                $defaultColors = [
                                    '#667eea', // Tím xanh
                                    '#f093fb', // Hồng
                                    '#4facfe', // Xanh dương nhạt
                                    '#43e97b', // Xanh lá
                                    '#fa709a', // Đỏ cam
                                    '#764ba2', // Tím đậm
                                ];
                                @endphp
                                @foreach($product->features as $index => $feature)
                                @php
                                $color = $feature->color ?? $defaultColors[$index % count($defaultColors)];
                                @endphp
                                <div class="col-md-6">
                                    <div class="tech-badge" style="background: linear-gradient(135deg, {{ $color }} 0%, {{ $color }}dd 100%);">
                                        <i class="{{ $feature->icon }}"></i>
                                        <div>
                                            <strong>{{ $feature->name }}</strong><br>
                                            @if($feature->description)
                                            <small>{{ $feature->description }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                {{ __('Chưa có thông tin tính năng nổi bật cho sản phẩm này.') }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Description Tab -->
                    <div class="tab-pane fade" id="description" role="tabpanel" data-aos="fade-up">
                        <div class="tech-card">
                            <h4 class="fw-bold mb-4">
                                <i class="fas fa-align-left text-info me-2"></i>{{ __('Mô Tả Chi Tiết') }}
                            </h4>
                            <div class="text-muted description-content" style="line-height: 1.8;">{!! nl2br(e($product->description)) !!}</div>
                            <hr class="my-4">
                            <div class="alert alert-info rounded-4">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>{{ __('Lưu ý:') }}</strong> {{ __('Sản phẩm công nghệ được kiểm tra kỹ lưỡng trước khi giao hàng. Bảo hành chính hãng 24 tháng tại các trung tâm bảo hành toàn quốc.') }}
                            </div>
                        </div>
                    </div>

                    @include('products.partials.reviews', ['product' => $product, 'averageRating' => $averageRating, 'totalReviews' => $totalReviews])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        AOS.init({ duration: 800, once: true });

        document.addEventListener('DOMContentLoaded', function () {
            const variantRadios = document.querySelectorAll('.variant-radio');
            if (!variantRadios.length) return;

            const mainPrice = document.getElementById('mainPriceDisplay');
            const mainSaleContainer = document.getElementById('mainSaleContainer');
            const mainOriginalPrice = document.getElementById('mainOriginalPriceDisplay');
            const mainDiscountBadge = document.getElementById('mainDiscountBadge');
            const stockAlertSuccess = document.getElementById('stockAlertSuccess');
            const stockAlertDanger = document.getElementById('stockAlertDanger');
            const stockText = document.getElementById('stockText');
            const btnAddToCart = document.getElementById('btnAddToCart');
            const btnBuyNow = document.getElementById('btnBuyNow');
            const specContainer = document.getElementById('variantSpecContentContainer');
            const defaultSpecsHtml = specContainer ? specContainer.innerHTML : '';

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            function updateSpecsUI(radio) {
                if (!specContainer) return;
                const variantSpec = radio && radio.dataset.specs ? radio.dataset.specs.trim() : '';
                if (variantSpec) {
                    specContainer.innerHTML = `
                        <div class="p-4 bg-light rounded-4 border-start border-4 border-info">
                            <div class="text-dark" style="white-space: pre-line; line-height: 1.8; font-size: 0.98rem;">
                                ${escapeHtml(variantSpec).replace(/\n/g, '<br>')}
                            </div>
                        </div>
                    `;
                } else {
                    specContainer.innerHTML = defaultSpecsHtml;
                }
            }

            function updateVariantUI(radio) {
                if (!radio) return;

                // Update technical specs for selected variant
                updateSpecsUI(radio);

                // Active class on cards
                document.querySelectorAll('.variant-option-card').forEach(card => {
                    card.classList.remove('active');
                });
                const card = radio.closest('.variant-option-card');
                if (card) {
                    card.classList.add('active');
                }

                // Update price
                const price = radio.dataset.price;
                const originalPrice = radio.dataset.originalPrice;
                const isOnSale = radio.dataset.isOnSale === '1';
                const discountPercent = radio.dataset.discountPercent;
                const stock = parseInt(radio.dataset.stock, 10);

                if (mainPrice && price) {
                    mainPrice.textContent = price;
                }

                if (mainSaleContainer) {
                    if (isOnSale && originalPrice) {
                        mainSaleContainer.style.setProperty('display', 'flex', 'important');
                        if (mainOriginalPrice) mainOriginalPrice.textContent = originalPrice;
                        if (mainDiscountBadge) mainDiscountBadge.textContent = '-' + discountPercent + '%';
                    } else {
                        mainSaleContainer.style.setProperty('display', 'none', 'important');
                    }
                }

                // Update stock display and button state
                if (stock > 0) {
                    if (stockAlertSuccess) {
                        stockAlertSuccess.style.setProperty('display', 'flex', 'important');
                        if (stockText) stockText.textContent = 'Còn hàng (' + stock + ' sản phẩm)';
                    }
                    if (stockAlertDanger) {
                        stockAlertDanger.style.setProperty('display', 'none', 'important');
                    }
                    if (btnAddToCart) {
                        btnAddToCart.disabled = false;
                        btnAddToCart.innerHTML = '<i class="fas fa-shopping-cart me-2"></i> {{ __("Thêm vào giỏ") }}';
                    }
                    if (btnBuyNow) btnBuyNow.disabled = false;
                } else {
                    if (stockAlertSuccess) {
                        stockAlertSuccess.style.setProperty('display', 'none', 'important');
                    }
                    if (stockAlertDanger) {
                        stockAlertDanger.style.setProperty('display', 'inline-block', 'important');
                    }
                    if (btnAddToCart) {
                        btnAddToCart.disabled = true;
                        btnAddToCart.innerHTML = '<i class="fas fa-ban me-2"></i> {{ __("Hết hàng") }}';
                    }
                    if (btnBuyNow) btnBuyNow.disabled = true;
                }
            }

            variantRadios.forEach(radio => {
                radio.addEventListener('change', function () {
                    updateVariantUI(this);
                });
            });

            // Also click on card to select radio
            document.querySelectorAll('.variant-option-card').forEach(card => {
                card.addEventListener('click', function (e) {
                    if (this.classList.contains('disabled')) return;
                    const radio = this.querySelector('.variant-radio');
                    if (radio && !radio.checked) {
                        radio.checked = true;
                        updateVariantUI(radio);
                    }
                });
            });

            // Initial load
            const checkedRadio = document.querySelector('.variant-radio:checked') || document.querySelector('.variant-radio:not(:disabled)');
            if (checkedRadio) {
                checkedRadio.checked = true;
                updateVariantUI(checkedRadio);
            }
        });
    </script>
@endpush
