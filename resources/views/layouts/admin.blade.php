<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — DungThu</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/dungthu.png') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    @stack('styles')
</head>
<body class="admin-body">

<!-- ========================
     Sidebar
     ======================== -->
<aside class="admin-sidebar" id="adminSidebar">

    <!-- Logo -->
    <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <i class="fas fa-shield-alt"></i>
        </div>
        <span class="sidebar-logo-text">DungThu Admin</span>
    </a>

    <!-- Navigation -->
    <nav class="sidebar-nav">

        <!-- Tổng quan -->
        <div class="sidebar-section-label">Tổng quan</div>
        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-th-large"></i></span>
            <span class="nav-text">Dashboard</span>
        </a>

        <div class="sidebar-divider"></div>

        <!-- Sản phẩm -->
        <div class="sidebar-section-label">Sản phẩm</div>
        <a href="{{ route('admin.products') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-box"></i></span>
            <span class="nav-text">Sản phẩm</span>
        </a>
        <a href="{{ route('admin.categories') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-layer-group"></i></span>
            <span class="nav-text">Danh mục</span>
        </a>
        <a href="{{ route('admin.features') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.features*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-star"></i></span>
            <span class="nav-text">Tính năng SP</span>
        </a>
        <a href="{{ route('admin.products', ['flash_sale' => 1]) }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.products') && request('flash_sale') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-bolt"></i></span>
            <span class="nav-text">Flash Sale</span>
        </a>

        <div class="sidebar-divider"></div>

        <!-- Buff Service -->
        <div class="sidebar-section-label">Buff Service</div>
        <a href="{{ route('admin.buff.dashboard') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.buff.dashboard') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-chart-line"></i></span>
            <span class="nav-text">Buff Dashboard</span>
        </a>
        <a href="{{ route('admin.buff.orders.index') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.buff.orders*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-list-ol"></i></span>
            <span class="nav-text">Buff Đơn hàng</span>
            <span class="nav-badge" id="sidebarBuffOrderBadge" style="display: none;">0</span>
        </a>
        <a href="{{ route('admin.buff.services.index') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.buff.services*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-cogs"></i></span>
            <span class="nav-text">Buff Dịch vụ</span>
        </a>
        <a href="{{ route('admin.buff.servers.index') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.buff.servers*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-server"></i></span>
            <span class="nav-text">Buff Servers</span>
        </a>
        <a href="{{ route('admin.buff.prices.index') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.buff.prices*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-money-bill-wave"></i></span>
            <span class="nav-text">Buff Bảng giá</span>
        </a>

        <div class="sidebar-divider"></div>

        <!-- Đơn hàng -->
        <div class="sidebar-section-label">Đơn hàng & Giao dịch</div>
        <a href="{{ route('admin.orders') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-shopping-cart"></i></span>
            <span class="nav-text">Đơn hàng</span>
            <span class="nav-badge" id="sidebarOrderBadge" style="display: none;">0</span>
        </a>
        <a href="{{ route('admin.card-exchanges') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.card-exchanges*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-credit-card"></i></span>
            <span class="nav-text">Đổi thẻ cào</span>
            <span class="nav-badge" id="sidebarCardExchangeBadge" style="display: none;">0</span>
        </a>
        <a href="{{ route('admin.abandoned-carts') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.abandoned-carts*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-shopping-basket"></i></span>
            <span class="nav-text">Giỏ bỏ quên</span>
            <span class="nav-badge" id="sidebarAbandonedCartBadge" style="display: none;">0</span>
        </a>
        <a href="{{ route('admin.preorders') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.preorders*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-hourglass-half"></i></span>
            <span class="nav-text">Pre-orders</span>
            <span class="nav-badge" id="sidebarPreorderBadge" style="display: none;">0</span>
        </a>
        <a href="{{ route('admin.customer-durations') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.customer-durations*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-user-clock"></i></span>
            <span class="nav-text">Thời hạn khách</span>
        </a>

        <div class="sidebar-divider"></div>

        <!-- Người dùng -->
        <div class="sidebar-section-label">Người dùng</div>
        <a href="{{ route('admin.users') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-users"></i></span>
            <span class="nav-text">Danh sách User</span>
        </a>
        <a href="{{ route('admin.affiliates.index') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.affiliates*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-handshake"></i></span>
            <span class="nav-text">Cộng tác viên</span>
            <span class="nav-badge" id="sidebarAffiliateBadge" style="display: none;">0</span>
        </a>

        <div class="sidebar-divider"></div>

        <!-- Nội dung -->
        <div class="sidebar-section-label">Nội dung</div>
        <a href="{{ route('admin.blogs') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.blogs*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-blog"></i></span>
            <span class="nav-text">Blog</span>
        </a>
        <a href="{{ route('admin.blog-topics') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.blog-topics*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-tags"></i></span>
            <span class="nav-text">Chủ đề Blog</span>
        </a>
        <a href="{{ route('admin.seo-keywords') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.seo-keywords*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-search"></i></span>
            <span class="nav-text">Từ khóa SEO</span>
        </a>

        <div class="sidebar-divider"></div>

        <!-- Hệ thống -->
        <div class="sidebar-section-label">Hệ thống</div>
        <a href="{{ route('admin.chat.index') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.chat*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-comments"></i></span>
            <span class="nav-text">Chat</span>
            <span class="nav-badge" id="sidebarChatBadge" style="display: none;">0</span>
        </a>

        <a href="{{ route('admin.system-notifications') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.system-notifications*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-bullhorn"></i></span>
            <span class="nav-text">Thông báo HT</span>
        </a>
        <a href="{{ route('admin.google-indexing.index') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.google-indexing*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fab fa-google"></i></span>
            <span class="nav-text">Google Indexing</span>
        </a>
        <a href="{{ route('admin.menu-settings') }}"
           class="sidebar-nav-item {{ request()->routeIs('admin.menu-settings*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-sliders-h"></i></span>
            <span class="nav-text">Menu Settings</span>
        </a>

    </nav>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <a href="{{ url('/') }}" class="sidebar-nav-item" target="_blank">
            <span class="nav-icon"><i class="fas fa-external-link-alt"></i></span>
            <span class="nav-text">Xem trang web</span>
        </a>
        <a href="javascript:void(0)" onclick="adminLockManual()" class="sidebar-nav-item" style="color: #fc8181;">
            <span class="nav-icon"><i class="fas fa-lock"></i></span>
            <span class="nav-text">Khóa Admin</span>
        </a>
    </div>

