# Hướng Dẫn Cài Đặt Tính Năng Săn Sale Tiktok Shop

## 📋 Các Thay Đổi Đã Thực Hiện

### 1. Database & Models
- ✅ Tạo migration: `2026_01_22_100000_create_tiktok_deals_table.php`
- ✅ Tạo model: `app/Models/TiktokDeal.php`

### 2. Controllers
- ✅ Tạo controller CRUD: `app/Http/Controllers/Admin/TiktokDealController.php`
- ✅ Cập nhật HomeController để hiển thị Tiktok Deals và Featured Products

### 3. Routes
- ✅ Thêm routes cho admin quản lý Tiktok Deals

### 4. Views
- ✅ Tạo views admin:
  - `resources/views/admin/tiktok-deals/index.blade.php` (Danh sách)
  - `resources/views/admin/tiktok-deals/create.blade.php` (Thêm mới)
  - `resources/views/admin/tiktok-deals/edit.blade.php` (Chỉnh sửa)
- ✅ Cập nhật trang home với 2 phần:
  - Săn Sale Tiktok Shop (thay thế Thời Trang)
  - Sản Phẩm Nổi Bật (giữ nguyên)

### 5. CSS
- ✅ Thêm styles cho Tiktok Deal cards trong `public/css/home.css`

## 🚀 Các Bước Cài Đặt

### Bước 1: Chạy Migration
```bash
php artisan migrate
```

### Bước 2: Tạo symbolic link cho storage (nếu chưa có)
```bash
php artisan storage:link
```

### Bước 3: Truy cập trang admin
Đăng nhập với tài khoản admin và truy cập:
```
http://your-domain.com/admin/tiktok-deals
```

## 📝 Hướng Dẫn Sử Dụng

### Thêm Deal Tiktok Mới
1. Truy cập **Admin > Săn Sale TikTok**
2. Click nút **"Thêm Deal Mới"**
3. Điền thông tin:
   - Tên Deal (bắt buộc)
   - Mô tả
   - Hình ảnh
   - Link Tiktok Shop (bắt buộc)
   - Giá gốc
   - Giá sale
   - % Giảm giá
   - Thứ tự hiển thị (số nhỏ hiện trước)
   - Trạng thái (Active/Inactive)
4. Click **"Lưu Deal"**

### Sửa Deal
1. Trong danh sách, click nút **Sửa** (màu vàng)
2. Cập nhật thông tin
3. Click **"Cập Nhật"**

### Xóa Deal
1. Trong danh sách, click nút **Xóa** (màu đỏ)
2. Xác nhận xóa

### Bật/Tắt Deal
Click nút trạng thái (màu xanh/xám) để bật/tắt hiển thị deal

## 🎨 Tính Năng Nổi Bật

### Trang Home
- **Phần 1: Săn Sale Tiktok Shop**
  - Hiển thị tối đa 8 deals hot
  - Badge giảm giá nổi bật
  - Nút "Mua Ngay" mở link Tiktok Shop trong tab mới
  - Animation hover đẹp mắt
  
- **Phần 2: Sản Phẩm Nổi Bật**
  - Hiển thị 6 sản phẩm nổi bật
  - Giữ nguyên chức năng như cũ

### Menu Category
- Thay đổi **"Thời Trang"** thành **"Săn Sale TikTok"** với icon Tiktok

### Admin Panel
- Giao diện quản lý hiện đại
- Bảng danh sách với đầy đủ thông tin
- Preview hình ảnh
- Toggle trạng thái nhanh
- Link xem deal trên Tiktok

## 🔧 Cấu Trúc Database

### Bảng `tiktok_deals`
```
- id: Primary key
- name: Tên deal (required)
- description: Mô tả
- image: Đường dẫn hình ảnh
- tiktok_link: Link Tiktok Shop (required)
- original_price: Giá gốc
- sale_price: Giá sale
- discount_percent: % Giảm
- is_active: Trạng thái (boolean)
- order: Thứ tự hiển thị
- created_at, updated_at: Timestamps
```

## 📸 Lưu Ý

- Hình ảnh được lưu trong `storage/app/public/tiktok-deals/`
- Chỉ deals có `is_active = true` mới hiển thị trên trang home
- Deals được sắp xếp theo thứ tự `order` (nhỏ đến lớn)
- Link Tiktok phải là URL hợp lệ (bắt đầu với http:// hoặc https://)

## 🎯 Các Tính Năng Có Thể Mở Rộng

1. Thêm thống kê số click vào deals
2. Lên lịch tự động bật/tắt deals theo thời gian
3. Tích hợp API Tiktok để lấy thông tin sản phẩm tự động
4. Thêm bộ lọc và tìm kiếm trong danh sách deals
5. Export danh sách deals ra Excel

## 🐛 Xử Lý Lỗi

### Nếu không thấy hình ảnh:
```bash
php artisan storage:link
chmod -R 775 storage/
```

### Nếu migration lỗi:
```bash
php artisan migrate:fresh
# Hoặc
php artisan migrate:rollback
php artisan migrate
```

### Nếu lỗi 404 Not Found:
```bash
php artisan route:clear
php artisan cache:clear
php artisan config:clear
```

## ✅ Hoàn Tất!

Hệ thống của bạn đã sẵn sàng với tính năng **Săn Sale Tiktok Shop**! 🎉
