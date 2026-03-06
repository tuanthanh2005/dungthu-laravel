<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên Hệ Chúng Tôi - DungThu.com</title>
    <!-- Fonts & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4facfe;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .contact-card {
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            text-decoration: none;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            height: 100%;
        }
        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.1) !important;
            border-color: var(--primary) !important;
        }
        .contact-card:hover .icon-circle {
            transform: scale(1.1);
            transition: transform 0.3s ease;
        }
        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #6c757d;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.2s;
        }
        .back-btn:hover {
            color: var(--primary);
        }
    </style>
</head>
<body>

    <a href="{{ route('home') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i> Về trang chủ
    </a>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center" style="max-width: 800px; width: 100%;">
                <div class="mb-5 d-flex justify-content-center">
                    <div style="background: rgba(79, 172, 254, 0.1); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-headset fs-1" style="color: var(--primary);"></i>
                    </div>
                </div>
                
                <h2 class="fw-bold mb-3" style="color: #2b3452;">Liên Hệ Với Chúng Tôi</h2>
                <p class="text-muted mb-5" style="font-size: 1.1rem; line-height: 1.6;">Hệ thống luôn sẵn sàng hỗ trợ bạn 24/7. Vui lòng chọn một trong các kênh liên lạc dưới đây để được hỗ trợ nhanh nhất.</p>
                
                <div class="row justify-content-center g-4">
                    <!-- Zalo -->
                    <div class="col-12 col-sm-4">
                        <a href="https://zalo.me/0708910952" target="_blank" class="contact-card d-block p-4 rounded-4">
                            <div class="icon-circle mx-auto mb-3" style="width: 70px; height: 70px; background: rgba(0, 104, 255, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #0068ff;">
                                <i class="fas fa-comments fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark">Zalo</h5>
                            <p class="text-muted small mb-0 mt-2">Hỗ trợ qua tin nhắn</p>
                        </a>
                    </div>

                    <!-- Telegram -->
                    <div class="col-12 col-sm-4">
                        <a href="https://t.me/specademy" target="_blank" class="contact-card d-block p-4 rounded-4">
                            <div class="icon-circle mx-auto mb-3" style="width: 70px; height: 70px; background: rgba(0, 136, 204, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #0088cc;">
                                <i class="fab fa-telegram-plane fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark">Telegram</h5>
                            <p class="text-muted small mb-0 mt-2">@specademy</p>
                        </a>
                    </div>

                    <!-- Email -->
                    <div class="col-12 col-sm-4">
                        <a href="mailto:tranthanhtuanfix@gmail.com" class="contact-card d-block p-4 rounded-4">
                            <div class="icon-circle mx-auto mb-3" style="width: 70px; height: 70px; background: rgba(245, 87, 108, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #f5576c;">
                                <i class="fas fa-envelope fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark">Email</h5>
                            <p class="text-muted small mb-0 mt-2" style="word-break: break-all;">tran...x@gmail.com</p>
                        </a>
                    </div>
                </div>
                
                <div class="mt-5 text-muted small">
                    <i class="fas fa-clock me-1"></i> Giờ hoạt động: 8:00 AM - 10:00 PM (Tất cả các ngày trong tuần)
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
