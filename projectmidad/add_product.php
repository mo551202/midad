<?php
session_start();
require 'db.php'; // الاتصال بقاعدة البيانات

// التأكد من أن المستخدم مسجل دخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// عند إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = floatval($_POST['price']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $user_id = $_SESSION['user_id'];

    // حفظ في قاعدة البيانات
    $sql = "INSERT INTO products (product_name, price, description, user_id)
            VALUES ('$name', '$price', '$desc', '$user_id')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php");
        exit;
    } else {
        $error = "حدث خطأ أثناء إضافة المنتج.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>إضافة منتج</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
</head>
<body>
  <div class="container mt-5">
    <h2 class="mb-4">إضافة منتج جديد</h2>

    <?php if (!empty($error)) : ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label for="name" class="form-label">اسم المنتج</label>
        <input type="text" class="form-control" id="name" name="name" required>
      </div>

      <div class="mb-3">
        <label for="price" class="form-label">السعر</label>
        <input type="number" class="form-control" id="price" name="price" required>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">الوصف</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
      </div>

      <button type="submit" class="btn btn-primary">إضافة المنتج</button>
      <a href="index.php" class="btn btn-secondary">إلغاء</a>
    </form>
  </div>
</body>
</html>
