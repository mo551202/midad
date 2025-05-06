<?php
session_start();

// التحقق من دور المستخدم
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); // إعادة التوجيه إلى صفحة تسجيل الدخول إذا لم يكن المستخدم admin
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم المدير</title>
    <link rel="stylesheet" href="admin_dashboard.css"> <!-- ملف CSS المخصص -->
</head>
<body>

<div class="container">
    <h2 class="welcome-message">مرحبًا بك في لوحة تحكم المدير</h2>
    <div class="links">
        <p><a href="manage_products.php" class="button">إدارة المنتجات</a></p>
        <p><a href="users.php" class="button">إدارة المستخدمين</a></p>
        <p><a href="logout.php" class="button">تسجيل الخروج</a></p>
    </div>
</div>

</body>
</html>
