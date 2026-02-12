<nav class="navbar navbar-expand-lg navbar-light navbar-glass fixed-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold fs-3" href="{{ route('home') }}" style="color: var(--primary);">
            <i class="fas fa-layer-group"></i> DungThu<span class="text-dark">.com</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center nav-wrap">
                <li class="nav-item"><a class="nav-link" href="{{ route('shop') }}">Cửa Hàng</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('community.index') }}">Cộng đồng</a></li>
                <li class="nav-item"><a class="nav-link disabled text-muted opacity-75" href="#" tabindex="-1" aria-disabled="true">Đổi thẻ cào</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="https://tradevip.online/" target="_blank">
                        Tool BlockChain
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('blog.index') }}">Blog</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#contactModal">
                        <i class="fas fa-phone me-1"></i>Liên Hệ
                    </a>
                </li>
                @auth
                <li class="nav-item d-lg-none"><a class="nav-link" href="{{ route('user.orders') }}"><i class="fas fa-box me-2"></i>Đơn hàng</a></li>
                @endauth
                <li class="nav-item">
                    <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                        <i class="fas fa-shopping-cart"></i> Giỏ hàng
                        @php
                            $cart = session('cart', []);
                            $cartCount = count($cart);
                        @endphp
                        @if($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                              style="font-size: 0.65rem; padding: 0.25em 0.5em;">
                            {{ $cartCount }}
                            <span class="visually-hidden">sản phẩm trong giỏ</span>
                        </span>
                        @endif
                    </a>
                </li>
                
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(Auth::user()->role === 'admin')
                            <li><a class="dropdown-item" href="/admin"><i class="fas fa-tachometer-alt me-2"></i> Dashboard Admin</a></li>
                            <li><hr class="dropdown-divider"></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('user.account') }}"><i class="fas fa-user me-2"></i> Tài khoản</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.orders') }}"><i class="fas fa-box me-2"></i> Đơn hàng</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item ms-3">
                        <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">Đăng Nhập</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
