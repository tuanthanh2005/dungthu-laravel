<nav class="navbar navbar-expand-lg navbar-techfeed sticky-top" id="mainNavbar">
    <div class="container-fluid px-3 px-xl-5">
        {{-- Logo --}}
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <div class="brand-icon">
                <i class="fa-solid fa-bolt"></i>
            </div>
            <span>DungThu<span class="brand-dot">.com</span></span>
        </a>

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
            {{-- Shop Button (desktop) --}}
            <a href="{{ route('shop') }}" class="btn-shop d-none d-sm-flex align-items-center gap-1">
                <i class="fa-solid fa-store"></i>
                <span>Cửa hàng</span>
            </a>

            {{-- Cart --}}
            <a href="{{ route('cart.index') }}" class="nav-icon-btn position-relative" aria-label="Giỏ hàng">
                <i class="fa-solid fa-cart-shopping"></i>
                @php $cartCount = count(session('cart', [])); @endphp
                @if($cartCount > 0)
                    <span class="nav-badge">{{ $cartCount }}</span>
                @endif
            </a>

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
                            <li><hr class="dropdown-divider"></li>
                        @endif
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
    <a href="{{ route('home') }}" class="mobile-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="fa-solid fa-house"></i>
        <span>Trang chủ</span>
    </a>
    <a href="{{ route('shop') }}" class="mobile-nav-item {{ request()->routeIs('shop') ? 'active' : '' }}">
        <i class="fa-solid fa-store"></i>
        <span>Cửa hàng</span>
    </a>
    <a href="{{ route('blog.index') }}" class="mobile-nav-item {{ request()->routeIs('blog.*') ? 'active' : '' }}">
        <i class="fa-solid fa-newspaper"></i>
        <span>Blog</span>
    </a>
    <a href="{{ route('cart.index') }}" class="mobile-nav-item position-relative {{ request()->routeIs('cart.*') ? 'active' : '' }}">
        <i class="fa-solid fa-cart-shopping"></i>
        @if(isset($cartCount) && $cartCount > 0)
            <span class="nav-badge">{{ $cartCount }}</span>
        @endif
        <span>Giỏ hàng</span>
    </a>
    @auth
        <a href="{{ route('user.account') }}" class="mobile-nav-item {{ request()->routeIs('user.*') ? 'active' : '' }}">
            <i class="fa-solid fa-user"></i>
            <span>Tài khoản</span>
        </a>
    @else
        <a href="{{ route('login') }}" class="mobile-nav-item">
            <i class="fa-solid fa-right-to-bracket"></i>
            <span>Đăng nhập</span>
        </a>
    @endauth
</nav>