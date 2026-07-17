extends('layouts.admin')

@section('title', 'Quản Lý Chủ Đề Blog')

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
                    <i class="fas fa-tags text-primary me-2"></i>Quản lý Chủ đề bài viết Blog
                </h3>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.blog-topics.create') }}" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                        <i class="fas fa-plus-circle me-2"></i>Thêm chủ đề mới
                    </a>
                </div>
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.blog-topics') }}" method="GET" class="mb-4">
                <div class="input-group shadow-sm rounded-pill overflow-hidden">
                    <input type="text" name="search" class="form-control border-0 px-4 py-3" placeholder="Tìm kiếm chủ đề bằng nhãn hoặc slug..." value="{{ $search }}">
                    <button class="btn btn-white border-0 px-4" type="submit">
                        <i class="fas fa-search text-muted"></i>
                    </button>
                    @if($search)
                        <a href="{{ route('admin.blog-topics') }}" class="btn btn-white border-0 px-3 d-flex align-items-center">
                            <i class="fas fa-times text-danger"></i>
                        </a>
                    @endif
                </div>
            </form>

            @if($topics->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 150px;">Nhãn hiển thị</th>
                                <th style="width: 180px;">Slug / URL</th>
                                <th>Từ khóa (Aliases)</th>
                                <th class="text-center" style="width: 120px;">Trạng thái</th>
                                <th class="text-end" style="width: 180px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topics as $t)
                                <tr>
                                    <td>
                                        <strong class="text-dark">{{ $t->label }}</strong>
                                    </td>
                                    <td>
                                        <code class="text-primary" style="font-size: 0.9rem;">/blog/chu-de/{{ $t->slug }}</code>
                                    </td>
                                    <td>
                                        @if(is_array($t->aliases) && count($t->aliases) > 0)
                                            @foreach($t->aliases as $alias)
                                                <span class="keyword-badge">{{ $alias }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted" style="font-size: 0.85rem;">Không có</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($t->is_active)
                                            <span class="badge bg-success rounded-pill px-3 py-2">Hoạt động</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill px-3 py-2">Tắt</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <a href="{{ route('admin.blog-topics.edit', $t->id) }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                                <i class="fas fa-edit me-1"></i>Sửa
                                            </a>
                                            <form action="{{ route('admin.blog-topics.delete', $t->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa chủ đề này? Hành động này không thể hoàn tác.');" class="m-0">
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
                    {{ $topics->links() }}
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-search fa-3x mb-3 text-slate-300"></i>
                    <h5>Không tìm thấy chủ đề nào</h5>
                    <p class="mb-0">Thử tìm kiếm với cụm từ khác hoặc tạo chủ đề mới.</p>
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
</script>
@endpush
