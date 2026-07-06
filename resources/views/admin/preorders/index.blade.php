@extends('layouts.admin')

@section('title', 'Quản Lý Đăng Ký Pre-order')

@section('page_title', 'Pre-orders')

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
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 15px;">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 15px;">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 15px;">
                <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Left Column: Keywords and Stats -->
            <div class="col-lg-5 col-md-12">
                <div class="admin-card" data-aos="fade-right">
                    <h4 class="fw-bold mb-4">
                        <i class="fas fa-chart-pie text-primary me-2"></i>Thống kê theo từ khóa
                    </h4>
                    
                    @if($keywords->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Từ khóa (Slug)</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($keywords as $k)
                                <tr class="{{ $filterKeyword === $k->keyword ? 'table-primary' : '' }}">
                                    <td>
                                        <strong class="text-dark">{{ Str::headline(str_replace('-', ' ', $k->keyword)) }}</strong>
                                        <div class="text-muted" style="font-size: 0.75rem;">/go/{{ $k->keyword }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill" style="font-size: 0.85rem; padding: 6px 12px;">{{ $k->count }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.preorders', ['keyword' => $k->keyword]) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                            <i class="fas fa-eye me-1"></i>Xem chi tiết
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $keywords->links() }}
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-folder-open fa-3x mb-3"></i>
                        <p class="mb-0">Chưa có lượt đăng ký pre-order nào.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Detail List of Selected Keyword -->
            <div class="col-lg-7 col-md-12">
                <div class="admin-card" data-aos="fade-left">
                    @if($filterKeyword)
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h4 class="fw-bold mb-0">
                                <i class="fas fa-list text-success me-2"></i>Danh sách: {{ Str::headline(str_replace('-', ' ', $filterKeyword)) }}
                            </h4>
                            <span class="badge bg-success">{{ $preorders->total() }} email</span>
                        </div>

                        <!-- Matching Product Alert/Status & Manual Selector -->
                        <div class="mb-4">
                            <div class="card p-3 border-0 shadow-sm mb-3" style="border-radius: 15px; background: #f8fafc; border: 1px solid #edf2f7 !important;">
                                <label class="form-label fw-bold text-dark mb-2" style="font-size: 0.95rem;">
                                    <i class="fas fa-link text-primary me-1"></i> Liên kết sản phẩm để gửi email:
                                </label>
                                <select class="form-select rounded-pill px-3" id="manual-product-select" style="font-size: 0.9rem; border-color: #cbd5e0;">
                                    <option value="">-- Chọn sản phẩm để liên kết --</option>
                                    @foreach($allProducts as $p)
                                        <option value="{{ $p->id }}" 
                                            @if($matchedProduct && $matchedProduct->id === $p->id) selected @endif
                                            data-image="{{ $p->image ? (Str::startsWith($p->image, ['http://', 'https://']) ? $p->image : url($p->image)) : '' }}"
                                            data-name="{{ $p->name }}"
                                            data-price="{{ $p->formatted_price }}"
                                        >
                                            {{ $p->name }} ({{ $p->formatted_price }})
                                        </option>
                                    @endforeach
                                </select>
                                
                                <!-- Preview of selected product -->
                                <div id="product-preview-box" class="mt-3 p-3 bg-white rounded border d-none" style="border-radius: 12px !important; border-color: #e2e8f0 !important;">
                                    <div class="d-flex align-items-center">
                                        <img id="preview-product-img" src="" alt="" class="rounded me-3 d-none" style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #e2e8f0;">
                                        <div id="preview-product-no-img" class="rounded me-3 bg-secondary text-white d-none" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                                            <i class="fas fa-box"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1 text-primary" id="preview-product-title" style="font-size: 0.9rem;">Sản phẩm đã chọn</h6>
                                            <p class="mb-0 text-danger fw-bold" id="preview-product-price" style="font-size: 0.85rem;"></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Warning alert if no product is selected -->
                                <div id="product-warning-alert" class="alert alert-warning border-0 p-2 mt-3 mb-0 d-none" style="border-radius: 10px; font-size: 0.8rem; background-color: #fffaf0; color: #dd6b20;">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Chưa chọn sản phẩm. Các nút gửi thông báo sẽ bị khóa.
                                </div>
                            </div>

                            @php
                                // Count pending preorders for this keyword
                                $pendingCount = \App\Models\PreOrder::where('keyword', $filterKeyword)->where('status', 'pending')->count();
                            @endphp

                            <div class="d-flex justify-content-end align-items-center">
                                <form id="notify-all-form" action="{{ route('admin.preorders.notify-keyword') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn gửi email thông báo hàng đã có cho TẤT CẢ khách hàng đang chờ của từ khóa này?');" class="m-0">
                                    @csrf
                                    <input type="hidden" name="keyword" value="{{ $filterKeyword }}">
                                    <input type="hidden" name="product_id" class="selected-product-id" value="{{ $matchedProduct ? $matchedProduct->id : '' }}">
                                    
                                    @if($pendingCount > 0)
                                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 py-2 fw-bold text-white border-0 notify-btn" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); box-shadow: 0 4px 15px rgba(56, 239, 125, 0.3);">
                                            <i class="fas fa-paper-plane me-1"></i> Gửi thông báo tất cả ({{ $pendingCount }})
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4 py-2 fw-bold text-white" disabled style="cursor: not-allowed; opacity: 0.6;">
                                            <i class="fas fa-check-double me-1"></i> Đã thông báo hết
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </div>

                        @if($preorders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Email</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày đăng ký</th>
                                        <th class="text-end" style="min-width: 180px;">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($preorders as $po)
                                    <tr>
                                        <td>#{{ $po->id }}</td>
                                        <td><strong>{{ $po->email }}</strong></td>
                                        <td>
                                            @if($po->status === 'pending')
                                                <span class="badge bg-warning text-dark">Đang chờ</span>
                                            @else
                                                <span class="badge bg-success">Đã thông báo</span>
                                            @endif
                                        </td>
                                        <td style="font-size: 0.8rem; color: #718096;">
                                            {{ $po->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex align-items-center justify-content-end gap-2">
                                                <form action="{{ route('admin.preorders.notify', $po->id) }}" method="POST" class="m-0 notify-single-form">
                                                    @csrf
                                                    <input type="hidden" name="product_id" class="selected-product-id" value="{{ $matchedProduct ? $matchedProduct->id : '' }}">
                                                    
                                                    @if($po->status === 'pending')
                                                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 py-1 notify-btn notify-single-btn" style="font-size: 0.75rem; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border: none;">
                                                            <i class="fas fa-paper-plane me-1"></i> Gửi thông báo
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 notify-btn" style="font-size: 0.75rem;" title="Gửi lại thông báo">
                                                            <i class="fas fa-redo me-1"></i> Gửi lại
                                                        </button>
                                                    @endif
                                                </form>
                                                
                                                <form action="{{ route('admin.preorders.delete', $po->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lượt đăng ký này?');" class="m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Xóa" style="text-decoration: none;">
                                                        <i class="fas fa-trash"></i> Xóa
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $preorders->appends(['keyword' => $filterKeyword])->links() }}
                        </div>
                        @else
                        <p class="text-muted text-center py-4">Không có email nào trong danh sách này.</p>
                        @endif
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-hand-pointer fa-4x mb-3 text-slate-300"></i>
                            <h5>Xem chi tiết danh sách đăng ký</h5>
                            <p class="mb-0">Chọn một từ khóa ở cột bên trái để hiển thị danh sách email khách hàng đã đăng ký chờ.</p>
                        </div>
                    @endif
                </div>
            </div>
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

    // Xử lý chọn sản phẩm liên kết thủ công cho Preorder
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('manual-product-select');
        const previewBox = document.getElementById('product-preview-box');
        const previewImg = document.getElementById('preview-product-img');
        const previewNoImg = document.getElementById('preview-product-no-img');
        const previewTitle = document.getElementById('preview-product-title');
        const previewPrice = document.getElementById('preview-product-price');
        const warningAlert = document.getElementById('product-warning-alert');
        const notifyBtns = document.querySelectorAll('.notify-btn');

        function updateProductSelection() {
            if (!productSelect) return;
            
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const productId = productSelect.value;

            // Cập nhật giá trị hidden fields
            document.querySelectorAll('.selected-product-id').forEach(input => {
                input.value = productId;
            });

            if (productId) {
                // Hiển thị preview
                const name = selectedOption.getAttribute('data-name');
                const price = selectedOption.getAttribute('data-price');
                const image = selectedOption.getAttribute('data-image');

                previewTitle.textContent = name;
                previewPrice.textContent = price;

                if (image) {
                    previewImg.src = image;
                    previewImg.classList.remove('d-none');
                    previewNoImg.classList.add('d-none');
                } else {
                    previewImg.classList.add('d-none');
                    previewNoImg.classList.remove('d-none');
                }

                previewBox.classList.remove('d-none');
                warningAlert.classList.add('d-none');

                // Bật các nút gửi thông báo
                notifyBtns.forEach(btn => {
                    btn.removeAttribute('disabled');
                    btn.style.opacity = '1';
                    btn.style.cursor = 'pointer';
                });
            } else {
                // Ẩn preview, hiện cảnh báo
                previewBox.classList.add('d-none');
                warningAlert.classList.remove('d-none');

                // Tắt các nút gửi thông báo
                notifyBtns.forEach(btn => {
                    btn.setAttribute('disabled', 'true');
                    btn.style.opacity = '0.5';
                    btn.style.cursor = 'not-allowed';
                });
            }
        }

        if (productSelect) {
            productSelect.addEventListener('change', updateProductSelection);
            // Chạy lần đầu để thiết lập trạng thái ban đầu dựa trên matchedProduct
            updateProductSelection();
        }
    });
</script>
@endpush
