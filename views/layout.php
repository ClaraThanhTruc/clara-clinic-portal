<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Clara Clinic - Secure Portal</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<nav class="navbar">
    <strong>🏥 Clara Clinic Portal</strong>
    <a href="/">Trang Tổng Quan</a>
    <a href="/appointments">Danh Sách Lịch Hẹn</a>
    <a href="/appointments/create">Đặt Lịch Khám</a>
    <a href="/dashboard">Bác Sĩ Dashboard</a>
    <a href="/login">Đăng Nhập</a>
    <form method="post" action="/logout" class="inline-form">
        <button type="submit" class="link-button">Đăng Xuất</button>
    </form>
</nav>
 
<main class="container">
    <?php if ($success = flash_get('success')): ?>
        <div class="alert success"><?= h($success) ?></div>
    <?php endif; ?>
 
    <?php if ($error = flash_get('error')): ?>
        <div class="alert error"><?= h($error) ?></div>
    <?php endif; ?>
 
    <?php require view_path($view); ?>
</main>
</body>
</html>