@extends('layouts.app')

@section('title', $product->name . ' - DungThu.com')
@section('meta_description', Str::limit(strip_tags($product->description), 160))
@section('og_image', asset($product->image))
@section('canonical', route('product.show', $product->slug))

@push('head')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => Str::limit(strip_tags($product->description), 300),
            'image' => asset($product->image),
            'url' => route('product.show', $product->slug),
            'brand' => [
                '@type' => 'Brand',
                'name' => 'DungThu.com',
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('product.show', $product->slug),
                'priceCurrency' => 'VND',
                'price' => (float) $product->effective_price,
                'availability' => $product->isInStock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .product-detail-image {
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .description-content {
            font-size: 1rem;
        }
        .product-detail-image:hover {
            transform: scale(1.02);
        }
        .info-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        .info-badge i {
            font-size: 1.5rem;
            margin-right: 15px;
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
            border-color: #0d6efd !important;
            background: #eff6ff !important;
            box-shadow: 0 3px 12px rgba(13, 110, 253, 0.15);
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
            accent-color: #0d6efd;
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
            color: #0d6efd;
            white-space: nowrap;
        }
        .variant-option-card.active .variant-price-display {
            color: #0b5ed7 !important;
            font-weight: 800;
        }
        .product-notice-banner {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 1px solid #fde68a;
            border-left: 4px solid #f59e0b;
            border-radius: 12px;
            padding: 10px 14px;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 1px 3px rgba(245, 158, 11, 0.08);
        }
        .product-notice-banner:hover {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-color: #f59e0b;
            border-left-color: #d97706;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.18);
        }
        .notice-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 4px;
        }
        .notice-badge {
            background: #f59e0b;
            color: #ffffff;
            font-size: 0.68rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
        }
        .notice-action-btn {
            font-size: 0.76rem;
            font-weight: 700;
            color: #b45309;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }
        .notice-desc {
            font-size: 0.81rem;
            font-weight: 500;
            color: #78350f;
            line-height: 1.45;
            margin: 0;
        }
        @keyframes noticeBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(3px); }
        }
        .animate-bounce {
            animation: noticeBounce 1.4s ease-in-out infinite;
        }
        .nav-tabs .nav-link {
            border: none;
            background: white;
            margin: 0 5px;
            border-radius: 12px;
            padding: 15px 30px;
            color: #6c757d;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        .nav-tabs .nav-link:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102,126,234,0.3);
        }
        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 5px 20px rgba(102,126,234,0.4);
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
            .container {
                padding-left: 15px;
                padding-right: 15px;
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
            }
            h1.fw-bold {
                font-size: 1.4rem;
                line-height: 1.4;
            }
            .lead.text-muted {
                font-size: 0.95rem;
                margin-bottom: 1.2rem !important;
            }
            h2.text-primary {
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
            
            /* Badges */
            .info-badge {
                padding: 12px 15px;
                border-radius: 10px;
                flex-direction: row;
                text-align: left;
                gap: 10px;
            }
            .info-badge i {
                margin-right: 0;
                font-size: 1.4rem;
            }
            
            /* Tabs responsive */
            .nav-tabs.nav-fill {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 5px;
                -webkit-overflow-scrolling: touch;
            }
            .nav-tabs.nav-fill::-webkit-scrollbar {
                display: none;
            }
            .nav-tabs .nav-link {
                padding: 10px 15px;
                margin: 0 4px;
                font-size: 0.85rem;
                white-space: nowrap;
                min-width: 120px;
            }
            
            /* Typography & Spacing inside cards */
            .card-body {
                padding: 1.2rem !important;
            }
            .description-content {
                font-size: 0.95rem;
            }
            .display-4 {
                font-size: 2.2rem;
            }
            .rating-input label {
                font-size: 24px;
            }
        }
    </style>
@endpush

