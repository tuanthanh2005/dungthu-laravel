<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #667eea; }
        .message-box { background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 30px; border-left: 4px solid #667eea; }
        .btn-wrapper { text-align: center; margin: 30px 0; }
        .btn { display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; text-decoration: none; border-radius: 30px; font-weight: bold; }
        .footer { text-align: center; font-size: 12px; color: #999; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">DungThu.com</div>
        <h2>Xin chào {{ $user->name ?? 'bạn' }},</h2>
    </div>

    <div class="message-box">
        {!! nl2br(e($customMessage)) !!}
    </div>

    <div class="btn-wrapper">
        <a href="{{ url('/') }}" class="btn">Khám phá ngay</a>
    </div>

    <div class="footer">
        <p>Cảm ơn bạn đã luôn đồng hành cùng DungThu.com!</p>
        <p>&copy; {{ date('Y') }} DungThu.com. Tất cả các quyền được bảo lưu.</p>
    </div>
</body>
</html>
