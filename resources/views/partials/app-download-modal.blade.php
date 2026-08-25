<!-- Modal Tải App (Android, iOS, Desktop) -->
<div class="modal fade" id="appDownloadModal" tabindex="-1" aria-labelledby="appDownloadModalLabel" aria-hidden="true" style="z-index: 1056;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: #ffffff;">
            
            {{-- Header --}}
            <div class="modal-header text-white p-3 p-sm-4 border-0 position-relative" style="background: linear-gradient(135deg, #ff5e00 0%, #ff8e43 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white p-2 rounded-3 shadow-sm d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                        <img src="{{ asset('images/dungthu.png') }}" alt="Dùng Thử App Logo" 
                             onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';" 
                             style="width: 34px; height: 34px; object-fit: contain;">
                        <div class="align-items-center justify-content-center text-warning" style="display: none; width: 34px; height: 34px; font-size: 22px;">
                            <i class="fa-solid fa-bolt" style="color: #ff5e00;"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0" id="appDownloadModalLabel" style="font-size: 1.25rem; letter-spacing: -0.01em;">
                            <i class="fa-solid fa-mobile-screen-button me-2"></i>{{ __('Tải App Dùng Thử') }}
                        </h5>
                        <p class="mb-0 text-white-50 text-xs mt-0.5" style="font-size: 0.84rem;">
                            {{ __('Trải nghiệm mượt mà trên Điện thoại & Máy tính dạng App WebView') }}
                        </p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Close" style="opacity: 0.9;"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-3 p-sm-4" style="background-color: #f8fafc;">
                
                {{-- Platform Tabs --}}
                <ul class="nav nav-pills nav-justified mb-4 p-1.5 bg-white rounded-4 shadow-sm border border-slate-200" id="appPlatformTabs" role="tablist">
                    <li class="nav-item me-1" role="presentation">
                        <button class="nav-link active fw-bold py-2.5 px-2 rounded-3 d-flex align-items-center justify-content-center gap-2 border-0" 
                                id="android-tab" data-bs-toggle="pill" data-bs-target="#android-app-pane" type="button" role="tab">
                            <i class="fa-brands fa-android icon-tab-android fs-5"></i>
                            <span>Android (APK)</span>
                        </button>
                    </li>
                    <li class="nav-item me-1" role="presentation">
                        <button class="nav-link fw-bold py-2.5 px-2 rounded-3 d-flex align-items-center justify-content-center gap-2 border-0" 
                                id="ios-tab" data-bs-toggle="pill" data-bs-target="#ios-app-pane" type="button" role="tab">
                            <i class="fa-brands fa-apple icon-tab-ios fs-5"></i>
                            <span>iOS (iPhone/iPad)</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold py-2.5 px-2 rounded-3 d-flex align-items-center justify-content-center gap-2 border-0" 
                                id="desktop-tab" data-bs-toggle="pill" data-bs-target="#desktop-app-pane" type="button" role="tab">
                            <i class="fa-solid fa-desktop icon-tab-desktop fs-5"></i>
                            <span>Desktop (PC/Mac)</span>
                        </button>
                    </li>
                </ul>

                {{-- Tab Contents --}}
                <div class="tab-content" id="appPlatformTabContent">
                    
                    {{-- 1. ANDROID TAB --}}
                    <div class="tab-pane fade show active" id="android-app-pane" role="tabpanel" tabindex="0">
                        <div class="row align-items-center g-4">
                            <div class="col-md-7">
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1.5 rounded-pill mb-2 fw-bold" style="font-size: 0.78rem;">
                                    <i class="fa-brands fa-android me-1"></i> Android WebView App
                                </span>
                                <h4 class="fw-bold text-dark mb-2" style="font-size: 1.3rem;">{{ __('Tải ứng dụng Android (.APK)') }}</h4>
                                <p class="text-muted text-sm mb-3" style="font-size: 0.88rem; line-height: 1.5;">
                                    {{ __('Ứng dụng gọn nhẹ, tải trực tiếp file APK hoặc cài đặt WebApp WebView không tốn dung lượng máy.') }}
                                </p>

                                <div class="d-flex flex-column gap-2 mb-3">
                                    <a href="{{ route('app.download', 'android') }}" class="btn btn-success btn-lg fw-bold rounded-3 d-inline-flex align-items-center justify-content-center gap-2 shadow-sm py-2.5" style="background: #16a34a; border-color: #16a34a;">
                                        <i class="fa-solid fa-download fs-5"></i>
                                        <span>{{ __('Tải Trực Tiếp File APK (.apk)') }}</span>
                                    </a>

                                    <button type="button" class="btn btn-outline-success fw-bold rounded-3 d-inline-flex align-items-center justify-content-center gap-2 btn-pwa-install py-2" id="pwaAndroidBtn" style="display: none;">
                                        <i class="fa-solid fa-bolt fs-5"></i>
                                        <span>{{ __('Cài đặt Nhanh (PWA Native App)') }}</span>
                                    </button>
                                </div>

                                <div class="bg-white p-3 rounded-3 border border-slate-200 shadow-xs">
                                    <h6 class="fw-bold mb-2 text-dark" style="font-size: 0.86rem;"><i class="fa-solid fa-circle-info text-info me-1"></i> {{ __('Hướng dẫn cài đặt file APK:') }}</h6>
                                    <ol class="mb-0 text-muted ps-3" style="font-size: 0.82rem; line-height: 1.6;">
                                        <li>{{ __('Nhấn nút Tải file APK ở trên về điện thoại.') }}</li>
                                        <li>{{ __('Mở file APK đã tải, cho phép "Cài đặt từ nguồn không xác định" nếu được hỏi.') }}</li>
                                        <li>{{ __('Hoàn tất cài đặt và khởi chạy App Dùng Thử ngay!') }}</li>
                                    </ol>
                                </div>
                            </div>
                            <div class="col-md-5 text-center">
                                <div class="bg-white p-3 rounded-4 border border-slate-200 shadow-sm d-inline-block">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode(route('app.download', 'android')) }}" alt="QR Code Android App" class="img-fluid rounded-3 mb-2" style="max-width: 160px; height: auto;">
                                    <div class="text-xs text-muted fw-semibold" style="font-size: 0.78rem;">
                                        <i class="fa-solid fa-qrcode me-1 text-success"></i>{{ __('Quét mã QR để tải APK trên Android') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. IOS TAB --}}
                    <div class="tab-pane fade" id="ios-app-pane" role="tabpanel" tabindex="0">
                        <div class="row align-items-center g-4">
                            <div class="col-md-7">
                                <span class="badge bg-dark text-white px-3 py-1.5 rounded-pill mb-2 fw-bold" style="font-size: 0.78rem;">
                                    <i class="fa-brands fa-apple me-1"></i> iOS Web Clip & PWA App
                                </span>
                                <h4 class="fw-bold text-dark mb-2" style="font-size: 1.3rem;">{{ __('Thêm App vào iPhone / iPad') }}</h4>
                                <p class="text-muted text-sm mb-3" style="font-size: 0.88rem; line-height: 1.5;">
                                    {{ __('Chỉ với 2 thao tác đơn giản, tạo biểu tượng App Dùng Thử WebView ngay trên màn hình chính Safari iOS.') }}
                                </p>

                                <div class="bg-white p-3 rounded-3 border border-slate-200 mb-3 shadow-xs">
                                    <h6 class="fw-bold mb-2 text-dark" style="font-size: 0.86rem;">
                                        <i class="fa-solid fa-list-check text-primary me-1"></i> {{ __('Các bước thực hiện trên Safari (iOS):') }}
                                    </h6>
                                    <div class="d-flex align-items-start gap-2 mb-2" style="font-size: 0.82rem;">
                                        <span class="badge bg-danger rounded-circle px-2 py-1 flex-shrink-0" style="width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center;">1</span>
                                        <span>Mở trang web bằng trình duyệt <strong>Safari</strong> trên iPhone/iPad.</span>
                                    </div>
                                    <div class="d-flex align-items-start gap-2 mb-2" style="font-size: 0.82rem;">
                                        <span class="badge bg-danger rounded-circle px-2 py-1 flex-shrink-0" style="width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center;">2</span>
                                        <span>Nhấn vào biểu tượng <strong>Chia sẻ (Share) <i class="fa-solid fa-arrow-up-from-bracket text-primary"></i></strong> ở thanh công cụ dưới.</span>
                                    </div>
                                    <div class="d-flex align-items-start gap-2" style="font-size: 0.82rem;">
                                        <span class="badge bg-danger rounded-circle px-2 py-1 flex-shrink-0" style="width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center;">3</span>
                                        <span>Cuộn xuống và chọn <strong>"Thêm vào MH chính" (Add to Home Screen) <i class="fa-regular fa-square-plus text-primary"></i></strong>.</span>
                                    </div>
                                </div>

                                <a href="{{ route('app.download', 'ios') }}" class="btn btn-dark btn-lg fw-bold rounded-3 w-100 d-inline-flex align-items-center justify-content-center gap-2 shadow-sm py-2.5" style="background: #0f172a;">
                                    <i class="fa-brands fa-apple fs-5"></i>
                                    <span>{{ __('Xem Cấu Hình iOS Wrapper / TestFlight') }}</span>
                                </a>
                            </div>
                            <div class="col-md-5 text-center">
                                <div class="bg-white p-3 rounded-4 border border-slate-200 shadow-sm d-inline-block">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode(url('/')) }}" alt="QR Code iOS App" class="img-fluid rounded-3 mb-2" style="max-width: 160px; height: auto;">
                                    <div class="text-xs text-muted fw-semibold" style="font-size: 0.78rem;">
                                        <i class="fa-solid fa-qrcode me-1 text-dark"></i>{{ __('Quét mã QR bằng Camera iPhone') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. DESKTOP TAB --}}
                    <div class="tab-pane fade" id="desktop-app-pane" role="tabpanel" tabindex="0">
                        <div class="row align-items-center g-4">
                            <div class="col-md-7">
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-1.5 rounded-pill mb-2 fw-bold" style="font-size: 0.78rem;">
                                    <i class="fa-solid fa-desktop me-1"></i> Desktop Electron App
                                </span>
                                <h4 class="fw-bold text-dark mb-2" style="font-size: 1.3rem;">{{ __('Ứng dụng Máy Tính (Windows / Mac)') }}</h4>
                                <p class="text-muted text-sm mb-3" style="font-size: 0.88rem; line-height: 1.5;">
                                    {{ __('Chạy mượt mà dưới dạng ứng dụng độc lập trên máy tính cá nhân, có cửa sổ riêng và không bị phân tâm bởi các tab trình duyệt.') }}
                                </p>

                                <div class="d-flex flex-column gap-2 mb-3">
                                    <a href="{{ route('app.download', 'desktop') }}" class="btn btn-primary btn-lg fw-bold rounded-3 d-inline-flex align-items-center justify-content-center gap-2 shadow-sm py-2.5" style="background: linear-gradient(135deg, #ff5e00 0%, #ff8e43 100%); border: none;">
                                        <i class="fa-brands fa-windows fs-5"></i>
                                        <span>{{ __('Tải App Cho Windows (.exe)') }}</span>
                                    </a>

                                    <button type="button" class="btn btn-outline-primary fw-bold rounded-3 d-inline-flex align-items-center justify-content-center gap-2 btn-pwa-install py-2" id="pwaDesktopBtn" style="display: none; border-color: #ff5e00; color: #ff5e00;">
                                        <i class="fa-solid fa-download me-1"></i> {{ __('Cài Đặt Nhanh Vào Máy Tính (Chrome App)') }}
                                    </button>
                                </div>

                                <div class="bg-white p-3 rounded-3 border border-slate-200 shadow-xs">
                                    <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.86rem;"><i class="fa-solid fa-bolt text-warning me-1"></i> {{ __('Tính năng nổi bật trên Desktop:') }}</h6>
                                    <ul class="mb-0 text-muted ps-3" style="font-size: 0.82rem; line-height: 1.6;">
                                        <li>{{ __('Tự động ghi nhớ đăng nhập & cập nhật liên tục.') }}</li>
                                        <li>{{ __('Hỗ trợ thông báo đẩy (Push Notifications) tức thì.') }}</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-5 text-center">
                                <div class="bg-white p-4 rounded-4 border border-slate-200 shadow-sm text-center">
                                    <div class="mb-3" style="color: #ff5e00;">
                                        <i class="fa-solid fa-laptop-code" style="font-size: 4rem;"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">{{ __('App Desktop Native') }}</h6>
                                    <p class="text-muted text-xs mb-0" style="font-size: 0.78rem;">{{ __('Tương thích Windows 10/11 & macOS') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer bg-white px-4 py-3 justify-content-between border-top border-slate-100">
                <div class="text-muted text-xs d-flex align-items-center gap-1.5" style="font-size: 0.82rem;">
                    <i class="fa-solid fa-shield-halved text-success fs-6"></i>
                    <span>{{ __('An toàn & Đã kiểm duyệt 100% Virus') }}</span>
                </div>
                <button type="button" class="btn btn-secondary px-4 fw-bold rounded-3" data-bs-dismiss="modal" style="font-size: 0.88rem; background: #64748b; border: none;">{{ __('Đóng') }}</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling for App Download Modal Tabs */
    #appPlatformTabs {
        background-color: #ffffff !important;
    }
    #appPlatformTabs .nav-link {
        color: #475569 !important;
        background: #f8fafc;
        border: 1px solid #e2e8f0 !important;
        transition: all 0.2s ease-in-out;
        font-size: 0.9rem;
    }
    #appPlatformTabs .nav-link:hover {
        background: #f1f5f9;
        color: #0f172a !important;
    }
    #appPlatformTabs .nav-link.active {
        background: linear-gradient(135deg, #ff5e00 0%, #ff8e43 100%) !important;
        color: #ffffff !important;
        border-color: #ff5e00 !important;
        box-shadow: 0 4px 14px rgba(255, 94, 0, 0.3) !important;
    }
    #appPlatformTabs .nav-link.active i {
        color: #ffffff !important;
    }
    
    /* Specific icon colors when inactive */
    #appPlatformTabs .nav-link:not(.active) .icon-tab-android { color: #16a34a !important; }
    #appPlatformTabs .nav-link:not(.active) .icon-tab-ios { color: #0f172a !important; }
    #appPlatformTabs .nav-link:not(.active) .icon-tab-desktop { color: #2563eb !important; }
