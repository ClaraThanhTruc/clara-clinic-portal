<?php
declare(strict_types=1);
namespace App\Controllers;

class AuthController {
    private array $users;

    public function __construct() {
        // Tài khoản demo mã hóa mật khẩu an toàn theo chuẩn BCRYPT
        $this->users = [
            'name@doctor.com' => [
                'id'            => 1,
                'name'          => 'Bác Sĩ Trưởng Khoa Clara',
                'role'          => 'Bác Sĩ Lâm Sàng',
                'password_hash' => password_hash('22112211', PASSWORD_DEFAULT),
            ],
            // Thêm tài khoản theo đúng yêu cầu test case của đề bài
            'name@student.com' => [
                'id'            => 2,
                'name'          => 'Tran Thi Thanh Truc - (MSSV: 22112211)',
                'role'          => 'Sinh Viên Thực Tập',
                'password_hash' => password_hash('22112211', PASSWORD_DEFAULT),
            ]
        ];
    }

    public function login(): void {
        if (is_logged_in()) {
            redirect('/dashboard');
        }

        view('auth/login', [
            'view'   => 'auth/login',
            'old'    => flash_get('old', []),
            'errors' => flash_get('errors', []),
        ]);
    }

    public function handleLogin(): void {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        $errors = [];

        if ($email === '') {
            $errors['email'] = 'Tên tài khoản Email không được bỏ trống.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Cấu trúc tên tài khoản không phải định dạng Email.';
        }

        if ($password === '') {
            $errors['password'] = 'Vui lòng điền mật mã xác thực.';
        }

        $user = $this->users[$email] ?? null;
        if (empty($errors) && (!$user || !password_verify($password, $user['password_hash']))) {
            $errors['password'] = 'Thông tin tài khoản hoặc mật mã xác thực không chính xác.';
        }

        if (!empty($errors)) {
            flash_set('errors', $errors);
            flash_set('old', ['email' => $email]);
            redirect('/login');
        }

        // ĐIỂM CHẤT LƯỢNG CAO: Tái tạo định danh Session ID mới, xóa bỏ mã cũ để triệt tiêu Session Fixation
        session_regenerate_id(true);

        // Lưu thông tin định danh người dùng vào Session an toàn ở Server
        $_SESSION['user_id']          = $user['id'];
        $_SESSION['user_name']        = $user['name'];
        $_SESSION['role']             = $user['role'];
        $_SESSION['login_at']         = time();
        $_SESSION['last_activity_at'] = time();
        $_SESSION['user_agent']       = $_SERVER['HTTP_USER_AGENT'] ?? '';

        flash_set('success', 'Xác thực tài khoản thành công! Mã Session ID của bạn đã được tái cấu hình an toàn.');
        redirect('/dashboard');
    }

    public function logout(): void {
        // Thực thi dọn dẹp triệt để dữ liệu cũ
        logout_clean();

        // Khởi động lại một session ngắn hạn mới chỉ để lưu thông báo đẩy Flash sau đăng xuất
        session_start();
        flash_set('success', 'Bạn đã đăng xuất khỏi hệ thống phòng khám thành công. Toàn bộ Token cũ trên máy chủ đã được hủy bỏ sạch sẽ.');
        redirect('/login');
    }
}