@extends('layouts.app')

@section('title', 'Cửa Hàng - DungThu.com')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/category-filter.css') }}?v={{ time() }}">
    <style>
        .category-filter-link {
            text-decoration: none;
        }
        
        .category-filter-link:hover {
            text-decoration: none;
        }
    </style>
@endpush

@section('content')
<div class="container py-2" style="margin-top: 80px;">
    <div class="row mb-4" data-aos="fade-down">
        <div class="col-12 text-center">
            <h1 class="fw-bold mb-3">Cửa Hàng</h1>
        </div>
    </div>

    <!-- Filter và Search -->
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <!-- Category Filter Icons -->
                    @if(isset($categories) && $categories->count() > 0)
                    <div class="category-filter-section mb-4">
                        <label class="form-label fw-bold mb-3 text-center d-block" style="font-size: 1.1rem;">
                            <i class="fas fa-filter me-2 text-primary"></i>Danh mục
                        </label>
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            <!-- All Categories -->
                            <a href="{{ route('shop') }}" class="category-filter-link {{ $currentCategoryId == 'all' ? 'active' : '' }}">
                                <button class="category-filter-btn {{ $currentCategoryId == 'all' ? 'active' : '' }}" type="button">
                                    <div class="category-icon-wrap">
                                        <i class="fas fa-th-large"></i>
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
                                                    <i class="fas fa-laptop"></i>
                                                    @break
                                                @case('ebooks')
                                                    <i class="fas fa-book"></i>
                                                    @break
                                                @case('doc')
                                                    <i class="fas fa-file-alt"></i>
                                                    @break
                                                @default
                                                    <i class="fas fa-box"></i>
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

                    <form action="{{ route('shop') }}" method="GET" class="row g-3">
                        <!-- Search Box -->
                        <div class="col-md-10">
                            <label class="form-label fw-bold">
                                <i class="fas fa-search me-2 text-primary"></i>Tìm kiếm sản phẩm
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg" 
                                   name="search" 
                                   placeholder="Nhập tên sản phẩm..." 
                                   value="{{ $searchTerm }}"
                                   style="border-radius: 10px; border: 2px solid #e0e0e0;">
                            <input type="hidden" name="category_id" value="{{ $currentCategoryId }}">
                        </div>

                        <!-- Submit Button -->
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-lg w-100" style="border-radius: 10px;">
                                <i class="fas fa-search me-2"></i>Tìm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Count -->
    @if($searchTerm || $currentCategoryId != 'all')
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Tìm thấy <strong>{{ $items->total() }}</strong> sản phẩm
                @if($searchTerm) cho "<strong>{{ $searchTerm }}</strong>" @endif
                @if($currentCategoryId != 'all') 
                    @php
                        $selectedCategory = $categories->firstWhere('id', $currentCategoryId);
                    @endphp
                    @if($selectedCategory)
                        trong danh mục <strong>{{ $selectedCategory->name }}</strong>
                    @endif
                @endif
            </div>
        </div>
    </div>
    @endif

    @if($items->count() > 0)
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach($items as $product)
        <div class="col" data-aos="fade-up">
            <div class="product-card">
                <div class="card-img-wrap">
                    <span class="badge-custom">{{ strtoupper($product->category) }}</span>
                    @if($product->category === 'ebooks' && $product->hasFile())
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i class="fas fa-file-{{ $product->file_type === 'pdf' ? 'pdf' : ($product->file_type === 'docx' ? 'word' : 'alt') }} fa-4x text-white opacity-75"></i>
                        </div>
                    @endif
                    <img src="{{ $product->image ?? 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}">
                </div>
                <div class="p-3">
                    <h6 class="fw-bold product-title-2lines">{{ $product->name }}</h6>
                    <p class="text-muted small mb-2" style="font-size: 0.85rem; height: 2.5rem; overflow: hidden;">
                        {{ Str::limit($product->description, 50) }}
                    </p>
                    @if($product->category === 'ebooks' && $product->hasFile())
                        <div class="mb-2">
                            <span class="badge bg-info">
                                <i class="fas fa-file"></i> {{ strtoupper($product->file_type) }}
                            </span>
                            <span class="badge bg-secondary">
                                <i class="fas fa-download"></i> {{ $product->formatted_file_size }}
                            </span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div class="flex-grow-1 me-2" style="min-width: 0;">
                            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-1 gap-sm-2">
                                <span class="text-primary fw-bold">{{ $product->formatted_price }}</span>
                                @if($product->is_on_sale)
                                    <div class="d-flex align-items-center gap-1 flex-wrap">
                                        <span class="text-muted text-decoration-line-through small">{{ $product->formatted_original_price }}</span>
                                        <span class="badge bg-danger sale-badge">-{{ $product->discount_percent }}%</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-light rounded-circle text-primary">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <a href="{{ route('product.show', $product->slug) }}" class="stretched-link"></a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $items->links() }}
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
        <h4 class="text-muted">Không tìm thấy sản phẩm nào</h4>
        <p class="text-muted">Thử tìm kiếm với từ khóa khác hoặc chọn danh mục khác</p>
        <a href="{{ route('shop') }}" class="btn btn-primary mt-3">
            <i class="fas fa-arrow-left"></i> Quay lại cửa hàng
        </a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
    <script>
        AOS.init({ duration: 800, once: true });
    </script>
@endpush