</aside>

<!-- Sidebar Overlay (Mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ========================
     Topbar
     ======================== -->
<header class="admin-topbar" id="adminTopbar">

    <!-- Toggle Button -->
    <button class="sidebar-toggle-btn" id="sidebarToggle" title="Thu/mở sidebar">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Breadcrumb / Page Title -->
    <div class="topbar-breadcrumb">
        <span><i class="fas fa-home" style="font-size:12px"></i></span>
        <span class="separator">/</span>
        <span>Admin</span>
        @hasSection('breadcrumb')
            <span class="separator">/</span>
            @yield('breadcrumb')
        @endif
        <span class="separator">&nbsp;—&nbsp;</span>
        <span class="page-title">@yield('page_title', 'Dashboard')</span>
    </div>

    <!-- Actions -->
    <div class="topbar-actions">
        <!-- View Site -->
        <a href="{{ url('/') }}" class="topbar-icon-btn d-none d-sm-flex" target="_blank" title="Xem trang web">
            <i class="fas fa-globe"></i>
        </a>

        <!-- Lock -->
        <a href="javascript:void(0)" onclick="adminLockManual()" class="topbar-icon-btn" title="Khóa Admin" style="border-color: #fed7d7; color: #fc8181;">
            <i class="fas fa-lock"></i>
        </a>

        <!-- User -->
        <div class="topbar-user">
            <div class="topbar-user-avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <span class="topbar-user-name">{{ auth()->user()->name ?? 'Admin' }}</span>
        </div>
    </div>

</header>

<!-- ========================
     Main Content
     ======================== -->
<main class="admin-main" id="adminMain">

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="admin-alert success mb-4">
            <i class="fas fa-check-circle fa-lg"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="ms-auto btn-close btn-close-sm" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="admin-alert error mb-4">
            <i class="fas fa-exclamation-circle fa-lg"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="ms-auto btn-close btn-close-sm" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="admin-alert info mb-4">
            <i class="fas fa-info-circle fa-lg"></i>
            <span>{{ session('info') }}</span>
            <button type="button" class="ms-auto btn-close btn-close-sm" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="admin-alert warning mb-4">
            <i class="fas fa-exclamation-triangle fa-lg"></i>
            <span>{{ session('warning') }}</span>
            <button type="button" class="ms-auto btn-close btn-close-sm" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')

</main>

<!-- ========================
     Scripts
     ======================== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ========================
// Sidebar Toggle Logic
// ========================
const sidebar = document.getElementById('adminSidebar');
const topbar = document.getElementById('adminTopbar');
const mainContent = document.getElementById('adminMain');
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebarOverlay = document.getElementById('sidebarOverlay');

function isMobile() {
    return window.innerWidth < 992;
}

// Load saved state
const sidebarCollapsed = localStorage.getItem('adminSidebarCollapsed') === 'true';
if (!isMobile() && sidebarCollapsed) {
    sidebar.classList.add('collapsed');
    topbar.classList.add('collapsed');
    mainContent.classList.add('collapsed');
}

sidebarToggle.addEventListener('click', function() {
    if (isMobile()) {
        // Mobile: show/hide overlay
        sidebar.classList.toggle('mobile-open');
        sidebarOverlay.classList.toggle('active');
    } else {
        // Desktop: collapse/expand
        const isCollapsed = sidebar.classList.toggle('collapsed');
        topbar.classList.toggle('collapsed', isCollapsed);
        mainContent.classList.toggle('collapsed', isCollapsed);
        localStorage.setItem('adminSidebarCollapsed', isCollapsed);
    }
});

sidebarOverlay.addEventListener('click', function() {
    sidebar.classList.remove('mobile-open');
    sidebarOverlay.classList.remove('active');
});

// Resize handler
window.addEventListener('resize', function() {
    if (!isMobile()) {
        sidebar.classList.remove('mobile-open');
        sidebarOverlay.classList.remove('active');
    }
});

// ========================
// Admin Lock
// ========================
function adminLockManual() {
    Swal.fire({
        title: @json(__('Khóa Admin?')),
        text: @json(__('Bạn sẽ cần nhập PIN để mở lại.')),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#6b7280',
        confirmButtonText: @json(__('Khóa ngay')),
        cancelButtonText: @json(__('Hủy')),
    }).then((result) => {
        if (result.isConfirmed) {
            sessionStorage.removeItem('admin_unlocked');
            sessionStorage.removeItem('revenue_unlocked');
            window.location.href = "{{ route('admin.lock') }}";
        }
    });
}

// ========================
// Flash message auto-dismiss
// ========================
document.querySelectorAll('.admin-alert').forEach(function(el) {
    const closeBtn = el.querySelector('.btn-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            el.style.opacity = '0';
            el.style.transform = 'translateY(-8px)';
            el.style.transition = 'all 0.3s ease';
            setTimeout(() => el.remove(), 300);
        });
    }
    // Auto-dismiss after 5s
    setTimeout(function() {
        if (el.parentNode) {
            el.style.opacity = '0';
            el.style.transition = 'opacity 0.5s ease';
            setTimeout(() => el.remove(), 500);
        }
    }, 5000);
});

