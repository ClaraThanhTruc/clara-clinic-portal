<h1>🗂️ Danh Sách Bệnh Nhân Đăng Ký Lịch Hẹn</h1>
<p>Dưới đây là danh sách toàn bộ hồ sơ đăng ký đặt lịch khám lâm sàng được lưu trữ bảo mật trong hệ thống của phòng khám.</p>
<p><a class="btn primary" href="/appointments/create">➕ Đặt Lịch Hẹn Mới</a></p>
 
<table class="table">
    <thead>
        <tr>
            <th>Mã Số</th>
            <th>Họ Tên Bệnh Nhân</th>
            <th>Email Liên Hệ</th>
            <th>Số Điện Thoại</th>
            <th>Chuyên Khoa</th>
            <th>Thời Gian Đăng Ký</th>
        </tr>
    </thead>
    <tbody>
    <?php if (empty($items)): ?>
        <tr>
            <td colspan="6" style="text-align: center; color: #64748b;">Hiện tại chưa có hồ sơ bệnh án nào được đăng ký.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><span class="badge tech"><?= h($item['id']) ?></span></td>
                <td><strong><?= h($item['patient_name']) ?></strong></td>
                <td><?= h($item['email']) ?></td>
                <td><?= h($item['phone']) ?></td>
                <td>
                    <?php
                        $mapping = ['internal' => 'Khoa Nội Tổng Quát', 'pediatrics' => 'Khoa Nhi', 'cardiology' => 'Khoa Tim Mạch'];
                        echo h($mapping[$item['specialty']] ?? $item['specialty']);
                    ?>
                </td>
                <td><?= h($item['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>