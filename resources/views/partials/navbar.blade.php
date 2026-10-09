@php
    $menuHome         = \App\Models\SiteSetting::getValue('menu_home', '1') === '1';
    $menuShop         = \App\Models\SiteSetting::getValue('menu_shop', '1') === '1';
    $menuVpn          = \App\Models\SiteSetting::getValue('menu_vpn', '1') === '1';
    $menuBuff         = false;
    $menuWebdesign    = \App\Models\SiteSetting::getValue('menu_webdesign', '1') === '1';
    $menuCardExchange = \App\Models\SiteSetting::getValue('menu_card_exchange', '1') === '1';
    $menuBlog         = \App\Models\SiteSetting::getValue('menu_blog', '1') === '1';
    $menuCommunity    = false;
    $menuMinigame     = \App\Models\SiteSetting::getValue('menu_minigame', '1') === '1';
    $menuZaloGroup    = \App\Models\SiteSetting::getValue('menu_zalo_group', '1') === '1';
    $menuCart         = \App\Models\SiteSetting::getValue('menu_cart', '1') === '1';
    $menuChat         = \App\Models\SiteSetting::getValue('menu_chat', '1') === '1';

    $navItems = [
        [
            'key'     => 'home',
            'enabled' => $menuHome,
            'url'     => route('home'),
            'label'   => __('Trang chủ'),
            'icon'    => 'fa-solid fa-house',
            'active'  => request()->routeIs('home'),
            'color'   => null,
            'target'  => '_self',
        ],
        [
            'key'     => 'shop',
            'enabled' => $menuShop,
            'url'     => route('shop'),
            'label'   => __('Cửa hàng'),
            'icon'    => 'fa-solid fa-shop',
            'active'  => request()->routeIs('shop'),
            'color'   => null,
            'target'  => '_self',
        ],
        [
            'key'     => 'vpn',
            'enabled' => $menuVpn,
            'url'     => route('vpn.index'),
            'label'   => __('VPN'),
            'icon'    => 'fa-solid fa-network-wired',
            'active'  => request()->routeIs('vpn.*'),
            'color'   => '#00bcd4',
            'target'  => '_self',
        ],

        [
            'key'     => 'card_exchange',
            'enabled' => $menuCardExchange,
            'url'     => route('card-exchange.index'),
            'label'   => __('Đổi thẻ'),
            'icon'    => 'fa-solid fa-credit-card',
            'active'  => request()->routeIs('card-exchange.*'),
            'color'   => null,
            'target'  => '_self',
        ],
        [
            'key'     => 'blog',
            'enabled' => $menuBlog,
            'url'     => route('blog.index'),
            'label'   => __('Blog'),
            'icon'    => 'fa-solid fa-newspaper',
            'active'  => request()->routeIs('blog.*'),
            'color'   => null,
            'target'  => '_self',
        ],

        [
            'key'     => 'minigame',
            'enabled' => $menuMinigame,
            'url'     => route('minigame.index'),
            'label'   => __('Mini Game'),
            'icon'    => 'fa-solid fa-gamepad',
            'active'  => request()->routeIs('minigame.*'),
            'color'   => '#e11d48',
            'target'  => '_self',
        ],
        [
            'key'     => 'zalo_group',
            'enabled' => $menuZaloGroup,
            'url'     => \App\Models\SiteSetting::getValue('zalo_group_link', 'https://zalo.me/g/ptarfhnomeuotiyk7cot'),
            'label'   => __('Nhóm Zalo'),
            'icon'    => 'fa-solid fa-comment-dots',
            'active'  => false,
            'color'   => '#0068ff',
            'target'  => '_blank',
        ],
    ];

    $enabledNavItems = array_filter($navItems, function($item) {
        return $item['enabled'];
    });
@endphp

