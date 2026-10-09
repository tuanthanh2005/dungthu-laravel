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
        <div class="d-flex gap-2">
            <a href="{{ route('web-design') }}" target="_blank" class="btn btn-outline-primary fw-semibold rounded-pill px-3">
                <i class="fas fa-external-link-alt me-1"></i> Xem trang ngoài
            </a>
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
            <button type="button" class="btn btn-sm btn-primary fw-bold rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#createPackageModal">
                <i class="fas fa-plus me-1"></i> Thêm Gói Mới
            </button>
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
                                Chưa có gói thiết kế website nào. Hãy bấm "Thêm Gói Mới" để tạo gói đầu tiên!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Modal Thêm Gói Mới --}}
<div class="modal fade" id="createPackageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-plus-circle text-primary me-2"></i> Thêm Gói Thiết Kế Website Mới
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

{{-- Modals Chỉnh Sửa Từng Gói --}}
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

@endsection
