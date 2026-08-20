<!-- Modal Cảnh Báo Cấm Spam & Khóa Thiết Bị -->
<div class="modal fade" id="spamWarningWelcomeModal" tabindex="-1" aria-labelledby="spamWarningWelcomeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: #ffffff;">
            {{-- Header --}}
            <div class="modal-header border-0 text-white px-4 py-3 position-relative d-flex align-items-center justify-content-between" 
                 style="background: linear-gradient(135deg, #dc2626 0%, #f97316 100%);">
                <div>
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2 mb-1" id="spamWarningWelcomeModalLabel" style="font-size: 16.5px;">
                        <i class="fa-solid fa-triangle-exclamation text-warning animate-bounce"></i>
                        {{ __('CẢNH BÁO NGHÊM CẤM SPAM') }}
                    </h5>
                    <div class="d-flex align-items-center gap-1 text-white-50" style="font-size: 11px;">
                        <span style="width: 7px; height: 7px; background-color: #ef4444; border-radius: 50%; display: inline-block; animation: pulseDotLive 1.5s infinite;"></span>
                        <span class="text-white fw-medium">{{ __('Hệ thống giám sát an ninh tự động 24/7') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white close-spam-modal-btn" data-bs-dismiss="modal" aria-label="Close" style="opacity: 0.9; filter: invert(1) grayscale(1) brightness(2);"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-3 p-sm-4" style="background-color: #f8f9fa;">
                <div class="p-3 bg-white rounded-3 shadow-sm border border-danger border-opacity-25 mb-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-shield-halved text-danger fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">
                                {{ __('Quy định bảo mật & Chống Spam') }}
                            </h6>
                            <p class="text-secondary mb-0" style="font-size: 12.5px; line-height: 1.5;">
                                {{ __('Vui lòng không spam, tạo nhiều kết nối rác hoặc rà quét website dưới mọi hình thức.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2 text-start">
                    <div class="d-flex align-items-start gap-2 p-2 rounded-2" style="background-color: #fff5f5;">
                        <i class="fa-solid fa-ban text-danger mt-1" style="font-size: 13px;"></i>
                        <span class="text-dark" style="font-size: 12.5px; line-height: 1.4;">
                            <strong>{{ __('Khóa 24 Giờ:') }}</strong> {{ __('Tự động ngắt kết nối & khóa IP/thiết bị 24h nếu phát sinh spam phiên làm việc hoặc yêu cầu dồn dập.') }}
                        </span>
                    </div>

                    <div class="d-flex align-items-start gap-2 p-2 rounded-2" style="background-color: #fff1f2;">
                        <i class="fa-solid fa-lock text-danger mt-1" style="font-size: 13px;"></i>
                        <span class="text-dark" style="font-size: 12.5px; line-height: 1.4;">
                            <strong>{{ __('Khóa Vĩnh Viễn:') }}</strong> {{ __('Các hành vi cố tình rà quét lỗ hổng, tấn công hoặc tái phạm sẽ bị khóa thiết bị & IP VĨNH VIỄN.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer border-0 p-3 bg-white d-flex align-items-center justify-content-between">
                <button type="button" class="btn btn-sm btn-light border text-muted px-3 close-spam-modal-btn" data-bs-dismiss="modal" style="border-radius: 20px; font-size: 12px;">
                    <i class="fa-solid fa-xmark me-1"></i>{{ __('Đóng (Tắt 1h)') }}
                </button>
                <button type="button" class="btn btn-sm text-white px-4 fw-bold shadow-sm close-spam-modal-btn" data-bs-dismiss="modal" style="background: linear-gradient(135deg, #dc2626 0%, #f97316 100%); border-radius: 20px; font-size: 12.5px;">
                    <i class="fa-solid fa-check me-1"></i>{{ __('Tôi đã hiểu') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('spamWarningWelcomeModal');
        if (!modalEl) return;

        const STORAGE_KEY = 'spam_warning_modal_closed_until';
        const closedUntil = localStorage.getItem(STORAGE_KEY);
        const now = Date.now();

        // Nếu chưa đóng hoặc đã hết thời hạn 1 tiếng -> Tự động bật Modal sau 2.5 giây
        if (!closedUntil || now >= parseInt(closedUntil, 10)) {
            setTimeout(() => {
                const bsModal = new bootstrap.Modal(modalEl);
                bsModal.show();
            }, 2500);
        }

        // Khi người dùng nhấn nút Đóng, X, hoặc Tôi đã hiểu -> Lưu mốc thời gian ẩn 1 tiếng (3,600,000 ms)
        const actionElements = modalEl.querySelectorAll('.close-spam-modal-btn');
        actionElements.forEach(el => {
            el.addEventListener('click', function() {
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