<nav class="navbar navbar-expand-xl navbar-techfeed sticky-top" id="mainNavbar">
    <div class="container-fluid px-3">
        {{-- Logo --}}
        <a class="navbar-brand d-flex align-items-center gap-2 me-0 me-sm-2" href="{{ route('home') }}">
            <div class="brand-icon">
                <i class="fa-solid fa-bolt"></i>
            </div>
            <span>DungThu<span class="brand-dot">.com</span></span>
        </a>

        {{-- Mobile Floating Live Online Badge --}}
        <div class="mobile-live-online-float live-online-interactive-pill d-flex d-md-none align-items-center shadow-sm" 
             title="{{ __('Nhấn để xem chi tiết') }}"
             style="cursor: pointer; user-select: none;">
            <span class="live-dot-pulse" style="width: 6px; height: 6px; background-color: #dc2626; border-radius: 50%; display: inline-block; margin-right: 4px;"></span>
            <i class="fa-solid fa-eye me-1" style="color: #dc2626; font-size: 10px;"></i>
            <span class="online-count-val" style="font-size: 11px; font-weight: 800; color: #dc2626;">--</span>
            <span class="online-extra-text text-danger fw-bold" style="font-size: 10px;">người đang xem</span>
        </div>

        {{-- Desktop Nav Links --}}
        <div class="d-none d-xl-flex align-items-center gap-1 gap-xxl-2 ms-2 me-auto desktop-nav-links" style="font-size: 13.5px; flex-wrap: nowrap; overflow-x: auto; scrollbar-width: none;">
            @foreach($enabledNavItems as $item)
                <a href="{{ $item['url'] }}" 
                   target="{{ $item['target'] }}" 
                   class="nav-text-link text-nowrap {{ $item['active'] ? 'active' : '' }}"
                   @if($item['color']) style="color: {{ $item['color'] }}; font-weight: 700;" @endif>
                    <i class="{{ $item['icon'] }} me-1"></i>{{ $item['label'] }}
                </a>
            @endforeach
            <a href="javascript:void(0)" class="nav-text-link text-nowrap" data-bs-toggle="modal" data-bs-target="#quickContactModal">
                <i class="fa-solid fa-headset me-1"></i>{{ __('Liên hệ') }}
            </a>
            @if($menuWebdesign)
            <a href="{{ route('web-design') }}" class="btn btn-sm text-white fw-bold rounded-pill px-3 ms-1 me-1 shadow-sm d-inline-flex align-items-center gap-1.5 text-nowrap btn-webdesign-cta {{ request()->routeIs('web-design') ? 'active-cta' : '' }}" style="background: linear-gradient(135deg, #ff5e00 0%, #ff8e43 100%); font-size: 13px; flex-shrink: 0;">
                <i class="fa-solid fa-code"></i> {{ __('Thiết kế Website') }}
            </a>
            @endif
        </div>

        {{-- Compact menu for small laptops/tablets --}}
        <div class="dropdown d-none d-lg-block d-xl-none ms-auto me-2">
            <button class="nav-icon-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{{ __('Mở menu') }}">
                <i class="fa-solid fa-bars"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-techfeed">
                @foreach($enabledNavItems as $item)
                    <li>
                        <a class="dropdown-item {{ $item['color'] ? 'fw-bold' : '' }}" 
                           href="{{ $item['url'] }}" 
                           target="{{ $item['target'] }}"
                           @if($item['color']) style="color: {{ $item['color'] }};" @endif>
                            <i class="{{ $item['icon'] }} me-2 {{ $item['color'] ? '' : 'text-primary' }}"></i>{{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#quickContactModal"><i class="fa-solid fa-headset me-2 text-primary"></i>{{ __('Liên hệ') }}</a></li>
                <li><a class="dropdown-item fw-bold" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#appDownloadModal" style="color: #ff5e00;"><i class="fa-solid fa-cloud-arrow-down me-2"></i>{{ __('Tải App') }}</a></li>
            </ul>
        </div>

        {{-- Right Actions --}}
        <div class="d-flex align-items-center gap-2 gap-sm-3">
            {{-- Search Icon (desktop) --}}
            <button class="nav-icon-btn d-none d-xl-flex" type="button" data-bs-toggle="modal" data-bs-target="#searchProductsModal" aria-label="{{ __('Tìm kiếm') }}">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>

            {{-- Notification Bell Modal Trigger --}}
            <button class="nav-icon-btn position-relative notification-bell-btn" type="button" data-bs-toggle="modal" data-bs-target="#spamWarningWelcomeModal" title="{{ __('Thông báo & Hỗ trợ') }}" aria-label="{{ __('Thông báo') }}">
                <i class="fa-solid fa-bell text-warning" style="font-size: 1.15rem;"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm notification-pulse-badge" style="font-size: 10px; padding: 1.5px 5px; transform: translate(-45%, 15%) !important; line-height: 1;">
                    !
                </span>
            </button>



            {{-- Cart --}}
            @if($menuCart)
            <a href="{{ route('cart.index') }}" class="nav-icon-btn position-relative" aria-label="{{ __('Giỏ hàng') }}">
                <i class="fa-solid fa-cart-shopping"></i>
                @php $cartCount = count(session('cart', [])); @endphp
                @if($cartCount > 0)
                    <span class="nav-badge">{{ $cartCount }}</span>
                @endif
            </a>
            @endif

            {{-- Mobile/Tablet Search --}}
            <button class="nav-icon-btn d-xl-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSearchBar" aria-label="{{ __('Tìm kiếm') }}">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>

            {{-- Language Switcher (Desktop Only) --}}
            <div class="dropdown d-none d-lg-flex">
                <button class="nav-icon-btn d-flex align-items-center justify-content-center" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('Ngôn ngữ') }}" style="width: 40px; height: 40px; border-radius: 50%; padding: 0;">
                    @if(app()->getLocale() === 'en')
                        <img src="https://flagcdn.com/w40/us.png" width="22" alt="US" style="border-radius: 3px; box-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                    @else
                        <img src="https://flagcdn.com/w40/vn.png" width="22" alt="VN" style="border-radius: 3px; box-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-techfeed" style="min-width: 150px; border-radius: 12px; padding: 6px;">
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 {{ app()->getLocale() === 'vi' ? 'active fw-bold' : '' }}" href="{{ route('change-language', 'vi') }}" style="font-size: 13px;">
                            <img src="https://flagcdn.com/w40/vn.png" width="18" alt="VN" style="border-radius: 2px;">
                            {{ __('Tiếng Việt') }}
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 {{ app()->getLocale() === 'en' ? 'active fw-bold' : '' }}" href="{{ route('change-language', 'en') }}" style="font-size: 13px;">
                            <img src="https://flagcdn.com/w40/us.png" width="18" alt="US" style="border-radius: 2px;">
                            English
                        </a>
                    </li>
                </ul>
            </div>

            {{-- User Menu --}}
            @auth
                <div class="dropdown">
                    <button class="user-avatar-btn" data-bs-toggle="dropdown" aria-expanded="false" id="userMenuDropdownBtn" aria-label="{{ __('Menu người dùng') }}">
                        <span class="user-initial">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-techfeed user-menu-dropdown p-0" aria-labelledby="userMenuDropdownBtn">
                        {{-- 1. Profile Header --}}
                        <li class="user-dropdown-header px-3 py-2.5 border-bottom bg-light-subtle">
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar-mini">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="user-info-text overflow-hidden">
                                    <div class="fw-bold text-dark text-truncate" style="font-size: 13.5px;">{{ Auth::user()->name }}</div>
                                    <div class="text-muted text-truncate" style="font-size: 11.5px;">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                            @php
                                $isAdmin = in_array(Auth::user()->role, ['superadmin_1', 'sieusuperadmin', 'blog_editor'], true);
                                $isAffiliate = Auth::guard('affiliate')->check();
                            @endphp
                            <div class="mt-2 d-flex gap-1 align-items-center">
                                @if($isAdmin)
                                    <span class="badge rounded-pill" style="background: #fee2e2; color: #dc2626; font-size: 10px; font-weight: 700;">
                                        <i class="fa-solid fa-shield-halved me-1"></i>Admin
                                    </span>
                                @elseif($isAffiliate)
                                    <span class="badge rounded-pill" style="background: #dbeafe; color: #2563eb; font-size: 10px; font-weight: 700;">
                                        <i class="fa-solid fa-handshake me-1"></i>CTV
                                    </span>
                                @else
                                    <span class="badge rounded-pill" style="background: #f1f5f9; color: #475569; font-size: 10px; font-weight: 700;">
                                        <i class="fa-regular fa-user me-1"></i>Thành viên
                                    </span>
                                @endif
                            </div>
                        </li>

                        {{-- 2. Tài khoản cá nhân (Ưu tiên khách thấy ngay lập tức) --}}
                        <li class="dropdown-section-title px-3 pt-2 pb-1 text-uppercase text-muted" style="font-size: 10px; font-weight: 700; letter-spacing: 0.5px;">
                            {{ __('Tài khoản của bạn') }}
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('user.account') }}">
                                <i class="fa-solid fa-circle-user me-2 text-primary"></i>
                                <span>{{ __('Thông tin tài khoản') }}</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('user.orders') }}">
                                <i class="fa-solid fa-box-open me-2 text-primary"></i>
                                <span>{{ __('Đơn hàng đã mua') }}</span>
                            </a>
                        </li>
                        @if($menuMinigame)
                        <li>
                            <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('minigame.index') }}">
                                <span><i class="fa-solid fa-gamepad me-2 text-danger"></i>{{ __('Vòng xoay may mắn') }}</span>
                                <span class="badge rounded-pill bg-danger-subtle text-danger" style="font-size: 9.5px;">Hot</span>
                            </a>
                        </li>
                        @endif

                        <li><hr class="dropdown-divider my-1"></li>

                        {{-- 3. NÚT TẢI APP & DỊCH VỤ TIỆN ÍCH --}}
                        <li class="dropdown-section-title px-3 pt-1 pb-1 text-uppercase text-muted" style="font-size: 10px; font-weight: 700; letter-spacing: 0.5px;">
                            {{ __('Ứng dụng & Dịch vụ') }}
                        </li>
                        {{-- Nút Tải App đưa vào dropdown theo yêu cầu --}}
                        <li>
                            <a class="dropdown-item dropdown-item-app py-2 my-1" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#appDownloadModal">
                                <div class="d-flex align-items-center justify-content-between w-100">
                                    <span class="d-flex align-items-center fw-bold" style="color: #ff5e00;">
                                        <i class="fa-solid fa-cloud-arrow-down me-2" style="font-size: 14px;"></i>
                                        {{ __('Tải App Dùng Thử') }}
                                    </span>
                                    <span class="badge rounded-pill text-white" style="background: linear-gradient(135deg, #ff5e00, #ff8e43); font-size: 9.5px; font-weight: 700; padding: 2px 7px;">
                                        FREE
                                    </span>
                                </div>
                            </a>
                        </li>

                        @if($menuWebdesign)
                        <li class="d-xl-none">
                            <a class="dropdown-item" href="{{ route('web-design') }}">
                                <i class="fa-solid fa-code me-2 text-primary"></i>
                                <span>{{ __('Thiết kế Website') }}</span>
                            </a>
                        </li>
                        @endif

                        @if($menuVpn)
                        <li>
                            <a class="dropdown-item" href="{{ route('vpn.index') }}">
                                <i class="fa-solid fa-network-wired me-2 text-info"></i>
                                <span>{{ __('Dịch vụ VPN') }}</span>
                            </a>
                        </li>
                        @endif

                        @if($menuCardExchange)
                        <li>
                            <a class="dropdown-item" href="{{ route('card-exchange.index') }}">
                                <i class="fa-solid fa-credit-card me-2 text-warning"></i>
                                <span>{{ __('Đổi thẻ cào') }}</span>
                            </a>
                        </li>
                        @endif

                        {{-- CTV --}}
                        @if($isAffiliate)
                            <li>
                                <a class="dropdown-item" href="{{ route('affiliate.dashboard') }}">
                                    <i class="fa-solid fa-handshake me-2 text-success"></i>
                                    <span>{{ __('Dashboard CTV') }}</span>
                                </a>
                            </li>
                        @else
                            <li>
                                <a class="dropdown-item" href="{{ route('affiliate.login') }}">
                                    <i class="fa-solid fa-handshake me-2 text-success"></i>
                                    <span>{{ __('Kiếm tiền CTV') }}</span>
                                </a>
                            </li>
                        @endif

                        {{-- 4. Quản trị Admin (Gọn gàng, chỉ hiện với Admin) --}}
                        @if($isAdmin)
                            <li><hr class="dropdown-divider my-1"></li>
                            <li class="dropdown-section-title px-3 pt-1 pb-1 text-uppercase text-danger" style="font-size: 10px; font-weight: 700; letter-spacing: 0.5px;">
                                <i class="fa-solid fa-shield-halved me-1"></i>{{ __('Quản trị hệ thống') }}
                            </li>
                            <li>
                                <a class="dropdown-item fw-semibold text-danger" href="/admin">
                                    <i class="fas fa-tachometer-alt me-2"></i>{{ __('Dashboard Admin') }}
                                </a>
                            </li>
                            @if(in_array(Auth::user()->role, ['superadmin_1', 'sieusuperadmin'], true))
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.menu-settings') }}">
                                        <i class="fas fa-sliders-h me-2 text-warning"></i>{{ __('Quản lý Menu') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.proxies') }}">
                                        <i class="fas fa-network-wired me-2 text-info"></i>{{ __('Quản lý Proxy') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.buff.dashboard') }}">
                                        <i class="fas fa-chart-line me-2" style="color: #8b5cf6;"></i>{{ __('Quản lý Buff') }}
                                    </a>
                                </li>
                            @endif
                        @endif

                        <li><hr class="dropdown-divider my-1"></li>

                        {{-- 5. Hỗ trợ & Cài đặt --}}
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#quickContactModal">
                                <i class="fa-solid fa-headset me-2 text-primary"></i>
                                <span>{{ __('Hỗ trợ & Liên hệ') }}</span>
                            </a>
                        </li>
                        @if($menuZaloGroup)
                        <li>
                            <a class="dropdown-item" href="{{ \App\Models\SiteSetting::getValue('zalo_group_link', 'https://zalo.me/g/ptarfhnomeuotiyk7cot') }}" target="_blank">
                                <i class="fa-solid fa-users me-2 text-primary"></i>
                                <span style="color: #0068ff;">{{ __('Nhóm Zalo hỗ trợ') }}</span>
                            </a>
                        </li>
                        @endif

                        {{-- Switcher ngôn ngữ trên Mobile --}}
                        <li class="d-lg-none px-3 py-1.5 my-1">
                            <div class="d-flex align-items-center justify-content-between p-1 bg-light rounded-pill border" style="font-size: 11px;">
                                <a href="{{ route('change-language', 'vi') }}" class="btn btn-sm rounded-pill flex-fill text-center py-1 {{ app()->getLocale() === 'vi' ? 'bg-white shadow-xs fw-bold text-primary' : 'text-muted' }}" style="font-size: 11px;">
                                    <img src="https://flagcdn.com/w40/vn.png" width="14" alt="VN" class="me-1 rounded-1">Tiếng Việt
                                </a>
                                <a href="{{ route('change-language', 'en') }}" class="btn btn-sm rounded-pill flex-fill text-center py-1 {{ app()->getLocale() === 'en' ? 'bg-white shadow-xs fw-bold text-primary' : 'text-muted' }}" style="font-size: 11px;">
                                    <img src="https://flagcdn.com/w40/us.png" width="14" alt="US" class="me-1 rounded-1">English
                                </a>
                            </div>
                        </li>

                        <li><hr class="dropdown-divider my-1"></li>

                        {{-- 6. Đăng xuất --}}
                        <li class="pb-1">
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger fw-semibold">
                                    <i class="fas fa-sign-out-alt me-2"></i>{{ __('Đăng xuất') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                {{-- Guest Dropdown Menu --}}
                <div class="dropdown">
                    <button class="nav-icon-btn d-flex align-items-center justify-content-center" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('Tài khoản & Tiện ích') }}" style="border-radius: 50%;" aria-label="{{ __('Menu tài khoản') }}">
                        <i class="fa-solid fa-circle-user" style="font-size: 1.25rem; color: #ff5e00;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-techfeed user-menu-dropdown p-0">
                        {{-- Welcome Header --}}
                        <li class="px-3 py-3 border-bottom text-center" style="background: linear-gradient(135deg, rgba(255, 94, 0, 0.05) 0%, rgba(255, 142, 67, 0.1) 100%);">
                            <div class="fw-bold text-dark mb-1" style="font-size: 13.5px;">{{ __('Chào mừng đến với DungThu!') }}</div>
                            <div class="text-muted mb-2" style="font-size: 11.5px;">{{ __('Đăng nhập để nhận ưu đãi & lưu đơn') }}</div>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('login') }}" class="btn btn-sm text-white fw-bold px-3 rounded-pill" style="background: linear-gradient(135deg, #ff5e00, #ff8e43); font-size: 12px;">
                                    <i class="fa-solid fa-right-to-bracket me-1"></i>{{ __('Đăng nhập') }}
                                </a>
                                @if(Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-sm btn-outline-secondary fw-semibold px-3 rounded-pill" style="font-size: 12px;">
                                    {{ __('Đăng ký') }}
                                </a>
                                @endif
                            </div>
                        </li>

                        {{-- Tải App Nổi Bật Cho Guest --}}
                        <li class="pt-2">
                            <a class="dropdown-item dropdown-item-app py-2 my-1" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#appDownloadModal">
                                <div class="d-flex align-items-center justify-content-between w-100">
                                    <span class="d-flex align-items-center fw-bold" style="color: #ff5e00;">
                                        <i class="fa-solid fa-cloud-arrow-down me-2" style="font-size: 14px;"></i>
                                        {{ __('Tải App Dùng Thử') }}
                                    </span>
                                    <span class="badge rounded-pill text-white" style="background: linear-gradient(135deg, #ff5e00, #ff8e43); font-size: 9.5px; font-weight: 700; padding: 2px 7px;">
                                        FREE
                                    </span>
                                </div>
                            </a>
                        </li>

                        @if($menuWebdesign)
                        <li class="d-xl-none">
                            <a class="dropdown-item" href="{{ route('web-design') }}">
                                <i class="fa-solid fa-code me-2 text-primary"></i>
                                <span>{{ __('Thiết kế Website') }}</span>
                            </a>
                        </li>
                        @endif

                        @if($menuVpn)
                        <li>
                            <a class="dropdown-item" href="{{ route('vpn.index') }}">
                                <i class="fa-solid fa-network-wired me-2 text-info"></i>
                                <span>{{ __('Dịch vụ VPN') }}</span>
                            </a>
                        </li>
                        @endif

                        @if($menuCardExchange)
                        <li>
                            <a class="dropdown-item" href="{{ route('card-exchange.index') }}">
                                <i class="fa-solid fa-credit-card me-2 text-warning"></i>
                                <span>{{ __('Đổi thẻ cào') }}</span>
                            </a>
                        </li>
                        @endif

                        @if($menuMinigame)
                        <li>
                            <a class="dropdown-item" href="{{ route('minigame.index') }}">
                                <i class="fa-solid fa-gamepad me-2 text-danger"></i>
                                <span>{{ __('Vòng xoay may mắn') }}</span>
                            </a>
                        </li>
                        @endif

                        <li><hr class="dropdown-divider my-1"></li>

                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#quickContactModal">
                                <i class="fa-solid fa-headset me-2 text-primary"></i>
                                <span>{{ __('Hỗ trợ & Liên hệ') }}</span>
                            </a>
                        </li>
                        @if($menuZaloGroup)
                        <li>
                            <a class="dropdown-item" href="{{ \App\Models\SiteSetting::getValue('zalo_group_link', 'https://zalo.me/g/ptarfhnomeuotiyk7cot') }}" target="_blank">
                                <i class="fa-solid fa-users me-2 text-primary"></i>
                                <span style="color: #0068ff;">{{ __('Nhóm Zalo hỗ trợ') }}</span>
                            </a>
                        </li>
                        @endif

                        {{-- Mobile Language Switcher --}}
                        <li class="d-lg-none px-3 py-1.5 my-1">
                            <div class="d-flex align-items-center justify-content-between p-1 bg-light rounded-pill border" style="font-size: 11px;">
                                <a href="{{ route('change-language', 'vi') }}" class="btn btn-sm rounded-pill flex-fill text-center py-1 {{ app()->getLocale() === 'vi' ? 'bg-white shadow-xs fw-bold text-primary' : 'text-muted' }}" style="font-size: 11px;">
                                    <img src="https://flagcdn.com/w40/vn.png" width="14" alt="VN" class="me-1 rounded-1">Tiếng Việt
                                </a>
                                <a href="{{ route('change-language', 'en') }}" class="btn btn-sm rounded-pill flex-fill text-center py-1 {{ app()->getLocale() === 'en' ? 'bg-white shadow-xs fw-bold text-primary' : 'text-muted' }}" style="font-size: 11px;">
                                    <img src="https://flagcdn.com/w40/us.png" width="14" alt="US" class="me-1 rounded-1">English
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>
    </div>

    {{-- Mobile Search Bar (collapsed) --}}
    <div class="collapse w-100" id="mobileSearchBar">
        <div class="px-3 pb-2">
            <form class="search-bar-inner w-100" action="{{ route('shop') }}" method="GET" style="border: 1.5px solid #ff5e00; background-color: #fff;">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" name="search" class="search-input" placeholder="{{ __('Tìm kiếm...') }}" value="{{ request('search') }}">
            </form>
        </div>
    </div>
</nav>

{{-- Mobile Bottom Nav --}}
<nav class="mobile-bottom-nav d-lg-none">
    @if($menuHome)
    <a href="{{ route('home') }}" class="mobile-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="fa-solid fa-house"></i>
        <span>{{ __('Trang chủ') }}</span>
    </a>
    @endif
    @if($menuShop)
    <a href="{{ route('shop') }}" class="mobile-nav-item {{ request()->routeIs('shop') ? 'active' : '' }}">
        <i class="fa-solid fa-store"></i>
        <span>{{ __('Cửa hàng') }}</span>
    </a>
    @endif
    @if($menuBlog)
    <a href="{{ route('blog.index') }}" class="mobile-nav-item {{ request()->routeIs('blog.*') ? 'active' : '' }}">
        <i class="fa-solid fa-newspaper" style="color: #ff5e00;"></i>
        <span style="color: #ff5e00; font-weight: bold;">{{ __('Blog') }}</span>
    </a>
    @endif
    @if($menuCart)
    <a href="{{ route('cart.index') }}" class="mobile-nav-item position-relative {{ request()->routeIs('cart.*') ? 'active' : '' }}">
        <i class="fa-solid fa-cart-shopping"></i>
        @if(isset($cartCount) && $cartCount > 0)
            <span class="nav-badge">{{ $cartCount }}</span>
        @endif
        <span>{{ __('Giỏ hàng') }}</span>
    </a>
    @endif
    @auth
    @if($menuChat)
    <a href="{{ route('user.orders') }}" class="mobile-nav-item {{ request()->routeIs('user.orders') ? 'active' : '' }}">
        <i class="fa-solid fa-box"></i>
        <span>{{ __('Đơn hàng') }}</span>
    </a>
    @endif
    @endauth
    <a href="{{ \App\Models\SiteSetting::getValue('zalo_group_link', 'https://zalo.me/g/ptarfhnomeuotiyk7cot') }}" target="_blank" class="mobile-nav-item">
        <i class="fa-solid fa-users" style="color: #0068ff;"></i>
        <span style="color: #0068ff; font-weight: bold;">{{ __('Nhóm') }}</span>
    </a>
</nav>

{{-- Quick Contact Modal --}}
<div class="modal fade" id="quickContactModal" tabindex="-1" aria-labelledby="quickContactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: #ffffff;">
            {{-- Header --}}
            <div class="modal-header border-0 text-white px-4 py-3 position-relative d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #ff5e00 0%, #ff8e43 100%);">
                <div>
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2 mb-0" id="quickContactModalLabel" style="font-size: 18px;">
                        <i class="fa-solid fa-headset"></i>
                        {{ __('Liên hệ & Nhắn tin nhanh') }}
                    </h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="opacity: 0.8; filter: invert(1) grayscale(1) brightness(2);"></button>
            </div>
            
            {{-- Body --}}
            <div class="modal-body p-4" style="background-color: #f8f9fa;">
                <div class="d-flex flex-column gap-3">
                    
                    {{-- Item 1: Zalo Group --}}
                    <a href="{{ \App\Models\SiteSetting::getValue('zalo_group_link', 'https://zalo.me/g/ptarfhnomeuotiyk7cot') }}" 
                       target="_blank" 
                       class="d-flex align-items-center gap-3 p-3 text-decoration-none bg-white rounded-3 contact-modal-item"
                       style="border: 1px solid #e5e7eb; transition: all 0.2s ease;">
                        <div class="contact-modal-icon-wrap d-flex align-items-center justify-content-center" 
                             style="width: 48px; height: 48px; border-radius: 12px; background: #e6f0ff; color: #0068ff; min-width: 48px; font-size: 18px;">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div class="flex-grow-1 text-start">
                            <h6 class="fw-bold mb-1" style="color: #1f2937; font-size: 14px;">{{ __('GROUP ZALO HỖ TRỢ') }}</h6>
                            <p class="mb-0 text-muted" style="font-size: 12px;">{{ __('Tham gia nhóm hỗ trợ thành viên') }}</p>
                        </div>
                        <div style="color: #9ca3af;"><i class="fa-solid fa-chevron-right"></i></div>
                    </a>

                    {{-- Item 2: Telegram --}}
                    <a href="{{ \App\Helpers\SupportHelper::getTelegramLink() }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="d-flex align-items-center gap-3 p-3 text-decoration-none bg-white rounded-3 contact-modal-item"
                       style="border: 1px solid #e5e7eb; transition: all 0.2s ease;">
                        <div class="contact-modal-icon-wrap d-flex align-items-center justify-content-center"
                             style="width: 48px; height: 48px; border-radius: 12px; background: #e6f7ff; color: #0088cc; min-width: 48px; font-size: 18px;">
                            <i class="fa-brands fa-telegram-plane"></i>
                        </div>
                        <div class="flex-grow-1 text-start">
                            <h6 class="fw-bold mb-1" style="color: #1f2937; font-size: 14px;">{{ __('TELEGRAM ADMIN') }}</h6>
                            <p class="mb-0 text-muted" style="font-size: 12px;">{{ __('Telegram liên hệ:') }} {{ \App\Helpers\SupportHelper::getTelegramUsername() }}</p>
                        </div>
                        <div style="color: #9ca3af;"><i class="fa-solid fa-chevron-right"></i></div>
                    </a>

                    {{-- Item 3: Admin Zalo --}}
                    <a href="{{ \App\Helpers\SupportHelper::getZaloLink() }}" 
                       target="_blank" 
                       class="d-flex align-items-center gap-3 p-3 text-decoration-none bg-white rounded-3 contact-modal-item"
                       style="border: 1px solid #e5e7eb; transition: all 0.2s ease;">
                        <div class="contact-modal-icon-wrap d-flex align-items-center justify-content-center" 
                             style="width: 48px; height: 48px; border-radius: 12px; background: #e8f8f5; color: #07be9e; min-width: 48px; font-size: 18px;">
                            <i class="fa-solid fa-comment-dots"></i>
                        </div>
                        <div class="flex-grow-1 text-start">
                            <h6 class="fw-bold mb-1" style="color: #1f2937; font-size: 14px;">{{ __('CHAT ZALO ADMIN') }}</h6>
                            <p class="mb-0 text-muted" style="font-size: 12px;">{{ __('Zalo liên hệ:') }} {{ \App\Helpers\SupportHelper::getZaloNumber() }}</p>
                        </div>
                        <div style="color: #9ca3af;"><i class="fa-solid fa-chevron-right"></i></div>
                    </a>

                </div>
            </div>
            
            {{-- Footer --}}
            <div class="modal-footer border-0 justify-content-center py-2" style="background-color: #f1f3f5;">
                <span class="text-muted" style="font-size: 11px; font-weight: 500;">{{ __('DungThu.com hân hạnh hỗ trợ!') }}</span>
            </div>
        </div>
    </div>
</div>

<style>
    /* Unified User & Header Dropdown Menu Optimization */
    .dropdown:hover > .dropdown-menu:not(.show) {
        display: none !important;
    }

    /* Ultra-thin Floating Scrollbar for Dropdown Menu */
    .shadow-techfeed.dropdown-menu,
    ul.shadow-techfeed {
        max-height: calc(100vh - 75px) !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        min-width: 250px !important;
        max-width: 285px !important;
        padding: 4px 0 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        border-radius: 14px !important;
        margin-right: 6px !important;
        background-color: #ffffff !important;
    }

    .dropdown-menu::-webkit-scrollbar,
    ul.shadow-techfeed::-webkit-scrollbar,
    .shadow-techfeed::-webkit-scrollbar,
    .shadow-techfeed.dropdown-menu::-webkit-scrollbar {
        width: 3px !important;
        height: 3px !important;
        background: transparent !important;
    }

    .dropdown-menu::-webkit-scrollbar-track,
    ul.shadow-techfeed::-webkit-scrollbar-track,
    .shadow-techfeed::-webkit-scrollbar-track,
    .shadow-techfeed.dropdown-menu::-webkit-scrollbar-track {
        background: transparent !important;
    }

    .dropdown-menu::-webkit-scrollbar-thumb,
    ul.shadow-techfeed::-webkit-scrollbar-thumb,
    .shadow-techfeed::-webkit-scrollbar-thumb,
    .shadow-techfeed.dropdown-menu::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.25) !important;
        border-radius: 10px !important;
    }

    .dropdown-menu::-webkit-scrollbar-thumb:hover,
    ul.shadow-techfeed::-webkit-scrollbar-thumb:hover,
    .shadow-techfeed::-webkit-scrollbar-thumb:hover,
    .shadow-techfeed.dropdown-menu::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.5) !important;
    }

    @supports (-moz-appearance: none) {
        .shadow-techfeed.dropdown-menu,
        ul.shadow-techfeed {
            scrollbar-width: thin !important;
            scrollbar-color: rgba(0, 0, 0, 0.25) transparent !important;
        }
    }

    .shadow-techfeed .dropdown-item {
        display: flex !important;
        align-items: center !important;
        padding: 7px 12px !important;
        font-size: 13px !important;
        color: #1f2937 !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        transition: background-color 0.15s ease, color 0.15s ease !important;
        border-radius: 8px !important;
        margin: 1.5px 6px !important;
        width: calc(100% - 12px) !important;
        box-sizing: border-box !important;
    }

    .shadow-techfeed .dropdown-item:hover,
    .shadow-techfeed .dropdown-item:focus {
        background-color: rgba(255, 94, 0, 0.08) !important;
        color: #ff5e00 !important;
    }

    .shadow-techfeed button.dropdown-item.text-danger:hover,
    .shadow-techfeed button.dropdown-item.text-danger:focus {
        color: #dc2626 !important;
        background-color: rgba(220, 38, 38, 0.08) !important;
    }

    .shadow-techfeed .dropdown-item.active {
        background-color: rgba(255, 94, 0, 0.12) !important;
        color: #ff5e00 !important;
        font-weight: 700;
    }

    /* Dropdown Section Titles & Avatars */
    .dropdown-section-title {
        font-size: 10px !important;
        letter-spacing: 0.5px !important;
        font-weight: 700 !important;
        color: #94a3b8 !important;
        padding-left: 14px !important;
        padding-right: 14px !important;
    }

    .user-avatar-mini {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ff5e00 0%, #ff8e43 100%);
        color: #fff;
        font-weight: 700;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .dropdown-item-app {
        border-radius: 8px !important;
        transition: transform 0.15s ease, background 0.15s ease !important;
    }

    .dropdown-item-app:hover {
        background: linear-gradient(135deg, rgba(255, 94, 0, 0.14) 0%, rgba(255, 142, 67, 0.18) 100%) !important;
        transform: translateX(2px);
    }

    .btn-webdesign-cta {
        transition: transform 0.15s ease, box-shadow 0.15s ease !important;
    }

    .btn-webdesign-cta:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 94, 0, 0.35) !important;
        color: #ffffff !important;
    }

    @media (max-width: 576px) {
        .shadow-techfeed.dropdown-menu,
        .user-menu-dropdown {
            right: 6px !important;
            left: auto !important;
            max-width: calc(100vw - 16px) !important;
            width: 275px !important;
        }
    }

    .contact-modal-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
        border-color: rgba(255, 94, 0, 0.25) !important;
        background-color: #fafbfc !important;
    }
    .contact-modal-item:hover .contact-modal-icon-wrap {
        transform: scale(1.05);
    }
