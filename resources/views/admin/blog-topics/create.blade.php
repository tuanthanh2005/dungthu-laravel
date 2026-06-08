@extends('layouts.app')

@section('title', 'Thêm Chủ Đề Blog Mới')

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
<div class="admin-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="admin-card" data-aos="fade-up">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h3 class="fw-bold mb-0">
                            <i class="fas fa-plus-circle text-primary me-2"></i>Thêm chủ đề Blog mới
                        </h3>
                        <a href="{{ route('admin.blog-topics') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
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

                    <form action="{{ route('admin.blog-topics.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3">
                            <!-- Label -->
                            <div class="col-md-6">
                                <label for="label" class="form-label">Tên chủ đề (Label) <span class="text-danger">*</span></label>
                                <input type="text" name="label" id="label" class="form-control rounded-3" placeholder="Ví dụ: ChatGPT, Lập trình AI" value="{{ old('label') }}" required>
                                <div class="form-text">Nhãn hiển thị trên danh sách chủ đề nổi bật của trang Blog.</div>
                            </div>

                            <!-- Slug -->
                            <div class="col-md-6">
                                <label for="slug" class="form-label">Đường dẫn URL (Slug) <span class="text-danger">*</span></label>
                                <input type="text" name="slug" id="slug" class="form-control rounded-3" placeholder="Ví dụ: chatgpt, lap-trinh-ai" value="{{ old('slug') }}" required>
                                <div class="form-text">Sẽ tạo đường dẫn <code>/blog/chu-de/tên-chủ-đề</code>. Tự động sinh từ Tên chủ đề.</div>
                            </div>

                            <!-- Page Heading -->
                            <div class="col-12">
                                <label for="heading" class="form-label">Tiêu đề trang (Heading H1) <span class="text-danger">*</span></label>
                                <input type="text" name="heading" id="heading" class="form-control rounded-3" placeholder="Ví dụ: Hướng dẫn học và lập trình AI hiệu quả" value="{{ old('heading') }}" required>
                                <div class="form-text">Tiêu đề hiển thị ở đầu trang danh sách bài viết của chủ đề này.</div>
                            </div>

                            <!-- SEO Meta Title -->
                            <div class="col-12">
                                <label for="title" class="form-label">Thẻ tiêu đề SEO (Meta Title) <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control rounded-3" placeholder="Ví dụ: Hướng dẫn học và lập trình AI hiệu quả - DungThu.com" value="{{ old('title') }}" required>
                                <div class="form-text">Tiêu đề hiển thị trên tab trình duyệt và Google Search.</div>
                            </div>

                            <!-- SEO Meta Description -->
                            <div class="col-12">
                                <label for="description" class="form-label">Mô tả SEO (Meta Description) <span class="text-danger">*</span></label>
                                <textarea name="description" id="description" rows="3" class="form-control rounded-3" placeholder="Ví dụ: Tổng hợp các bài viết hướng dẫn lập trình AI, cách sử dụng các mô hình và tối ưu hóa workflow công nghệ..." required>{{ old('description') }}</textarea>
                                <div class="form-text">Đoạn mô tả ngắn hiển thị trên kết quả tìm kiếm của Google.</div>
                            </div>

                            <!-- Aliases -->
                            <div class="col-12">
                                <label for="aliases" class="form-label">Từ khóa bài viết liên quan (Aliases)</label>
                                <textarea name="aliases" id="aliases" rows="2" class="form-control rounded-3" placeholder="Ví dụ: ai, lap trinh ai, ai code, cursor, copilot">{{ old('aliases') }}</textarea>
                                <div class="form-text">Nhập các từ khóa liên quan, ngăn cách bởi dấu phẩy hoặc xuống dòng. Hệ thống sẽ tự quét bài viết có tiêu đề/nội dung chứa các từ khóa này để tự động gom vào chủ đề này.</div>
                            </div>

                            <!-- Active status -->
                            <div class="col-12">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') === '1' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-dark" for="is_active">Hoạt động (Hiển thị ra ngoài giao diện)</label>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary px-5 py-2.5 rounded-pill shadow-sm">
                                    <i class="fas fa-save me-2"></i>Lưu chủ đề
                                </button>
                                <a href="{{ route('admin.blog-topics') }}" class="btn btn-light px-4 py-2.5 rounded-pill ms-2">Hủy bỏ</a>
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

    // Tự động sinh Slug & điền sẵn Heading, Title mẫu khi người dùng gõ Label
    const labelInput = document.getElementById('label');
    const slugInput = document.getElementById('slug');
    const headingInput = document.getElementById('heading');
    const titleInput = document.getElementById('title');
    const descriptionInput = document.getElementById('description');

    labelInput.addEventListener('input', function() {
        // Sinh slug
        let label = this.value;
        let slug = label.toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .replace(/đ/g, "d").replace(/Đ/g, "d")
            .replace(/[^a-z0-9\s-]/g, "")
            .replace(/\s+/g, "-")
            .replace(/-+/g, "-")
            .trim();
        
        slugInput.value = slug;

        // Điền sẵn gợi ý cho Heading, Title, Description nếu trống
        if (label.trim() !== '') {
            if (headingInput.value.trim() === '') {
                headingInput.value = `Bài viết chia sẻ kinh nghiệm về ${label}`;
            }
            if (titleInput.value.trim() === '') {
                titleInput.value = `Blog ${label} - Tổng hợp bài viết hay nhất - DungThu.com`;
            }
            if (descriptionInput.value.trim() === '') {
                descriptionInput.value = `Tổng hợp các bài viết chia sẻ kinh nghiệm, thủ thuật, hướng dẫn sử dụng và cập nhật thông tin mới nhất về chủ đề ${label} tại DungThu.com.`;
            }
        }
    });
</script>
@endpush
