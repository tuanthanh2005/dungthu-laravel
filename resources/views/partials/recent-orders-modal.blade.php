@php
    // Lấy 2 sản phẩm ngẫu nhiên/nổi bật làm mẫu giao dịch vừa thành công
    $recentProducts = \App\Models\Product::inRandomOrder()->take(2)->get();
    $sampleBuyers = ['Nguyễn***', 'Trần***', 'Lê***', 'Phạm***', 'Hoàng***', 'Vũ***'];
@endphp

<!-- Modal 2 Sản Phẩm Vừa Được Mua Gần Đây -->
<div class="modal fade" id="recentOrdersWelcomeModal" tabindex="-1" aria-labelledby="recentOrdersWelcomeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: #ffffff;">
            {{-- Header --}}
            <div class="modal-header border-0 text-white px-4 py-3 position-relative d-flex align-items-center justify-content-between" 
                 style="background: linear-gradient(135deg, #ff5e00 0%, #ff8e43 100%);">
                <div>
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2 mb-1" id="recentOrdersWelcomeModalLabel" style="font-size: 17px;">
                        <i class="fa-solid fa-fire text-warning"></i>
                        {{ __('GIAO DỊCH VỪA HOÀN THÀNH') }}
                    </h5>
                    <div class="d-flex align-items-center gap-1 text-white-50" style="font-size: 11px;">
                        <span style="width: 7px; height: 7px; background-color: #22c55e; border-radius: 50%; display: inline-block; animation: pulseDotLive 1.5s infinite;"></span>
                        <span class="text-white fw-medium">{{ __('Hệ thống bàn giao tự động 24/7') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white close-recent-modal-btn" data-bs-dismiss="modal" aria-label="Close" style="opacity: 0.9; filter: invert(1) grayscale(1) brightness(2);"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-3 p-sm-4" style="background-color: #f8f9fa;">
                <p class="text-muted small mb-3 text-center" style="font-size: 12.5px;">
                    <i class="fa-solid fa-circle-check text-success me-1"></i>{{ __('Các đơn hàng vừa được xử lý và kích hoạt thành công cho khách hàng:') }}
                </p>

                <div class="d-flex flex-column gap-3">
                    @foreach($recentProducts as $index => $product)
                        @php
                            $buyer = $sampleBuyers[$index % count($sampleBuyers)];
                            $price = $product->sale_price ?: $product->price;
                            $imgSrc = $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : asset('images/default-product.png');
                        @endphp
                        <div class="p-3 bg-white rounded-3 shadow-sm border border-light text-start">
                            <div class="d-flex align-items-start gap-3">
                                <!-- Thumbnail -->
                                <img src="{{ $imgSrc }}" 
                                     alt="{{ $product->name }}" 
                                     style="width: 52px; height: 52px; object-fit: cover; border-radius: 10px; border: 1px solid #f1f5f9; flex-shrink: 0;">
                                
                                <!-- Details -->
                                <div class="flex-grow-1 min-w-0">
                                    <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                                        <h6 class="fw-bold text-dark mb-0" style="font-size: 13px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="{{ $product->name }}">
                                            {{ $product->name }}
                                        </h6>
                                        <span class="fw-bold text-danger flex-shrink-0 ms-1" style="font-size: 13.5px;">
                                            {{ number_format($price, 0, ',', '.') }}đ
                                        </span>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between gap-2 mt-2">
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-1 px-2" style="font-size: 10.5px; font-weight: 600;">
                                            <i class="fa-solid fa-bolt me-1"></i>{{ __('Đã giao tự động') }}
                                        </span>
                                        <span class="text-muted" style="font-size: 11px; font-weight: 500;">
                                            <i class="fa-solid fa-user me-1 text-secondary"></i>{{ $buyer }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer border-0 p-3 bg-white justify-content-between">
                <button type="button" class="btn btn-sm btn-light border text-muted px-3 close-recent-modal-btn" data-bs-dismiss="modal" style="border-radius: 20px; font-size: 12px;">
                    <i class="fa-solid fa-xmark me-1"></i>{{ __('Đóng (Tắt 1h)') }}
                </button>
                <a href="{{ route('shop') }}" class="btn btn-sm text-white px-4 fw-bold shadow-sm" style="background: linear-gradient(135deg, #ff5e00 0%, #ff8e43 100%); border-radius: 20px; font-size: 12.5px;">
                    <i class="fa-solid fa-cart-shopping me-1"></i>{{ __('Mua sắm ngay') }}
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('recentOrdersWelcomeModal');
        if (!modalEl) return;

        const STORAGE_KEY = 'recent_orders_modal_closed_until';
        const closedUntil = localStorage.getItem(STORAGE_KEY);
        const now = Date.now();

        // Nếu chưa đóng hoặc đã hết thời hạn 1 tiếng -> Tự động bật Modal sau 1.5 giây
        if (!closedUntil || now >= parseInt(closedUntil, 10)) {
            setTimeout(() => {
                const bsModal = new bootstrap.Modal(modalEl);
                bsModal.show();
            }, 1500);
        }

        // Khi người dùng nhấn nút Đóng hoặc X -> Lưu mốc thời gian ẩn 1 tiếng (3,600,000 ms)
        const closeButtons = modalEl.querySelectorAll('.close-recent-modal-btn');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const oneHourLater = Date.now() + 3600000;
                localStorage.setItem(STORAGE_KEY, oneHourLater.toString());
            });
        });

        // Nếu người dùng đóng Modal bằng cách click backdrop hoặc phím ESC
        modalEl.addEventListener('hidden.bs.modal', function () {
            const oneHourLater = Date.now() + 3600000;
            localStorage.setItem(STORAGE_KEY, oneHourLater.toString());
        });
    });
</script>