// ========================
// Admin PIN for POST/PUT/DELETE
// ========================
(function () {
    function isAdminAction(form) {
        try {
            const action = form.getAttribute('action') || window.location.href;
            const url = new URL(action, window.location.origin);
            return url.pathname.startsWith('/admin');
        } catch (e) { return false; }
    }

    function getIntendedMethod(form) {
        const method = (form.getAttribute('method') || 'get').toLowerCase();
        const override = form.querySelector('input[name="_method"]');
        return (override ? override.value : method).toUpperCase();
    }

    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (!(form instanceof HTMLFormElement)) return;
        if (!isAdminAction(form)) return;
        if (form.dataset.adminPinSkip === '1') return;

        const intended = getIntendedMethod(form);
        if (['GET', 'HEAD', 'OPTIONS'].includes(intended)) return;
        if (form.dataset.adminPinVerified === '1') return;

        e.preventDefault();

        Swal.fire({
            title: @json(__('Xác nhận thao tác')),
            text: @json(__('Nhập mã PIN 3 số để xác nhận:')),
            input: 'password',
            inputAttributes: { maxlength: 3, pattern: '[0-9]{3}', inputmode: 'numeric' },
            showCancelButton: true,
            confirmButtonText: @json(__('Xác nhận')),
            cancelButtonText: @json(__('Hủy')),
            confirmButtonColor: '#667eea',
        }).then((result) => {
            if (!result.isConfirmed) return;
            const pin = result.value;
            if (!/^\d{3}$/.test(pin)) {
                Swal.fire({ icon: 'error', title: @json(__('Lỗi')), text: @json(__('Mã PIN phải đúng 3 số.')) });
                return;
            }
            let input = form.querySelector('input[name="admin_pin"]');
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'admin_pin';
                form.appendChild(input);
            }
            input.value = pin;
            form.dataset.adminPinVerified = '1';
            form.submit();
        });
    }, true);
})();
</script>

