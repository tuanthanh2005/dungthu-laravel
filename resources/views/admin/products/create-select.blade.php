@extends('layouts.app')

@section('title', 'Chọn Danh mục - Admin')

@push('styles')
<style>
    .admin-wrapper {
        padding: 40px 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        margin-top: 70px;
    }

    .admin-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        max-width: 900px;
        margin: 0 auto;
    }

    .category-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
        margin-top: 30px;
    }

    .category-card {
        background: white;
        border: 3px solid #e2e8f0;
        border-radius: 20px;
        padding: 40px 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        color: #2d3748;
    }

    .category-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        text-decoration: none;
        color: white;
    }

    .category-card.tech {
        border-color: #00d4ff;
    }

    .category-card.tech:hover {
        background: linear-gradient(135deg, #00d4ff 0%, #0099cc 100%);
        border-color: #00d4ff;
    }

    .category-card.fashion {
        border-color: #ff6b6b;
    }

    .category-card.fashion:hover {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
        border-color: #ff6b6b;
    }

    .category-card.doc {
        border-color: #00acc1;
    }

    .category-card.doc:hover {
        background: linear-gradient(135deg, #00acc1 0%, #0097a7 100%);
        border-color: #00acc1;
    }

    .category-card i {
        font-size: 4rem;
        margin-bottom: 20px;
        display: block;
    }

    .category-card.tech i {
        color: #00d4ff;
    }

    .category-card.fashion i {
        color: #ff6b6b;
    }

    .category-card.doc i {
        color: #00acc1;
    }

    .category-card:hover i {
        color: white;
    }

    .category-card h4 {
        font-weight: 700;
        margin-bottom: 10px;
        font-size: 1.5rem;
    }

    .category-card p {
        margin: 0;
        font-size: 0.9rem;
        opacity: 0.8;
    }
</style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="container">
        <div class="admin-card" data-aos="fade-up">
            <div class="mb-4">
                <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary rounded-pill mb-3">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
                <h3 class="fw-bold mb-0 text-center">
                    <i class="fas fa-plus-circle text-primary me-3"></i>Chọn Danh mục Sản phẩm
                </h3>
                <p class="text-center text-muted mt-2">Chọn danh mục phù hợp để thêm sản phẩm mới</p>
            </div>

            <div class="category-grid">
                <a href="{{ route('admin.products.create', 'tech') }}" class="category-card tech">
                    <i class="fas fa-laptop"></i>
                    <h4>Công nghệ</h4>
                    <p>Laptop, điện thoại, phụ kiện</p>
                    <small class="d-block mt-3 text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Có thông số kỹ thuật
                    </small>
                </a>

                <a href="{{ route('admin.products.create', 'fashion') }}" class="category-card fashion">
                    <i class="fas fa-tshirt"></i>
                    <h4>Thời trang</h4>
                    <p>Quần áo, giày dép, phụ kiện</p>
                    <small class="d-block mt-3 text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Có size, màu sắc
                    </small>
                </a>

                <a href="{{ route('admin.products.create', 'doc') }}" class="category-card doc">
                    <i class="fas fa-book"></i>
                    <h4>Tài liệu</h4>
                    <p>Sách, văn phòng phẩm</p>
                    <small class="d-block mt-3 text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Có thông tin bao bì
                    </small>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true });
</script>
@endpush
