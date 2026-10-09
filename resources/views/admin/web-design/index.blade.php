@extends('layouts.admin')

@section('title', 'Quản lý Dịch vụ Thiết kế Website')

@section('content')
<div class="container-fluid px-4 py-3">

    {{-- Header Page --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
                <i class="fas fa-palette text-primary"></i> Quản lý Trang & Gói Thiết Kế Website
            </h4>
            <p class="text-muted mb-0 small">Tùy chỉnh thông tin giới thiệu, chính sách và danh sách các gói dịch vụ</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('web-design') }}" target="_blank" class="btn btn-outline-primary fw-semibold rounded-pill px-3">
                <i class="fas fa-external-link-alt me-1"></i> Xem trang ngoài
            </a>
            <button type="button" class="btn text-white fw-bold rounded-pill px-3 shadow-sm btn-ai-modal" data-bs-toggle="modal" data-bs-target="#aiCreatePackagesModal" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                <i class="fas fa-wand-magic-sparkles me-1.5"></i> Thêm Bằng AI
            </button>
            <button type="button" class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#createPackageModal">
                <i class="fas fa-plus-circle me-1.5"></i> Thêm Gói Mới
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Phần 1: Cài đặt nội dung văn bản trang ngoài (Tránh sửa code) --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white py-3 border-bottom border-light">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="fas fa-edit text-warning me-2"></i> Nội Dung Giới Thiệu (Không gán cứng)
                </h5>
                <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-1 rounded-pill small">Admin có quyền sửa</span>
            </div>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.web-design.update-settings') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- Tiêu đề Hero --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">Tiêu đề lớn (Hero Title)</label>
                        <input type="text" name="web_design_hero_title" class="form-control rounded-3" 
                               value="{{ old('web_design_hero_title', $settings['hero_title']) }}" required 
                               placeholder="Ví dụ: Thiết kế website giá rẻ">
                    </div>

                    {{-- Nút bấm --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary">Chữ trên nút tư vấn (Button CTA)</label>
                        <input type="text" name="web_design_btn_text" class="form-control rounded-3" 
                               value="{{ old('web_design_btn_text', $settings['btn_text']) }}" 
                               placeholder="Ví dụ: Nhận tư vấn">
                    </div>

                    {{-- Mô tả Hero --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold text-secondary">Mô tả ngắn dưới tiêu đề lớn (Hero Subtitle)</label>
                        <textarea name="web_design_hero_subtitle" rows="2" class="form-control rounded-3" 
                                  placeholder="Ví dụ: Trao đổi nhanh, chốt trong 1-2 tiếng. Thiết kế chuẩn SEO, tối ưu mobile, bàn giao nhanh.">{{ old('web_design_hero_subtitle', $settings['hero_subtitle']) }}</textarea>
                    </div>

                    <div class="col-12"><hr class="my-2 border-light"></div>

                    {{-- Tag dịch vụ & Tiêu đề khối --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary">Thẻ phụ (Tag Subtitle)</label>
                        <input type="text" name="web_design_service_tag" class="form-control rounded-3" 
                               value="{{ old('web_design_service_tag', $settings['service_tag']) }}" 
                               placeholder="Ví dụ: Dịch vụ">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-semibold text-secondary">Tiêu đề khối giới thiệu (Section Title)</label>
                        <input type="text" name="web_design_service_title" class="form-control rounded-3" 
                               value="{{ old('web_design_service_title', $settings['service_title']) }}" required 
                               placeholder="Ví dụ: Thiết kế website giá rẻ">
                    </div>

                    {{-- Mô tả chi tiết dịch vụ --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold text-secondary">Mô tả chi tiết điều kiện & cam kết dịch vụ</label>
                        <textarea name="web_design_service_desc" rows="3" class="form-control rounded-3" 
                                  placeholder="Chỉ nhận: website bán hàng, website blog, website tin tức...">{{ old('web_design_service_desc', $settings['service_desc']) }}</textarea>
                    </div>

                    <div class="col-12 text-end pt-2">
                        <button type="submit" class="btn btn-warning fw-bold px-4 rounded-pill shadow-sm">
                            <i class="fas fa-save me-1"></i> Lưu Cài Đặt Nội Dung
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Phần 2: Quản lý danh sách các gói --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 border-bottom border-light d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="fas fa-layer-group text-primary me-2"></i> Danh Sách Các Gói Thiết Kế Website
                </h5>
                <span class="text-muted small">Quy tắc hiển thị: 1 hàng 3 gói, thêm 4 gói sẽ xuống hàng, từ gói thứ 7 sẽ tự động phân trang (6 gói/trang).</span>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-sm text-white fw-bold rounded-pill px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#aiCreatePackagesModal" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                    <i class="fas fa-wand-magic-sparkles me-1"></i> Thêm Bằng AI
                </button>
                <button type="button" class="btn btn-sm btn-primary fw-bold rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#createPackageModal">
                    <i class="fas fa-plus me-1"></i> Thêm Gói Mới
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 70px;">Thứ tự</th>
                            <th>Tên gói</th>
                            <th>Huy hiệu (Badge)</th>
                            <th>Giá gói (VNĐ)</th>
                            <th>Tính năng (Gạch đầu dòng)</th>
                            <th class="text-center" style="width: 120px;">Trạng thái</th>
                            <th class="text-end pe-4" style="width: 140px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $pkg)
                        <tr>
                            <td class="ps-4 fw-bold text-muted">#{{ $pkg->sort_order }}</td>
                            <td>
                                <div class="fw-bold text-dark fs-6">{{ $pkg->name }}</div>
                            </td>
                            <td>
                                @if($pkg->badge)
                                    <span class="badge bg-{{ $pkg->badge_color ?? 'primary' }} rounded-pill px-2.5 py-1">
                                        {{ $pkg->badge }}
                                    </span>
                                @else
                                    <span class="text-muted small">--</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-primary fs-6">{{ number_format($pkg->price, 0, ',', '.') }}đ</span>
                            </td>
                            <td>
                                @php $features = is_array($pkg->features) ? $pkg->features : []; @endphp
                                <span class="badge bg-light text-dark border px-2.5 py-1">
                                    <i class="fas fa-check-circle text-success me-1"></i> {{ count($features) }} tính năng
                                </span>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.web-design.packages.toggle', $pkg) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm border-0 p-0" title="Bấm để bật/tắt">
                                        @if($pkg->is_active)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5">
                                                <i class="fas fa-check me-1"></i> Đang hiện
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1.5">
                                                <i class="fas fa-eye-slash me-1"></i> Đang ẩn
                                            </span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 me-1" 
                                        data-bs-toggle="modal" data-bs-target="#editPackageModal_{{ $pkg->id }}" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.web-design.packages.destroy', $pkg) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa gói \'{{ $pkg->name }}\' này không?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2.5" title="Xóa gói">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-layer-group fs-2 mb-2 d-block opacity-50"></i>
                                Chưa có gói thiết kế website nào. Hãy bấm "Thêm Gói Mới" hoặc "Thêm Bằng AI" để tạo gói!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- MODAL 1: THÊM BẰNG AI (GEMINI) --}}
<div class="modal fade" id="aiCreatePackagesModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 text-white px-4 py-3 position-relative" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                <div>
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2 mb-1" style="font-size: 18px;">
                        <i class="fas fa-wand-magic-sparkles"></i> Trợ Lý AI Tạo Gói Thiết Kế Website
                    </h5>
                    <p class="mb-0 opacity-75 small">Nhập mô tả tự do, mỗi dòng có dấu <code>-</code> ở đầu tương đương với 1 gói cần tạo</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-light-subtle">
                {{-- Form nhập mô tả cho AI --}}
                <div id="aiInputSection">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark d-flex align-items-center justify-content-between">
                            <span>
                                <i class="fas fa-list-ul text-primary me-1"></i> Mô tả các gói cần tạo:
                            </span>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill small">
                                1 hàng có dấu <code>-</code> = 1 gói
                            </span>
                        </label>
                        <textarea class="form-control rounded-3 p-3 font-monospace" id="aiPromptInput" rows="7" 
                                  placeholder="- Gói Landing Page giá 1.5tr cho chạy ads bán khoá học, tối ưu chuyển đổi&#10;- Gói Bán Hàng Nhanh giá 3.5tr có giỏ hàng, đặt hàng nhanh, chuẩn mobile&#10;- Gói Doanh Nghiệp VIP giá 7.9tr chuẩn SEO chuyên sâu, bảo hành 1 năm, hosting VIP" 
                                  style="font-size: 13.5px; line-height: 1.6;"></textarea>
                        <div class="form-text text-muted mt-2 small">
                            <i class="fas fa-info-circle text-info me-1"></i> <strong>Quy tắc:</strong> Mỗi hàng bắt đầu bằng dấu <code>-</code> tương đương với 1 gói. AI sẽ tự động phân tích tên gói, huy hiệu, giá tiền VNĐ và viết đầy đủ các tính năng chi tiết, hấp dẫn nhất.
                        </div>
                    </div>

                    <div class="row g-3 align-items-center mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">
                                <i class="fas fa-microchip text-primary me-1"></i> Mô hình AI (Model)
                            </label>
                            <select class="form-select form-select-sm rounded-pill" id="aiModelSelect">
                                @foreach($availableModels ?? [] as $modelKey => $modelLabel)
                                    <option value="{{ $modelKey }}" {{ ($defaultModel ?? 'gemini-2.0-flash') === $modelKey ? 'selected' : '' }}>
                                        {{ $modelLabel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 pt-md-3">
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input" type="checkbox" id="aiAutoSaveSwitch" checked>
                                <label class="form-check-label fw-semibold ms-1 small" for="aiAutoSaveSwitch">
                                    Tự động lưu vào hệ thống ngay sau khi tạo
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Thông báo lỗi nếu có --}}
                    <div class="alert alert-danger d-none border-0 shadow-xs mb-3" id="aiErrorAlert" role="alert">
                        <i class="fas fa-exclamation-triangle me-1"></i> <span id="aiErrorMessage"></span>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-2">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn text-white fw-bold rounded-pill px-4 shadow-sm" id="btnSubmitAiGenerate" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                            <i class="fas fa-bolt me-1.5"></i> Bắt Đầu Tạo Bằng AI
                        </button>
                    </div>
                </div>

                {{-- Trạng thái đang tải (Loading Spinner) --}}
                <div class="text-center py-5 d-none" id="aiLoadingSection">
                    <div class="spinner-border text-primary mb-3" style="width: 3.5rem; height: 3.5rem;" role="status">
                        <span class="visually-hidden">Đang xử lý...</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Trợ lý AI đang suy nghĩ và viết các gói...</h5>
                    <p class="text-muted small mb-0">Hệ thống đang phân tích yêu cầu, định giá và sinh tính năng. Vui lòng đợi trong giây lát!</p>
                </div>

                {{-- Vùng xem trước kết quả (Nếu không chọn auto-save) --}}
                <div class="d-none" id="aiPreviewSection">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <h6 class="fw-bold text-success mb-0 d-flex align-items-center gap-1.5">
                            <i class="fas fa-check-circle"></i> AI đã tạo thành công danh sách gói sau:
                        </h6>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1" id="aiPackageCountBadge">0 gói</span>
                    </div>

                    <div class="row g-3" id="aiPreviewCardsContainer" style="max-height: 400px; overflow-y: auto;">
                        {{-- Cards will be injected by JavaScript --}}
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 mt-3 border-top">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-3 btn-sm" id="btnAiBackToInput">
                            <i class="fas fa-arrow-left me-1"></i> Viết lại mô tả
                        </button>
                        <button type="button" class="btn btn-success fw-bold rounded-pill px-4 shadow-sm" id="btnAiSaveAllPreview">
                            <i class="fas fa-save me-1.5"></i> Lưu Tất Cả Gói Này Vào Hệ Thống
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL 2: THÊM GÓI THỦ CÔNG --}}
<div class="modal fade" id="createPackageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-plus-circle text-primary me-2"></i> Thêm Gói Thiết Kế Website Thủ Công
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.web-design.packages.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tên gói <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control rounded-3" placeholder="Ví dụ: Gói Starter, Gói VIP..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Giá gói (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control rounded-3" placeholder="Ví dụ: 3000000" min="0" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Huy hiệu (Badge)</label>
                            <input type="text" name="badge" class="form-control rounded-3" placeholder="Ví dụ: Phổ biến, Đề xuất, Tiết kiệm...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Màu huy hiệu</label>
                            <select name="badge_color" class="form-select rounded-3">
                                <option value="primary" selected>Xanh dương (Primary)</option>
                                <option value="success">Xanh lá (Success)</option>
                                <option value="warning">Vàng cam (Warning)</option>
                                <option value="danger">Đỏ (Danger)</option>
                                <option value="info">Xanh ngọc (Info)</option>
                                <option value="dark">Đen (Dark)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Thứ tự hiển thị (Số nhỏ đứng trước)</label>
                            <input type="number" name="sort_order" class="form-control rounded-3" value="0">
                        </div>
                        <div class="col-md-6 d-flex align-items-center pt-3">
                            <div class="form-check form-switch fs-5">
                                <input class="form-check-input" type="checkbox" name="is_active" id="createIsActive" value="1" checked>
                                <label class="form-check-label fs-6 fw-semibold ms-2" for="createIsActive">Kích hoạt hiển thị ra ngoài</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                Danh sách tính năng (Mỗi dòng một tính năng gạch đầu dòng)
                            </label>
                            <textarea name="features" rows="8" class="form-control rounded-3" 
                                      placeholder="Website 1-3 trang (Trang chủ, Giới thiệu, Liên hệ)&#10;Thêm 1 trang sản phẩm&#10;Giao diện chuẩn mobile, hiển thị đẹp trên điện thoại&#10;Bao gồm tên miền + hosting 1 năm&#10;Hỗ trợ chỉnh sửa nhỏ trong 7 ngày&#10;Bàn giao là chạy ngay"></textarea>
                            <div class="form-text text-muted small">Mỗi lần xuống dòng (Enter) sẽ tự động tạo thành 1 dấu tích tính năng trên giao diện khách xem.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4">
                        <i class="fas fa-save me-1"></i> Thêm Gói
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODALS 3: CHỈNH SỬA TỪNG GÓI --}}
@foreach($packages as $pkg)
<div class="modal fade" id="editPackageModal_{{ $pkg->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-edit text-primary me-2"></i> Chỉnh Sửa: {{ $pkg->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.web-design.packages.update', $pkg) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tên gói <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control rounded-3" value="{{ old('name', $pkg->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Giá gói (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control rounded-3" value="{{ old('price', $pkg->price) }}" min="0" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Huy hiệu (Badge)</label>
                            <input type="text" name="badge" class="form-control rounded-3" value="{{ old('badge', $pkg->badge) }}" placeholder="Ví dụ: Phổ biến, Đề xuất...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Màu huy hiệu</label>
                            <select name="badge_color" class="form-select rounded-3">
                                <option value="primary" {{ $pkg->badge_color === 'primary' ? 'selected' : '' }}>Xanh dương (Primary)</option>
                                <option value="success" {{ $pkg->badge_color === 'success' ? 'selected' : '' }}>Xanh lá (Success)</option>
                                <option value="warning" {{ $pkg->badge_color === 'warning' ? 'selected' : '' }}>Vàng cam (Warning)</option>
                                <option value="danger" {{ $pkg->badge_color === 'danger' ? 'selected' : '' }}>Đỏ (Danger)</option>
                                <option value="info" {{ $pkg->badge_color === 'info' ? 'selected' : '' }}>Xanh ngọc (Info)</option>
                                <option value="dark" {{ $pkg->badge_color === 'dark' ? 'selected' : '' }}>Đen (Dark)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Thứ tự hiển thị (Số nhỏ đứng trước)</label>
                            <input type="number" name="sort_order" class="form-control rounded-3" value="{{ old('sort_order', $pkg->sort_order) }}">
                        </div>
                        <div class="col-md-6 d-flex align-items-center pt-3">
                            <div class="form-check form-switch fs-5">
                                <input class="form-check-input" type="checkbox" name="is_active" id="editIsActive_{{ $pkg->id }}" value="1" {{ $pkg->is_active ? 'checked' : '' }}>
                                <label class="form-check-label fs-6 fw-semibold ms-2" for="editIsActive_{{ $pkg->id }}">Kích hoạt hiển thị ra ngoài</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                Danh sách tính năng (Mỗi dòng một tính năng gạch đầu dòng)
                            </label>
                            @php
                                $featuresText = is_array($pkg->features) ? implode("\n", $pkg->features) : '';
                            @endphp
                            <textarea name="features" rows="8" class="form-control rounded-3">{{ old('features', $featuresText) }}</textarea>
                            <div class="form-text text-muted small">Mỗi lần xuống dòng (Enter) sẽ tự động tạo thành 1 dấu tích tính năng trên giao diện khách xem.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4">
                        <i class="fas fa-save me-1"></i> Lưu Thay Đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- SCRIPT XỬ LÝ AI TẠO GÓI --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnSubmit = document.getElementById('btnSubmitAiGenerate');
    const inputSection = document.getElementById('aiInputSection');
    const loadingSection = document.getElementById('aiLoadingSection');
    const previewSection = document.getElementById('aiPreviewSection');
    const errorAlert = document.getElementById('aiErrorAlert');
    const errorMessage = document.getElementById('aiErrorMessage');
    const promptInput = document.getElementById('aiPromptInput');
    const modelSelect = document.getElementById('aiModelSelect');
    const autoSaveSwitch = document.getElementById('aiAutoSaveSwitch');
    const cardsContainer = document.getElementById('aiPreviewCardsContainer');
    const packageCountBadge = document.getElementById('aiPackageCountBadge');
    const btnBack = document.getElementById('btnAiBackToInput');
    const btnSaveAll = document.getElementById('btnAiSaveAllPreview');

    let currentGeneratedPackages = [];
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    function showError(msg) {
        errorMessage.textContent = msg;
        errorAlert.classList.remove('d-none');
    }

    function hideError() {
        errorAlert.classList.add('d-none');
        errorMessage.textContent = '';
    }

    if (btnSubmit) {
        btnSubmit.addEventListener('click', function () {
            hideError();
            const promptText = promptInput.value.trim();

            if (!promptText) {
                showError('Vui lòng nhập mô tả các gói cần tạo!');
                promptInput.focus();
                return;
            }

            if (!promptText.includes('-')) {
                showError('Vui lòng thêm dấu "-" ở đầu mỗi dòng tương ứng với 1 gói (ví dụ: - Gói cơ bản giá 2tr).');
                promptInput.focus();
                return;
            }

            // Chuyển sang màn hình loading
            inputSection.classList.add('d-none');
            previewSection.classList.add('d-none');
            loadingSection.classList.remove('d-none');

            fetch('{{ route("admin.web-design.ai-generate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || ''
                },
                body: JSON.stringify({
                    prompt: promptText,
                    model: modelSelect.value,
                    auto_save: autoSaveSwitch.checked
                })
            })
            .then(res => res.json().then(data => ({ status: res.status, ok: res.ok, data })))
            .then(({ ok, data }) => {
                loadingSection.classList.add('d-none');

                if (!ok || !data.success) {
                    inputSection.classList.remove('d-none');
                    showError(data.message || 'Có lỗi xảy ra trong quá trình gọi AI.');
                    return;
                }

                if (data.saved) {
                    // Đã tự động lưu thành công
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: data.message || 'Đã tạo và lưu các gói vào hệ thống!',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        alert(data.message);
                        window.location.reload();
                    }
                } else {
                    // Hiển thị preview
                    currentGeneratedPackages = data.packages || [];
                    renderPreview(currentGeneratedPackages);
                }
            })
            .catch(err => {
                loadingSection.classList.add('d-none');
                inputSection.classList.remove('d-none');
                showError('Không thể kết nối đến máy chủ. Vui lòng thử lại!');
                console.error(err);
            });
        });
    }

    function renderPreview(packages) {
        cardsContainer.innerHTML = '';
        packageCountBadge.textContent = `${packages.length} gói`;

        packages.forEach((pkg, idx) => {
            const col = document.createElement('div');
            col.className = 'col-md-6';

            const featuresList = (pkg.features || []).map(f => `<li>${f}</li>`).join('');
            const priceFormatted = new Intl.NumberFormat('vi-VN').format(pkg.price || 0) + 'đ';

            col.innerHTML = `
                <div class="p-3 bg-white border rounded-3 shadow-xs h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold mb-0 text-dark">${pkg.name || 'Gói Website'}</h6>
                        ${pkg.badge ? `<span class="badge bg-${pkg.badge_color || 'primary'} rounded-pill">${pkg.badge}</span>` : ''}
                    </div>
                    <div class="fw-bold text-primary fs-5 mb-2">${priceFormatted}</div>
                    <ul class="text-muted small ps-3 mb-0" style="font-size: 12.5px;">
                        ${featuresList}
                    </ul>
                </div>
            `;
            cardsContainer.appendChild(col);
        });

        inputSection.classList.add('d-none');
        loadingSection.classList.add('d-none');
        previewSection.classList.remove('d-none');
    }

    if (btnBack) {
        btnBack.addEventListener('click', function () {
            previewSection.classList.add('d-none');
            inputSection.classList.remove('d-none');
        });
    }

    if (btnSaveAll) {
        btnSaveAll.addEventListener('click', function () {
            if (!currentGeneratedPackages.length) return;

            btnSaveAll.disabled = true;
            btnSaveAll.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Đang lưu...';

            fetch('{{ route("admin.web-design.packages.bulk") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || ''
                },
                body: JSON.stringify({
                    packages: currentGeneratedPackages
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: data.message || 'Đã lưu các gói vào hệ thống!',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        alert(data.message);
                        window.location.reload();
                    }
                } else {
                    btnSaveAll.disabled = false;
                    btnSaveAll.innerHTML = '<i class="fas fa-save me-1.5"></i> Lưu Tất Cả Gói Này Vào Hệ Thống';
                    alert(data.message || 'Có lỗi khi lưu các gói.');
                }
            })
            .catch(err => {
                btnSaveAll.disabled = false;
                btnSaveAll.innerHTML = '<i class="fas fa-save me-1.5"></i> Lưu Tất Cả Gói Này Vào Hệ Thống';
                alert('Có lỗi xảy ra khi lưu.');
            });
        });
    }
});
</script>

@endsection
