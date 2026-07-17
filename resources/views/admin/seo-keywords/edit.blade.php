@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Từ Khóa SEO')

@push('styles')
<style>

    .admin-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .form-label {
        font-weight: 600;
        color: #4a5568;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="admin-card" data-aos="fade-up">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h3 class="fw-bold mb-0">
                            <i class="fas fa-edit text-primary me-2"></i>Chỉnh sửa từ khóa SEO: {{ $keyword->label }}
                        </h3>
                        <a href="{{ route('admin.seo-keywords') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại
                        </a>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><strong>Đã xảy ra lỗi:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.seo-keywords.update', $keyword->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <!-- Label -->
                            <div class="col-md-6">
                                <label for="label" class="form-label">Nhãn hiển thị (Label) <span class="text-danger">*</span></label>
                                <input type="text" name="label" id="label" class="form-control rounded-3" placeholder="Ví dụ: Kiro AI" value="{{ old('label', $keyword->label) }}" required>
                                <div class="form-text">Nhãn hiển thị trên nút tìm nhanh ở Trang chủ / Cửa hàng.</div>
                            </div>

                            <!-- Slug -->
                            <div class="col-md-6">
                                <label for="slug" class="form-label">Đường dẫn URL (Slug) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="slug" id="slug" class="form-control rounded-start-3" placeholder="Ví dụ: kiro-ai" value="{{ old('slug', $keyword->slug) }}" required>
                                    <button type="button" class="btn btn-outline-secondary rounded-end-3" id="btn-sync-slug" title="Sinh lại slug từ Label">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                                <div class="form-text text-warning"><i class="fas fa-exclamation-circle me-1"></i>Thay đổi slug có thể ảnh hưởng đến các URL đã được Google lập chỉ mục (index).</div>
                            </div>

                            <!-- Page Heading -->
                            <div class="col-12">
                                <label for="heading" class="form-label">Tiêu đề trang (Heading H1) <span class="text-danger">*</span></label>
                                <input type="text" name="heading" id="heading" class="form-control rounded-3" placeholder="Ví dụ: Mua tài khoản Kiro AI Pro giá rẻ" value="{{ old('heading', $keyword->heading) }}" required>
                                <div class="form-text">Thẻ H1 hiển thị ở đầu trang landing tìm kiếm của từ khóa này.</div>
                            </div>

                            <!-- SEO Meta Title -->
                            <div class="col-12">
                                <label for="title" class="form-label">Thẻ tiêu đề SEO (Meta Title) <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control rounded-3" placeholder="Ví dụ: Mua tài khoản Kiro AI Pro giá rẻ - DungThu.com" value="{{ old('title', $keyword->title) }}" required>
                                <div class="form-text">Tiêu đề hiển thị trên tab trình duyệt và Google Search (Meta Title).</div>
                            </div>

                            <!-- SEO Meta Description -->
                            <div class="col-12">
                                <label for="description" class="form-label">Mô tả SEO (Meta Description) <span class="text-danger">*</span></label>
                                <textarea name="description" id="description" rows="3" class="form-control rounded-3" placeholder="Ví dụ: Danh sách tài khoản Kiro AI Pro và các gói Kiro đang bán tại DungThu.com, giá tốt, bảo hành uy tín..." required>{{ old('description', $keyword->description) }}</textarea>
                                <div class="form-text">Đoạn mô tả ngắn hiển thị trên kết quả tìm kiếm của Google (Meta Description).</div>
                            </div>

                            <!-- Aliases -->
                            <div class="col-12">
                                <label for="aliases" class="form-label">Từ khóa đồng nghĩa (Aliases)</label>
                                <textarea name="aliases" id="aliases" rows="2" class="form-control rounded-3" placeholder="Ví dụ: kiro, kiro ai, kiro pro, kiro ide">{{ old('aliases', is_array($keyword->aliases) ? implode(', ', $keyword->aliases) : $keyword->aliases) }}</textarea>
                                <div class="form-text">Nhập các từ khóa đồng nghĩa hoặc tìm kiếm liên quan, ngăn cách bởi dấu phẩy hoặc xuống dòng.</div>
                            </div>

                            <!-- Active status -->
                            <div class="col-12">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $keyword->is_active ? '1' : '0') === '1' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-dark" for="is_active">Hoạt động (Hiển thị ra ngoài giao diện)</label>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary px-5 py-2.5 rounded-pill shadow-sm">
                                    <i class="fas fa-save me-2"></i>Cập nhật từ khóa
                                </button>
                                <a href="{{ route('admin.seo-keywords') }}" class="btn btn-light px-4 py-2.5 rounded-pill ms-2">Hủy bỏ</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true });

    // Tự sinh Slug từ Label khi nhấn nút Sync
    document.getElementById('btn-sync-slug').addEventListener('click', function() {
        let label = document.getElementById('label').value;
        let slug = label.toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .replace(/đ/g, "d").replace(/Đ/g, "d")
            .replace(/[^a-z0-9\s-]/g, "")
            .replace(/\s+/g, "-")
            .replace(/-+/g, "-")
            .trim();
        
        document.getElementById('slug').value = slug;
    });
</script>
@endpush
