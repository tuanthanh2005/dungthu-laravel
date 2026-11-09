<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaptchaController extends Controller
{
    /**
     * Generate an SVG captcha image and store the code in session.
     */
    public function generate(Request $request)
    {
        $chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $length = 5;
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }

        // Lưu mã vào session dưới dạng chữ thường để so sánh không phân biệt hoa thường
        $request->session()->put('register_captcha', strtolower($code));

        $width = 135;
        $height = 42;

        $colors = ['#1e293b', '#2563eb', '#7c3aed', '#059669', '#dc2626', '#d97706', '#0891b2'];
        $lineColors = ['#cbd5e1', '#94a3b8', '#a5b4fc', '#fca5a5', '#86efac'];

        // Bắt đầu tạo SVG
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">';
        $svg .= '<defs>';
        $svg .= '<linearGradient id="bgGrad" x1="0%" y1="0%" x2="100%" y2="100%">';
        $svg .= '<stop offset="0%" stop-color="#f8fafc" />';
        $svg .= '<stop offset="100%" stop-color="#e2e8f0" />';
        $svg .= '</linearGradient>';
        $svg .= '</defs>';

        // Background
        $svg .= '<rect width="100%" height="100%" rx="8" fill="url(#bgGrad)" stroke="#cbd5e1" stroke-width="1"/>';

        // Đường kẻ nhiễu ngẫu nhiên (noise lines)
        for ($i = 0; $i < 4; $i++) {
            $x1 = random_int(0, 30);
            $y1 = random_int(5, 37);
            $cx = random_int(40, 95);
            $cy = random_int(5, 37);
            $x2 = random_int(100, $width);
            $y2 = random_int(5, 37);
            $strokeColor = $lineColors[array_rand($lineColors)];
            $strokeWidth = random_int(12, 20) / 10;
            $svg .= '<path d="M ' . $x1 . ' ' . $y1 . ' Q ' . $cx . ' ' . $cy . ' ' . $x2 . ' ' . $y2 . '" fill="none" stroke="' . $strokeColor . '" stroke-width="' . $strokeWidth . '" opacity="0.8"/>';
        }

        // Chấm nhiễu (noise dots)
        for ($i = 0; $i < 16; $i++) {
            $dx = random_int(4, $width - 4);
            $dy = random_int(4, $height - 4);
            $dr = random_int(10, 20) / 10;
            $dotColor = $lineColors[array_rand($lineColors)];
            $svg .= '<circle cx="' . $dx . '" cy="' . $dy . '" r="' . $dr . '" fill="' . $dotColor . '" opacity="0.7"/>';
        }

        // Ký tự captcha
        $xStart = 16;
        $xGap = 22;
        for ($i = 0; $i < $length; $i++) {
            $char = $code[$i];
            $charColor = $colors[array_rand($colors)];
            $rot = random_int(-22, 22);
            $cx = $xStart + ($i * $xGap);
            $cy = random_int(26, 30);
            $fontSize = random_int(22, 25);

            $svg .= '<text x="' . $cx . '" y="' . $cy . '" ';
            $svg .= 'font-family="Arial, Helvetica, sans-serif" font-size="' . $fontSize . '" font-weight="900" ';
            $svg .= 'fill="' . $charColor . '" ';
            $svg .= 'transform="rotate(' . $rot . ' ' . $cx . ' ' . ($cy - 8) . ')" ';
            $svg .= 'text-anchor="middle">';
            $svg .= htmlspecialchars($char, ENT_QUOTES, 'UTF-8');
            $svg .= '</text>';
        }

        // Đường gạch ngang bảo vệ xuyên qua chữ
        $svg .= '<line x1="5" y1="' . random_int(18, 24) . '" x2="' . ($width - 5) . '" y2="' . random_int(18, 24) . '" stroke="#94a3b8" stroke-width="1.2" stroke-dasharray="3,2" opacity="0.6"/>';
        $svg .= '</svg>';

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Sat, 01 Jan 2000 00:00:00 GMT',
        ]);
    }
}

