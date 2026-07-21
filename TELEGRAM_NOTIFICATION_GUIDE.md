# Hệ Thống Thông Báo Telegram Tự Động

## Cấu Hình

### Thông Tin Telegram Bot
- **Chat ID**: `YOUR_TELEGRAM_CHAT_ID`
- **Bot Token**: `YOUR_TELEGRAM_BOT_TOKEN`

## Cách Hoạt Động

### 1. Khi Nào Gửi Thông Báo?
Thông báo sẽ được gửi **TỰ ĐỘNG** qua Telegram khi:
- ✅ Khách hàng điền form thông tin và nhấn nút **"Xác nhận đã thanh toán"**
- ✅ Đơn hàng được tạo thành công trong hệ thống

### 2. Nội Dung Thông Báo Bao Gồm:

#### 📦 Thông tin đơn hàng
- Mã đơn hàng
- Loại đơn hàng (QR Deal, Tài liệu, Giao hàng, Digital)
- Thời gian đặt hàng
- Trạng thái đơn hàng

#### 👤 Thông tin khách hàng
- Họ và tên
- Email
- Số điện thoại
- Địa chỉ giao hàng (nếu là đơn vật lý)

#### 🛒 Chi tiết sản phẩm
- Tên sản phẩm
- Số lượng
- Đơn giá
- Thành tiền

#### 💰 Tổng tiền đơn hàng

## Files Đã Thay Đổi

### 1. `app/Helpers/TelegramHelper.php` (MỚI)
- Helper class để gửi thông báo Telegram
- Format message đẹp mắt với HTML
- Log lỗi nếu gửi thất bại

### 2. `app/Http/Controllers/CartController.php`
- Thêm `use App\Helpers\TelegramHelper;`
- Gọi `TelegramHelper::sendNewOrderNotification($order);` sau khi tạo đơn thành công

## Ví Dụ Thông Báo

```
🔔 ĐỚN HÀNG MỚI - XÁC NHẬN ĐÃ THANH TOÁN
━━━━━━━━━━━━━━━━━━━━━━

📦 THÔNG TIN ĐƠN HÀNG
• Mã đơn: #123
• Loại đơn: 📄 Tài liệu
• Thời gian: 22/01/2026 15:30:00
• Trạng thái: Chờ xử lý

👤 THÔNG TIN KHÁCH HÀNG
• Họ tên: Nguyễn Văn A
• Email: customer@example.com
• SĐT: 0987654321

🛒 CHI TIẾT SẢN PHẨM
• Copilot Pro Gói 1 Năm
  ├ Số lượng: 1
  ├ Đơn giá: 148.000đ
  └ Thành tiền: 148.000đ

━━━━━━━━━━━━━━━━━━━━━━
💰 TỔNG TIỀN: 148.000đ
━━━━━━━━━━━━━━━━━━━━━━

⚠️ Khách hàng đã xác nhận thanh toán. Vui lòng kiểm tra và xử lý đơn hàng!
```

## Kiểm Tra

### Test gửi thông báo:
1. Truy cập trang shop
2. Thêm sản phẩm vào giỏ hàng
3. Vào checkout
4. Điền thông tin và nhấn "Xác nhận đã thanh toán"
5. ✅ Kiểm tra Telegram của bạn để nhận thông báo!

## Xử Lý Lỗi

- Nếu gửi Telegram thất bại, hệ thống vẫn tạo đơn hàng bình thường
- Lỗi được ghi log vào `storage/logs/laravel.log`
- Kiểm tra log bằng: `tail -f storage/logs/laravel.log`

## Lưu Ý

⚠️ **Bảo mật**: Bot Token được hardcode trong code. Trong production nên:
- Lưu vào file `.env`
- Sử dụng `config('services.telegram.token')`

⚠️ **Rate Limit**: Telegram có giới hạn số lượng message/giây. Nếu có nhiều đơn cùng lúc, cân nhắc dùng queue.