@section('content')
<div class="container py-3">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3" data-aos="fade-down">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Trang chủ') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop') }}">{{ __('Cửa hàng') }}</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-4 col-md-5 mb-4" data-aos="fade-right">
            <img src="{{ $product->image ?? 'https://via.placeholder.com/600' }}" 
                 class="img-fluid product-detail-image w-100" 
                 alt="{{ $product->name }}">
            @include('products.partials.desktop_banners')
        </div>
        
        <div class="col-lg-8 col-md-7" data-aos="fade-left">
            @php
                $hasVariants = $product->hasVariants();
                $sortedVariants = $hasVariants ? $product->activeVariants->sortBy(function($v) {
                    return (float) $v->effective_price;
                })->values() : collect();
                $firstVariant = $sortedVariants->firstWhere('stock', '>', 0) ?? $sortedVariants->first();
                $displayPrice = $firstVariant ? $firstVariant->formatted_price : $product->formatted_price;
                $displayOriginalPrice = $firstVariant ? ($firstVariant->is_on_sale ? $firstVariant->formatted_original_price : '') : ($product->is_on_sale ? $product->formatted_original_price : '');
                $displayIsOnSale = $firstVariant ? $firstVariant->is_on_sale : $product->is_on_sale;
                $displayDiscountPercent = $firstVariant ? $firstVariant->discount_percent : $product->discount_percent;
                $displayStock = $firstVariant ? $firstVariant->stock : $product->stock;
                $canOrder = $hasVariants ? ($sortedVariants->sum('stock') > 0 || ($firstVariant && $firstVariant->stock > 0)) : ($product->stock > 0);
            @endphp

            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1" style="font-size: 11px;">
                    <i class="fas fa-tag me-1"></i>{{ strtoupper($product->category) }}
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

            <h1 class="fw-bold {{ $hasVariants ? 'mb-3' : 'mb-2' }}" style="font-size: 1.35rem; line-height: 1.3;">{{ $product->name }}</h1>
            
            @if(!$hasVariants)
            <p class="text-muted mb-2" style="font-size: 0.86rem; line-height: 1.45;">{{ Str::limit($product->description, 130, '...') }}</p>

            <!-- Compact Price Bar -->
            <div class="mb-3 px-3 py-2 bg-light rounded-3 border" id="mainPriceContainer">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-baseline gap-2 flex-wrap">
                        <h2 class="text-primary fw-bold mb-0" style="font-size: 1.55rem; line-height: 1;" id="mainPriceDisplay">{{ $displayPrice }}</h2>
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
                            <i class="fas fa-layer-group text-primary"></i> {{ __('Chọn gói / Thời gian') }}
                        </label>
                        <small class="text-muted" style="font-size: 0.72rem;">{{ __('Click để chọn gói') }}</small>
                    </div>
                    <div class="variant-options-grid" id="variantOptionsList">
                        @foreach($sortedVariants as $index => $variant)
                            @php
                                $isAvailable = $variant->stock > 0;
                                $isSelected = $firstVariant && $firstVariant->id === $variant->id;
                            @endphp
                            <label class="variant-option-card {{ $isSelected ? 'active' : '' }} {{ !$isAvailable ? 'disabled' : '' }}" 
                                   data-variant-id="{{ $variant->id }}">
                                <div class="d-flex align-items-center gap-2 flex-grow-1" style="min-width: 0;">
                                    <input type="radio" name="variant_id" value="{{ $variant->id }}" 
                                           class="form-check-input mt-0 variant-radio" 
                                           {{ $isSelected ? 'checked' : '' }}
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
                                    <div class="fw-bold text-primary variant-price-display">{{ $variant->formatted_price }}</div>
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

                <!-- Product Info Notice -->
                <div class="product-notice-banner mt-3" 
                     onclick="scrollToProductDetails()" 
                     role="button"
                     tabindex="0"
                     title="{{ __('Nhấp để cuộn xuống xem thông tin chi tiết sản phẩm') }}">
                    <div class="notice-header">
                        <span class="notice-badge">
                            <i class="fas fa-exclamation-triangle me-1"></i>{{ __('LƯU Ý') }}
                        </span>
                        <span class="notice-action-btn">
                            {{ __('Xem chi tiết') }} <i class="fas fa-arrow-down ms-1 animate-bounce"></i>
                        </span>
                    </div>
                    <p class="notice-desc">
                        {{ __('Vui lòng kéo xuống đọc kỹ thông tin sản phẩm trước khi mua!') }}
                    </p>
                </div>

                <div class="d-flex gap-2 mt-3 flex-wrap align-items-center">
                    <button type="submit" id="btnAddToCart" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold shadow-sm flex-grow-1" style="font-size: 0.92rem;">
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

            <div class="mt-5">
                <h5 class="fw-bold mb-3"><i class="fas fa-star text-warning"></i> {{ __('Ưu điểm nổi bật') }}</h5>
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="info-badge">
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <strong>{{ __('Chính hãng 100%') }}</strong><br>
                                <small>{{ __('Cam kết hàng thật') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-badge" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="fas fa-tools"></i>
                            <div>
                                <strong>{{ __('Bảo hành 12 tháng') }}</strong><br>
                                <small>{{ __('Đổi trả miễn phí') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-badge" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="fas fa-shipping-fast"></i>
                            <div>
                                <strong>{{ __('Giao hàng nhanh') }}</strong><br>
                                <small>{{ __('Toàn quốc 24h') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-badge" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <i class="fas fa-headset"></i>
                            <div>
                                <strong>{{ __('Hỗ trợ 24/7') }}</strong><br>
                                <small>{{ __('Tư vấn miễn phí') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabs Section -->
    <div class="row mt-5" id="productSpecsSection">
        <div class="col-12">
            <ul class="nav nav-tabs nav-fill border-0" id="productTabs" role="tablist" data-aos="fade-up">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold" id="features-tab" data-bs-toggle="tab" 
                            data-bs-target="#features" type="button" role="tab">
                        <i class="fas fa-star me-2"></i>{{ __('Tính Năng Nổi Bật') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" id="description-tab" data-bs-toggle="tab" 
                            data-bs-target="#description" type="button" role="tab">
                        <i class="fas fa-align-left me-2"></i>{{ __('Mô Tả Chi Tiết') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" id="reviews-tab" data-bs-toggle="tab" 
                            data-bs-target="#reviews" type="button" role="tab">
                        <i class="fas fa-comments me-2"></i>{{ __('Đánh Giá Sản Phẩm') }}
                    </button>
                </li>
            </ul>

            <div class="tab-content mt-4" id="productTabsContent">
                <!-- Features Tab -->
                <div class="tab-pane fade show active" id="features" role="tabpanel" data-aos="fade-up">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4">
                                <i class="fas fa-star text-warning me-2"></i>{{ __('Tính Năng Nổi Bật') }}
                            </h4>
                            @if($product->features && $product->features->count() > 0)
                                <div class="row g-3">
                                    @foreach($product->features as $feature)
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="me-3">
                                                    <i class="{{ $feature->icon }} fs-4" style="color: {{ $feature->color }}"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-1">{{ $feature->name }}</h6>
                                                    @if($feature->description)
                                                        <p class="text-muted mb-0">{{ $feature->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    {{ __('Chưa có thông tin tính năng cho sản phẩm này.') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Description Tab -->
                <div class="tab-pane fade" id="description" role="tabpanel" data-aos="fade-up">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4">
                                <i class="fas fa-align-left text-primary me-2"></i>{{ __('Mô Tả Chi Tiết') }}
                            </h4>
                            <div class="text-muted mb-4 description-content" style="line-height: 1.8;" >{{ $product->description }}</div>
                            
                            <div class="border-top pt-4 mt-4">
                                <h5 class="fw-bold text-primary mb-4">
                                    <i class="fas fa-cube me-2"></i>{{ __('Thông Số Kỹ Thuật') }}
                                </h5>
                                <div class="row g-4">
                                    <div class="col-lg-6">
                                        <div class="p-3 bg-light rounded-3 mb-3">
                                            <i class="fas fa-list-ul text-primary me-2"></i><strong>{{ __('Danh mục:') }}</strong>
                                            <p class="ms-4 mb-0 text-muted">{{ strtoupper($product->category) }}</p>
                                        </div>
                                        <div class="p-3 bg-light rounded-3 mb-3">
                                            <i class="fas fa-barcode text-primary me-2"></i><strong>SKU:</strong>
                                            <p class="ms-4 mb-0 text-muted">#{{ $product->id }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="p-3 bg-light rounded-3 mb-3">
                                            <i class="fas fa-check-circle text-success me-2"></i><strong>{{ __('Tình trạng:') }}</strong>
                                            <p class="ms-4 mb-0 text-muted">{{ $product->stock > 0 ? __('Còn hàng') : __('Hết hàng') }}</p>
                                        </div>
                                        <div class="p-3 bg-light rounded-3 mb-3">
                                            <i class="fas fa-globe text-primary me-2"></i><strong>{{ __('Xuất xứ:') }}</strong>
                                            <p class="ms-4 mb-0 text-muted">{{ __('Chính hãng') }}</p>
                                        </div>
                                    </div>
                                </div>

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
                                        <div class="mt-4">
                                            <div class="p-3 bg-light rounded-3 text-dark border-start border-4 border-primary" style="white-space: pre-line; line-height: 1.8; font-size: 0.95rem;">{!! nl2br(e($textContent)) !!}</div>
                                        </div>
                                    @elseif($hasTableSpecs)
                                        <div class="row g-4 mt-2">
                                            @foreach($specsData as $key => $value)
                                                @if($key !== '_type' && !empty($value))
                                                    <div class="col-lg-6">
                                                        <div class="p-3 bg-light rounded-3 mb-3">
                                                            <i class="fas fa-info-circle text-primary me-2"></i><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                            <p class="ms-4 mb-0 text-muted">{{ is_array($value) ? implode(', ', $value) : $value }}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @elseif(!empty(trim($fallbackContent)))
                                        <div class="mt-4">
                                            <div class="p-3 bg-light rounded-3 text-dark border-start border-4 border-primary" style="white-space: pre-line; line-height: 1.8; font-size: 0.95rem;">{!! nl2br(e($fallbackContent)) !!}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="border-top pt-4 mt-4">
                                <h5 class="fw-bold text-primary mb-4">
                                    <i class="fas fa-box-open me-2"></i>{{ __('Thông Tin Thêm') }}
                                </h5>
                                <div class="row g-4">
                                    <div class="col-lg-6">
                                        <div class="p-3 bg-light rounded-3 mb-3">
                                            <i class="fas fa-shield-alt text-primary me-2"></i><strong>{{ __('Bảo hành:') }}</strong>
                                            <p class="ms-4 mb-0 text-muted">{{ __('12 tháng') }}</p>
                                        </div>
                                        <div class="p-3 bg-light rounded-3 mb-3">
                                            <i class="fas fa-credit-card text-primary me-2"></i><strong>{{ __('Thanh toán:') }}</strong>
                                            <p class="ms-4 mb-0 text-muted">COD, Banking, Transfer</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="p-3 bg-light rounded-3 mb-3">
                                            <i class="fas fa-shipping-fast text-primary me-2"></i><strong>{{ __('Giao hàng:') }}</strong>
                                            <p class="ms-4 mb-0 text-muted">{{ __('Toàn quốc (24-48h)') }}</p>
                                        </div>
                                        <div class="p-3 bg-light rounded-3 mb-3">
                                            <i class="fas fa-undo text-primary me-2"></i><strong>{{ __('Đổi trả:') }}</strong>
                                            <p class="ms-4 mb-0 text-muted">{{ __('7 ngày từ ngày mua') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info mt-4 rounded-4">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>{{ __('Lưu ý:') }}</strong> {{ __('Sản phẩm được đóng gói cẩn thận, kiểm tra kỹ càng trước khi giao hàng. Quý khách vui lòng kiểm tra sản phẩm trước khi thanh toán.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel" data-aos="fade-up">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4">
                                <i class="fas fa-comments text-warning me-2"></i>{{ __('Đánh Giá Sản Phẩm') }}
                            </h4>
                            
                            <!-- Overall Rating -->
                            <div class="text-center mb-5 p-4 bg-light rounded-4">
                                <div class="display-4 fw-bold text-primary mb-2">{{ number_format($averageRating, 1) }}</div>
                                <div class="mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($averageRating))
                                            <i class="fas fa-star text-warning"></i>
                                        @elseif($i - $averageRating < 1)
                                            <i class="fas fa-star-half-alt text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-muted mb-0">{{ __('Dựa trên') }} <strong>{{ $totalReviews }}</strong> {{ __('đánh giá') }}</p>
                            </div>

                            <!-- Comment Form (Only for logged in users) -->
                            @auth
                            <div class="card bg-light border-0 mb-4 rounded-4">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-3">
                                        <i class="fas fa-edit text-primary me-2"></i>{{ __('Viết đánh giá của bạn') }}
                                    </h5>
                                    <form action="{{ route('product.comment', $product->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">{{ __('Đánh giá của bạn') }} <span class="text-danger">*</span></label>
                                            <div class="rating-input mb-2">
                                                <input type="radio" name="rating" value="5" id="star5" required>
                                                <label for="star5" title="5 sao"><i class="fas fa-star"></i></label>
                                                <input type="radio" name="rating" value="4" id="star4">
                                                <label for="star4" title="4 sao"><i class="fas fa-star"></i></label>
                                                <input type="radio" name="rating" value="3" id="star3">
                                                <label for="star3" title="3 sao"><i class="fas fa-star"></i></label>
                                                <input type="radio" name="rating" value="2" id="star2">
                                                <label for="star2" title="2 sao"><i class="fas fa-star"></i></label>
                                                <input type="radio" name="rating" value="1" id="star1">
                                                <label for="star1" title="1 sao"><i class="fas fa-star"></i></label>
                                            </div>
                                            @error('rating')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">{{ __('Nhận xét') }} <span class="text-danger">*</span></label>
                                            <textarea name="comment" class="form-control rounded-3" rows="4" 
                                                      placeholder="{{ __('Chia sẻ trải nghiệm của bạn về sản phẩm...') }}" required>{{ old('comment') }}</textarea>
                                            @error('comment')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                                            <i class="fas fa-paper-plane me-2"></i>{{ __('Gửi đánh giá') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-info rounded-4 mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                {!! __('Bạn cần :login để viết đánh giá.', ['login' => '<a href="'.route('login').'" class="alert-link fw-bold">'.__('đăng nhập').'</a>']) !!}
                            </div>
                            @endauth

                            <!-- Individual Reviews -->
                            @forelse($product->comments as $comment)
                            <div class="review-item mb-4 pb-4 border-bottom">
                                <div class="d-flex align-items-start">
                                    <div class="avatar me-3">
                                        <div class="bg-{{ ['primary', 'success', 'info', 'warning', 'danger'][rand(0, 4)] }} text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px; font-weight: bold;">
                                            {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="fw-bold mb-0">{{ $comment->user->name }}</h6>
                                            <small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                        <div class="mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $comment->rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <p class="text-muted mb-0">{{ $comment->comment }}</p>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-5">
                                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                <p class="text-muted">{{ __('Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên!') }}</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
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
                        <div class="mt-4">
                            <div class="p-3 bg-light rounded-3 text-dark border-start border-4 border-primary" style="white-space: pre-line; line-height: 1.8; font-size: 0.95rem;">
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

        function scrollToProductDetails() {
            const target = document.getElementById('productSpecsSection') 
                        || document.getElementById('description')
                        || document.querySelector('.nav-tabs')
                        || document.querySelector('.card:has(#description)');
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                window.scrollBy({ top: 400, behavior: 'smooth' });
            }
        }
    </script>
@endpush
