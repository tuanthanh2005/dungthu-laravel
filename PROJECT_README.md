# DungThu.com - Platform Thương Mại Điện Tử & Blog

Website thương mại điện tử kết hợp blog chia sẻ kiến thức, được xây dựng bằng Laravel 11 theo mô hình MVC chuẩn.

## 🚀 Tính Năng

### ✅ Đã Hoàn Thành
- ✨ **Trang chủ động** với Hero section và typing effect
- 🛍️ **Quản lý sản phẩm**: Hiển thị, lọc theo category, chi tiết sản phẩm
- 📝 **Blog system**: Đăng bài, categories, views counter
- 🎨 **UI/UX hiện đại**: Glassmorphism, animations với AOS
- 📱 **Responsive design**: Tương thích mobile, tablet, desktop
- 🗂️ **Cấu trúc MVC chuẩn**: Models, Controllers, Views tách biệt
- 💾 **Database**: Migrations, Seeders với dữ liệu mẫu
- 🎯 **CSS/JS riêng biệt**: Dễ bảo trì và mở rộng

### 🔨 Đang Phát Triển
- 🛒 Shopping Cart functionality
- 💳 Checkout & Payment integration
- 👤 User Authentication & Profile
- 🔧 Free Tools pages
- 🔍 Search functionality
- ⭐ Product reviews & ratings

## 📦 Cài Đặt

### Yêu Cầu
- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Node.js & NPM (optional cho assets)

### Các Bước Cài Đặt

1. **Clone repository**
```bash
git clone <repository-url>
cd dungthu
```

2. **Cài đặt dependencies**
```bash
composer install
```

3. **Cấu hình môi trường**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Cấu hình database** trong file `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dungthu_db
DB_USERNAME=root
DB_PASSWORD=
```

5. **Chạy migrations và seeders**
```bash
php artisan migrate
php artisan db:seed
```

6. **Khởi động server**
```bash
php artisan serve
```

Truy cập: `http://localhost:8000`

## 📁 Cấu Trúc Project

```
dungthu/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── HomeController.php      # Trang chủ
│   │       ├── ProductController.php   # Sản phẩm
│   │       ├── BlogController.php      # Blog
│   │       └── CartController.php      # Giỏ hàng
│   └── Models/
│       ├── Product.php                 # Model sản phẩm
│       ├── Blog.php                    # Model blog
│       ├── Order.php                   # Model đơn hàng
│       └── OrderItem.php               # Model chi tiết đơn
├── database/
│   ├── migrations/                     # Migrations
│   └── seeders/
│       ├── ProductSeeder.php          # Dữ liệu sản phẩm mẫu
│       └── BlogSeeder.php             # Dữ liệu blog mẫu
├── public/
│   ├── css/
│   │   └── home.css                   # CSS trang chủ
│   └── js/
│       └── home.js                    # JavaScript trang chủ
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php          # Master layout
│       ├── partials/
│       │   ├── navbar.blade.php       # Navigation bar
│       │   └── footer.blade.php       # Footer
│       ├── home.blade.php             # Trang chủ
│       ├── products/
│       │   ├── index.blade.php        # Danh sách sản phẩm
│       │   └── show.blade.php         # Chi tiết sản phẩm
│       └── blogs/
│           ├── index.blade.php        # Danh sách blog
│           └── show.blade.php         # Chi tiết blog
└── routes/
    └── web.php                        # Định nghĩa routes
```

## 🎯 Routes Chính

| Route | Description |
|-------|-------------|
| `/` | Trang chủ |
| `/shop` | Danh sách sản phẩm |
| `/product/{slug}` | Chi tiết sản phẩm |
| `/blog` | Danh sách blog |
| `/blog/{slug}` | Chi tiết bài viết |
| `/cart` | Giỏ hàng |
| `/checkout` | Thanh toán |

## 🛠️ Công Nghệ Sử Dụng

- **Backend**: Laravel 11
- **Frontend**: Bootstrap 5, AOS Animation, Font Awesome
- **Database**: MySQL
- **Template Engine**: Blade
- **Architecture**: MVC Pattern

## 📝 Database Schema

### Products Table
- id, name, slug, description, price, image, category, stock, timestamps

### Blogs Table
- id, title, slug, excerpt, content, image, category, user_id, views, is_published, published_at, timestamps

### Orders Table
- id, user_id, total, status, timestamps

### Order Items Table
- id, order_id, product_id, quantity, price, timestamps

## 🎨 Customization

### Thay đổi màu sắc
Chỉnh sửa CSS variables trong `public/css/home.css`:
```css
:root {
    --primary: #6c5ce7;
    --secondary: #a29bfe;
    --accent: #00cec9;
}
```

### Thêm sản phẩm mới
1. Thêm vào database qua seeder hoặc admin panel
2. Hoặc chạy: `php artisan tinker` và tạo Product mới

## 📮 Contact & Support

- Website: [dungthu.com](https://dungthu.com)
- Email: support@dungthu.com

## 📄 License

This project is open-sourced software licensed under the MIT license.

---

**Developed with ❤️ by DungThu Team**