</style>

{{-- Search Products Modal --}}
<div class="modal fade" id="searchProductsModal" tabindex="-1" aria-labelledby="searchProductsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: #ffffff;">
            {{-- Header --}}
            <div class="modal-header border-0 text-white px-4 py-3 position-relative d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #ff5e00 0%, #ff8e43 100%);">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2 mb-0" id="searchProductsModalLabel" style="font-size: 17px;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    {{ __('Tìm kiếm sản phẩm') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="opacity: 0.8; filter: invert(1) grayscale(1) brightness(2);"></button>
            </div>
            
            {{-- Body --}}
            <div class="modal-body p-4" style="background-color: #f8f9fa;">
                <form action="{{ route('shop') }}" method="GET">
                    <div class="input-group mb-3 shadow-sm" style="border-radius: 25px; overflow: hidden; border: 1.5px solid #ff5e00;">
                        <span class="input-group-text bg-white border-0 ps-3" style="color: #9ca3af;">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-0 py-2" 
                               placeholder="{{ __('Nhập tên sản phẩm để tìm...') }}" 
                               style="font-size: 14.5px; outline: none; box-shadow: none; background-color: #fff;" 
                               required 
                               id="searchModalInput">
                        <button class="btn btn-primary border-0 px-4 fw-bold" type="submit" style="background: linear-gradient(135deg, #ff5e00 0%, #ff8e43 100%);">
                            {{ __('Tìm kiếm') }}
                        </button>
                    </div>
                </form>
                
                {{-- Fast tags --}}
                <div class="ps-2 text-start">
                    <span class="text-muted" style="font-size: 12px; font-weight: 500;">{{ __('Gợi ý:') }}</span>
                    <a href="{{ route('shop', ['search' => 'gpt']) }}" class="badge bg-white text-secondary text-decoration-none px-2 py-1.5 ms-1 border" style="font-size: 11px; border-radius: 12px;">gpt</a>
                    <a href="{{ route('shop', ['search' => 'gemini']) }}" class="badge bg-white text-secondary text-decoration-none px-2 py-1.5 ms-1 border" style="font-size: 11px; border-radius: 12px;">gemini</a>
                    <a href="{{ route('shop', ['search' => 'cursor']) }}" class="badge bg-white text-secondary text-decoration-none px-2 py-1.5 ms-1 border" style="font-size: 11px; border-radius: 12px;">cursor</a>
                    <a href="{{ route('shop', ['search' => 'antigravity']) }}" class="badge bg-white text-secondary text-decoration-none px-2 py-1.5 ms-1 border" style="font-size: 11px; border-radius: 12px;">antigravity</a>
                    <a href="{{ route('shop', ['search' => 'grok']) }}" class="badge bg-white text-secondary text-decoration-none px-2 py-1.5 ms-1 border" style="font-size: 11px; border-radius: 12px;">grok</a>
                    <a href="{{ route('shop', ['search' => 'capcut']) }}" class="badge bg-white text-secondary text-decoration-none px-2 py-1.5 ms-1 border" style="font-size: 11px; border-radius: 12px;">capcut</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes pulseDotLive {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7); }
        70% { transform: scale(1.1); box-shadow: 0 0 0 5px rgba(220, 38, 38, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
    }
    .live-dot-pulse {
        animation: pulseDotLive 1.5s infinite;
    }
    .mobile-live-online-float {
        position: fixed;
        bottom: 122px;
        right: 12px;
        z-index: 9999;
        padding: 4px 10px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(220, 38, 38, 0.3);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        pointer-events: auto;
        transition: all 0.35s ease;
    }
    @media (max-width: 576px) {
        .mobile-live-online-float {
            bottom: 118px;
            right: 10px;
        }
    }
    /* Chế độ mặc định (Thu gọn): Ẩn chữ "người đang xem", chỉ hiện Icon Mắt + Số */
    .live-online-interactive-pill .online-extra-text {
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        white-space: nowrap;
        display: inline-block;
        vertical-align: bottom;
        transition: max-width 0.35s ease, opacity 0.25s ease, margin 0.25s ease;
        margin-left: 0 !important;
    }
    /* Khi Click mở rộng: Hiện đầy đủ "X người đang xem" trong 3 giây */
    .live-online-interactive-pill.is-expanded .online-extra-text {
        max-width: 140px;
        opacity: 1;
        margin-left: 4px !important;
    }
    
    /* Animation cho Icon Chuông lắc lắc & Badge ! nhấp nháy liên tục */
    @keyframes bellBadgePulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.8); }
        50% { transform: scale(1.25); box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
    @keyframes bellShakeContinuous {
        0%, 100% { transform: rotate(0deg); }
        10% { transform: rotate(18deg); }
        20% { transform: rotate(-16deg); }
        30% { transform: rotate(14deg); }
        40% { transform: rotate(-12deg); }
        50% { transform: rotate(8deg); }
        60% { transform: rotate(-6deg); }
        70% { transform: rotate(0deg); }
    }
    .notification-bell-btn .fa-bell {
        animation: bellShakeContinuous 2s infinite ease-in-out;
        transform-origin: top center;
        display: inline-block;
    }
    .notification-pulse-badge {
        animation: bellBadgePulse 1.3s infinite ease-in-out;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        color: #ffffff !important;
        font-weight: 900 !important;
        border: 1.5px solid #ffffff;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchModalEl = document.getElementById('searchProductsModal');
        if (searchModalEl) {
            searchModalEl.addEventListener('shown.bs.modal', function () {
                const searchInput = document.getElementById('searchModalInput');
                if (searchInput) {
                    searchInput.focus();
                }
            });
        }

        // Global Real-time Online Users Count & Heartbeat
        const onlineCountValEls = document.querySelectorAll('.online-count-val');
        const heroTextEls = document.querySelectorAll('.heroOnlineCountText');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        function updateGlobalOnlineUsersCount() {
            if (document.visibilityState && document.visibilityState !== 'visible') {
                return;
            }
            fetch('{{ route("online-users.ping") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || ''
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data && data.success) {
                    onlineCountValEls.forEach(el => {
                        el.textContent = `${data.count}`;
                    });
                    heroTextEls.forEach(el => {
                        el.textContent = `${data.count} {{ __('đang xem') }}`;
                    });
                }
            })
            .catch(() => {});
        }

        updateGlobalOnlineUsersCount();
        // Keep the counter fresh without waking hidden tabs or creating an
        // unnecessary request for every visitor twice per minute.
        setInterval(updateGlobalOnlineUsersCount, 60000);
        document.addEventListener('visibilitychange', function () {
            if (document.visibilityState === 'visible') {
                updateGlobalOnlineUsersCount();
            }
        });

        // Cơ chế Click -> Mở rộng chi tiết trong 3s -> Tự động thu gọn lại (Icon Mắt + Số)
        const interactivePills = document.querySelectorAll('.live-online-interactive-pill');
        interactivePills.forEach(pill => {
            let expandTimer = null;
            pill.addEventListener('click', function(e) {
                e.stopPropagation();
                
                // Mở rộng hiển thị chữ "người đang xem"
                this.classList.add('is-expanded');
                
                if (expandTimer) clearTimeout(expandTimer);
                
                // 3 giây sau tự động thu gọn lại chỉ còn Icon Mắt + Số
                expandTimer = setTimeout(() => {
                    pill.classList.remove('is-expanded');
                }, 3000);
            });
        });
    });
</script>
