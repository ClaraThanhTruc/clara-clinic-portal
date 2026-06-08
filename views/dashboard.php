<h1>📊 Bác Sĩ Dashboard - Trung Tâm Quản Lý Lâm Sàng</h1>
<p>Chào mừng Y Bác Sĩ đã trở lại làm việc. Dưới đây là thông số kỹ thuật phiên bảo mật hiện hành của tài khoản.</p>
 
<div class="card">
    <h2>🩺 Hồ sơ phiên trực ban: <?= h($_SESSION['user_name'] ?? '') ?></h2>
    <div style="margin-top: 15px;">
        <p>• Quyền hạn hệ thống: <strong style="color: #0d9488;"><?= h($_SESSION['role'] ?? '') ?></strong></p>
        <p>• Thời điểm đăng nhập hệ thống: <code><?= h(date('Y-m-d H:i:s', $_SESSION['login_at'] ?? time())) ?></code></p>
        <p>• Ghi nhận tương tác cuối cùng (Last Activity): <code><?= h(date('Y-m-d H:i:s', $_SESSION['last_activity_at'] ?? time())) ?></code></p>
    </div>
    <div style="margin-top: 20px;">
        <a class="btn primary" href="/session-demo" target="_blank">🔍 Xuất Dữ Liệu Session Debug (JSON)</a>
    </div>
</div>