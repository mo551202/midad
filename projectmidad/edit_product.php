<?php
session_start();
require 'db.php';

// تحقق من أن المستخدم سجل الدخول ومعرّف المنتج موجود
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'user'; // 'admin' أو 'user'

// جلب بيانات المنتج (المدير يمكنه تعديل أي منتج، أما المستخدم فيعدل منتجاته فقط)
if ($role === 'admin') {
    $query = "SELECT * FROM products WHERE id = $id";
} else {
    $query = "SELECT * FROM products WHERE id = $id AND user_id = $user_id";
}

$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "المنتج غير موجود أو ليس لديك صلاحية التعديل.";
    exit;
}

// عند إرسال النموذج، حدّث المنتج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = floatval($_POST['price']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);

    $update = "UPDATE products SET product_name='$name', price='$price', description='$desc' WHERE id=$id";
    if (mysqli_query($conn, $update)) {
        // إعادة التوجيه بعد الحفظ حسب الدور
        $redirect = ($role === 'admin') ? 'manage_products.php' : 'index.php';
        header("Location: $redirect");
        exit;
    } else {
        echo "حدث خطأ أثناء التحديث: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل المنتج - معرض السطوف</title>
    <link rel="stylesheet" href="edit_prodect.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="form-container">
        <h2>✏️ تعديل المنتج</h2>
        <form method="POST">
            <label for="name">اسم المنتج</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['product_name']) ?>" required>

            <label for="price">السعر</label>
            <input type="number" name="price" id="price" value="<?= $product['price'] ?>" required step="0.01">

            <label for="description">الوصف</label>
            <textarea name="description" id="description" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>

            <button type="submit" class="submit-btn">💾 حفظ التعديلات</button>
        </form>

        <a href="<?= ($role === 'admin') ? 'manage_products.php' : 'index.php' ?>" class="back-link">🔙 العودة</a>
    </div>
</body>
</html>
