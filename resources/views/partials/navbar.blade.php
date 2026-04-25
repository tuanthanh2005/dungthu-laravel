@php
    $menuHome        = \App\Models\SiteSetting::getValue('menu_home', '1') === '1';
    $menuShop        = \App\Models\SiteSetting::getValue('menu_shop', '1') === '1';
    $menuBlog        = \App\Models\SiteSetting::getValue('menu_blog', '1') === '1';
    $menuCart        = \App\Models\SiteSetting::getValue('menu_cart', '1') === '1';
    $menuWebdesign   = \App\Models\SiteSetting::getValue('menu_webdesign', '1') === '1';
    $menuBuff        = \App\Models\SiteSetting::getValue('menu_buff', '1') === '1';
    $menuCommunity   = \App\Models\SiteSetting::getValue('menu_community', '1') === '1';
    $menuCardExchange = \App\Models\SiteSetting::getValue('menu_card_exchange', '1') === '1';
@endphp

<nav class="navbar navbar-expand-lg navbar-techfeed sticky-top" id="mainNavbar">
    <div class="container-fluid px-3 px-xl-5">
        {{-- Logo --}}
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <div class="brand-icon">
                <i class="fa-solid fa-bolt"></i>
            </div>
            <span>DungThu<span class="brand-dot">.com</span></span>
        </a>

        {{-- Desktop Nav Links --}}
        <div class="d-none d-lg-flex align-items-center gap-1 ms-3">
            @if($menuHome)
            <a href="{{ route('home') }}" class="nav-text-link {{ request()->routeIs('home') ? 'active' : '' }}">
                Trang chủ
            </a>
            @endif
            @if($menuShop)
            <a href="{{ route('shop') }}" class="nav-text-link {{ request()->routeIs('shop') ? 'active' : '' }}">
                Cửa hàng
            </a>
            @endif
            @if($menuBlog)
            <a href="{{ route('blog.index') }}" class="nav-text-link {{ request()->routeIs('blog.*') ? 'active' : '' }}">
                Blog
            </a>
            @endif
            @if($menuWebdesign)
            <a href="{{ route('web-design') }}" class="nav-text-link {{ request()->routeIs('web-design') ? 'active' : '' }}">
                Thiết kế WS
            </a>
            @endif
            @if($menuCardExchange)
            <a href="{{ route('card-exchange.index') }}" class="nav-text-link {{ request()->routeIs('card-exchange.*') ? 'active' : '' }}">
                Đổi thẻ cào
            </a>
            @endif
            @if($menuBuff)
            <a href="{{ route('buff.index') }}" class="nav-text-link {{ request()->routeIs('buff.*') ? 'active' : '' }}" style="color: #ff5e00; font-weight: 700;">
                Buff Mạng XH
            </a>
            @endif
            @if($menuCommunity)
            <a href="{{ route('community.index') }}" class="nav-text-link {{ request()->routeIs('community.*') ? 'active' : '' }}">
                Cộng đồng
            </a>
            @endif
            <a href="{{ route('affiliate.login') }}" class="nav-text-link {{ request()->is('cong-tac-vien*') ? 'active' : '' }}">
                Cộng tác viên
            </a>
        </div>

        {{-- Search Bar (desktop) --}}
        <div class="mx-auto d-none d-lg-flex search-bar-wrap align-items-center">
            <form class="search-bar-inner" action="{{ route('shop') }}" method="GET">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" name="search" class="search-input" 
                       placeholder="Tìm kiếm tin tức, sản phẩm, đánh giá..."
                       value="{{ request('search') }}">
            </form>
        </div>

        {{-- Right Actions --}}
        <div class="d-flex align-items-center gap-2 gap-sm-3">
            {{-- Cart --}}
            @if($menuCart)
            <a href="{{ route('cart.index') }}" class="nav-icon-btn position-relative" aria-label="Giỏ hàng">
                <i class="fa-solid fa-cart-shopping"></i>
                @php $cartCount = count(session('cart', [])); @endphp
                @if($cartCount > 0)
                    <span class="nav-badge">{{ $cartCount }}</span>
                @endif
            </a>
            @endif

            {{-- Mobile Search --}}
            <button class="nav-icon-btn d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSearchBar" aria-label="Tìm kiếm">
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
                            <li><a class="dropdown-item" href="/admin"><i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard Admin</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.menu-settings') }}"><i class="fas fa-sliders-h me-2 text-warning"></i>Quản lý Menu</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="px-3 pb-1" style="font-size:0.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.5px;">Quản lý Buff</li>
                            <li><a class="dropdown-item" href="{{ route('admin.buff.dashboard') }}"><i class="fas fa-chart-line me-2" style="color:#8b5cf6;"></i>Buff Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.buff.orders.index') }}"><i class="fas fa-list-alt me-2" style="color:#ec4899;"></i>Đơn Buff</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.buff.services.index') }}"><i class="fas fa-cogs me-2" style="color:#06b6d4;"></i>Dịch vụ Buff</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.buff.servers.index') }}"><i class="fas fa-server me-2" style="color:#10b981;"></i>Máy chủ Buff</a></li>
                            <li><hr class="dropdown-divider"></li>
                        @endif
                        @if(Auth::guard('affiliate')->check())
                            <li><a class="dropdown-item fw-bold text-primary" href="{{ route('affiliate.dashboard') }}"><i class="fas fa-handshake me-2"></i>Dashboard CTV</a></li>
                            <li><hr class="dropdown-divider"></li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('affiliate.login') }}"><i class="fas fa-handshake me-2"></i>Đăng ký CTV</a></li>
                        @endif
                        @if($menuCardExchange)
                            <li class="d-lg-none"><a class="dropdown-item" href="{{ route('card-exchange.index') }}"><i class="fas fa-exchange-alt me-2 text-warning"></i>Đổi thẻ cào</a></li>
                        @endif
                        @if($menuBuff)
                            <li class="d-lg-none"><a class="dropdown-item fw-bold" href="{{ route('buff.index') }}" style="color: #ff5e00;"><i class="fas fa-rocket me-2"></i>Buff Mạng XH</a></li>
                        @endif
                        @if($menuCommunity)
                            <li class="d-lg-none"><a class="dropdown-item" href="{{ route('community.index') }}"><i class="fas fa-users me-2 text-success"></i>Cộng đồng</a></li>
                        @endif
                        <li class="d-lg-none"><hr class="dropdown-divider"></li>

                        <li><a class="dropdown-item" href="{{ route('user.account') }}"><i class="fas fa-user me-2"></i>Tài khoản</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.orders') }}"><i class="fas fa-box me-2"></i>Đơn hàng</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn-login">Đăng Nhập</a>
            @endauth
        </div>
    </div>

    {{-- Mobile Search Bar (collapsed) --}}
    <div class="collapse w-100" id="mobileSearchBar">
        <div class="px-3 pb-2">
            <form class="search-bar-inner w-100" action="{{ route('shop') }}" method="GET">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" name="search" class="search-input" placeholder="Tìm kiếm..." value="{{ request('search') }}">
            </form>
        </div>
    </div>
