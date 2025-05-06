<?php
session_start();

// تضمين ملف الاتصال بقاعدة البيانات
require_once 'db.php';

// التحقق من دور المستخدم
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// جلب المنتجات
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("فشل في جلب المنتجات: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة المنتجات - لوحة تحكم المدير</title>
    <link rel="stylesheet" href="manage_products.css">
</head>
<body>

<div class="container">
    <h2 class="welcome-message">إدارة المنتجات</h2>

    <table class="product-table">
        <thead>
            <tr>
                <th>الصورة</th>
                <th>اسم المنتج</th>
                <th>السعر</th>
                <th>الوصف</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td>
                        <?php if (!empty($row['image'])): ?>
                            <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="صورة" class="table-image">
                        <?php else: ?>
                            <img src="images/default.png" alt="لا توجد صورة" class="table-image">
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                    <td><?= htmlspecialchars($row['price']) ?> ر.س</td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td>
                    <a href="edit_product.php?id=<?= $row['id'] ?>" class="button edit-btn">تعديل</a>
                        <a href="delete_product.php?id=<?= $row['id'] ?>" class="button delete-btn" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="admin_dashboard.php" class="button back-btn">العودة للوحة التحكم</a>
</div>

</body>
</html>
