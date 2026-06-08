<?php
declare(strict_types=1);

// Chống hack XSS: Escape dữ liệu hiển thị ra HTML
function h(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// Chuyển hướng trang nhanh và dừng script ngay lập tức
function redirect(string $path): void {
    header('Location: ' . $path);
    exit;
}

// Lưu thông báo Flash (chỉ tồn tại trong 1 phiên request tiếp theo)
function flash_set(string $key, mixed $value): void {
    $_SESSION['_flash'][$key] = $value;
}

// Lấy thông báo Flash và xóa ngay lập tức
function flash_get(string $key, mixed $default = null): mixed {
    $value = $_SESSION['_flash'][$key] ?? $default;
    unset($_SESSION['_flash'][$key]);
    return $value;
}

// Kiểm tra trạng thái đăng nhập
function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}

// Bảo vệ các route nhạy cảm (Auth Guard)
function require_login(): void {
    if (!is_logged_in()) {
        flash_set('error', 'Vui lòng đăng nhập hệ thống phòng khám để truy cập chức năng này.');
        redirect('/login');
    }
}

// Kiểm tra thời gian rảnh (Session Idle Timeout)
function check_session_timeout(): void {
    $idleLimit = 15 * 60; // 15 phút bảo mật.

    if (!isset($_SESSION['user_id'])) {
        return;
    }

    $last = $_SESSION['last_activity_at'] ?? time();
    if (time() - $last > $idleLimit) {
        logout_clean();
        session_start();
        flash_set('error', 'Phiên làm việc đã hết hạn do bạn không hoạt động lâu. Vui lòng đăng nhập lại.');
        redirect('/login');
    }

    $_SESSION['last_activity_at'] = time();
}

// Kiểm tra ngữ cảnh Session bằng User-Agent
function check_session_context(): void {
    if (!isset($_SESSION['user_id'])) {
        return;
    }

    $currentAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $savedAgent = $_SESSION['user_agent'] ?? '';

    if ($savedAgent !== '' && $savedAgent !== $currentAgent) {
        logout_clean();
        session_start();
        flash_set('error', 'Phát hiện môi trường đăng nhập bất thường. Để bảo mật thông tin bệnh nhân, vui lòng đăng nhập lại.');
        redirect('/login');
    }
}

// Cơ chế xóa sạch Session và Cookie lưu vết
function logout_clean(): void {
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}

// Hàm render giao diện lồng vào Layout chung
function view(string $view, array $data = []): void {
    extract($data);
    require __DIR__ . '/../../views/layout.php';
}

function view_path(string $view): string {
    return __DIR__ . '/../../views/' . $view . '.php';
}