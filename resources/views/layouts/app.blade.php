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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="JXAkwIu8Sp6m3NoBdys1fP9YRH7eeUiiVQ49OEGUSqw" />
    <title>@yield('title', 'Dùng Thử | AI | Blog | Khám Phá')</title>
    <meta name="description" content="@yield('meta_description', 'Dùng Thử - Nền tảng khám phá AI, Blog công nghệ và sản phẩm số hàng đầu Việt Nam. Trải nghiệm & Mua sắm an toàn, chất lượng.')">
    <meta name="keywords" content="@yield('meta_keywords', 'dung thu, dungthu, dungthu.com, dung thu ai, blog cong nghe, mua sam truc tuyen, san pham so, kham pha ai')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Dùng Thử | AI | Blog | Khám Phá')">
    <meta property="og:description" content="@yield('meta_description', 'Dùng Thử - Nền tảng khám phá AI, Blog công nghệ và sản phẩm số hàng đầu Việt Nam. Trải nghiệm & Mua sắm an toàn, chất lượng.')">
    <meta property="og:image" content="@yield('og_image', asset('images/dungthu-seo.png'))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'Dùng Thử | AI | Blog | Khám Phá')">
    <meta property="twitter:description" content="@yield('meta_description', 'Dùng Thử - Nền tảng khám phá AI, Blog công nghệ và sản phẩm số hàng đầu Việt Nam. Trải nghiệm & Mua sắm an toàn, chất lượng.')">
    <meta property="twitter:image" content="@yield('og_image', asset('images/dungthu-seo.png'))">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/dungthu.png') }}">
    
    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- TechFeed Theme -->
    <link rel="stylesheet" href="{{ asset('css/techfeed.css') }}?v={{ filemtime(\App\Helpers\PathHelper::publicRootPath('css/techfeed.css')) }}">

    <!-- Page-specific CSS -->
    @stack('styles')

    <style>
        /* TechFeed global toast overrides */
        .small-toast { font-size: 13px !important; }
        .small-toast-title { font-size: 15px !important; margin-bottom: 5px !important; }
        .small-toast-text { font-size: 13px !important; }
        .swal2-toast .swal2-icon { width: 2em !important; height: 2em !important; margin: 0.5em 0.8em 0.5em 0 !important; }
        .swal2-toast { flex-direction: row !important; align-items: center !important; }

        /* Fix Mobile Zoom & Overflow */
        html, body {
            max-width: 100%;
            overflow-x: hidden;
            position: relative;
        }
        input, select, textarea {
            font-size: 16px !important; /* Prevents auto-zoom on iOS */
        }
    </style>
</head>
<body>
    @yield('seo_h1')


    <!-- Navbar -->
    @include('partials.navbar')

    <!-- Main Content -->
    <main>
        {{-- GLOBAL TOP AD (Desktop only, minimal height) --}}
        <div class="container-fluid d-none d-lg-block pb-3" style="max-width:1200px; margin: 0 auto;">
            <div style="border-bottom: 1px solid #eee; padding: 10px 0; text-align: center; min-height: 90px; overflow: hidden;">
                <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-3065867660863139" data-ad-slot="4989157975" data-ad-format="horizontal" data-full-width-responsive="true"></ins>
                <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
            </div>
        </div>

        @yield('content')

        {{-- GLOBAL PRE-FOOTER AD (System wide) --}}
        <div class="container-fluid py-4" style="max-width:1100px; margin: 0 auto;">
            <div style="background: #fdfdfd; border: 1px dashed #ddd; border-radius: 12px; padding: 15px; text-align: center; min-height: 100px; overflow: hidden;">
                <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-3065867660863139" data-ad-slot="4989157975" data-ad-format="fluid" data-ad-layout="in-article"></ins>
                <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
            </div>
        </div>
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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


