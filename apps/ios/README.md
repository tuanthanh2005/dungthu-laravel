# iOS WKWebView Wrapper - Dùng Thử App (iPhone / iPad)

Dự án WebView iOS viết bằng **Swift** (sử dụng `WKWebView`) tương thích tất cả iPhone và iPad.

## 2 Phương Pháp Cài App Cho iOS:

### Cách 1: PWA Web Clip (Không cần máy tính macOS / Xcode - Nhanh nhất cho người dùng)
1. Người dùng mở trình duyệt **Safari** trên iPhone/iPad và truy cập `dungthu.com`.
2. Bấm vào nút **Share** (Chia sẻ <i class="fa-solid fa-arrow-up-from-bracket"></i>) ở mép dưới màn hình.
3. Chọn **Thêm vào MH chính** (Add to Home Screen).
4. Biểu tượng App Dùng Thử sẽ xuất hiện trên màn hình chính như một ứng dụng Native thực thụ!

---

### Cách 2: Biên dịch ứng dụng iOS Xcode App (.IPA)
1. Mở Xcode trên máy Mac.
2. Tạo dự án iOS App tên `DungThuApp`.
3. Thay mã nguồn `ViewController.swift` bằng file [ViewController.swift](file:///apps/ios/DungThuApp/ViewController.swift).
4. Cấu hình Signing & Capabilities với Apple Developer Account.
5. Export file `.ipa` để phát hành qua TestFlight hoặc Enterprise distribution.
