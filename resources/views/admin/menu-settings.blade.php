@extends('layouts.admin')

@section('title', 'Quản lý Menu - Admin')

@section('page_title', 'Menu Settings')

@push('styles')
<style>
    .menu-settings-wrapper {
        padding: 40px 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        margin-top: 0;
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

    .settings-card {
        background: white;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.12);
    }

    .settings-header {
        margin-bottom: 35px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f5;
    }

    .settings-header h2 {
        font-size: 1.8rem;
        font-weight: 800;
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .menu-item-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-radius: 16px;
        background: #f8f9ff;
        margin-bottom: 14px;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .menu-item-row:hover {
        border-color: #667eea40;
        background: #f0f2ff;
        transform: translateX(4px);
    }

    .menu-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .menu-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .menu-label {
        font-weight: 700;
        font-size: 1rem;
        color: #2d3748;
    }

    .menu-desc {
        font-size: 0.82rem;
        color: #718096;
        margin-top: 2px;
    }

    /* Toggle Switch */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 62px;
        height: 32px;
        flex-shrink: 0;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #cbd5e0;
        border-radius: 34px;
        transition: 0.35s;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 24px;
        width: 24px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        border-radius: 50%;
        transition: 0.35s;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    .toggle-switch input:checked + .toggle-slider {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(30px);
    }

    .toggle-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .toggle-status {
        font-size: 0.82rem;
        font-weight: 700;
        min-width: 40px;
    }

    .status-on  { color: #38a169; }
    .status-off { color: #e53e3e; }

    .save-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 14px;
        padding: 14px 40px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102,126,234,0.4);
    }

    .save-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102,126,234,0.5);
    }

    .alert-success {
        border-radius: 14px;
        border: none;
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
        font-weight: 600;
        padding: 14px 20px;
    }

    .preview-badge {
        font-size: 0.7rem;
        padding: 3px 8px;
        border-radius: 20px;
        font-weight: 600;
        margin-left: 8px;
    }
    .badge-mobile { background: #e9ecef; color: #495057; }
    .badge-desktop { background: #cfe2ff; color: #084298; }
    .badge-both { background: #d1ecf1; color: #0c5460; }
</style>
@endpush

@section('content')
<div class="menu-settings-wrapper">
    <div class="container">
        {{-- Admin Nav --}}
        

        <div class="settings-card" data-aos="fade-up">
            {{-- Header --}}
            <div class="settings-header">
                <h2><i class="fas fa-sliders-h me-2"></i>Quản lý Menu Hiển thị</h2>
                <p class="text-muted mb-0">Bật/tắt từng mục menu trên website. Thay đổi có hiệu lực ngay lập tức.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success mb-4">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.menu-settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                @php
                    $menuItems = [
                        'menu_home' => [
                            'label'  => 'Trang chủ',
                            'desc'   => 'Menu chính, trang chủ website',
                            'icon'   => 'fa-house',
                            'color'  => 'linear-gradient(135deg,#667eea,#764ba2)',
                            'where'  => 'both',
                        ],
                        'menu_shop' => [
                            'label'  => 'Cửa hàng',
                            'desc'   => 'Trang danh sách sản phẩm',
                            'icon'   => 'fa-store',
                            'color'  => 'linear-gradient(135deg,#f093fb,#f5576c)',
                            'where'  => 'both',
                        ],
                        'menu_blog' => [
                            'label'  => 'Blog',
                            'desc'   => 'Trang bài viết & tin tức',
                            'icon'   => 'fa-newspaper',
                            'color'  => 'linear-gradient(135deg,#4facfe,#00f2fe)',
                            'where'  => 'both',
                        ],
                        'menu_cart' => [
                            'label'  => 'Giỏ hàng',
                            'desc'   => 'Icon giỏ hàng trên thanh điều hướng',
                            'icon'   => 'fa-cart-shopping',
                            'color'  => 'linear-gradient(135deg,#43e97b,#38f9d7)',
                            'where'  => 'both',
                        ],
                        'menu_webdesign' => [
                            'label'  => 'Thiết kế WS',
                            'desc'   => 'Trang dịch vụ thiết kế website',
                            'icon'   => 'fa-palette',
                            'color'  => 'linear-gradient(135deg,#fa709a,#fee140)',
                            'where'  => 'both',
                        ],
                        'menu_card_exchange' => [
                            'label'  => 'Đổi thẻ cào',
                            'desc'   => 'Trang đổi thẻ cào lấy tiền mặt',
                            'icon'   => 'fa-credit-card',
                            'color'  => 'linear-gradient(135deg,#f7971e,#ffd200)',
                            'where'  => 'both',
                        ],
                        'menu_buff' => [
                            'label'  => 'Buff Mạng XH',
                            'desc'   => 'Trang dịch vụ buff mạng xã hội',
                            'icon'   => 'fa-chart-line',
                            'color'  => 'linear-gradient(135deg,#a18cd1,#fbc2eb)',
                            'where'  => 'desktop',
                        ],
                        'menu_community' => [
                            'label'  => 'Cộng đồng',
                            'desc'   => 'Trang cộng đồng & forum',
                            'icon'   => 'fa-users',
                            'color'  => 'linear-gradient(135deg,#0ba360,#3cba92)',
                            'where'  => 'desktop',
                        ],
                        'menu_chat' => [
                            'label'  => 'Chat Admin',
                            'desc'   => 'Trang chat trực tiếp với Admin',
                            'icon'   => 'fa-comments',
                            'color'  => 'linear-gradient(135deg,#6366f1,#a855f7)',
                            'where'  => 'both',
                        ],
                        'menu_minigame' => [
                            'label'  => 'Mini Game',
                            'desc'   => 'Menu Mini Game (Vòng xoay may mắn)',
                            'icon'   => 'fa-gamepad',
                            'color'  => 'linear-gradient(135deg,#e11d48,#be123c)',
                            'where'  => 'both',
                        ],
                        'menu_zalo_group' => [
                            'label'  => 'Nhóm Zalo',
                            'desc'   => 'Link tham gia nhóm Zalo',
                            'icon'   => 'fa-users',
                            'color'  => 'linear-gradient(135deg,#0068ff,#00c6ff)',
                            'where'  => 'both',
                        ],
                        'adsense_enabled' => [
                            'label'  => 'Google AdSense',
                            'desc'   => 'Bật/tắt toàn bộ ô quảng cáo Google AdSense',
                            'icon'   => 'fa-rectangle-ad',
                            'color'  => 'linear-gradient(135deg,#f59e0b,#d97706)',
                            'where'  => 'both',
                        ],
                        'home_show_flash_sale' => [
                            'label'  => 'Uu tien Flash Sale',
                            'desc'   => 'Dua len 4 o giam gia tren trang chu',
                            'icon'   => 'fa-bolt',
                            'color'  => 'linear-gradient(135deg,#ff416c,#ff4b2b)',
                            'where'  => 'both',
                        ],
                        'home_show_featured' => [
                            'label'  => 'Sản phẩm nổi bật',
                            'desc'   => 'Hiển thị trên trang chủ - Hàng đầu tiên',
                            'icon'   => 'fa-star',
                            'color'  => 'linear-gradient(135deg,#ffca28,#fbc02d)',
                            'where'  => 'both',
                        ],
                        'home_show_exclusive' => [
                            'label'  => 'Sản phẩm độc quyền',
                            'desc'   => 'Hiển thị trên trang chủ - Hàng thứ 2',
                            'icon'   => 'fa-gem',
                            'color'  => 'linear-gradient(135deg,#8b5cf6,#ec4899)',
                            'where'  => 'both',
                        ],
                        'home_show_combo_ai' => [
                            'label'  => 'Combo AI giá rẻ',
                            'desc'   => 'Hiển thị ở trang chủ - mục Combo AI giá rẻ',
                            'icon'   => 'fa-robot',
                            'color'  => 'linear-gradient(135deg,#6366f1,#a855f7)',
                            'where'  => 'both',
                        ],
                    ];
                @endphp

                <div class="mb-4">
                    @foreach($menuItems as $key => $item)
                    <div class="menu-item-row">
                        <div class="menu-info">
                            <div class="menu-icon" style="background: {{ $item['color'] }}; color: white;">
                                <i class="fa-solid {{ $item['icon'] }}"></i>
                            </div>
                            <div>
                                <div class="menu-label">
                                    {{ $item['label'] }}
                                    @if($item['where'] === 'both')
                                        <span class="preview-badge badge-both">Mobile + Desktop</span>
                                    @elseif($item['where'] === 'desktop')
                                        <span class="preview-badge badge-desktop">Desktop only</span>
                                    @else
                                        <span class="preview-badge badge-mobile">Mobile only</span>
                                    @endif
                                </div>
                                <div class="menu-desc">{{ $item['desc'] }}</div>
                            </div>
                        </div>

                        <div class="toggle-wrap">
                            @php $isOn = \App\Models\SiteSetting::getValue($key, '1') === '1'; @endphp
                            <span class="toggle-status {{ $isOn ? 'status-on' : 'status-off' }}" id="status-{{ $key }}">
                                {{ $isOn ? 'Bật' : 'Tắt' }}
                            </span>
                            <label class="toggle-switch" title="Bật/tắt {{ $item['label'] }}">
                                <input type="checkbox" name="{{ $key }}" value="1"
                                    {{ $isOn ? 'checked' : '' }}
                                    onchange="updateStatus(this, '{{ $key }}')">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>



                {{-- Thêm cài đặt Top Mua Hàng (Fake Orders) --}}
                <div class="settings-header mt-5">
                    <h2><i class="fas fa-crown me-2"></i>Cài đặt Top Mua Hàng</h2>
                    <p class="text-muted mb-0">Điều chỉnh số lượng đơn hàng cộng thêm hiển thị trên bục vinh quang trang chủ.</p>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="menu-item-row flex-column align-items-start">
                            <label class="form-label fw-bold text-warning mb-2"><i class="fas fa-crown"></i> Top 1</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-plus text-muted"></i></span>
                                <input type="number" class="form-control border-start-0 ps-0" name="fake_orders_top1" value="{{ \App\Models\SiteSetting::getValue('fake_orders_top1', '30') }}" min="0">
                                <span class="input-group-text bg-light">đơn</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="menu-item-row flex-column align-items-start">
                            <label class="form-label fw-bold text-secondary mb-2">Top 2</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-plus text-muted"></i></span>
                                <input type="number" class="form-control border-start-0 ps-0" name="fake_orders_top2" value="{{ \App\Models\SiteSetting::getValue('fake_orders_top2', '19') }}" min="0">
                                <span class="input-group-text bg-light">đơn</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="menu-item-row flex-column align-items-start">
                            <label class="form-label fw-bold mb-2" style="color: #CD7F32;">Top 3</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-plus text-muted"></i></span>
                                <input type="number" class="form-control border-start-0 ps-0" name="fake_orders_top3" value="{{ \App\Models\SiteSetting::getValue('fake_orders_top3', '10') }}" min="0">
                                <span class="input-group-text bg-light">đơn</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Cài đặt Link Zalo --}}
                <div class="settings-header mt-5">
                    <h2><i class="fas fa-link me-2"></i>Cài đặt Liên kết Zalo</h2>
                    <p class="text-muted mb-0">Cập nhật link tham gia nhóm Zalo hiển thị trên Navbar và Menu di động.</p>
                </div>

                <div class="menu-item-row flex-column align-items-start mb-4">
                    <label class="form-label fw-bold text-primary mb-2"><i class="fas fa-users"></i> Link Nhóm Zalo</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0" style="color: #0068ff;"><i class="fas fa-comments"></i></span>
                        <input type="url" class="form-control border-start-0 ps-0" name="zalo_group_link" 
                               value="{{ \App\Models\SiteSetting::getValue('zalo_group_link', 'https://zalo.me/g/ptarfhnomeuotiyk7cot') }}" 
                               placeholder="https://zalo.me/g/...">
                    </div>
                    <div class="form-text mt-2">
                        <i class="fas fa-info-circle me-1"></i> Nhập link nhóm Zalo đầy đủ (ví dụ: https://zalo.me/g/abc...).
                    </div>
                </div>

                {{-- Cấu hình Kênh Hỗ trợ & CSKH --}}
                <div class="settings-header mt-5">
                    <h2><i class="fas fa-headset me-2" style="color: #6366f1;"></i>Cấu hình Kênh Hỗ trợ & CSKH</h2>
                    <p class="text-muted mb-0">Cập nhật thông tin các kênh liên hệ hỗ trợ chính thức hiển thị trên website.</p>
                </div>

                <div class="row mb-4">
                    {{-- Facebook Fanpage --}}
                    <div class="col-md-6 mb-3">
                        <div class="menu-item-row flex-column align-items-start h-100 mb-0">
                            <label class="form-label fw-bold text-primary mb-2"><i class="fab fa-facebook"></i> Link Fanpage Facebook</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0" style="color: #1877f2;"><i class="fab fa-facebook-f"></i></span>
                                <input type="url" class="form-control border-start-0 ps-0" name="support_facebook_link" 
                                       value="{{ \App\Helpers\SupportHelper::getFacebookLink() }}" 
                                       placeholder="https://www.facebook.com/profile.php?id=...">
                            </div>
                            <div class="form-text mt-2 text-muted">Link Fanpage hỗ trợ chính thức.</div>
                        </div>
                    </div>

                    {{-- Telegram --}}
                    <div class="col-md-6 mb-3">
                        <div class="menu-item-row flex-column align-items-start h-100 mb-0">
                            <label class="form-label fw-bold text-info mb-2"><i class="fab fa-telegram"></i> Link Telegram Hỗ trợ</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0" style="color: #0088cc;"><i class="fab fa-telegram-plane"></i></span>
                                <input type="url" class="form-control border-start-0 ps-0" name="support_telegram_link" 
                                       value="{{ \App\Helpers\SupportHelper::getTelegramLink() }}" 
                                       placeholder="https://t.me/...">
                            </div>
                            <div class="form-text mt-2 text-muted">Đường dẫn click chat trực tiếp Telegram.</div>
                        </div>
                    </div>

                    {{-- Telegram Username --}}
                    <div class="col-md-6 mb-3">
                        <div class="menu-item-row flex-column align-items-start h-100 mb-0">
                            <label class="form-label fw-bold text-info mb-2"><i class="fab fa-telegram"></i> Username Telegram hiển thị</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0" style="color: #0088cc;"><i class="fas fa-at"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0" name="support_telegram_username" 
                                       value="{{ \App\Helpers\SupportHelper::getTelegramUsername() }}" 
                                       placeholder="@username">
                            </div>
                            <div class="form-text mt-2 text-muted">Tên Telegram hiển thị (ví dụ: @specademy).</div>
                        </div>
                    </div>

                    {{-- Zalo Chat Link --}}
                    <div class="col-md-6 mb-3">
                        <div class="menu-item-row flex-column align-items-start h-100 mb-0">
                            <label class="form-label fw-bold text-success mb-2"><i class="fas fa-comment-dots"></i> Link Chat Zalo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0" style="color: #07be9e;"><i class="fas fa-comments"></i></span>
                                <input type="url" class="form-control border-start-0 ps-0" name="support_zalo_link" 
                                       value="{{ \App\Helpers\SupportHelper::getZaloLink() }}" 
                                       placeholder="https://zalo.me/...">
                            </div>
                            <div class="form-text mt-2 text-muted">Đường dẫn click chat Zalo cá nhân/OA.</div>
                        </div>
                    </div>

                    {{-- Zalo Phone Number --}}
                    <div class="col-md-6 mb-3">
                        <div class="menu-item-row flex-column align-items-start h-100 mb-0">
                            <label class="form-label fw-bold text-success mb-2"><i class="fas fa-phone-alt"></i> Số Zalo hiển thị</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0" style="color: #07be9e;"><i class="fas fa-comment-alt"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0" name="support_zalo_number" 
                                       value="{{ \App\Helpers\SupportHelper::getZaloNumber() }}" 
                                       placeholder="0123456789">
                            </div>
                            <div class="form-text mt-2 text-muted">Số điện thoại Zalo hiển thị ở phần thông tin.</div>
                        </div>
                    </div>

                    {{-- Hotline --}}
                    <div class="col-md-6 mb-3">
                        <div class="menu-item-row flex-column align-items-start h-100 mb-0">
                            <label class="form-label fw-bold text-danger mb-2"><i class="fas fa-phone-volume"></i> Số Hotline Gọi điện</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0" style="color: #e53e3e;"><i class="fas fa-phone"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0" name="support_phone" 
                                       value="{{ \App\Helpers\SupportHelper::getPhone() }}" 
                                       placeholder="0123456789">
                            </div>
                            <div class="form-text mt-2 text-muted">Số Hotline để khách hàng gọi điện hỗ trợ.</div>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6 mb-3">
                        <div class="menu-item-row flex-column align-items-start h-100 mb-0">
                            <label class="form-label fw-bold text-warning mb-2"><i class="fas fa-envelope"></i> Email Hỗ trợ</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0" style="color: #d97706;"><i class="fas fa-envelope-open-text"></i></span>
                                <input type="email" class="form-control border-start-0 ps-0" name="support_email" 
                                       value="{{ \App\Helpers\SupportHelper::getEmail() }}" 
                                       placeholder="support@domain.com">
                            </div>
                            <div class="form-text mt-2 text-muted">Hòm thư điện tử nhận yêu cầu hỗ trợ.</div>
                        </div>
                    </div>
                </div>

                {{-- Cài đặt Tỷ giá USD --}}
                <div class="settings-header mt-5">
                    <h2><i class="fa-solid fa-money-bill-transfer me-2"></i>Cài đặt Tỷ giá USD (USD Exchange Rate)</h2>
                    <p class="text-muted mb-0">Cấu hình tỷ giá VND sang 1 USD dùng để tự động đổi giá tiền trên website khi bật giao diện tiếng Anh.</p>
                </div>

                <div class="menu-item-row flex-column align-items-start mb-4">
                    <label class="form-label fw-bold text-primary mb-2"><i class="fa-solid fa-dollar-sign"></i> Tỷ giá (VND/USD)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0" style="color: #38a169;"><i class="fa-solid fa-sack-dollar"></i></span>
                        <input type="number" class="form-control border-start-0 ps-0" name="usd_exchange_rate" 
                               value="{{ \App\Models\SiteSetting::getValue('usd_exchange_rate', '25000') }}" 
                               placeholder="25000" min="1" step="1">
                    </div>
                    <div class="form-text mt-2">
                        <i class="fas fa-info-circle me-1"></i> Ví dụ: Nhập 25000 nghĩa là 1 USD = 25.000đ.
                    </div>
                </div>
 
                {{-- Cài đặt Hệ thống Fanpage Chính Thức --}}
                <div class="settings-header mt-5">
                    <h2><i class="fas fa-shield-halved me-2" style="color: #38a169;"></i>Hệ thống Fanpage & Kênh chính chủ</h2>
                    <p class="text-muted mb-0">Quản lý danh sách các fanpage chính thức và fanpage con để người dùng nhận biết tránh lừa đảo.</p>
                </div>

                <div class="menu-item-row flex-column align-items-stretch mb-4 p-4">
                    <input type="hidden" name="fanpages_submitted" value="1">
                    
                    <label class="form-label fw-bold text-success mb-3"><i class="fas fa-list"></i> Danh sách trang chính chủ</label>
                    <div id="fanpages-container">
                        @php
                            $fanpagesJson = \App\Models\SiteSetting::getValue('official_fanpages', '[]');
                            $officialFanpages = json_decode($fanpagesJson, true) ?: [];
                        @endphp
                        
                        @if(count($officialFanpages) > 0)
                            @foreach($officialFanpages as $index => $fp)
                                <div class="row g-2 align-items-center mb-3 fanpage-row">
                                    <div class="col-md-2">
                                        <select name="fanpages_platforms[]" class="form-select">
                                            <option value="facebook" {{ ($fp['platform'] ?? 'facebook') === 'facebook' ? 'selected' : '' }}>Facebook</option>
                                            <option value="zalo" {{ ($fp['platform'] ?? 'zalo') === 'zalo' ? 'selected' : '' }}>Zalo</option>
                                            <option value="telegram" {{ ($fp['platform'] ?? 'telegram') === 'telegram' ? 'selected' : '' }}>Telegram</option>
                                            <option value="youtube" {{ ($fp['platform'] ?? 'youtube') === 'youtube' ? 'selected' : '' }}>YouTube</option>
                                            <option value="tiktok" {{ ($fp['platform'] ?? 'tiktok') === 'tiktok' ? 'selected' : '' }}>TikTok</option>
                                            <option value="globe" {{ ($fp['platform'] ?? 'globe') === 'globe' ? 'selected' : '' }}>Website</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="fanpages_names[]" class="form-control" value="{{ $fp['name'] }}" placeholder="Tên trang (ví dụ: Fanpage CSKH)" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="url" name="fanpages_urls[]" class="form-control" value="{{ $fp['url'] }}" placeholder="https://..." required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="fanpages_descs[]" class="form-control" value="{{ $fp['desc'] ?? '' }}" placeholder="Mô tả (ví dụ: Trang chính hỗ trợ)">
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <button type="button" class="btn btn-danger btn-sm rounded-circle p-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" onclick="this.closest('.fanpage-row').remove()">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <div class="text-start mt-2">
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="addFanpageRow()">
                            <i class="fas fa-plus me-1"></i> Thêm trang mới
                        </button>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="save-btn">
                        <i class="fas fa-save me-2"></i>Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true });

    function updateStatus(checkbox, key) {
        const statusEl = document.getElementById('status-' + key);
        if (checkbox.checked) {
            statusEl.textContent = 'Bật';
            statusEl.className = 'toggle-status status-on';
        } else {
            statusEl.textContent = 'Tắt';
            statusEl.className = 'toggle-status status-off';
        }
    }

    function addFanpageRow() {
        const container = document.getElementById('fanpages-container');
        const row = document.createElement('div');
        row.className = 'row g-2 align-items-center mb-3 fanpage-row';
        row.innerHTML = `
            <div class="col-md-2">
                <select name="fanpages_platforms[]" class="form-select">
                    <option value="facebook">Facebook</option>
                    <option value="zalo">Zalo</option>
                    <option value="telegram">Telegram</option>
                    <option value="youtube">YouTube</option>
                    <option value="tiktok">TikTok</option>
                    <option value="globe">Website</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="fanpages_names[]" class="form-control" placeholder="Tên trang (ví dụ: Fanpage CSKH)" required>
            </div>
            <div class="col-md-3">
                <input type="url" name="fanpages_urls[]" class="form-control" placeholder="https://..." required>
            </div>
            <div class="col-md-3">
                <input type="text" name="fanpages_descs[]" class="form-control" placeholder="Mô tả (ví dụ: Trang chính hỗ trợ)">
            </div>
            <div class="col-md-1 text-center">
                <button type="button" class="btn btn-danger btn-sm rounded-circle p-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" onclick="this.closest('.fanpage-row').remove()">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
    }
</script>
@endpush
