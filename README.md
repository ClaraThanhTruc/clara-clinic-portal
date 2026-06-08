# 🏥 Clara Clinic Portal - Hệ Thống Đặt Lịch Hẹn Khám Bệnh Bảo Mật

Dự án xây dựng cổng thông tin đăng ký đặt lịch khám lâm sàng trực tuyến dành cho bệnh nhân và hệ thống quản trị phiên làm việc dành cho Y Bác Sĩ. Mã nguồn được tối ưu hóa bảo mật theo tiêu chuẩn OWASP.

## 🌟 Tính Năng Nổi Bật & Lá Chắn Bảo Mật

* **T01 - T06:** Hệ thống Form tiếp nhận thông tin bệnh nhân có bộ lọc Validation nghiêm ngặt ở cả Frontend và Backend.
* **T07 (Anti-Bypass):** Chống can thiệp F12 (Inspect Element) để phá vỡ ràng buộc dữ liệu trống.
* **T08 (Rate Limiting):** Cơ chế chống spam gửi đơn liên tục gây quá tải Server.
* **T09 (XSS Defense):** Áp dụng hàm `h()` (Escape Output bằng `htmlspecialchars`) triệt tiêu hoàn toàn mã độc Cross-Site Scripting.
* **T10 - T11:** Xác thực tài khoản Y Bác Sĩ bằng cơ chế băm mật khẩu độ mật cao `password_hash` và `password_verify`.
* **T12 (Auth Guard):** Lá chắn phân quyền, tự động đá văng (Redirect) các truy cập ẩn danh trái phép vào khu vực nội bộ.
* **T13 (Session Destruction):** Cơ chế đăng xuất phá hủy hoàn toàn phiên làm việc an toàn.
* **T14 - T16:** Bộ định tuyến thông minh tự động bắt bài và trả về giao diện lỗi 404, 405 chuyên nghiệp.

## 🛠️ Hướng Dẫn Cài Đặt & Vận Hành (Dành cho Hội đồng chấm điểm)

Để chạy hệ thống trên máy cục bộ, Thầy/Cô vui lòng thực hiện các bước sau:

1. **Khôi phục các gói phụ thuộc (Dependencies):**
   ```bash
   composer install