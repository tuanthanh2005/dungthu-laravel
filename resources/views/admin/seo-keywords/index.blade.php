@extends('layouts.admin')

@section('title', 'Quản Lý Từ Khóa SEO')

@section('page_title', 'Từ khóa SEO')

@push('styles')
<style>

    .admin-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    .admin-nav .nav-link {
        color: #4a5568;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 10px;
        transition: all 0.3s ease;
        margin: 0 5px;
    }

    .admin-nav .nav-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .admin-nav .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .keyword-badge {
        font-size: 0.75rem;
        padding: 4px 8px;
        margin: 2px;
        background-color: #edf2f7;
        color: #4a5568;
        border-radius: 5px;
        display: inline-block;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
        <div class="admin-card" data-aos="fade-up">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-pill px-4 mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
                <h3 class="fw-bold mb-0">
                    <i class="fas fa-search-plus text-primary me-2"></i>Quản lý Từ khóa SEO tìm nhanh
                </h3>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success px-4 py-2 rounded-pill shadow-sm btn-submit-all-index" data-url="{{ route('admin.seo-keywords.submit-all') }}">
                        <i class="fab fa-google me-2"></i>Gửi Index Hàng Loạt
                    </button>
                    <a href="{{ route('admin.seo-keywords.create') }}" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                        <i class="fas fa-plus-circle me-2"></i>Thêm từ khóa mới
                    </a>
                </div>
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.seo-keywords') }}" method="GET" class="mb-4">
                <div class="input-group shadow-sm rounded-pill overflow-hidden">
                    <input type="text" name="search" class="form-control border-0 px-4 py-3" placeholder="Tìm kiếm từ khóa bằng nhãn hoặc slug..." value="{{ $search }}">
                    <button class="btn btn-white border-0 px-4" type="submit">
                        <i class="fas fa-search text-muted"></i>
                    </button>
                    @if($search)
                        <a href="{{ route('admin.seo-keywords') }}" class="btn btn-white border-0 px-3 d-flex align-items-center">
                            <i class="fas fa-times text-danger"></i>
                        </a>
                    @endif
                </div>
            </form>

            @if($keywords->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 150px;">Nhãn hiển thị</th>
                                <th style="width: 180px;">Slug / URL</th>
                                <th>Đồng nghĩa (Aliases)</th>
                                <th class="text-center" style="width: 120px;">Trạng thái</th>
                                <th class="text-end" style="width: 180px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($keywords as $k)
                                <tr>
                                    <td>
                                        <strong class="text-dark">{{ $k->label }}</strong>
                                    </td>
                                    <td>
                                        <code class="text-primary" style="font-size: 0.9rem;">/tim-kiem/{{ $k->slug }}</code>
                                    </td>
                                    <td>
                                        @if(is_array($k->aliases) && count($k->aliases) > 0)
                                            @foreach($k->aliases as $alias)
                                                <span class="keyword-badge">{{ $alias }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted" style="font-size: 0.85rem;">Không có</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($k->is_active)
                                            <span class="badge bg-success rounded-pill px-3 py-2">Hoạt động</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill px-3 py-2">Tắt</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3 btn-submit-index" data-id="{{ $k->id }}" data-url="{{ route('admin.seo-keywords.submit-index', $k->id) }}">
                                                <i class="fab fa-google me-1"></i>Gửi Index
                                            </button>
                                            <a href="{{ route('admin.seo-keywords.edit', $k->id) }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                                <i class="fas fa-edit me-1"></i>Sửa
                                            </a>
                                            <form action="{{ route('admin.seo-keywords.delete', $k->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa từ khóa này? Hành động này không thể hoàn tác.');" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                    <i class="fas fa-trash me-1"></i>Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $keywords->links() }}
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-search fa-3x mb-3 text-slate-300"></i>
                    <h5>Không tìm thấy từ khóa nào</h5>
                    <p class="mb-0">Thử tìm kiếm với cụm từ khác hoặc tạo từ khóa mới.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true });

    function unlockEverything() {
        sessionStorage.setItem('admin_unlocked', 'true');
    }

    // Protection for other links
    document.querySelectorAll('.protected-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-url');
            
            if (sessionStorage.getItem('admin_unlocked') === 'true') {
                window.location.href = url;
            } else {
                const pass = prompt('Đây là khu vực bảo mật. Vui lòng nhập mật khẩu để tiếp tục:');
                if (pass === '113') {
                    unlockEverything();
                    window.location.href = url;
                } else if (pass !== null) {
                    alert('Mật khẩu không chính xác!');
                }
            }
        });
    });

    function adminLockManual() {
        if (confirm('Bạn có muốn khóa lại các khu vực bảo mật không?')) {
            sessionStorage.removeItem('admin_unlocked');
            sessionStorage.removeItem('revenue_unlocked');
            window.location.href = "{{ route('admin.lock') }}";
        }
    }

    // Gửi index từ khóa SEO thủ công qua AJAX
    document.querySelectorAll('.btn-submit-index').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            const button = this;
            const originalHtml = button.innerHTML;
            
            const pin = window.prompt('Nhập mã xác nhận (PIN admin 8 số) để gửi Index lên Google:');
            if (pin === null) return;
            if (!/^\d{8}$/.test(pin)) {
                alert('Mã xác nhận phải đúng 8 số.');
                return;
            }

            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang gửi...';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    admin_pin: pin
                })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                if (status === 200 && body.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: body.message || 'Gửi Index thành công!',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Thất bại!',
                        text: body.message || 'Lỗi xảy ra từ hệ thống hoặc API đã hết hạn mức hôm nay.'
                    });
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi kết nối!',
                    text: 'Không thể kết nối đến server.'
                });
            })
            .finally(() => {
                button.disabled = false;
                button.innerHTML = originalHtml;
            });
        });
    });

    // Gửi index hàng loạt từ khóa SEO
    const btnSubmitAll = document.querySelector('.btn-submit-all-index');
    if (btnSubmitAll) {
        btnSubmitAll.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            const button = this;
            const originalHtml = button.innerHTML;
            
            const pin = window.prompt('Nhập mã xác nhận (PIN admin 8 số) để gửi INDEX HÀNG LOẠT lên Google:');
            if (pin === null) return;
            if (!/^\d{8}$/.test(pin)) {
                alert('Mã xác nhận phải đúng 8 số.');
                return;
            }

            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang gửi hàng loạt...';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    admin_pin: pin
                })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                if (status === 200 && body.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: body.message || 'Gửi Index hàng loạt thành công!',
                        confirmButtonText: 'Đồng ý'
                    });
                } else {
                    let failedDetails = '';
                    if (body.failed && body.failed.length > 0) {
                        failedDetails = '\n\nChi tiết lỗi:\n' + body.failed.map(f => `- ${f.slug}: ${f.message}`).join('\n');
                    }
                    Swal.fire({
                        icon: body.submitted > 0 ? 'warning' : 'error',
                        title: body.submitted > 0 ? 'Hoàn thành một phần!' : 'Thất bại!',
                        text: (body.message || 'Lỗi xảy ra từ hệ thống hoặc API đã hết hạn mức hôm nay.') + failedDetails
                    });
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi kết nối!',
                    text: 'Không thể kết nối đến server.'
                });
            })
            .finally(() => {
                button.disabled = false;
                button.innerHTML = originalHtml;
            });
        });
    }
</script>
@endpush
