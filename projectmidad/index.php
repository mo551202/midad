<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$query = "SELECT p.*, u.username FROM products p JOIN users u ON p.user_id = u.id";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>الصفحة الرئيسية - معرض السطوف</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="index.css"> <!-- ملف CSS المخصص -->
</head>
<body>

<!-- شريط التنقل -->
<nav class="navbar">
  <div class="container">
    <a class="navbar-brand" href="index.php">معرض السطوف</a>
    <div class="navbar-links">
      <ul>
      <li><a href="index.php">الصفحة الرئيسة</a></li>
        <li><a href="products.php">منتجاتي</a></li>
        <li><a href="add_product.php">إضافة منتج</a></li>
        <li><a href="logout.php">تسجيل الخروج</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- المحتوى -->
<div class="container main-content">
  <h2 class="text-center mb-4"> مرحبا بك في معرض السطوف للمأكولات!!</h2>
  <p class="text-center mb-4">استمتع بتصفح منتجات المطاعم!</p>

  <div class="product-list">
    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
      <div class="product-card">
        <?php if (!empty($row['image'])): ?>
          <img src="<?= htmlspecialchars($row['image']) ?>" alt="صورة المنتج" class="product-image">
        <?php else: ?>
          <img src="images/y.jpg" alt="صورة افتراضية" class="product-image">
        <?php endif; ?>
        <div class="product-details">
          <h5><?= htmlspecialchars($row['product_name']) ?></h5>
          <p class="price">السعر: <?= htmlspecialchars($row['price']) ?> ل.س</p>
          
          <!-- عرض أول كلمتين فقط من الوصف -->
          <?php 
          $description = htmlspecialchars($row['description']);
          $words = explode(' ', $description);
          $short_description = implode(' ', array_slice($words, 0, 2)) . '...';
          ?>
          <p class="description"><?= $short_description ?></p>
          
          <p class="restaurant-name text-muted small">اسم المطعم : <?= htmlspecialchars($row['username']) ?></p>
          <a href="product.php?id=<?= $row['id'] ?>&from=index.php" class="btn">عرض التفاصيل</a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

</body>
</html>