<style>
    /* Prevent double-tap to zoom on admin devices */
    html, body {
        touch-action: manipulation;
    }
</style>

<!-- Prevent pinch-to-zoom and double-tap zoom via JS -->
<script>
    // 1. Chặn pinch-to-zoom khi di chuyển ngón tay (touchmove)
    document.addEventListener('touchmove', function (event) {
        // event.scale chỉ có trên iOS Safari
        if (event.scale !== 1 && event.scale !== undefined) {
            event.preventDefault();
        }
        // Chặn trên các trình duyệt Android/Chrome
        if (event.touches.length > 1) {
            event.preventDefault();
        }
    }, { passive: false });

    // 2. Chặn khởi động zoom khi chạm nhiều ngón (touchstart)
    document.addEventListener('touchstart', function (event) {
        if (event.touches.length > 1) {
            event.preventDefault();
        }
    }, { passive: false });

</script>

<script>
    // Fetch and update admin sidebar badges/counters
    document.addEventListener('DOMContentLoaded', function() {
        function updateAdminSidebarCounters() {
            fetch('{{ route('admin.sidebar-counters') }}')
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    function updateBadge(id, count) {
                        const badge = document.getElementById(id);
                        if (badge) {
                            if (count > 0) {
                                badge.textContent = count;
                                badge.style.display = 'inline-block';
                            } else {
                                badge.style.display = 'none';
                            }
                        }
                    }
                    
                    updateBadge('sidebarChatBadge', data.unread_chats);
                    updateBadge('sidebarOrderBadge', data.pending_orders);
                    updateBadge('sidebarBuffOrderBadge', data.pending_buff_orders);
                    updateBadge('sidebarCardExchangeBadge', data.pending_card_exchanges);
                    updateBadge('sidebarAbandonedCartBadge', data.abandoned_carts);
                    updateBadge('sidebarPreorderBadge', data.pending_preorders);
                    updateBadge('sidebarAffiliateBadge', data.pending_affiliates_total);
                })
                .catch(err => console.error('Error fetching sidebar counters:', err));
        }
        
        updateAdminSidebarCounters();
        setInterval(updateAdminSidebarCounters, 15000); // refresh every 15 seconds
    });
</script>

@stack('scripts')

</body>
</html>
