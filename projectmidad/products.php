<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM products WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>منتجاتي - معرض السطوف</title>
    <link rel="stylesheet" href="products.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
 
</head>
<body>

<!-- شريط التنقل -->
<nav class="navbar">
  <div class="container">
    <a class="navbar-brand" href="index.php">معرض السطوف</a>
    <div class="navbar-links">
      <ul>
      <li><a href="javascript:history.back()">الصفحة الرئيسة</a></li>
        <li><a href="products.php">منتجاتي</a></li>
        <li><a href="add_product.php">إضافة منتج</a></li>
        <li><a href="logout.php">تسجيل الخروج</a></li>
       
      </ul>
    </div>
  </div>
</nav>

<!-- محتوى الصفحة -->
<div class="container">
    <h2 class="page-title">منتجاتي</h2>
    <div class="product-list">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="product-card">
                <?php if (!empty($row['image'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="صورة المنتج" class="product-image">
                <?php else: ?>
                    <img src="images/vaccine.png" alt="لا توجد صورة" class="product-image">
                <?php endif; ?>
                
                <div class="product-details">
                    <h5 class="product-name"><?php echo htmlspecialchars($row['product_name']); ?></h5>
                    <p class="product-description"><?php echo htmlspecialchars($row['description']); ?></p>
                    <p class="product-price"><strong><?php echo htmlspecialchars($row['price']); ?> ر.س</strong></p>
                    
                    <div class="product-actions">
                        <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">تعديل</a>
                        <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
