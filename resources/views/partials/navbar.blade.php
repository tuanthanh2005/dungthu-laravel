@php
    $menuHome        = \App\Models\SiteSetting::getValue('menu_home', '1') === '1';
    $menuShop        = \App\Models\SiteSetting::getValue('menu_shop', '1') === '1';
    $menuBlog        = \App\Models\SiteSetting::getValue('menu_blog', '1') === '1';
    $menuCart        = \App\Models\SiteSetting::getValue('menu_cart', '1') === '1';
    $menuWebdesign   = \App\Models\SiteSetting::getValue('menu_webdesign', '1') === '1';
    $menuBuff        = \App\Models\SiteSetting::getValue('menu_buff', '1') === '1';
    $menuCommunity   = \App\Models\SiteSetting::getValue('menu_community', '1') === '1';
    $menuCardExchange = \App\Models\SiteSetting::getValue('menu_card_exchange', '1') === '1';
    $menuChat        = \App\Models\SiteSetting::getValue('menu_chat', '1') === '1';
    $menuMinigame    = \App\Models\SiteSetting::getValue('menu_minigame', '1') === '1';
    $menuZaloGroup   = \App\Models\SiteSetting::getValue('menu_zalo_group', '1') === '1';
@endphp

<nav class="navbar navbar-expand-xl navbar-techfeed sticky-top" id="mainNavbar">
    <div class="container-fluid px-3">
        {{-- Logo --}}
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <div class="brand-icon">
                <i class="fa-solid fa-bolt"></i>
            </div>
            <span>DungThu<span class="brand-dot">.com</span></span>
        </a>

        {{-- Desktop Nav Links --}}
        <div class="d-none d-xl-flex align-items-center gap-2 mx-auto desktop-nav-links" style="font-size: 14.5px;">
            @if($menuHome)
            <a href="{{ route('home') }}" class="nav-text-link {{ request()->routeIs('home') ? 'active' : '' }}">
                {{ __('Trang chủ') }}
            </a>
            @endif
            @if($menuShop)
            <a href="{{ route('shop') }}" class="nav-text-link {{ request()->routeIs('shop') ? 'active' : '' }}">
                {{ __('Cửa hàng') }}
            </a>
            @endif
            <a href="{{ route('vpn.index') }}" class="nav-text-link {{ request()->routeIs('vpn.*') ? 'active' : '' }}" style="color: #00bcd4; font-weight: 700;">
                <i class="fa-solid fa-network-wired me-1"></i>{{ __('VPN & Proxy') }}
            </a>
            @if($menuBuff)
            <a href="{{ route('buff.index') }}" class="nav-text-link {{ request()->routeIs('buff.*') ? 'active' : '' }}" style="color: #ff5e00; font-weight: 700;">
                {{ __('Dịch Vụ MXH') }}
            </a>
            @endif
            @if($menuWebdesign)
            <a href="{{ route('web-design') }}" class="nav-text-link {{ request()->routeIs('web-design') ? 'active' : '' }}">
                {{ __('Thiết Kế Website') }}
            </a>
            @endif
            @if($menuCardExchange)
            <a href="{{ route('card-exchange.index') }}" class="nav-text-link {{ request()->routeIs('card-exchange.*') ? 'active' : '' }}">
                {{ __('Đổi Thẻ Cào') }}
            </a>
            @endif
            @if($menuBlog)
            <a href="{{ route('blog.index') }}" class="nav-text-link {{ request()->routeIs('blog.*') ? 'active' : '' }}">
                {{ __('Blog') }}
            </a>
            @endif
            @if($menuCommunity)
            <a href="{{ route('community.index') }}" class="nav-text-link {{ request()->routeIs('community.*') ? 'active' : '' }}">
                {{ __('Cộng đồng') }}
            </a>
            @endif
            @if($menuMinigame)
            <a href="{{ route('minigame.index') }}" class="nav-text-link {{ request()->routeIs('minigame.*') ? 'active' : '' }}" style="color: #e11d48; font-weight: 700;">
                <i class="fa-solid fa-gamepad me-1"></i>{{ __('Mini Game') }}
            </a>
            @endif
            @if($menuZaloGroup)
            <a href="{{ \App\Models\SiteSetting::getValue('zalo_group_link', 'https://zalo.me/g/ptarfhnomeuotiyk7cot') }}" target="_blank" class="nav-text-link fw-bold" style="color: #0068ff;">
                {{ __('Nhóm Zalo') }}
            </a>
            @endif
            <a href="javascript:void(0)" class="nav-text-link" data-bs-toggle="modal" data-bs-target="#quickContactModal">
                {{ __('Liên hệ') }}
            </a>
        </div>

        {{-- Compact menu for small laptops/tablets --}}
        <div class="dropdown d-none d-lg-block d-xl-none ms-auto me-2">
            <button class="nav-icon-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{{ __('Mở menu') }}">
                <i class="fa-solid fa-bars"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-techfeed">
                @if($menuHome)
                    <li><a class="dropdown-item" href="{{ route('home') }}"><i class="fa-solid fa-house me-2 text-primary"></i>{{ __('Trang chủ') }}</a></li>
                @endif
                @if($menuShop)
                    <li><a class="dropdown-item" href="{{ route('shop') }}"><i class="fa-solid fa-store me-2 text-primary"></i>{{ __('Cửa hàng') }}</a></li>
                @endif
                <li><a class="dropdown-item fw-bold" href="{{ route('vpn.index') }}" style="color: #00bcd4;"><i class="fa-solid fa-network-wired me-2"></i>{{ __('VPN & Proxy') }}</a></li>
                @if($menuBuff)
                    <li><a class="dropdown-item fw-bold" href="{{ route('buff.index') }}" style="color: #ff5e00;"><i class="fa-solid fa-rocket me-2"></i>{{ __('Dịch Vụ MXH') }}</a></li>
                @endif
                @if($menuWebdesign)
                    <li><a class="dropdown-item" href="{{ route('web-design') }}"><i class="fa-solid fa-code me-2 text-primary"></i>{{ __('Thiết Kế Website') }}</a></li>
                @endif
                @if($menuCardExchange)
                    <li><a class="dropdown-item" href="{{ route('card-exchange.index') }}"><i class="fa-solid fa-credit-card me-2 text-warning"></i>{{ __('Đổi Thẻ Cào') }}</a></li>
                @endif
                @if($menuBlog)
                    <li><a class="dropdown-item" href="{{ route('blog.index') }}"><i class="fa-solid fa-newspaper me-2 text-primary"></i>{{ __('Blog') }}</a></li>
                @endif
                @if($menuCommunity)
                    <li><a class="dropdown-item" href="{{ route('community.index') }}"><i class="fa-solid fa-users me-2 text-success"></i>{{ __('Cộng đồng') }}</a></li>
                @endif
                @if($menuMinigame)
                    <li><a class="dropdown-item fw-bold" href="{{ route('minigame.index') }}" style="color: #e11d48;"><i class="fa-solid fa-gamepad me-2"></i>{{ __('Mini Game') }}</a></li>
                @endif
                <li><hr class="dropdown-divider"></li>
                @if($menuZaloGroup)
                <li><a class="dropdown-item fw-bold" href="{{ \App\Models\SiteSetting::getValue('zalo_group_link', 'https://zalo.me/g/ptarfhnomeuotiyk7cot') }}" target="_blank" style="color: #0068ff;"><i class="fa-solid fa-users me-2"></i>{{ __('Nhóm Zalo') }}</a></li>
                @endif
                <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#quickContactModal"><i class="fa-solid fa-headset me-2 text-primary"></i>{{ __('Liên hệ') }}</a></li>
            </ul>
        </div>

        {{-- Search Bar (desktop) --}}
        <div class="d-none d-xl-flex search-bar-wrap align-items-center ms-auto me-3" style="max-width: 250px;">
            <form class="search-bar-inner w-100" action="{{ route('shop') }}" method="GET" style="border: 1.5px solid #ff5e00; background-color: #fff;">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" name="search" class="search-input w-100" 
                       placeholder="{{ __('Tìm kiếm sản phẩm...') }}"
                       value="{{ request('search') }}">
            </form>
        </div>

        {{-- Right Actions --}}
        <div class="d-flex align-items-center gap-2 gap-sm-3">
            {{-- Language Switcher --}}
            <div class="dropdown">
                <button class="nav-icon-btn d-flex align-items-center justify-content-center gap-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('Ngôn ngữ') }}" style="padding: 6px 12px; font-size: 14px; border-radius: 20px;">
                    @if(app()->getLocale() === 'en')
                        <img src="https://flagcdn.com/w40/us.png" width="20" alt="US" style="border-radius: 2px; border: 1px solid rgba(0,0,0,0.15); display: inline-block;">
                        <span class="d-none d-md-inline ms-1 fw-bold" style="font-size: 12px;">EN</span>
                    @else
                        <img src="https://flagcdn.com/w40/vn.png" width="20" alt="VN" style="border-radius: 2px; border: 1px solid rgba(0,0,0,0.15); display: inline-block;">
                        <span class="d-none d-md-inline ms-1 fw-bold" style="font-size: 12px;">VI</span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-techfeed" style="min-width: 140px; border: none; border-radius: 12px;">
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 {{ app()->getLocale() === 'vi' ? 'active' : '' }}" href="{{ route('change-language', 'vi') }}" style="font-size: 13.5px;">
                            <img src="https://flagcdn.com/w40/vn.png" width="20" alt="VN" style="border-radius: 2px; border: 1px solid rgba(0,0,0,0.15);">
                            {{ __('Tiếng Việt') }}
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="{{ route('change-language', 'en') }}" style="font-size: 13.5px;">
                            <img src="https://flagcdn.com/w40/us.png" width="20" alt="US" style="border-radius: 2px; border: 1px solid rgba(0,0,0,0.15);">
                            English
                        </a>
                    </li>
                </ul>
            </div>

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

            {{-- User Menu --}}
            @auth
                <div class="dropdown">
                    <button class="user-avatar-btn" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="user-initial">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-techfeed">
                        <li class="px-3 py-2 border-bottom">
                            <div class="fw-bold text-sm">{{ Auth::user()->name }}</div>
                            <div class="text-muted" style="font-size:0.78rem;">{{ Auth::user()->email }}</div>
                        </li>
                        @if(Auth::user()->role === 'admin')
                            <li><a class="dropdown-item" href="/admin"><i class="fas fa-tachometer-alt me-2 text-primary"></i>{{ __('Dashboard Admin') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.menu-settings') }}"><i class="fas fa-sliders-h me-2 text-warning"></i>{{ __('Quản lý Menu') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.proxies') }}"><i class="fas fa-network-wired me-2 text-info"></i>{{ __('Quản lý Proxy') }}</a></li>
                            
                            {{-- Collapsible Quản lý Buff --}}
                            <li>
                                <a class="dropdown-item d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#adminBuffCollapse" role="button" aria-expanded="false" aria-controls="adminBuffCollapse" onclick="event.stopPropagation();">
                                    <span><i class="fas fa-chart-line me-2" style="color:#8b5cf6;"></i>{{ __('Quản lý Buff') }}</span>
                                    <i class="fas fa-chevron-down ms-2" style="font-size: 0.75rem;"></i>
                                </a>
                                <div class="collapse px-2" id="adminBuffCollapse" onclick="event.stopPropagation();">
                                    <ul class="list-unstyled ps-3 bg-light rounded py-1 my-1">
                                        <li><a class="dropdown-item py-1" href="{{ route('admin.buff.dashboard') }}" style="font-size: 0.85rem;"><i class="fas fa-chart-line me-2" style="color:#8b5cf6;"></i>{{ __('Buff Dashboard') }}</a></li>
                                        <li><a class="dropdown-item py-1" href="{{ route('admin.buff.orders.index') }}" style="font-size: 0.85rem;"><i class="fas fa-list-alt me-2" style="color:#ec4899;"></i>{{ __('Đơn Buff') }}</a></li>
                                        <li><a class="dropdown-item py-1" href="{{ route('admin.buff.services.index') }}" style="font-size: 0.85rem;"><i class="fas fa-cogs me-2" style="color:#06b6d4;"></i>{{ __('Dịch vụ Buff') }}</a></li>
                                        <li><a class="dropdown-item py-1" href="{{ route('admin.buff.servers.index') }}" style="font-size: 0.85rem;"><i class="fas fa-server me-2" style="color:#10b981;"></i>{{ __('Máy chủ Buff') }}</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                        @endif
                        <li><a class="dropdown-item" href="{{ route('home') }}"><i class="fa-solid fa-house me-2 text-primary"></i>{{ __('Trang chủ') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('shop') }}"><i class="fa-solid fa-store me-2 text-primary"></i>{{ __('Cửa hàng') }}</a></li>
                        <li><a class="dropdown-item fw-bold" href="{{ route('vpn.index') }}" style="color: #00bcd4;"><i class="fa-solid fa-network-wired me-2"></i>{{ __('VPN & Proxy') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('blog.index') }}"><i class="fa-solid fa-newspaper me-2 text-primary"></i>{{ __('Blog') }}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        
                        {{-- Collapsible Dịch vụ & Tiện ích --}}
                        <li>
                            <a class="dropdown-item d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#quickLinksCollapse" role="button" aria-expanded="false" aria-controls="quickLinksCollapse" onclick="event.stopPropagation();">
                                <span><i class="fas fa-link me-2 text-primary"></i>{{ __('Dịch vụ & Tiện ích') }}</span>
                                <i class="fas fa-chevron-down ms-2" style="font-size: 0.75rem;"></i>
                            </a>
                            <div class="collapse px-2" id="quickLinksCollapse" onclick="event.stopPropagation();">
                                <ul class="list-unstyled ps-3 bg-light rounded py-1 my-1">
                                    @if(Auth::guard('affiliate')->check())
                                        <li><a class="dropdown-item py-1 fw-bold text-primary" href="{{ route('affiliate.dashboard') }}" style="font-size: 0.85rem;"><i class="fas fa-handshake me-2"></i>{{ __('Dashboard CTV') }}</a></li>
                                    @else
                                        <li><a class="dropdown-item py-1" href="{{ route('affiliate.login') }}" style="font-size: 0.85rem;"><i class="fas fa-handshake me-2"></i>{{ __('Đăng ký CTV') }}</a></li>
                                    @endif
                                    <li><a class="dropdown-item py-1 fw-bold" href="{{ route('buff.index') }}" style="color: #ff5e00; font-size: 0.85rem;"><i class="fas fa-rocket me-2"></i>{{ __('Buff Mạng XH') }}</a></li>
                                    <li><a class="dropdown-item py-1" href="{{ route('web-design') }}" style="font-size: 0.85rem;"><i class="fa-solid fa-code me-2 text-primary"></i>{{ __('Thiết Kế Website') }}</a></li>
                                    <li><a class="dropdown-item py-1" href="{{ route('card-exchange.index') }}" style="font-size: 0.85rem;"><i class="fas fa-exchange-alt me-2 text-warning"></i>{{ __('Đổi thẻ cào') }}</a></li>
                                    <li><a class="dropdown-item py-1" href="{{ route('community.index') }}" style="font-size: 0.85rem;"><i class="fas fa-users me-2 text-success"></i>{{ __('Cộng đồng') }}</a></li>
                                    <li><a class="dropdown-item py-1 fw-bold" href="{{ \App\Models\SiteSetting::getValue('zalo_group_link', 'https://zalo.me/g/ptarfhnomeuotiyk7cot') }}" target="_blank" style="color: #0068ff; font-size: 0.85rem;"><i class="fas fa-users me-2"></i>{{ __('Nhóm Zalo') }}</a></li>
                                    <li><a class="dropdown-item py-1" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#quickContactModal" style="font-size: 0.85rem;"><i class="fa-solid fa-headset me-2 text-primary"></i>{{ __('Liên hệ') }}</a></li>
                                </ul>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        
                        <li><a class="dropdown-item" href="{{ route('minigame.index') }}"><i class="fas fa-gamepad me-2 text-danger"></i>{{ __('Vòng xoay may mắn') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.account') }}"><i class="fas fa-user me-2"></i>{{ __('Tài khoản') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.orders') }}"><i class="fas fa-box me-2"></i>{{ __('Đơn hàng') }}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>{{ __('Đăng xuất') }}</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="nav-icon-btn" title="{{ __('Đăng nhập') }}" aria-label="{{ __('Đăng nhập') }}">
                    <i class="fa-solid fa-right-to-bracket"></i>
                </a>
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
    <a href="{{ \App\Helpers\SupportHelper::getZaloLink() }}" target="_blank" class="mobile-nav-item">
        <i class="fa-solid fa-headset"></i>
        <span>{{ __('Hỗ trợ') }}</span>
    </a>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateUnreadChat() {
            fetch('{{ route('chat.unread-count') }}')
                .then(res => res.json())
                .then(data => {
                    const badges = [document.getElementById('navChatBadge'), document.getElementById('mobileChatBadge')];
                    badges.forEach(badge => {
                        if (badge) {
                            if (data.unread > 0) {
                                badge.textContent = data.unread;
                                badge.style.display = 'inline-block';
                            } else {
                                badge.style.display = 'none';
                            }
                        }
                    });
                })
                .catch(err => console.error('Chat unread count error:', err));
        }

        @auth
            updateUnreadChat();
            setInterval(updateUnreadChat, 10000); // Check every 10s
        @endauth
    });
</script>

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

                    {{-- Item 2: Fanpage --}}
                    <a href="{{ \App\Helpers\SupportHelper::getFacebookLink() }}" 
                       target="_blank" 
                       class="d-flex align-items-center gap-3 p-3 text-decoration-none bg-white rounded-3 contact-modal-item"
                       style="border: 1px solid #e5e7eb; transition: all 0.2s ease;">
                        <div class="contact-modal-icon-wrap d-flex align-items-center justify-content-center" 
                             style="width: 48px; height: 48px; border-radius: 12px; background: #e7f3ff; color: #1877f2; min-width: 48px; font-size: 18px;">
                            <i class="fa-brands fa-facebook"></i>
                        </div>
                        <div class="flex-grow-1 text-start">
                            <h6 class="fw-bold mb-1" style="color: #1f2937; font-size: 14px;">{{ __('FANPAGE FACEBOOK') }}</h6>
                            <p class="mb-0 text-muted" style="font-size: 12px;">{{ __('Gửi tin nhắn qua Fanpage chính thức') }}</p>
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
