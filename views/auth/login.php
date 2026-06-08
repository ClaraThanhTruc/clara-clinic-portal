<h1>🔐 Cổng Đăng Nhập Hệ Thống Phòng Khám</h1>
<p>Khu vực kiểm soát quyền hạn dành riêng cho Y Bác Sĩ và Nhân Viên Điều Phối Lâm Sàng.</p>
 
<form method="post" action="/login" class="card">
    <div class="form-group">
        <label>Địa chỉ Email nội bộ</label>
        <input type="text" name="email" value="<?= h($old['email'] ?? '') ?>" placeholder="name@student.com">
        <?php if (!empty($errors['email'])): ?><div class="error-text">❌ <?= h($errors['email']) ?></div><?php endif; ?>
    </div>
 
    <div class="form-group">
        <label>Mật mã xác thực</label>
        <input type="password" name="password" placeholder="••••••••">
        <?php if (!empty($errors['password'])): ?><div class="error-text">❌ <?= h($errors['password']) ?></div><?php endif; ?>
    </div>
 
    <div class="form-group">
        <label><input type="checkbox" name="remember_me" value="1"> Duy trì đăng nhập trên thiết bị này</label>
        <small style="color: #64748b; display: block; margin-top: 4px;">⚠️ Cảnh báo bảo mật: Hệ thống tuyệt đối không lưu trữ mật khẩu gốc trong Cookie để tránh lộ lọt thông tin.</small>
    </div>
 
    <button class="btn primary" type="submit">Đăng Nhập</button>
</form>
 
<div class="card" style="background: #f8fafc; border-left: 5px solid #0d9488;">
    <strong>🔑 Tài khoản::</strong><br>
    • Email: <code style="background:#e2e8f0; padding:2px 6px; border-radius:4px;">name@student.com</code><br>
    • Mật mã: <code style="background:#e2e8f0; padding:2px 6px; border-radius:4px;">22112211</code>
</div>
