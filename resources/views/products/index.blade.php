@extends('layouts.app')

@section('title', 'Cửa Hàng - DungThu.com')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/category-filter.css') }}?v={{ time() }}">
    <style>
        /* Shop Hero Section */
        .shop-hero {
            background: linear-gradient(135deg, rgba(108, 92, 231, 0.1) 0%, rgba(0, 206, 201, 0.1) 100%);
            border-radius: 24px;
            padding: 4rem 2rem;
            margin-top: 90px;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            border: 1px solid rgba(255,255,255,0.5);
        }
        .shop-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(108, 92, 231, 0.15) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            z-index: 0;
        }
        .shop-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            right: -5%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(0, 206, 201, 0.15) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            z-index: 0;
        }
        .shop-hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }
        .shop-title {
            font-weight: 800;
            font-size: 3rem;
            background: linear-gradient(135deg, #6c5ce7 0%, #00cec9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
            letter-spacing: -0.5px;
        }
        .shop-subtitle {
            color: #636e72;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto 2.5rem;
            line-height: 1.6;
        }
        
        /* Modern Search Box */
        .search-container {
            max-width: 700px;
            margin: 0 auto;
            position: relative;
        }
        .search-input-group {
            display: flex;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 50px;
            padding: 8px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            border: 2px solid rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }
        .search-input-group:focus-within {
            border-color: #a29bfe;
            box-shadow: 0 20px 45px rgba(108, 92, 231, 0.15);
            transform: translateY(-2px);
            background: #fff;
        }
        .search-input {
            border: none;
            background: transparent;
            padding: 12px 25px;
            font-size: 1.05rem;
            flex-grow: 1;
            outline: none;
            color: #2d3436;
        }
        .search-input::placeholder {
            color: #a4b0be;
            font-weight: 400;
        }
        .search-btn {
            background: linear-gradient(135deg, #6c5ce7, #81ecec);
            color: white;
            border: none;
            border-radius: 40px;
            padding: 12px 35px;
            font-weight: 600;
            font-size: 1.05rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .search-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 20px rgba(108, 92, 231, 0.3);
            background: linear-gradient(135deg, #5f27cd, #00cec9);
        }

        /* Category Section */
        .category-section {
            background: #fff;
            border-radius: 24px;
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.02);
        }
        .section-heading {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            position: relative;
        }
        .section-heading::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #6c5ce7, #00cec9);
            border-radius: 3px;
        }
        .section-heading h4 {
            font-weight: 800;
            margin: 0;
            color: #2d3436;
            font-size: 1.4rem;
        }

        /* Enhanced Product Cards */
        .product-grid {
            margin-top: 1rem;
        }
        .product-card-modern {
            border: none;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            box-shadow: 0 5px 20px rgba(0,0,0,0.04);
        }
        .product-card-modern:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 50px rgba(108, 92, 231, 0.12);
        }
        .product-image-wrapper {
            position: relative;
            padding-top: 80%;
            overflow: hidden;
            background: #f1f2f6;
        }
        .product-image-wrapper img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        .product-card-modern:hover .product-image-wrapper img {
            transform: scale(1.08);
        }
        .product-badges {
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            z-index: 2;
        }
        .badge-category {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(4px);
            color: #6c5ce7;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 800;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            letter-spacing: 0.5px;
        }
        .badge-sale {
            background: linear-gradient(135deg, #ff4757, #ff6b81);
            color: white;
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 800;
            box-shadow: 0 4px 15px rgba(255, 71, 87, 0.4);
        }
        .product-content {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            background: #fff;
        }
        .product-title {
            font-weight: 700;
            font-size: 1.15rem;
            color: #2d3436;
            margin-bottom: 0.7rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
            transition: color 0.3s;
        }
        .product-card-modern:hover .product-title {
            color: #6c5ce7;
        }
        .product-desc {
            color: #636e72;
            font-size: 0.9rem;
            margin-bottom: 1.2rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.5;
        }
        .product-meta {
            margin-bottom: 1.2rem;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .meta-tag {
            background: rgba(108, 92, 231, 0.05);
            color: #6c5ce7;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .meta-tag.size-tag {
            background: rgba(0, 206, 201, 0.05);
            color: #00cec9;
        }
        .product-footer {
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1.2rem;
            border-top: 1px solid rgba(0,0,0,0.04);
        }
        .price-wrap {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .price-current {
            font-size: 1.3rem;
            font-weight: 800;
            color: #6c5ce7;
            line-height: 1;
        }
        .price-old {
            font-size: 0.85rem;
            color: #b2bec3;
            text-decoration: line-through;
            font-weight: 500;
        }
        .btn-add-cart {
            width: 45px;
            height: 45px;
            border-radius: 14px;
            background: rgba(108, 92, 231, 0.08);
            color: #6c5ce7;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s;
            cursor: pointer;
            font-size: 1.1rem;
        }
        .btn-add-cart:hover {
            background: linear-gradient(135deg, #6c5ce7, #a29bfe);
            color: white;
            transform: scale(1.1) rotate(-5deg);
            box-shadow: 0 8px 20px rgba(108, 92, 231, 0.3);
        }
        
        /* Empty State */
        .empty-state {
            background: #fff;
            border-radius: 24px;
            padding: 5rem 2rem;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.02);
            margin: 2rem 0;
        }
        .empty-icon {
            font-size: 5rem;
            background: linear-gradient(135deg, #dfe4ea, #ced6e0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
            display: inline-block;
        }
        
        /* Custom Pagination */
        .pagination-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding: 4px 0 8px;
        }
        .pagination {
            gap: 8px;
            margin-bottom: 0;
            flex-wrap: nowrap;
        }
        .page-link {
            border-radius: 12px !important;
            border: none;
            color: #636e72;
            font-weight: 700;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            background: #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            white-space: nowrap;
        }
        .page-link:hover {
            background: rgba(108, 92, 231, 0.1);
            color: #6c5ce7;
            transform: translateY(-2px);
        }
        .page-item.active .page-link {
            background: linear-gradient(135deg, #6c5ce7, #a29bfe);
            color: white;
            box-shadow: 0 8px 20px rgba(108, 92, 231, 0.3);
            transform: translateY(-2px);
        }
        .page-item.disabled .page-link {
            background: #f1f2f6;
            color: #b2bec3;
            box-shadow: none;
        }

        /* Mobile Pagination */
        @media (max-width: 576px) {
            .pagination {
                gap: 4px;
            }
            .page-link {
                width: 36px;
                height: 36px;
                font-size: 0.85rem;
                border-radius: 10px !important;
            }
        }
        
        /* Category Filter Tweaks */
        .category-filter-btn {
            border-radius: 20px;
            padding: 16px 24px;
            border: 2px solid transparent;
            background: #f8f9fa;
        }
        .category-filter-btn:hover {
            background: #fff;
            border-color: rgba(108, 92, 231, 0.3);
            box-shadow: 0 15px 30px rgba(108, 92, 231, 0.1);
        }
        .category-filter-btn.active {
            background: linear-gradient(135deg, #6c5ce7, #a29bfe);
            border-color: transparent;
            box-shadow: 0 15px 30px rgba(108, 92, 231, 0.25);
        }

        /* --- MOBILE RESPONSIVE TWEAKS --- */
        @media (max-width: 768px) {
            .container {
                padding-left: 12px;
                padding-right: 12px;
            }
            
            /* Hero Section */
            .shop-hero {
                padding: 2.5rem 1.2rem;
                margin-top: 70px;
                border-radius: 16px;
                margin-bottom: 2rem;
            }
            .shop-title {
                font-size: 2rem;
            }
            .shop-subtitle {
                font-size: 0.95rem;
                margin-bottom: 1.5rem;
            }
            
            /* Search Box */
            .search-input-group {
                flex-direction: column;
                border-radius: 16px;
                padding: 10px;
                gap: 10px;
            }
            .search-input {
                padding: 10px 15px;
                font-size: 1rem;
                text-align: center;
            }
            .search-btn {
                width: 100%;
                justify-content: center;
                border-radius: 12px;
                padding: 12px;
            }
            
            /* Category Filter */
            .category-section {
                padding: 1.5rem 1rem;
                border-radius: 16px;
                margin-bottom: 1.5rem;
            }
            .section-heading h4 {
                font-size: 1.2rem;
            }
            
            /* Results Count Box */
            .d-flex.flex-md-row > h4 {
                font-size: 1.2rem !important;
                text-align: center;
            }
            .d-flex.flex-md-row > div {
                align-self: center !important;
            }
            
            /* Product Grid Mobile Spacing */
            .g-4, .gx-4, .gy-4 {
                --bs-gutter-x: 12px;
                --bs-gutter-y: 12px;
            }
            
            /* Product Cards (2 columns on mobile) */
            .product-card-modern {
                border-radius: 14px;
            }
            .product-content {
                padding: 0.8rem;
            }
            .product-title {
                font-size: 0.9rem;
                margin-bottom: 0.4rem;
                line-height: 1.35;
                height: 2.7em; /* Fix height for 2 lines to ensure perfect balance */
            }
            .product-desc {
                display: none; /* Hide descriptions to keep grid super clean & balanced */
            }
            
            /* Badges */
            .product-badges {
                top: 8px;
                left: 8px;
                right: 8px;
            }
            .badge-category {
                padding: 3px 8px;
                font-size: 0.65rem;
            }
            .badge-sale {
                padding: 3px 6px;
                font-size: 0.65rem;
            }
            
            /* Meta tags (sizes/formats) */
            .product-meta {
                margin-bottom: 0.6rem;
                gap: 4px;
            }
            .meta-tag {
                font-size: 0.65rem;
                padding: 2px 6px;
                border-radius: 6px;
            }
            
            /* Footer (Price & Add to cart) */
            .product-footer {
                padding-top: 0.6rem;
            }
            .price-current {
                font-size: 1.05rem;
            }
            .price-old {
                font-size: 0.75rem;
            }
            .btn-add-cart {
                width: 32px;
                height: 32px;
                border-radius: 8px;
                font-size: 0.9rem;
            }
        }
    </style>
@endpush

@section('content')
<div class="container py-2">
    <!-- Shop Hero -->
    <div class="shop-hero" data-aos="zoom-in" data-aos-duration="1000">
        <div class="shop-hero-content">
            <h1 class="shop-title">Khám Phá Sản Phẩm</h1>
            <p class="shop-subtitle">Tìm kiếm các tài nguyên kỹ thuật số, công nghệ và tài liệu chất lượng cao được tuyển chọn đặc biệt dành cho bạn.</p>
            
            <form action="{{ route('shop') }}" method="GET" class="search-container">
                <input type="hidden" name="category_id" value="{{ $currentCategoryId }}">
                <div class="search-input-group">
                    <input type="text" class="search-input" name="search" placeholder="Nhập tên sản phẩm, tài liệu..." value="{{ $searchTerm }}">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Filter -->
    @if(isset($categories) && $categories->count() > 0)
    <div class="category-section" data-aos="fade-up" data-aos-delay="100">
        <div class="section-heading">
            <h4><i class="fas fa-layer-group me-2 text-primary"></i>Danh mục nổi bật</h4>
        </div>
        <div class="d-flex flex-wrap gap-3 justify-content-center">
            <!-- All Categories -->
            <a href="{{ route('shop') }}" class="category-filter-link {{ $currentCategoryId == 'all' ? 'active' : '' }}">
                <button class="category-filter-btn {{ $currentCategoryId == 'all' ? 'active' : '' }}" type="button">
                    <div class="category-icon-wrap">
                        <i class="fas fa-border-all"></i>
                    </div>
                    <span class="category-name">Tất cả</span>
                </button>
            </a>
            
            @foreach($categories as $category)
            <a href="{{ route('shop', ['category_id' => $category->id]) }}" class="category-filter-link">
                <button class="category-filter-btn {{ $currentCategoryId == $category->id ? 'active' : '' }}" type="button">
                    <div class="category-icon-wrap">
                        @if($category->image)
                            <img src="{{ $category->image }}" alt="{{ $category->name }}">
                        @else
                            @switch($category->type)
                                @case('tech')
                                    <i class="fas fa-laptop-code"></i>
                                    @break
                                @case('ebooks')
                                    <i class="fas fa-book-open"></i>
                                    @break
                                @case('doc')
                                    <i class="fas fa-file-invoice"></i>
                                    @break
                                @default
                                    <i class="fas fa-box-open"></i>
                            @endswitch
                        @endif
                    </div>
                    <span class="category-name">{{ $category->name }}</span>
                    @if($category->products_count > 0)
                        <span class="category-count">{{ $category->products_count }}</span>
                    @endif
                </button>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Results Info -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3" data-aos="fade-in">
        <h4 class="fw-bold m-0 text-dark" style="font-size: 1.5rem;">
            @if($searchTerm || $currentCategoryId != 'all')
                Kết quả tìm kiếm
                @if($currentCategoryId != 'all') 
                    @php $selectedCategory = $categories->firstWhere('id', $currentCategoryId); @endphp
                    @if($selectedCategory) <span class="text-primary">- {{ $selectedCategory->name }}</span> @endif
                @endif
            @else
                Tất cả sản phẩm
            @endif
        </h4>
        <div class="d-flex align-items-center bg-white px-4 py-2 rounded-pill shadow-sm border" style="border-color: rgba(108, 92, 231, 0.2) !important;">
            <i class="fas fa-check-circle text-success me-2"></i> 
            <span class="fw-bold text-dark me-1">{{ $items->total() }}</span> sản phẩm
        </div>
    </div>

    <!-- Product Grid -->
    @if($items->count() > 0)
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4 product-grid">
        @foreach($items as $product)
        <div class="col" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 8) * 50 }}">
            <div class="product-card-modern">
                <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none d-block position-relative">
                    <div class="product-image-wrapper">
                        <div class="product-badges">
                            <span class="badge-category">{{ strtoupper($product->category) }}</span>
                            @if($product->is_on_sale)
                                <span class="badge-sale">-{{ $product->discount_percent }}%</span>
                            @endif
                        </div>
                        
                        @if($product->category === 'ebooks' && $product->hasFile())
                            <div class="position-absolute top-50 start-50 translate-middle z-1">
                                <i class="fas fa-file-{{ $product->file_type === 'pdf' ? 'pdf' : ($product->file_type === 'docx' ? 'word' : 'alt') }} fa-3x text-white" style="filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3)); opacity: 0.85;"></i>
                            </div>
                        @endif
                        <img src="{{ $product->image ?? 'https://via.placeholder.com/400x300?text=No+Image' }}" alt="{{ $product->name }}" loading="lazy">
                    </div>
                </a>
                
                <div class="product-content">
                    <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none">
                        <h3 class="product-title">{{ $product->name }}</h3>
                    </a>
                    <p class="product-desc">{{ Str::limit(strip_tags($product->description), 80) }}</p>
                    
                    @if($product->category === 'ebooks' && $product->hasFile())
                        <div class="product-meta">
                            <span class="meta-tag"><i class="fas fa-file-alt"></i> {{ strtoupper($product->file_type) }}</span>
                            <span class="meta-tag size-tag"><i class="fas fa-hdd"></i> {{ $product->formatted_file_size }}</span>
                        </div>
                    @endif
                    
                    <div class="product-footer">
                        <div class="price-wrap">
                            <span class="price-current">{{ $product->formatted_price }}</span>
                            @if($product->is_on_sale)
                                <span class="price-old">{{ $product->formatted_original_price }}</span>
                            @endif
                        </div>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="m-0 p-0">
                            @csrf
                            <button type="submit" class="btn-add-cart" title="Thêm vào giỏ">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-5 d-flex justify-content-center" data-aos="fade-up">
        <div class="pagination-wrapper d-flex justify-content-center">
            {{ $items->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="empty-state" data-aos="zoom-in">
        <i class="fas fa-search empty-icon"></i>
        <h3 class="fw-bold mb-3" style="color: #2d3436;">Không tìm thấy sản phẩm</h3>
        <p class="text-muted mb-4 mx-auto" style="max-width: 500px; font-size: 1.1rem;">Rất tiếc, chúng tôi không thể tìm thấy sản phẩm nào phù hợp với yêu cầu của bạn. Hãy thử lại với từ khóa khác.</p>
        <a href="{{ route('shop') }}" class="btn search-btn d-inline-flex mx-auto" style="width: auto;">
            <i class="fas fa-sync-alt"></i> Tải lại cửa hàng
        </a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof AOS !== 'undefined') {
                AOS.init({ duration: 800, once: true });
            }
        });
    </script>
@endpush

