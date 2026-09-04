<!-- Modal Thông Báo Giao Hàng & Hỗ Trợ -->
<div class="modal fade" id="spamWarningWelcomeModal" tabindex="-1" aria-labelledby="spamWarningWelcomeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: #ffffff;">
            
            {{-- Header --}}
            <div class="modal-header border-0 text-white px-4 py-3 position-relative d-flex align-items-center justify-content-between" 
                 style="background: linear-gradient(135deg, #dc2626 0%, #ea580c 50%, #f97316 100%);">
                <div>
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2 mb-1" id="spamWarningWelcomeModalLabel" style="font-size: 17px; letter-spacing: -0.2px;">
                        <i class="fas fa-bullhorn text-warning me-1"></i>
                        {{ __('THÔNG BÁO GIAO HÀNG & HỖ TRỢ') }}
                    </h5>
                    <div class="d-flex align-items-center gap-1.5 text-white-50" style="font-size: 11.5px;">
                        <span style="width: 7px; height: 7px; background-color: #4ade80; border-radius: 50%; display: inline-block; box-shadow: 0 0 8px #4ade80;"></span>
                        <span class="text-white fw-medium" style="opacity: 0.95;">{{ __('Hệ thống xử lý đơn hàng tự động & Hỗ trợ 24/7') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white close-spam-modal-btn" data-bs-dismiss="modal" aria-label="Close" style="opacity: 0.9; filter: invert(1) brightness(2);"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-3 p-sm-4" style="background-color: #f8fafc;">
                
                {{-- Box 1: Email --}}
                <div class="p-3 bg-white rounded-3 shadow-sm border border-1 mb-2.5" style="border-color: #fecdd3 !important; border-left: 4px solid #f43f5e !important;">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; background-color: #fff1f2;">
                            <i class="fas fa-envelope-open-text text-danger" style="font-size: 17px;"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 13.5px;">
                                {{ __('Giao Hàng Qua Email') }}
                            </h6>
                            <p class="text-secondary mb-0" style="font-size: 12.5px; line-height: 1.45;">
                                {{ __('Khi thanh toán đơn hàng sẽ được giao qua email của bạn.') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Box 2, 3 & 4 Container --}}
                <div class="d-flex flex-column gap-2 text-start">
                    
                    {{-- Box 2: Zalo --}}
                    <div class="p-2.5 px-3 bg-white rounded-3 shadow-sm border border-1 d-flex align-items-center justify-content-between gap-2" style="border-color: #bae6fd !important; border-left: 4px solid #0284c7 !important;">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; background-color: #e0f2fe;">
                                <i class="fas fa-comments text-info" style="font-size: 15px;"></i>
                            </div>
                            <span class="text-dark fw-semibold" style="font-size: 12.5px; line-height: 1.4;">
                                {{ __('Liên hệ admin qua ZALO để được cấp nhanh hơn') }}
                            </span>
                        </div>
                        <a href="{{ \App\Helpers\SupportHelper::getZaloLink() }}" target="_blank" class="btn btn-sm text-white fw-bold px-2.5 py-1 rounded-pill flex-shrink-0 d-inline-flex align-items-center gap-1 shadow-sm" style="background: linear-gradient(135deg, #0284c7 0%, #06b6d4 100%); font-size: 11.5px; border: none;">
                            <i class="fas fa-paper-plane" style="font-size: 10.5px;"></i> Zalo
                        </a>
                    </div>

                    {{-- Box 3: Telegram --}}
                    <div class="p-2.5 px-3 bg-white rounded-3 shadow-sm border border-1 d-flex align-items-center justify-content-between gap-2" style="border-color: #93c5fd !important; border-left: 4px solid #0088cc !important;">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; background-color: #f0f9ff;">
                                <i class="fab fa-telegram-plane" style="font-size: 16px; color: #0088cc;"></i>
                            </div>
                            <span class="text-dark fw-semibold" style="font-size: 12.5px; line-height: 1.4;">
                                {{ __('Liên hệ admin qua TELEGRAM để được cấp nhanh hơn') }}
                            </span>
                        </div>
                        <a href="{{ \App\Helpers\SupportHelper::getTelegramLink() }}" target="_blank" class="btn btn-sm text-white fw-bold px-2.5 py-1 rounded-pill flex-shrink-0 d-inline-flex align-items-center gap-1 shadow-sm" style="background: linear-gradient(135deg, #0088cc 0%, #24a1de 100%); font-size: 11.5px; border: none;">
                            <i class="fab fa-telegram-plane" style="font-size: 11px;"></i> Telegram
                        </a>
                    </div>

                    {{-- Box 4: Phone --}}
                    <div class="p-2.5 px-3 bg-white rounded-3 shadow-sm border border-1 d-flex align-items-center justify-content-between gap-2" style="border-color: #bbf7d0 !important; border-left: 4px solid #16a34a !important;">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; background-color: #dcfce7;">
                                <i class="fas fa-phone-alt text-success" style="font-size: 15px;"></i>
                            </div>
                            <div class="text-dark" style="font-size: 12.5px; line-height: 1.4;">
                                <div class="fw-medium text-secondary" style="font-size: 12px;">{{ __('Gọi Điện hoặc Cuộc Gọi Nhỡ Cho ADMIN:') }}</div>
                                <div class="d-flex align-items-center gap-1.5 mt-0.5">
                                    <strong class="text-danger fw-extrabold" style="font-size: 14px; letter-spacing: 0.3px;">{{ \App\Helpers\SupportHelper::getPhone() }}</strong>
                                    <span class="badge bg-danger text-white px-1.5 py-0.5" style="font-size: 10px; font-weight: 600; border-radius: 4px;">{{ __('hỗ trợ siêu nhanh') }}</span>
                                </div>
                            </div>
                        </div>
                        <a href="tel:{{ \App\Helpers\SupportHelper::getPhone() }}" class="btn btn-sm text-white fw-bold px-2.5 py-1 rounded-pill flex-shrink-0 d-inline-flex align-items-center gap-1 shadow-sm me-0" style="background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%); font-size: 11.5px; border: none;">
                            <i class="fas fa-phone" style="font-size: 10.5px;"></i> Gọi
                        </a>
                    </div>

                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer border-0 p-3 px-4 bg-white d-flex align-items-center justify-content-between">
                <button type="button" class="btn btn-sm btn-light border text-secondary px-3.5 py-2 fw-medium rounded-pill close-spam-modal-btn" data-duration="600000" data-bs-dismiss="modal" style="font-size: 12.5px;">
                    <i class="fas fa-times me-1"></i>{{ __('Đóng (Tắt 10p)') }}
                </button>
                <button type="button" class="btn btn-sm text-white px-4 py-2 fw-bold rounded-pill shadow-sm close-spam-modal-btn" data-duration="300000" data-bs-dismiss="modal" style="background: linear-gradient(135deg, #dc2626 0%, #f97316 100%); font-size: 12.5px; border: none;">
                    <i class="fas fa-check me-1.5"></i>{{ __('Tôi đã hiểu') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('spamWarningWelcomeModal');
        const STORAGE_KEY = 'spam_warning_modal_closed_until';
        const closedUntil = localStorage.getItem(STORAGE_KEY);
        const now = Date.now();

        // Thời gian chờ mặc định (10 phút nếu đóng bằng X hoặc backdrop)
        let activeCloseDuration = 600000;

        const shouldShowSpamModal = modalEl && (!closedUntil || now >= parseInt(closedUntil, 10));

        if (shouldShowSpamModal) {
            // 1. Hiển thị Modal Cảnh Báo Spam ĐẦU TIÊN (sau 1.2s)
            setTimeout(() => {
                const bsModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                bsModal.show();
            }, 1200);

            // Gắn sự kiện click cho các nút để lưu khoảng thời gian tắt modal
            if (modalEl) {
                const actionElements = modalEl.querySelectorAll('.close-spam-modal-btn');
                actionElements.forEach(el => {
                    el.addEventListener('click', function() {
                        const duration = parseInt(this.getAttribute('data-duration'), 10);
                        if (!isNaN(duration)) {
                            activeCloseDuration = duration;
                        }
                    });
                });
            }

            // Khi đóng Modal Cảnh báo (bằng nút Đóng, Tôi đã hiểu, X, ESC, Backdrop) -> Mới cho phép mở Modal Đơn hàng mới
            modalEl.addEventListener('hidden.bs.modal', function () {
                const closedUntilTime = Date.now() + activeCloseDuration;
                localStorage.setItem(STORAGE_KEY, closedUntilTime.toString());

                if (typeof window.showRecentOrdersModal === 'function') {
                    setTimeout(() => {
                        window.showRecentOrdersModal();
                    }, 400);
                }
            }, { once: true });
        } else {
            // 2. Nếu Modal Cảnh báo chưa hết thời gian tắt (5p hoặc 10p) -> Hiển thị thẳng Modal Đơn hàng mới
            if (typeof window.showRecentOrdersModal === 'function') {
                setTimeout(() => {
                    window.showRecentOrdersModal();
                }, 1500);
            }
        }
    });
</script>
