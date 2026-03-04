<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - DungThu.com')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/dungthu.png') }}">
    
    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: 250px;
            background: #2d3436;
            color: white;
            padding: 2rem 0;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .admin-sidebar .logo {
            padding: 0 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
            border-bottom: 1px solid #636e72;
            padding-bottom: 1.5rem;
        }

        .admin-sidebar .logo h3 {
            font-size: 1.3rem;
            font-weight: 800;
            color: #6c5ce7;
            margin: 0;
        }

        .admin-sidebar .nav-section {
            padding: 1rem 0;
        }

        .admin-sidebar .nav-section-title {
            padding: 0.75rem 1.5rem;
            font-size: 0.85rem;
            font-weight: 700;
            color: #b2bec3;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .admin-sidebar .nav-item {
            padding: 0;
        }

        .admin-sidebar .nav-link {
            padding: 0.75rem 1.5rem;
            color: #dfe6e9;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            background: rgba(108, 92, 231, 0.2);
            color: #6c5ce7;
            border-left-color: #6c5ce7;
        }

        .admin-sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }

        .admin-content {
            margin-left: 250px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .admin-topbar {
            background: white;
            padding: 1.5rem 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .admin-topbar-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2d3436;
            margin: 0;
        }

        .admin-topbar-user {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .admin-topbar-user a {
            color: #2d3436;
            text-decoration: none;
            font-weight: 600;
        }

        .admin-topbar-user a:hover {
            color: #6c5ce7;
        }

        .admin-main {
            flex: 1;
            overflow-y: auto;
            background: #f5f5f5;
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding: 1rem 0;
            }

            .admin-content {
                margin-left: 0;
            }

            .admin-sidebar .nav-link {
                display: inline-block;
                padding: 0.5rem 1rem;
                margin: 0.25rem;
            }

            .admin-topbar {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="logo">
                <h3>🎛️ Admin</h3>
            </div>

            <nav>
                <div class="nav-section">
                    <div class="nav-section-title">Điều Hành</div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="fas fa-home"></i> Trang Chủ
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Buff Service</div>
                    <a href="{{ route('admin.buff.dashboard') }}" class="nav-link">
                        <i class="fas fa-chart-line"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.buff.orders.index') }}" class="nav-link">
                        <i class="fas fa-list"></i> Đơn Hàng
                    </a>
                    <a href="{{ route('admin.buff.services.index') }}" class="nav-link">
                        <i class="fas fa-cog"></i> Dịch Vụ
                    </a>
                    <a href="{{ route('admin.buff.servers.index') }}" class="nav-link">
                        <i class="fas fa-server"></i> Server
                    </a>
                    <a href="{{ route('admin.buff.prices.index') }}" class="nav-link">
                        <i class="fas fa-money-bill"></i> Giá
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Content Area -->
        <div class="admin-content">
            <!-- Top Bar -->
            <div class="admin-topbar">
                <h1 class="admin-topbar-title">@yield('title', 'Admin')</h1>
                <div class="admin-topbar-user">
                    <span>{{ auth()->user()->name ?? 'Admin' }}</span>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Đăng Xuất
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <main class="admin-main">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
