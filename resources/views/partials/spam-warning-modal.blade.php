<!-- Modal Thông Báo Giao Hàng & Hỗ Trợ -->
<style>
    @media (max-width: 576px) {
        #spamWarningWelcomeModal .spam-modal-dialog {
            max-width: 350px !important;
            margin: 0.5rem auto !important;
        }
        #spamWarningWelcomeModal .modal-content {
            border-radius: 16px !important;
        }
        #spamWarningWelcomeModal .spam-modal-header {
            padding: 10px 14px !important;
        }
        #spamWarningWelcomeModal .modal-title {
            font-size: 14px !important;
        }
        #spamWarningWelcomeModal .header-subtext {
            font-size: 10.5px !important;
        }
        #spamWarningWelcomeModal .spam-modal-body {
            padding: 12px !important;
        }
        #spamWarningWelcomeModal .box-email-delivery {
            padding: 8px 10px !important;
            margin-bottom: 10px !important;
            border-left-width: 4px !important;
        }
        #spamWarningWelcomeModal .box-email-icon {
            width: 32px !important;
            height: 32px !important;
        }
        #spamWarningWelcomeModal .box-email-icon i {
            font-size: 13px !important;
        }
        #spamWarningWelcomeModal .box-email-title {
            font-size: 12px !important;
        }
        #spamWarningWelcomeModal .box-email-desc {
            font-size: 11px !important;
            line-height: 1.35 !important;
        }
        #spamWarningWelcomeModal .contact-section-header {
            margin-bottom: 8px !important;
            font-size: 10.5px !important;
        }
        #spamWarningWelcomeModal .card-contact {
            padding: 10px 6px !important;
        }
        #spamWarningWelcomeModal .card-contact-icon {
            width: 32px !important;
            height: 32px !important;
            margin-bottom: 4px !important;
        }
        #spamWarningWelcomeModal .card-contact-icon i {
            font-size: 14px !important;
        }
        #spamWarningWelcomeModal .card-contact-title {
            font-size: 12px !important;
            margin-bottom: 2px !important;
        }
        #spamWarningWelcomeModal .card-contact-sub {
            font-size: 10px !important;
            margin-bottom: 6px !important;
        }
        #spamWarningWelcomeModal .card-contact-btn {
            font-size: 10.5px !important;
            padding: 4px 6px !important;
        }
        #spamWarningWelcomeModal .hotline-bar {
            padding: 8px 10px !important;
            border-left-width: 3px !important;
        }
        #spamWarningWelcomeModal .hotline-icon {
            width: 32px !important;
            height: 32px !important;
        }
        #spamWarningWelcomeModal .hotline-icon i {
            font-size: 13px !important;
        }
        #spamWarningWelcomeModal .hotline-label {
            font-size: 10px !important;
        }
        #spamWarningWelcomeModal .hotline-number {
            font-size: 13px !important;
        }
        #spamWarningWelcomeModal .hotline-badge {
            font-size: 8px !important;
            padding: 1px 4px !important;
        }
        #spamWarningWelcomeModal .hotline-btn {
            font-size: 10px !important;
            padding: 4px 8px !important;
        }
        #spamWarningWelcomeModal .spam-modal-footer {
            padding: 8px 12px !important;
        }
        #spamWarningWelcomeModal .spam-modal-footer .btn {
            font-size: 11px !important;
            padding: 5px 10px !important;
        }
    }
</style>

