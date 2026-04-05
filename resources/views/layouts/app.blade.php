<!DOCTYPE html>
<html lang="vi">
<head>
    <!-- Google AdSense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3065867660863139"
     crossorigin="anonymous"></script>
    <meta name="google-adsense-account" content="ca-pub-3065867660863139">
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-K1EFHMNDGK"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-K1EFHMNDGK');
    </script>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="JXAkwIu8Sp6m3NoBdys1fP9YRH7eeUiiVQ49OEGUSqw" />
    <title>@yield('title', 'DungThu.com - Trải Nghiệm & Mua Sắm')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/dungthu.png') }}">
    
    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Custom CSS -->
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}?v={{ filemtime(\App\Helpers\PathHelper::publicRootPath('css/mobile.css')) }}">

    <style>
        /* ===== GLOBAL MOBILE FIX: chống zoom do overflow-x ===== */
        html, body {
            overflow-x: hidden;
            max-width: 100%;
        }
        /* Ngăn AdSense tràn ra ngoài viewport mobile */
        ins.adsbygoogle {
            max-width: 100% !important;
        }
        /* Ngăn bất kỳ ảnh/phần tử nào tràn */
        img, video, iframe {
            max-width: 100%;
        }
        /* 4. Ngăn nhảy nội dung khi ads load (CLS fix) */
        .ad-block {
            background: #fff;
            border: 1px dashed rgba(102,126,234,0.18);
            border-radius: 12px;
            overflow: hidden;
            margin: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .ad-block.ad-fluid { min-height: 120px; }
        .ad-block.ad-sidebar { min-height: 280px; }
        .ad-label {
            font-size: 0.62rem;
            color: #bbb;
            text-align: center;
            padding: 3px 0;
            background: #fafafa;
            border-bottom: 1px solid #eee;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            width: 100%;
        }

        /* 5. Container padding mobile */
        @media (max-width: 576px) {
            .container, .container-fluid {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }
            .ad-block.ad-sidebar { min-height: 200px; } /* Nhỏ hơn trên mobile */
        }
    </style>

    <style>
        /* Navbar Styling */
        .navbar-glass {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%) !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-bottom: 1px solid #e9ecef;
        }

        .navbar-glass .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary) !important;
            transition: all 0.3s ease;
        }

        .navbar-glass .navbar-brand:hover {
            opacity: 0.8;
        }

        .navbar-glass .navbar-brand span {
            color: #2d3436;
        }

        .navbar-glass .nav-link {
            color: #2d3436 !important;
            font-weight: 600;
            font-size: 0.95rem;
            margin: 0 0.25rem;
            padding: 0.5rem 0.75rem !important;
            border-radius: 6px;
            transition: all 0.3s ease;
            position: relative;
            white-space: nowrap;
        }

        .navbar-glass .nav-link:hover {
            color: var(--primary) !important;
            background: rgba(108, 92, 231, 0.1);
        }

        .navbar-glass .nav-link.active {
            color: var(--primary) !important;
            background: rgba(108, 92, 231, 0.15);
        }

        .navbar-glass .dropdown-toggle::after {
            border: 0.25em solid transparent;
            border-top-color: #2d3436;
            transition: all 0.3s ease;
        }

        .navbar-glass .dropdown-toggle[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }

        .navbar-glass .dropdown-menu {
            border: 1px solid #e9ecef;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 0.5rem;
        }

        .navbar-glass .dropdown-item {
            color: #2d3436;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .navbar-glass .dropdown-item:hover {
            color: var(--primary);
            background: #f8f9fa;
        }

        .navbar-glass .dropdown-item.active {
            background: rgba(108, 92, 231, 0.1);
            color: var(--primary);
        }

        .navbar-glass .dropdown-item.text-danger:hover {
            background: #fff5f5;
            color: #dc3545;
        }

        /* Navbar brand icon */
        .navbar-glass .navbar-brand i {
            color: var(--primary);
            margin-right: 0.35rem;
        }

        /* Nav icons */
        .navbar-glass .nav-link i {
            color: inherit;
        }

        /* Navbar items container - ensure they don't wrap */
        .navbar-glass .navbar-nav {
            gap: 0.25rem;
        }

        /* Button styling in navbar */
        .navbar-glass .btn-primary {

            border: none;
            box-shadow: 0 2px 8px rgba(108, 92, 231, 0.3);
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 0.95rem;
            white-space: nowrap;
            display: inline-block !important;
            visibility: visible !important;
            color: white !important;
        }

        .navbar-glass .btn-primary:hover {
            background: linear-gradient(135deg, #5f4ec7 0%, #9080d8 100%);
            box-shadow: 0 4px 12px rgba(108, 92, 231, 0.4);
            transform: translateY(-1px);
            color: white !important;
        }

        .navbar-glass .btn-primary:focus {
            color: white !important;
            box-shadow: 0 0 0 0.25rem rgba(108, 92, 231, 0.25) !important;
        }

        .navbar-glass .disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Ensure nav item with button is visible */
        .navbar-glass .navbar-nav .nav-item:has(.btn-primary) {
            display: flex;
            align-items: center;
        }

        /* Badge styling */
        .navbar-glass .badge {
            font-weight: 600;
        }

        @media (max-width: 991.98px) {
            .navbar-glass .navbar-collapse {
                background: white;
                margin-top: 0.5rem;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                padding: 0.75rem 0;
            }

            .navbar-glass .nav-link {
                padding: 0.7rem 1rem !important;
            }

            .navbar-glass .navbar-nav {
                gap: 0;
            }

            .navbar-glass .nav-item .btn-primary {
                display: block !important;
                width: auto !important;
                margin-top: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    @include('partials.navbar')

    <!-- Main Content -->
    <main style="padding-top: 66px;">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Chat Widget -->
    @include('partials.chat')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Admin action PIN: require 3-digit code for /admin POST/PUT/DELETE -->
    <script>
        (function () {
            function isAdminAction(form) {
                try {
                    const action = form.getAttribute('action') || window.location.href;
                    const url = new URL(action, window.location.origin);
                    return url.pathname.startsWith('/admin');
                } catch (e) {
                    return false;
                }
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

                const pin = window.prompt('Nhập mã xác nhận để thực hiện thao tác');
                if (pin === null) return;
                if (!/^\d{3}$/.test(pin)) {
                    alert('Mã xác nhận phải đúng 3 số.');
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
            }, true);
        })();
    </script>
    
    <!-- Flash Messages -->
    @if(session()->has('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '{{ session()->pull('success') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            showCloseButton: true,
            timer: 4000,
            timerProgressBar: true,
            width: '350px',
            padding: '12px',
            customClass: {
                popup: 'small-toast',
                title: 'small-toast-title',
                htmlContainer: 'small-toast-text',
                closeButton: 'small-close-btn'
            },
            didOpen: (toast) => {
                toast.style.cursor = 'default';
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: '{{ session('error') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            showCloseButton: true,
            timer: 4000,
            timerProgressBar: true,
            width: '350px',
            padding: '12px',
            customClass: {
                popup: 'small-toast',
                title: 'small-toast-title',
                htmlContainer: 'small-toast-text',
                closeButton: 'small-close-btn'
            },
            didOpen: (toast) => {
                toast.style.cursor = 'default';
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
    </script>
    @endif
    
    <style>
        .small-toast {
            font-size: 13px !important;
        }
        .small-toast-title {
            font-size: 15px !important;
            margin-bottom: 5px !important;
        }
        .small-toast-text {
            font-size: 13px !important;
        }
        .swal2-toast .swal2-icon {
            width: 2em !important;
            height: 2em !important;
            margin: 0.5em 0.8em 0.5em 0 !important;
            font-size: 1.5em !important;
        }
        .swal2-toast .swal2-icon .swal2-icon-content {
            font-size: 1.5em !important;
        }
        .swal2-toast .swal2-success-ring {
            width: 2em !important;
            height: 2em !important;
        }
        .small-close-btn {
            font-size: 18px !important;
            width: 24px !important;
            height: 24px !important;
        }
        .swal2-toast {
            flex-direction: row !important;
            align-items: center !important;
        }
        .swal2-toast .swal2-content {
            flex-grow: 1 !important;
        }
    </style>
    
    @stack('scripts')
</body>
</html>


