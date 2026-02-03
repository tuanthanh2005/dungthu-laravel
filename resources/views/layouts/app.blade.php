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
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}?v={{ filemtime(public_path('css/mobile.css')) }}">
</head>
<body>
    <!-- Navbar -->
    @include('partials.navbar')

    <!-- Main Content -->
    <main>
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
