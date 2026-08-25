# Desktop Electron App Wrapper - Dùng Thử App (Windows & Mac)

Dự án Desktop WebView App hoàn chỉnh chạy trên **Windows, macOS & Linux** sử dụng Electron framework.

## Các bước chạy và build ứng dụng Desktop

### 1. Chạy thử nghiệm trên máy tính
Mở Command Prompt hoặc PowerShell tại thư mục `apps/desktop` và gõ lệnh:
```bash
npm install
npm start
```
Cửa sổ ứng dụng Dùng Thử Desktop sẽ lập tức khởi chạy!

---

### 2. Đóng gói ra file cài đặt Windows (.exe)
Chạy lệnh sau để build bộ cài đặt Windows:
```bash
npm run build
```
File cài đặt `.exe` hoàn chỉnh sẽ nằm trong thư mục `apps/desktop/dist/Dùng Thử AI Setup 1.0.0.exe`.

---

### 3. Đưa file `.exe` lên Website để người dùng tải
Sau khi build xong, copy file `.exe` vừa tạo vào thư mục `public/downloads/dungthu-desktop-setup.exe` của dự án Laravel. Người dùng bấm nút **Tải App PC** trên giao diện website là có thể tải trực tiếp file `.exe` về máy tính để cài đặt!