<div class="modal fade" id="spamWarningWelcomeModal" tabindex="-1" aria-labelledby="spamWarningWelcomeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered spam-modal-dialog" style="max-width: 500px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden; background: #ffffff;">
            
            {{-- Header --}}
            <div class="modal-header border-0 text-white px-4 py-3.5 position-relative d-flex align-items-center justify-content-between spam-modal-header" 
                 style="background: linear-gradient(135deg, #dc2626 0%, #ea580c 50%, #f97316 100%);">
                <div>
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2 mb-1" id="spamWarningWelcomeModalLabel" style="font-size: 17.5px; letter-spacing: -0.3px;">
                        <i class="fas fa-bullhorn text-warning me-1"></i>
                        {{ __('THÔNG BÁO GIAO HÀNG & HỖ TRỢ') }}
                    </h5>
                    <div class="d-flex align-items-center gap-1.5 text-white header-subtext" style="font-size: 11.5px; opacity: 0.95;">
                        <span style="width: 7px; height: 7px; background-color: #4ade80; border-radius: 50%; display: inline-block; box-shadow: 0 0 8px #4ade80;"></span>
                        <span>{{ __('Hệ thống xử lý đơn hàng tự động & Hỗ trợ 24/7') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white close-spam-modal-btn" data-bs-dismiss="modal" aria-label="Close" style="opacity: 0.9; filter: invert(1) brightness(2);"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-4 spam-modal-body" style="background-color: #f8fafc;">
                
                {{-- Box 1: Email Delivery --}}
                <div class="p-3 rounded-3 shadow-sm border border-danger border-opacity-25 mb-4 bg-white box-email-delivery" style="border-left: 5px solid #f43f5e !important;">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm box-email-icon" style="width: 44px; height: 44px; background: #ffe4e6; color: #e11d48;">
                            <i class="fas fa-envelope-open-text fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1 box-email-title" style="font-size: 14px;">
                                {{ __('Giao Hàng Tự Động Qua Email') }}
                            </h6>
                            <p class="text-secondary mb-0 box-email-desc" style="font-size: 12.5px; line-height: 1.45;">
                                {{ __('Khi thanh toán thành công, đơn hàng sẽ được gửi ngay về email của bạn.') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Contact Section Header --}}
                <div class="d-flex align-items-center gap-2 mb-3 px-1 contact-section-header">
                    <i class="fas fa-headset text-danger" style="font-size: 13px;"></i>
                    <span class="fw-bold text-uppercase text-secondary" style="font-size: 11.5px; letter-spacing: 0.5px;">{{ __('Cần cấp nhanh? Liên hệ admin 24/7') }}</span>
                </div>

                {{-- 2 Columns Grid: Zalo & Telegram --}}
                <div class="row g-2 g-sm-3 mb-3">
                    {{-- Zalo Card --}}
                    <div class="col-6">
                        <a href="{{ \App\Helpers\SupportHelper::getZaloLink() }}" target="_blank" class="text-decoration-none h-100 d-block">
                            <div class="p-3 bg-white rounded-3 shadow-sm border border-info border-opacity-25 text-center h-100 transition-all d-flex flex-column justify-content-between card-contact" style="border-top: 3.5px solid #0284c7 !important;">
                                <div>
                                    <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center card-contact-icon" style="width: 40px; height: 40px; background-color: #e0f2fe;">
                                        <i class="fas fa-comments text-info" style="font-size: 18px;"></i>
                                    </div>
                                    <div class="fw-bold text-dark mb-1 card-contact-title" style="font-size: 13.5px;">Zalo Admin</div>
                                    <div class="text-muted mb-3 card-contact-sub" style="font-size: 11px;">Cấp nhanh qua Zalo</div>
                                </div>
                                <span class="btn btn-sm text-white fw-bold w-100 rounded-pill shadow-sm py-2 card-contact-btn" style="background: linear-gradient(135deg, #0284c7 0%, #06b6d4 100%); font-size: 11.5px; border: none;">
                                    <i class="fas fa-paper-plane me-1" style="font-size: 10px;"></i> {{ __('Chat Zalo') }}
                                </span>
                            </div>
                        </a>
                    </div>

                    {{-- Telegram Card --}}
                    <div class="col-6">
                        <a href="{{ \App\Helpers\SupportHelper::getTelegramLink() }}" target="_blank" class="text-decoration-none h-100 d-block">
                            <div class="p-3 bg-white rounded-3 shadow-sm border border-primary border-opacity-25 text-center h-100 transition-all d-flex flex-column justify-content-between card-contact" style="border-top: 3.5px solid #0088cc !important;">
                                <div>
                                    <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center card-contact-icon" style="width: 40px; height: 40px; background-color: #f0f9ff;">
                                        <i class="fab fa-telegram-plane" style="font-size: 18px; color: #0088cc;"></i>
                                    </div>
                                    <div class="fw-bold text-dark mb-1 card-contact-title" style="font-size: 13.5px;">Telegram Admin</div>
                                    <div class="text-muted mb-3 card-contact-sub" style="font-size: 11px;">Cấp nhanh Telegram</div>
                                </div>
                                <span class="btn btn-sm text-white fw-bold w-100 rounded-pill shadow-sm py-2 card-contact-btn" style="background: linear-gradient(135deg, #0088cc 0%, #24a1de 100%); font-size: 11.5px; border: none;">
                                    <i class="fab fa-telegram-plane me-1" style="font-size: 10.5px;"></i> {{ __('Telegram') }}
                                </span>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Phone Hotline Bar --}}
                <div class="p-3 bg-white rounded-3 shadow-sm border border-success border-opacity-25 d-flex align-items-center justify-content-between hotline-bar" style="border-left: 4px solid #16a34a !important;">
                    <div class="d-flex align-items-center gap-2 gap-sm-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 hotline-icon" style="width: 38px; height: 38px; background-color: #dcfce7;">
                            <i class="fas fa-phone-volume text-success" style="font-size: 16px;"></i>
                        </div>
                        <div>
                            <div class="text-muted fw-medium hotline-label" style="font-size: 11.5px;">{{ __('Gọi điện hoặc nháy máy Admin:') }}</div>
                            <div class="d-flex align-items-center gap-1.5 mt-0.5">
                                <strong class="text-danger fw-extrabold hotline-number" style="font-size: 15px; letter-spacing: 0.3px;">{{ \App\Helpers\SupportHelper::getPhone() }}</strong>
                                <span class="badge bg-danger text-white px-2 py-1 hotline-badge" style="font-size: 9.5px; font-weight: 600; border-radius: 4px;">{{ __('Siêu nhanh') }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="tel:{{ \App\Helpers\SupportHelper::getPhone() }}" class="btn btn-sm text-white fw-bold px-3 py-2 rounded-pill flex-shrink-0 shadow-sm d-inline-flex align-items-center gap-1 hotline-btn" style="background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%); font-size: 11.5px; border: none;">
                        <i class="fas fa-phone" style="font-size: 10.5px;"></i> {{ __('Gọi ngay') }}
                    </a>
                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer border-0 p-3 px-4 bg-white d-flex align-items-center justify-content-between spam-modal-footer">
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

            // Khi đóng Modal Cảnh báo (bằng nút Đóng, Tôi đã hiểu, X, ESC, Backdrop)
            modalEl.addEventListener('hidden.bs.modal', function () {
                const closedUntilTime = Date.now() + activeCloseDuration;
                localStorage.setItem(STORAGE_KEY, closedUntilTime.toString());
            }, { once: true });
        }
    });
</script>
