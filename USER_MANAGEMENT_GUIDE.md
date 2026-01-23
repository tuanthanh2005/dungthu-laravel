# Hệ Thống Quản Lý Người Dùng & Phòng Chống Gian Lận

## ✅ Đã Hoàn Thành

### 1. **Sửa Timezone Việt Nam**
- ✅ Thông báo Telegram hiển thị đúng giờ Việt Nam (UTC+7)
- File: `app/Helpers/TelegramHelper.php`
- Sử dụng: `->timezone('Asia/Ho_Chi_Minh')`

### 2. **Quản Lý Người Dùng**

#### Trang Danh Sách Người Dùng (`/admin/users`)
**Tính năng:**
- ✅ Hiển thị 10 người dùng/trang (phân trang)
- ✅ Thông tin hiển thị:
  - STT tăng dần
  - Họ tên
  - Email
  - Số điện thoại
  - **Số đơn hàng** (badge màu tím, tăng dần theo mỗi lần xác nhận thanh toán)
  - **Tổng tiền** (màu xanh lá, hiển thị tổng chi tiêu)
  - Ngày đăng ký
  - **Icon con mắt** 👁️ để xem chi tiết

**URL:** `http://127.0.0.1:8000/admin/users`

#### Trang Lịch Sử Người Dùng (`/admin/users/{id}/history`)
**Tính năng:**
- ✅ Thông tin tổng quan:
  - Tổng đơn hàng
  - Tổng chi tiêu
  - Trung bình tiền/đơn
  
- ✅ Cảnh báo gian lận:
  - Hiện thông báo đỏ nếu người dùng có > 5 đơn hàng
  - Nhắc nhở admin kiểm tra kỹ
  
- ✅ Chi tiết từng đơn hàng:
  - Mã đơn
  - Ngày giờ đặt (định dạng Việt Nam)
  - Trạng thái
  - Loại đơn (QR, Tài liệu, Giao hàng, Digital)
  - Danh sách sản phẩm với hình ảnh
  - Tổng tiền
  - Thông tin khách hàng đầy đủ

**URL:** `http://127.0.0.1:8000/admin/users/1/history`

### 3. **Phòng Chống Gian Lận**

#### Các Chỉ Số Giám Sát:
1. **Số đơn hàng tăng dần**
   - Mỗi lần click "Xác nhận đã thanh toán" → Số đơn tăng
   - Badge hiển thị rõ ràng
   
2. **Tổng tiền chi tiêu**
   - Tự động tính tổng từ tất cả đơn hàng
   - Hiển thị màu xanh lá
   
3. **Trung bình tiền/đơn**
   - Phát hiện đơn hàng bất thường (quá cao/thấp)
   
4. **Cảnh báo người dùng đáng ngờ**
   - Nếu > 5 đơn hàng → Hiện cảnh báo màu đỏ
   - Admin cần kiểm tra kỹ
   
5. **Xem lịch sử đầy đủ**
   - Icon con mắt 👁️
   - Xem tất cả đơn hàng đã đặt
   - Kiểm tra pattern mua hàng

#### Dấu Hiệu Gian Lận Cần Chú Ý:
- ⚠️ Nhiều đơn hàng cùng thời điểm
- ⚠️ Đơn hàng có giá trị bất thường
- ⚠️ Email/SĐT giống nhau nhưng tên khác
- ⚠️ Mua cùng sản phẩm nhiều lần
- ⚠️ Tổng chi tiêu quá cao trong thời gian ngắn

## 📁 Files Đã Tạo/Sửa

### Tạo Mới:
1. `resources/views/admin/users/index.blade.php` - Danh sách người dùng
2. `resources/views/admin/users/history.blade.php` - Lịch sử mua hàng

### Chỉnh Sửa:
1. `app/Helpers/TelegramHelper.php` - Sửa timezone
2. `app/Http/Controllers/Admin/AdminController.php` - Thêm methods:
   - `users()` - Danh sách người dùng
   - `userHistory($id)` - Lịch sử mua hàng
3. `app/Models/User.php` - Thêm relationship với orders
4. `routes/web.php` - Thêm routes:
   - `GET /admin/users`
   - `GET /admin/users/{user}/history`
5. `resources/views/admin/dashboard.blade.php` - Thêm link menu

## 🧪 Cách Sử Dụng

### Bước 1: Truy Cập Trang Quản Lý
```
http://127.0.0.1:8000/admin/users
```

### Bước 2: Xem Danh Sách
- Xem tất cả người dùng
- Sắp xếp theo ngày đăng ký (mới nhất)
- Phân trang 10 người/trang

### Bước 3: Kiểm Tra Chi Tiết
- Click icon 👁️ để xem lịch sử
- Kiểm tra pattern mua hàng
- Đánh giá rủi ro gian lận

### Bước 4: Hành Động
- Nếu phát hiện gian lận → Liên hệ người dùng
- Có thể block tài khoản (tính năng mở rộng)
- Lưu note về người dùng đáng ngờ

## 📊 Thống Kê Database

### Queries Được Tối Ưu:
```php
// Lấy số đơn hàng và tổng tiền trong 1 query
User::where('role', 'user')
    ->withCount('orders')
    ->withSum('orders', 'total_amount')
    ->paginate(10)
```

### Index Nên Tạo (Tùy Chọn):
```sql
-- Tăng tốc query
ALTER TABLE orders ADD INDEX idx_user_id (user_id);
ALTER TABLE orders ADD INDEX idx_created_at (created_at);
```

## 🎯 Tính Năng Mở Rộng (Tương Lai)

- [ ] Tìm kiếm người dùng theo tên/email
- [ ] Lọc người dùng theo tổng tiền
- [ ] Export Excel danh sách người dùng
- [ ] Gửi email cảnh báo người dùng đáng ngờ
- [ ] Block/Unblock người dùng
- [ ] Thêm note cho từng người dùng
- [ ] Biểu đồ thống kê chi tiêu theo thời gian
- [ ] So sánh với người dùng khác

## 🔐 Bảo Mật

✅ Routes được bảo vệ bởi:
- `middleware(['auth', 'admin'])`
- Chỉ admin mới truy cập được

## 💡 Tips

1. **Kiểm tra thường xuyên:** Vào trang users hàng ngày để theo dõi
2. **Chú ý số đơn cao:** Người có > 10 đơn cần kiểm tra kỹ
3. **So sánh pattern:** Xem người dùng khác mua gì để phát hiện bất thường
4. **Lưu chứng từ:** Chụp màn hình lịch sử giao dịch làm bằng chứng

## 🎉 Kết Quả

Hệ thống giúp bạn:
- ✅ Quản lý người dùng hiệu quả
- ✅ Phát hiện gian lận sớm
- ✅ Tăng độ tin cậy cho website
- ✅ Bảo vệ lợi ích kinh doanh
- ✅ Có dữ liệu đầy đủ để xử lý tranh chấp
