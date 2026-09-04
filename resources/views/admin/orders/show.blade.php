@extends('layouts.admin')

@section('title', 'Chi tiết Đơn hàng #' . $order->id . ' - Admin')

@section('page_title', 'Chi tiết đơn hàng')

@push('styles')
<style>
    .admin-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.06);
    }

    @media (max-width: 768px) {
        .admin-card {
            padding: 20px 15px;
        }
    }

    .info-section {
        background: #f8fafc;
        border-radius: 16px;
        padding: 22px;
        border: 1px solid #edf2f7;
    }

    .product-item {
        border-bottom: 1px dashed #e2e8f0;
        padding: 16px 0;
    }

    .product-item:last-child {
        border-bottom: none;
    }

    .order-type-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .type-qr {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .type-document {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .type-shipping {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: #0d5337;
    }

    .type-digital {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }

    .action-guide {
        background: #fff9f2;
        border-left: 4px solid #f59e0b;
        padding: 18px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        border: 1px solid #fee2e2;
        border-left-width: 4px;
    }

    .status-badge-lg {
        font-size: 1rem;
        padding: 8px 22px;
        border-radius: 30px;
        font-weight: 600;
        letter-spacing: 0.3px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .pre-wrap-box {
        white-space: pre-wrap;
        word-break: break-word;
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size: 0.9rem;
        line-height: 1.6;
        background: #ffffff;
        border-radius: 10px;
        padding: 14px 18px;
        border: 1px solid #e2e8f0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <div class="admin-card" data-aos="fade-up">
        
        <!-- Top Navigation -->
        <div class="mb-4">
            <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary rounded-pill px-4 mb-3 btn-sm">
                <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
            </a>
            
            <!-- Main Header -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2 text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-file-invoice text-primary"></i> Đơn hàng #{{ $order->id }}
                    </h3>
                    <span class="order-type-badge type-{{ $order->order_type }}">
                        @if($order->order_type == 'qr')
                            <i class="fas fa-qrcode"></i>Đơn QR (TikTok Deal)
                        @elseif($order->order_type == 'document')
                            <i class="fas fa-file-pdf"></i>Đơn Tài liệu
                        @elseif($order->order_type == 'shipping')
                            <i class="fas fa-shipping-fast"></i>Đơn Ship
                        @else
                            <i class="fas fa-download"></i>Đơn Digital
                        @endif
                    </span>
                </div>

                <div>
                    <span class="badge bg-{{ $order->status_color }} status-badge-lg">
                        <i class="fas fa-circle me-1 style="font-size: 8px; vertical-align: middle;"></i>
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>

            <!-- Full-width Status Note Banner if present -->
            @if($order->status_note)
                <div class="card border-0 shadow-sm mt-3 bg-light" style="border-left: 5px solid #ffc107 !important;">
                    <div class="card-body py-3 px-4">
                        <div class="d-flex align-items-center mb-2 text-dark fw-bold">
                            <i class="fas fa-sticky-note me-2 text-warning fs-5"></i>Ghi chú / Chú thích từ Admin:
                        </div>
                        <div class="pre-wrap-box shadow-sm">{{ $order->status_note }}</div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Order Completed Alert -->
        @if($order->status == 'completed')
            <div class="alert alert-success mb-4 rounded-3" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border: none; color: white;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fs-3 me-3"></i>
                    <div>
                        <h5 class="mb-1 text-white fw-bold">✅ Đơn hàng đã hoàn thành</h5>
                        <p class="mb-0" style="font-size: 0.95rem; opacity: 0.95;">Admin đã xác nhận đơn hàng và gửi thông tin bàn giao cho khách hàng.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Order Type Guide -->
        <div class="action-guide">
            <h6 class="fw-bold mb-2 text-warning-emphasis">
                <i class="fas fa-lightbulb me-2 text-warning"></i>Hướng dẫn xử lý đơn {{ $order->order_type }}:
            </h6>
            @if($order->order_type == 'qr')
                <ul class="mb-0 ps-3 small text-muted">
                    <li>Xác nhận thanh toán từ khách hàng.</li>
                    <li>Gửi mã QR code/voucher TikTok qua email hoặc Zalo.</li>
                    <li>Đánh dấu "Hoàn thành" sau khi gửi thành công.</li>
                </ul>
            @elseif($order->order_type == 'document')
                <ul class="mb-0 ps-3 small text-muted">
                    <li>Kiểm tra thanh toán đã được xác nhận.</li>
                    <li>Khách hàng có thể tải file ngay từ trang sản phẩm.</li>
                    <li>Không cần giao hàng vật lý - đánh dấu "Hoàn thành" ngay.</li>
                </ul>
            @elseif($order->order_type == 'shipping')
                <ul class="mb-0 ps-3 small text-muted">
                    <li>Đóng gói sản phẩm và chuẩn bị giao hàng.</li>
                    <li>Cập nhật trạng thái "Đã giao hàng" khi bàn giao cho đơn vị vận chuyển.</li>
                    <li>Liên hệ khách hàng xác nhận khi nhận hàng và đánh dấu "Hoàn thành".</li>
                </ul>
            @else
                <ul class="mb-0 ps-3 small text-muted">
                    <li>Xác nhận thanh toán.</li>
                    <li>Nhập Tài khoản / Key và Ghi chú bên dưới để hệ thống gửi email bàn giao tự động.</li>
                </ul>
            @endif
        </div>

        <div class="row g-4">
            <!-- Customer Information -->
            <div class="col-lg-6">
                <div class="info-section h-100">
                    <h5 class="fw-bold mb-3 text-dark pb-2 border-bottom">
                        <i class="fas fa-user text-primary me-2"></i>Thông tin khách hàng
                    </h5>
                    <div class="mb-2">
                        <span class="text-muted me-2">Họ tên:</span>
                        <strong class="text-dark">{{ $order->customer_name }}</strong>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted me-2">Email:</span>
                        <strong class="text-dark">{{ $order->customer_email }}</strong>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted me-2">Số điện thoại:</span>
                        <strong class="text-dark">{{ $order->customer_phone }}</strong>
                    </div>
                    
                    <div class="mt-3">
                        <div class="fw-bold text-dark mb-1">
                            @if($order->order_type == 'shipping')
                                <i class="fas fa-map-marker-alt text-danger me-1"></i>Địa chỉ giao hàng:
                            @else
                                <i class="fas fa-credit-card text-success me-1"></i>Ghi chú liên hệ &amp; Thanh toán:
                            @endif
                        </div>
                        <div class="pre-wrap-box bg-white mt-2 border-start border-4 border-primary shadow-sm" style="font-family: inherit;">
                            {!! e($order->customer_address) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Details -->
            <div class="col-lg-6">
                <div class="info-section h-100">
                    <h5 class="fw-bold mb-3 text-dark pb-2 border-bottom">
                        <i class="fas fa-info-circle text-primary me-2"></i>Chi tiết đơn hàng
                    </h5>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Mã đơn:</span>
                        <strong class="text-dark">#{{ $order->id }}</strong>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Ngày đặt:</span>
                        <strong class="text-dark">{{ $order->created_at->format('d/m/Y H:i') }}</strong>
                    </div>
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <span class="text-muted">Tổng tiền:</span>
                        <span class="text-primary fw-bold fs-4">{{ $order->formatted_total }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Số lượng sản phẩm:</span>
                        <strong class="text-dark">{{ $order->orderItems->sum('quantity') }} món</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products List -->
        <div class="info-section mt-4">
            <h5 class="fw-bold mb-3 text-dark pb-2 border-bottom">
                <i class="fas fa-box text-primary me-2"></i>Danh sách sản phẩm ({{ $order->orderItems->count() }})
            </h5>
            @foreach($order->orderItems as $item)
                <div class="product-item">
                    <div class="row align-items-center g-3">
                        <div class="col-auto">
                            @if($item->product && $item->product->image)
                                <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="rounded shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 60px; height: 60px;">
                                    <i class="fas fa-box fs-4"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col">
                            <div class="fw-bold text-dark d-flex align-items-center gap-2 flex-wrap">
                                {{ $item->product ? $item->product->name : 'Sản phẩm đã xóa' }}
                                @if($item->product && !($item->product->is_active ?? true))
                                    <span class="badge bg-secondary" style="font-size: 10px;">Đã ẩn</span>
                                @endif
                            </div>
                            <small class="text-muted">
                                @if($item->product)
                                    @if($item->product->category == 'ebooks')
                                        <i class="fas fa-file-pdf text-danger me-1"></i>Tài liệu số
                                    @elseif($item->product->category == 'tiktok')
                                        <i class="fas fa-qrcode text-primary me-1"></i>TikTok Deal
                                    @elseif($item->product->delivery_type == 'physical')
                                        <i class="fas fa-box text-success me-1"></i>Giao hàng vật lý
                                    @else
                                        <i class="fas fa-download text-info me-1"></i>Digital
                                    @endif
                                @endif
                            </small>
                        </div>
                        <div class="col-md-3 text-center">
                            @if($item->product)
                                <form action="{{ route('admin.products.toggle-active', $item->product) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $item->product->is_active ? 'btn-outline-success' : 'btn-outline-secondary' }} rounded-pill px-3 py-1" style="font-size: 11.5px; font-weight: 500;">
                                        <i class="fas {{ $item->product->is_active ? 'fa-eye text-success' : 'fa-eye-slash text-secondary' }} me-1"></i>
                                        <span>{{ $item->product->is_active ? 'Hiển thị' : 'Đã ẩn' }}</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div class="col-auto text-center px-3">
                            <span class="badge bg-secondary rounded-pill px-3 py-2">x{{ $item->quantity }}</span>
                        </div>
                        <div class="col-auto text-end" style="min-width: 100px;">
                            <div class="fw-bold text-primary">{{ number_format($item->price, 0, ',', '.') }}đ</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Digital Product Delivery Form -->
        @php
            $defaultNote = "Chào " . ($order->customer_name ?? 'bạn') . ",\n\nCảm ơn bạn đã ủng hộ DungThu.com. Dưới đây là thông tin bàn giao cho đơn hàng của bạn:\n";
            foreach($order->orderItems as $item) {
                $defaultNote .= "• " . ($item->product->name ?? 'Sản phẩm') . " (SL: " . $item->quantity . ")\n";
            }
            $defaultNote .= "\nNếu có bất kỳ câu hỏi nào, vui lòng liên hệ Zalo hoặc Email hỗ trợ nhé!";
        @endphp

        <div class="info-section mt-4" style="border: 2px dashed #667eea; background: #fafbff; border-radius: 16px; padding: 25px;">
            <h5 class="fw-bold mb-3 text-primary d-flex align-items-center gap-2">
                <i class="fas fa-paper-plane"></i>🔑 CẤP TÀI KHOẢN &amp; GỬI HÀNG QUA EMAIL
            </h5>
            <p class="text-muted small mb-3">Dành cho đơn hàng digital, tài liệu hoặc tài khoản game. Nhập thông tin bàn giao dưới đây để hệ thống tự động gửi email cho khách và chuyển trạng thái đơn thành <strong>Hoàn thành</strong>.</p>
            
            @if($order->status == 'completed' && ($order->delivery_account || $order->delivery_key || $order->delivery_note))
                <div class="alert alert-info py-2 px-3 mb-3 rounded-3 small">
                    <i class="fas fa-info-circle me-1"></i> Đơn hàng này đã được bàn giao trước đó. Bạn có thể cập nhật thông tin mới và gửi lại email bên dưới.
                </div>
            @endif

            <form action="{{ route('admin.orders.deliver', $order) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <!-- Account -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark small">Tài khoản / Account:</label>
                        <input type="text" name="delivery_account" class="form-control" 
                               value="{{ $order->delivery_account }}" 
                               placeholder="Ví dụ: taikhoan@gmail.com | matkhau123">
                        <small class="text-muted">Thông tin đăng nhập tài khoản (nếu có)</small>
                    </div>
                    
                    <!-- KEY -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark small">KEY / Mã kích hoạt:</label>
                        <input type="text" name="delivery_key" class="form-control" 
                               value="{{ $order->delivery_key }}" 
                               placeholder="Ví dụ: GPT-XXXX-XXXX-XXXX">
                        <small class="text-muted">Mã kích hoạt, license key hoặc link tải nhanh (nếu có)</small>
                    </div>

                    <!-- Note / Instructions -->
                    <div class="col-12">
                        <label class="form-label fw-bold text-dark small">Thông báo &amp; Hướng dẫn sử dụng:</label>
                        <textarea name="delivery_note" class="form-control" rows="5" placeholder="Ghi chú thêm cho khách hàng...">{{ $order->delivery_note ?? $defaultNote }}</textarea>
                        <small class="text-muted">Nội dung này hiển thị trực tiếp trong email bàn giao gửi tới khách hàng.</small>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                        <i class="fas fa-paper-plane me-2"></i>Gửi hàng &amp; Hoàn thành Đơn hàng
                    </button>
                </div>
            </form>
        </div>

        <!-- Update Status Form -->
        <div class="info-section mt-4">
            <h5 class="fw-bold mb-3 text-dark pb-2 border-bottom">
                <i class="fas fa-tasks text-primary me-2"></i>Cập nhật trạng thái đơn hàng
            </h5>
            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row align-items-end g-3 mb-3">
                    <div class="col-md-8">
                        <label class="form-label fw-bold text-dark small">Trạng thái mới:</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                            @if($order->order_type == 'shipping')
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đã giao hàng</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã nhận hàng</option>
                            @endif
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100 fw-semibold">
                            <i class="fas fa-save me-2"></i>Cập nhật trạng thái
                        </button>
                    </div>
                </div>
                <div>
                    <label class="form-label fw-bold text-dark small"><i class="fas fa-sticky-note me-1 text-warning"></i>Ghi chú / Chú thích trạng thái (Hiển thị nổi bật ở trang chi tiết):</label>
                    <textarea name="status_note" class="form-control" rows="3" placeholder="Nhập ghi chú hoặc chú thích cho trạng thái này...">{{ $order->status_note }}</textarea>
                </div>
            </form>
        </div>

        <!-- Delete Order -->
        <div class="mt-4 text-end">
            <form action="{{ route('admin.orders.delete', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger rounded-pill px-4">
                    <i class="fas fa-trash me-2"></i>Xóa đơn hàng
                </button>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    if (typeof AOS !== 'undefined') {
        AOS.init({ duration: 800, once: true });
    }
</script>
@endpush
