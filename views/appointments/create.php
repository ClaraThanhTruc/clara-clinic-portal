<h1>📋 Đăng Ký Đặt Lịch Hẹn Khám Bệnh</h1>
<p>Vui lòng điền chính xác các thông tin bệnh lý dưới đây. Hệ thống tự động mã hóa và bảo mật hồ sơ của bạn.</p>
 
<?php if (!empty($errors['_global'])): ?>
    <div class="alert error"><?= h($errors['_global']) ?></div>
<?php endif; ?>
 
<form method="post" action="/appointments" class="card">
    <div class="form-group">
        <label>Họ tên Bệnh nhân *</label>
        <input type="text" name="patient_name" value="<?= h($old['patient_name'] ?? '') ?>" placeholder="Ví dụ: Nguyễn Văn A">
        <?php if (!empty($errors['patient_name'])): ?><div class="error-text">❌ <?= h($errors['patient_name']) ?></div><?php endif; ?>
    </div>
 
    <div class="form-group">
        <label>Địa chỉ Email liên hệ *</label>
        <input type="text" name="email" value="<?= h($old['email'] ?? '') ?>" placeholder="name@example.com">
        <?php if (!empty($errors['email'])): ?><div class="error-text">❌ <?= h($errors['email']) ?></div><?php endif; ?>
    </div>
 
    <div class="form-group">
        <label>Số điện thoại di động *</label>
        <input type="text" name="phone" value="<?= h($old['phone'] ?? '') ?>" placeholder="Số điện thoại từ 9 đến 15 ký tự">
        <?php if (!empty($errors['phone'])): ?><div class="error-text">❌ <?= h($errors['phone']) ?></div><?php endif; ?>
    </div>
 
    <div class="form-group">
        <label>Chuyên khoa cần Đặt lịch *</label>
        <select name="specialty">
            <option value="">-- Vui lòng chọn chuyên khoa lâm sàng --</option>
            <option value="internal" <?= (($old['specialty'] ?? '') === 'internal') ? 'selected' : '' ?>>Khoa Nội Tổng Quát</option>
            <option value="pediatrics" <?= (($old['specialty'] ?? '') === 'pediatrics') ? 'selected' : '' ?>>Khoa Nhi</option>
            <option value="cardiology" <?= (($old['specialty'] ?? '') === 'cardiology') ? 'selected' : '' ?>>Khoa Tim Mạch</option>
        </select>
        <?php if (!empty($errors['specialty'])): ?><div class="error-text">❌ <?= h($specialty_err = $errors['specialty']) ?></div><?php endif; ?>
    </div>
 
    <div class="form-group">
        <label>Mô tả triệu chứng bệnh lý sơ bộ</label>
        <textarea name="symptoms" placeholder="Mô tả ngắn gọn tình trạng sức khỏe hiện tại của bạn (Tối đa 300 ký tự)"><?= h($old['symptoms'] ?? '') ?></textarea>
        <?php if (!empty($errors['symptoms'])): ?><div class="error-text">❌ <?= h($errors['symptoms']) ?></div><?php endif; ?>
    </div>
 
    <div class="honeypot">
        <label>Website URL</label>
        <input type="text" name="website" tabindex="-1" autocomplete="off">
    </div>
 
    <button class="btn primary" type="submit">🚀 Xác Nhận Đăng Ký Lịch Hẹn</button>
    <a class="btn secondary" href="/appointments">Hủy bỏ</a>
</form>