</nav>

{{-- Mobile Bottom Nav --}}
<nav class="mobile-bottom-nav d-lg-none">
    @if($menuHome)
    <a href="{{ route('home') }}" class="mobile-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="fa-solid fa-house"></i>
        <span>Trang chủ</span>
    </a>
    @endif
    @if($menuShop)
    <a href="{{ route('shop') }}" class="mobile-nav-item {{ request()->routeIs('shop') ? 'active' : '' }}">
        <i class="fa-solid fa-store"></i>
        <span>Cửa hàng</span>
    </a>
    @endif
    @if($menuBlog)
    <a href="{{ route('blog.index') }}" class="mobile-nav-item {{ request()->routeIs('blog.*') ? 'active' : '' }}">
        <i class="fa-solid fa-newspaper"></i>
        <span>Blog</span>
    </a>
    @endif
    @if($menuCart)
    <a href="{{ route('cart.index') }}" class="mobile-nav-item position-relative {{ request()->routeIs('cart.*') ? 'active' : '' }}">
        <i class="fa-solid fa-cart-shopping"></i>
        @if(isset($cartCount) && $cartCount > 0)
            <span class="nav-badge">{{ $cartCount }}</span>
        @endif
        <span>Giỏ hàng</span>
    </a>
    @endif
    @if($menuWebdesign)
    <a href="{{ route('web-design') }}" class="mobile-nav-item {{ request()->routeIs('web-design') ? 'active' : '' }}">
        <i class="fa-solid fa-palette"></i>
        <span>Thiết kế</span>
    </a>
    @endif
    {{--
    <a href="{{ route('affiliate.login') }}" class="mobile-nav-item {{ request()->is('cong-tac-vien*') ? 'active' : '' }}">
        <i class="fa-solid fa-handshake"></i>
        <span>CTV</span>
    </a>
    --}}
</nav>