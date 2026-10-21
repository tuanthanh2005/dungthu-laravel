<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Bot Live-Chat SyncViệt</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .container {
            max-width: 560px;
            width: 100%;
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 20px;
            padding: 40px 32px;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(129, 140, 248, 0.3);
            border-radius: 9999px;
            color: #a5b4fc;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            animation: pulse 1.8s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }
        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 12px;
            letter-spacing: -0.02em;
        }
        p {
            color: #94a3b8;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 28px;
        }
        .hint-box {
            background: rgba(15, 23, 42, 0.6);
            border: 1px dashed rgba(148, 163, 184, 0.25);
            border-radius: 12px;
            padding: 16px;
            font-size: 13.5px;
            color: #cbd5e1;
            margin-bottom: 28px;
        }
        .actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-primary {
            background: #4f46e5;
            color: #ffffff;
        }
        .btn-primary:hover {
            background: #4338ca;
            transform: translateY(-1px);
        }
        .btn-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: #e2e8f0;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.14);
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="badge">
            <span class="pulse-dot"></span>
            Test Environment Active
        </div>
        <h1>Thử nghiệm Live-Chat SyncViệt</h1>
        <p>Trang web trống dùng để kiểm tra hoạt động của bong bóng chat đa kênh SyncViệt.</p>
        
        <div class="hint-box">
            👉 Bong bóng chat sẽ tự động hiển thị ở góc dưới màn hình sau khi mã nguồn widget tải xong.
        </div>

        <div class="actions">
            <a href="{{ url('/') }}" class="btn btn-secondary">← Về trang chủ</a>
            <button onclick="window.location.reload()" class="btn btn-primary">Tải lại trang</button>
        </div>
    </div>

    <!-- Bắt đầu đoạn mã Live-Chat SyncViệt -->
    <script>
      (function(d,t) {
        var BASE_URL="https://app.syncviet.com";
        var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
        g.src=BASE_URL+"/widget.js"; g.defer=true; g.async=true;
        g.dataset.inbox="sync_03e07055d70915af34fcb60495bbc144";
        s.parentNode.insertBefore(g,s);
      })(document,"script");
    </script>
    <!-- Kết thúc đoạn mã Live-Chat SyncViệt -->
</body>
</html>
