<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Support\Response;

class AppointmentController {
    private array $allowedSpecialties = ['internal', 'pediatrics', 'cardiology'];

    public function index(): void {
        view('appointments/index', [
            'view' => 'appointments/index',
            'items' => $this->loadAppointments(),
        ]);
    }

    public function create(): void {
        // Lấy dữ liệu tạm thời từ cơ chế Flash sau khi bị ép quay về do lỗi nhập liệu
        $old = flash_get('old', []);
        $errors = flash_get('errors', []);

        view('appointments/create', [
            'view' => 'appointments/create',
            'old' => $old,
            'errors' => $errors,
            'allowedSpecialties' => $this->allowedSpecialties,
        ]);
    }

    public function store(): void {
        // Lọc sạch an toàn đầu vào dữ liệu, loại bỏ khoảng trắng thừa
        $data = [
            'patient_name' => trim($_POST['patient_name'] ?? ''),
            'email'        => trim($_POST['email'] ?? ''),
            'phone'        => trim($_POST['phone'] ?? ''),
            'specialty'    => trim($_POST['specialty'] ?? ''),
            'symptoms'     => trim($_POST['symptoms'] ?? ''),
            'website'      => trim($_POST['website'] ?? ''), // Trường Honeypot mật
        ];

        // Kích hoạt bộ lọc kiểm tra nghiệp vụ y tế dữ liệu
        $errors = $this->validate($data);

        if (!empty($errors)) {
            // Lưu lại vết dữ liệu cũ và lỗi cụ thể vào Flash Session
            flash_set('errors', $errors);
            flash_set('old', [
                'patient_name' => $data['patient_name'],
                'email'        => $data['email'],
                'phone'        => $data['phone'],
                'specialty'    => $data['specialty'],
                'symptoms'     => $data['symptoms'],
            ]);
            // Thực thi PRG: Redirect ngược về màn hình form nhập
            redirect('/appointments/create');
        }

        // Lưu trữ thông tin an toàn xuống ổ đĩa dạng tệp tin JSON
        $this->saveAppointment($data);
        
        // Cập nhật mốc thời gian tương tác cuối chống spam
        $_SESSION['last_appointment_submit_at'] = time();

        // Đặt thông báo thành công và điều hướng sang trang danh sách bệnh nhân
        flash_set('success', 'Hồ sơ đặt lịch khám bệnh trực tuyến đã được xác thực an toàn và lưu trữ thành công!');
        redirect('/appointments');
    }

    private function validate(array $data): array {
        $errors = [];

        // Kiểm thử 1: Kích hoạt bẫy Honeypot chặn phần mềm tự động Spambot
        if ($data['website'] !== '') {
            $errors['_global'] = 'Yêu cầu của bạn bị hệ thống phòng khám từ chối do phát hiện dấu hiệu Spam độc hại (Honeypot Triggered).';
            return $errors;
        }

        // Kiểm thử 2: Triển khai kiểm tra tần suất gửi đơn Rate Limiting trong 5 giây
        $lastSubmit = $_SESSION['last_appointment_submit_at'] ?? 0;
        if ($lastSubmit && (time() - $lastSubmit < 5)) {
            $errors['_global'] = 'Hệ thống phòng khám ghi nhận bạn đang thao tác quá nhanh. Vui lòng chờ 5 giây để tránh trùng lặp dữ liệu.';
            return $errors;
        }

        // Kiểm thử 3: Ràng buộc bắt buộc và độ dài của tên bệnh nhân
        if ($data['patient_name'] === '') {
            $errors['patient_name'] = 'Họ tên bệnh nhân bắt buộc phải nhập.';
        } elseif (mb_strlen($data['patient_name']) < 2) {
            $errors['patient_name'] = 'Họ tên bệnh nhân đăng ký phải có độ dài tối thiểu là 2 ký tự.';
        }

        // Kiểm thử 4: Kiểm tra tính chuẩn xác định dạng Email liên hệ
        if ($data['email'] === '') {
            $errors['email'] = 'Địa chỉ Email liên hệ không được phép bỏ trống.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Địa chỉ Email bạn nhập không đúng cấu trúc định dạng chuẩn.';
        }

        // Kiểm thử 5: Kiểm tra cấu trúc số điện thoại bằng biểu thức chính quy Regex
        if ($data['phone'] === '') {
            $errors['phone'] = 'Số điện thoại di động là trường dữ liệu bắt buộc.';
        } elseif (!preg_match('/^[0-9+\-\s]{9,15}$/', $data['phone'])) {
            $errors['phone'] = 'Số điện thoại không hợp lệ (Chỉ chứa số, khoảng trắng, +, -, độ dài từ 9 đến 15 kí tự).';
        }

        // Kiểm thử 6: Lọc danh mục chuyên khoa In-list an toàn bảo mật cấp cao
        if ($data['specialty'] === '' || !in_array($data['specialty'], $this->allowedSpecialties, true)) {
            $errors['specialty'] = 'Vui lòng lựa chọn một chuyên khoa lâm sàng hợp lệ có trong danh mục của phòng khám.';
        }

        // Kiểm thử 7: Giới hạn tối đa độ dài ký tự của mô tả bệnh lý
        if ($data['symptoms'] !== '' && mb_strlen($data['symptoms']) > 300) {
            $errors['symptoms'] = 'Nội dung mô tả triệu chứng y tế vượt quá độ dài quy định (Tối đa 300 kí tự).';
        }

        return $errors;
    }

    private function storageFile(): string {
    return __DIR__ . '/../../storage/appointments.json';
}

    private function loadAppointments(): array {
        if (!file_exists($this->storageFile())) {
            return [];
        }
        $json = file_get_contents($this->storageFile());
        return json_decode($json, true) ?: [];
    }

    private function saveAppointment(array $data): void {
        $items = $this->loadAppointments();
        $items[] = [
            'id'           => 'BN' . str_pad((string)(count($items) + 1), 3, '0', STR_PAD_LEFT),
            'patient_name' => $data['patient_name'],
            'email'        => $data['email'],
            'phone'        => $data['phone'],
            'specialty'    => $data['specialty'],
            'symptoms'     => $data['symptoms'],
            'created_at'   => date('Y-m-d H:i:s'),
        ];
        file_put_contents($this->storageFile(), json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}