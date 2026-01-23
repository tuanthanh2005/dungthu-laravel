@extends('layouts.app')

@section('title', 'Cửa Hàng - DungThu.com')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    <div class="row mb-4" data-aos="fade-down">
        <div class="col-12 text-center">
            <h1 class="fw-bold mb-3">Cửa Hàng</h1>
            <p class="text-muted">Khám phá các sản phẩm chất lượng cao với giá tốt nhất</p>
        </div>
    </div>

    <!-- Filter và Search -->
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <!-- Quick Filter Badges - Moved to Top -->
                    <div class="mb-4">
                        <label class="form-label fw-bold mb-3 text-center d-block" style="font-size: 1.1rem;">
                            <i class="fas fa-filter me-2 text-primary"></i>Danh mục
                        </label>
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            <a href="{{ route('shop') }}" 
                               class="btn btn-{{ $currentCategory == 'all' ? 'primary' : 'outline-primary' }} px-4 py-3" 
                               style="border-radius: 15px; font-size: 1rem; font-weight: 600; min-width: 150px;">
                                Tất cả
                            </a>
                            <a href="{{ route('shop', ['category' => 'tiktok']) }}" 
                               class="btn btn-{{ $currentCategory == 'tiktok' ? 'warning' : 'outline-warning' }} px-4 py-3" 
                               style="border-radius: 15px; font-size: 1rem; font-weight: 600; min-width: 150px;">
                                <i class="fab fa-tiktok me-2"></i>Săn Sale Tiktok
                            </a>
                            <a href="{{ route('shop', ['category' => 'tech']) }}" 
                               class="btn btn-{{ $currentCategory == 'tech' ? 'info' : 'outline-info' }} px-4 py-3" 
                               style="border-radius: 15px; font-size: 1rem; font-weight: 600; min-width: 150px;">
                                <i class="fas fa-microchip me-2"></i>Công nghệ
                            </a>
                            <a href="{{ route('shop', ['category' => 'ebooks']) }}" 
                               class="btn btn-{{ $currentCategory == 'ebooks' ? 'success' : 'outline-success' }} px-4 py-3" 
                               style="border-radius: 15px; font-size: 1rem; font-weight: 600; min-width: 150px;">
                                <i class="fas fa-file-pdf me-2"></i>Tài liệu kiếm tiền
                            </a>
                            <a href="{{ route('card-exchange.index') }}" 
                               class="btn btn-outline-danger px-4 py-3" 
                               style="border-radius: 15px; font-size: 1rem; font-weight: 600; min-width: 150px;">
                                <i class="fas fa-credit-card me-2"></i>Đổi Thẻ Cào
                            </a>
                        </div>
                    </div>

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
                            <input type="hidden" name="category" value="{{ $currentCategory }}">
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
    @if($searchTerm || $currentCategory != 'all')
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Tìm thấy <strong>{{ $items->total() }}</strong> {{ $isTiktok ? 'deal' : 'sản phẩm' }}
                @if($searchTerm) cho "<strong>{{ $searchTerm }}</strong>" @endif
                @if($currentCategory != 'all') trong danh mục <strong>{{ $currentCategory == 'tiktok' ? 'Săn Sale Tiktok Shop' : $currentCategory }}</strong> @endif
            </div>
        </div>
    </div>
    @endif

    @if($items->count() > 0)
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
        @if($isTiktok)
            {{-- Hiển thị Tiktok Deals --}}
            @foreach($items as $deal)
            <div class="col" data-aos="fade-up">
                <div class="product-deal-card">
                    @if($deal->discount_percent)
                        <span class="deal-badge">-{{ $deal->discount_percent }}%</span>
                    @endif
                    <div class="deal-img-wrap">
                        <img src="{{ $deal->image ? asset('storage/' . $deal->image) : 'https://via.placeholder.com/300' }}" 
                             alt="{{ $deal->name }}">
                    </div>
                    <div class="p-3">
                        <h6 class="fw-bold">{{ $deal->name }}</h6>
                        @if($deal->description)
                            <p class="text-muted small mb-2" style="font-size: 0.85rem; height: 2.5rem; overflow: hidden;">
                                {{ Str::limit($deal->description, 50) }}
                            </p>
                        @endif
                        @if($deal->original_price)
                            <div class="text-decoration-line-through text-muted small">
                                {{ $deal->formatted_original_price }}
                            </div>
                        @endif
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="text-danger fw-bold fs-5">{{ $deal->formatted_sale_price }}</span>
                        </div>
                        <a href="{{ $deal->tiktok_link }}" target="_blank" class="btn btn-danger w-100 mt-2">
                            <i class="fab fa-tiktok"></i> Mua ngay
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            {{-- Hiển thị Products --}}
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
                        <h6 class="fw-bold text-truncate">{{ $product->name }}</h6>
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
                            <span class="text-primary fw-bold">{{ $product->formatted_price }}</span>
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
        @endif
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $items->links() }}
    </div>
    @else
    <div class="text-center py-5">
        <i class="{{ $isTiktok ? 'fab fa-tiktok' : 'fas fa-box-open' }} fa-4x text-muted mb-3"></i>
        <h4 class="text-muted">Không tìm thấy {{ $isTiktok ? 'deal' : 'sản phẩm' }} nào</h4>
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
