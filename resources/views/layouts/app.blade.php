<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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
