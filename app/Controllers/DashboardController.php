<?php
declare(strict_types=1);
namespace App\Controllers;

class DashboardController {
    public function index(): void {
        // Lớp bảo vệ nghiêm ngặt: Nếu chưa đăng nhập lập tức đá văng về màn login kèm Flash báo lỗi
        require_login();
        view('dashboard', ['view' => 'dashboard']);
    }

    public function sessionDemo(): void {
        require_login();
        // Điểm cộng chuẩn hóa phản hồi: Trả về dữ liệu định dạng chuẩn JSON cho trình duyệt và Postman
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'user_id'          => $_SESSION['user_id'] ?? null,
            'user_name'        => $_SESSION['user_name'] ?? null,
            'role'             => $_SESSION['role'] ?? null,
            'login_at'         => date('Y-m-d H:i:s', $_SESSION['login_at'] ?? time()),
            'last_activity_at' => date('Y-m-d H:i:s', $_SESSION['last_activity_at'] ?? time()),
            'session_name'     => session_name(),
            'secure_cookie'    => ini_get('session.cookie_secure') ? 'BẬT (HTTPS)' : 'TẮT (HTTP Local)',
            'http_only_flag'   => ini_get('session.cookie_httponly') ? 'ĐÃ KÍCH HOẠT VỮNG CHẮC' : 'CHƯA BẬT',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}