</style>

<script>
    let deferredPrompt;

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        
        const androidBtn = document.getElementById('pwaAndroidBtn');
        const desktopBtn = document.getElementById('pwaDesktopBtn');
        if (androidBtn) androidBtn.style.display = 'inline-flex';
        if (desktopBtn) desktopBtn.style.display = 'inline-flex';
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Luôn hiển thị nút PWA trên các trình duyệt hỗ trợ
        const androidBtn = document.getElementById('pwaAndroidBtn');
        const desktopBtn = document.getElementById('pwaDesktopBtn');
        if (androidBtn) androidBtn.style.display = 'inline-flex';
        if (desktopBtn) desktopBtn.style.display = 'inline-flex';

        // Lắng nghe sự kiện click nút Cài đặt PWA
        const installBtns = document.querySelectorAll('.btn-pwa-install');
        installBtns.forEach(btn => {
            btn.addEventListener('click', async () => {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    const { outcome } = await deferredPrompt.userChoice;
                    console.log(`User choice: ${outcome}`);
                    if (outcome === 'accepted') {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cài đặt thành công!',
                                text: 'Ứng dụng Dùng Thử AI đã được thêm vào máy tính/điện thoại của bạn.',
                                confirmButtonColor: '#ff5e00'
                            });
                        }
                    }
                    deferredPrompt = null;
                } else {
                    // Nếu deferredPrompt null (do đã cài hoặc vừa xóa app)
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'info',
                            title: 'Hướng dẫn cài đặt lại ứng dụng',
                            html: `
                                <div class="text-start" style="font-size: 0.9rem; line-height: 1.6;">
                                    <p class="mb-2">Nếu bạn vừa <b>xóa App</b> và muốn cài đặt lại ngay:</p>
                                    <ol class="ps-3 mb-3">
                                        <li class="mb-1">Bấm nút <b>"Tải lại trang (F5)"</b> bên dưới để làm mới phiên làm việc.</li>
                                        <li class="mb-1">Hoặc nhấp vào biểu tượng <b>Cài đặt <i class="fa-solid fa-download text-primary"></i></b> ở góc phải thanh địa chỉ Chrome/Edge.</li>
                                        <li>Hoặc nhấn menu <b>3 chấm (⋮)</b> trên trình duyệt > chọn <b>"Cài đặt Dùng Thử AI"</b>.</li>
                                    </ol>
                                </div>
                            `,
                            showCancelButton: true,
                            confirmButtonText: '<i class="fa-solid fa-rotate-right me-1"></i> Tải lại trang để cài lại',
                            cancelButtonText: 'Đóng',
                            confirmButtonColor: '#ff5e00',
                            cancelButtonColor: '#64748b'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    } else {
                        alert('Hãy tải lại trang (F5) hoặc bấm icon Cài đặt trên thanh địa chỉ trình duyệt để cài lại App!');
                    }
                }
            });
        });

        // Thông báo khi bấm tải trực tiếp file APK / EXE
        const downloadLinks = document.querySelectorAll('a[href*="/download/app/"]');
        downloadLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (typeof Swal !== 'undefined') {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true
                    });
                    Toast.fire({
                        icon: 'info',
                        title: '🚀 Đang khởi tạo tải xuống file ứng dụng...'
                    });
                }
            });
        });
    });
</script>
