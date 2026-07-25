<?php

return [
    // Mã xác nhận 8 số cho thao tác thêm/sửa/xóa trong trang admin
    'action_pin' => env('ADMIN_ACTION_PIN', '12112004'),

    // Mật khẩu cổng bảo mật 2 lớp cho trang Admin
    'gate_password' => env('ADMIN_GATE_PASSWORD', 'sieusuperadmin_secret_gate'),

    // Các route name được miễn nhập mã (ví dụ: cập nhật trạng thái đơn hàng)
    'pin_exempt_route_names' => [
        'admin.orders.update-status',
        'admin.chat.reply',
    ],

    // Số đơn hàng hiển thị cho khách (tạo cảm giác shop đã bán nhiều)
    // Ví dụ: id=1 sẽ hiển thị #415 nếu start=415
    'public_order_start' => (int) env('ADMIN_PUBLIC_ORDER_START', 415),
];
