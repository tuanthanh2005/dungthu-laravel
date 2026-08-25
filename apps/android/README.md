# Android WebView Wrapper - Dùng Thử App

Dự án WebView Android hoàn chỉnh được viết bằng **Kotlin** để đóng gói trang web `dungthu.com` thành file APK cho điện thoại Android.

## Tính năng
-  Nhúng WebView full màn hình.
- 🔄 Kéo xuống để tải lại trang (Swipe To Refresh).
- 📁 Hỗ trợ chọn và upload file/ảnh từ điện thoại.
- 📱 Tự động xử lý phím Back vật lý (Quay lại trang trước đó thay vì thoát App).
- 🌐 Hỗ trợ mở link gọi điện, gửi email, Zalo, Telegram ra ứng dụng ngoài.

## Hướng dẫn build file APK bằng Android Studio
1. Mở phần mềm **Android Studio**.
2. Chọn **Open an existing project** và dẫn tới thư mục `apps/android`.
3. Thay đổi domain `APP_URL` trong file [MainActivity.kt](file:///apps/android/app/src/main/java/com/dungthu/app/MainActivity.kt) nếu cần.
4. Vào menu **Build** > **Build Bundle(s) / APK(s)** > **Build APK(s)**.
5. File APK xuất ra tại thư mục: `apps/android/app/build/outputs/apk/debug/app-debug.apk`.
6. Coppy file APK này vào thư mục `public/downloads/dungthu-app.apk` trên website để người dùng bấm nút **Tải file APK** là tải được ngay!
