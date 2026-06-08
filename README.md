# 🏥 Clara Clinic Portal - Secure Authentication & Session Management System

Dự án xây dựng cổng thông tin đặt lịch hẹn khám bệnh trực tuyến và phân quyền hệ thống quản trị nội bộ dành cho Y Bác Sĩ. Mã nguồn được tối ưu hóa bảo mật toàn diện theo tiêu chuẩn OWASP nhằm chống lại các lỗ hổng khai thác phổ biến.

---

## 📁 1. Cấu Trúc Thư Mục Dự Án (Project Architecture)

Hệ thống được thiết kế theo mô hình kiến trúc MVC đơn giản, tách biệt rõ ràng giữa logic xử lý, dữ liệu và giao diện hiển thị:

* **`/app`**: Bộ não của hệ thống (Chứa Controllers điều hướng, Xử lý Logic Auth, Đăng ký và bộ lọc Validation).
* **`/views`**: Nơi chứa giao diện người dùng (Form đặt lịch, Trang danh sách công khai, Cổng Login và Bác Sĩ Dashboard).
* **`/public`**: Điểm đón nhận yêu cầu (File `index.php` điều phối và các tài nguyên CSS/JS).
* **`/storage`**: Lưu trữ dữ liệu hồ sơ bệnh nhân an toàn dưới dạng cấu trúc tệp tin phi quan hệ.

---

## 🛡️ 2. Ma Trận Bảo Mật & 16 Kịch Bản Kiểm Thử (Test Cases Summary)

Dưới đây là bảng tổng hợp kết quả thực thi 16 Test Cases chứng minh các lá chắn bảo mật hoạt động hiệu quả tuyệt đối:

| Mã Test | Kịch Bản Kiểm Thử (Test Case) | Cơ Chế Bảo Mật Áp Dụng | Trạng Thái |
| :--- | :--- | :--- | :---: |
| **T01** | Tải giao diện Form đặt lịch hẹn | Định tuyến (Routing) GET an toàn | **Pass** |
| **T02** | Trống trường Họ Tên | Server-side Validation (Kiểm tra trống) | **Pass** |
| **T03** | Email sai cấu trúc quy định | `FILTER_VALIDATE_EMAIL` ở Backend | **Pass** |
| **T04** | Trống/Sai định dạng Số điện thoại | Regex Validation (Bộ lọc chuỗi số) | **Pass** |
| **T05** | Bỏ trống Ngày giờ hẹn khám | Server-side Date Validation | **Pass** |
| **T06** | Bỏ trống mô tả Triệu chứng lâm sàng | Server-side String Validation | **Pass** |
| **T07** | Can thiệp F12 (Bypass Client Validation) | Triệt tiêu phụ thuộc, ép kiểm tra tại Backend | **Pass** |
| **T08** | Thao tác gửi đơn liên tục (Spam) | Rate Limiting / Anti-Spam Barrier | **Pass** |
| **T09** | Tấn công mã độc Cross-Site Scripting | XSS Defense (Hàm `h()` / `htmlspecialchars`) | **Pass** |
| **T10** | Đăng nhập sai tài khoản/mật mã | Login Failure Handling (Từ chối an toàn) | **Pass** |
| **T11** | Đăng nhập thành công chính chủ | `password_verify` & Khởi tạo `$_SESSION` | **Pass** |
| **T12** | Ẩn danh cố tình truy cập `/dashboard` | Auth Guard (Lá chắn bảo mật phân quyền) | **Pass** |
| **T13** | Đột nhập lại sau khi Đăng xuất | Session Destruction (`session_destroy()`) | **Pass** |
| **T14** | Truy cập sai phương thức `/logout` | Giao thức an toàn (Chỉ chấp nhận POST) | **Pass** |
| **T15** | Treo máy rảnh (Hết hạn phiên) | Session Timeout (Tự động hủy Token phiên) | **Pass** |
| **T16** | Gõ đường dẫn bậy bạ không tồn tại | Khớp mẫu định tuyến $\rightarrow$ Trả mã lỗi 404 độc quyền | **Pass** |

---

## 🛠️ 3. Hướng Dẫn Khởi Chạy & Vận Hành Hệ Thống (Deployment Guide)

Để khởi chạy dự án và tiến hành hội đồng chấm điểm trên môi trường cục bộ (Localhost), Thầy/Cô vui lòng thực hiện tuần tự theo các bước chuẩn hóa sau:

### 📥 Bước 3.1: Chuẩn bị môi trường máy cục bộ
* Đảm bảo máy tính đã cài đặt sẵn môi trường chạy PHP (Phiên bản khuyến nghị từ 8.0 trở lên, ví dụ: XAMPP, Laragon).
* Máy tính đã cấu hình toàn cục công cụ quản lý thư viện **Composer**.

### 📦 Bước 3.2: Khôi phục cấu trúc và các gói thư viện phụ thuộc
Mở công cụ Terminal/Command Prompt ngay tại thư mục gốc của dự án vừa tải về, thực thi lệnh sau để hệ thống tự động tải và liên kết các thư viện bảo mật:
```bash
composer install