<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đang Bảo Trì – DungThu.com</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #0f0c29;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Animated gradient background */
        .bg-gradient {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            background-size: 400% 400%;
            animation: gradientShift 8s ease infinite;
            z-index: 0;
        }

        @keyframes gradientShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Floating orbs */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            animation: float 10s ease-in-out infinite;
            z-index: 0;
        }

        .orb-1 { width: 500px; height: 500px; background: #6c63ff; top: -100px; left: -150px; animation-delay: 0s; }
        .orb-2 { width: 400px; height: 400px; background: #ff6584; bottom: -80px; right: -100px; animation-delay: -4s; }
        .orb-3 { width: 300px; height: 300px; background: #43e97b; top: 50%; left: 60%; animation-delay: -2s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50%       { transform: translateY(-30px) scale(1.05); }
        }

        /* Particles */
        .particles {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
        }

        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: rgba(255,255,255,0.4);
            border-radius: 50%;
            animation: rise linear infinite;
        }

        @keyframes rise {
            from { transform: translateY(100vh) scale(0); opacity: 1; }
            to   { transform: translateY(-100px) scale(1); opacity: 0; }
        }

        /* Main card */
        .card {
            position: relative;
            z-index: 10;
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 28px;
            padding: 56px 48px;
            max-width: 560px;
            width: 90%;
            text-align: center;
            box-shadow: 0 32px 80px rgba(0,0,0,0.4);
            animation: cardIn 0.7s cubic-bezier(0.34,1.56,0.64,1) both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(40px) scale(0.9); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Icon */
        .icon-wrap {
            width: 100px;
            height: 100px;
            margin: 0 auto 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.6rem;
            color: white;
            position: relative;
            animation: pulse 2.5s ease-in-out infinite;
            box-shadow: 0 0 40px rgba(102,126,234,0.5);
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 30px rgba(102,126,234,0.4); transform: scale(1); }
            50%       { box-shadow: 0 0 60px rgba(102,126,234,0.7); transform: scale(1.05); }
        }

        /* Rotating ring */
        .icon-wrap::before {
            content: '';
            position: absolute;
            inset: -6px;
            border-radius: 50%;
            border: 2px solid transparent;
            border-top-color: rgba(102,126,234,0.8);
            border-right-color: rgba(118,75,162,0.5);
            animation: rotate 2s linear infinite;
        }

        @keyframes rotate {
            to { transform: rotate(360deg); }
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(102,126,234,0.2);
            border: 1px solid rgba(102,126,234,0.4);
            color: #a5b4fc;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 5px 14px;
            border-radius: 20px;
            margin-bottom: 20px;
        }

        h1 {
            color: #ffffff;
            font-size: 2rem;
            font-weight: 900;
            line-height: 1.2;
            margin-bottom: 14px;
        }

        h1 span {
            background: linear-gradient(135deg, #667eea, #f093fb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            color: rgba(255,255,255,0.6);
            font-size: 0.96rem;
            line-height: 1.65;
            margin-bottom: 36px;
        }

        /* Status bar */
        .status-bar {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 14px;
            padding: 18px 22px;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            gap: 14px;
            text-align: left;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #facc15;
            flex-shrink: 0;
            box-shadow: 0 0 8px #facc15;
            animation: blink 1.4s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.3; }
        }

        .status-text {
            color: rgba(255,255,255,0.75);
            font-size: 0.88rem;
            line-height: 1.5;
        }

        .status-text strong {
            color: #facc15;
            display: block;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        /* Buttons */
        .btn-group {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.92rem;
            padding: 13px 28px;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(102,126,234,0.35);
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 32px rgba(102,126,234,0.5);
            color: white;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.92rem;
            padding: 13px 24px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: rgba(255,255,255,0.15);
            color: white;
            transform: translateY(-2px);
        }

        /* Logo */
        .logo {
            position: fixed;
            top: 24px;
            left: 24px;
            z-index: 20;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .logo-icon {
            width: 36px;
            height: 36px;
            background: #ff4500;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .logo-text {
            font-weight: 800;
            font-size: 1.15rem;
            color: white;
        }

        .logo-text span { color: rgba(255,255,255,0.5); font-weight: 400; }

        /* Footer note */
        .footer-note {
            position: fixed;
            bottom: 20px;
            color: rgba(255,255,255,0.3);
            font-size: 0.75rem;
            z-index: 10;
        }

        @media (max-width: 480px) {
            .card { padding: 40px 24px; }
            h1 { font-size: 1.6rem; }
        }
    </style>
</head>
<body>

    <div class="bg-gradient"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    {{-- Particles --}}
    <div class="particles" id="particles"></div>

    {{-- Logo --}}
    <a href="{{ route('home') }}" class="logo">
        <div class="logo-icon"><i class="fa-solid fa-bolt"></i></div>
        <div class="logo-text">DungThu<span>.com</span></div>
    </a>

    {{-- Main Card --}}
    <div class="card">
        <div class="icon-wrap">
            <i class="fa-solid fa-wrench"></i>
        </div>

        <div class="badge">
            <i class="fa-solid fa-circle-dot"></i>
            Tạm thời đóng
        </div>

        <h1>Trang đang<br><span>Bảo Trì</span></h1>

        <p class="subtitle">
            Chức năng <strong style="color:rgba(255,255,255,0.9);">{{ $pageName ?? 'này' }}</strong> hiện đang được bảo trì hoặc tạm thời đóng cửa.<br>
            Hệ thống sẽ sớm trở lại, cảm ơn bạn đã kiên nhẫn!
        </p>

        <div class="status-bar">
            <div class="status-dot"></div>
            <div class="status-text">
                <strong>Trạng thái hệ thống</strong>
                Chúng tôi đang nâng cấp tính năng này để mang lại trải nghiệm tốt hơn cho bạn.
            </div>
        </div>

        <div class="btn-group">
            <a href="{{ route('home') }}" class="btn-home">
                <i class="fa-solid fa-house"></i> Về trang chủ
            </a>
            <a href="javascript:history.back()" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="footer-note">© {{ date('Y') }} DungThu.com — Hệ thống đang được cập nhật</div>

    <script>
        // Generate particles
        const container = document.getElementById('particles');
        for (let i = 0; i < 40; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.cssText = `
                left: ${Math.random() * 100}%;
                width: ${Math.random() * 4 + 1}px;
                height: ${Math.random() * 4 + 1}px;
                animation-duration: ${Math.random() * 12 + 6}s;
                animation-delay: ${Math.random() * 10}s;
                opacity: ${Math.random() * 0.5 + 0.1};
            `;
            container.appendChild(p);
        }
    </script>
</body>
</html>
