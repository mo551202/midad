<?php
session_start();

$pdo = new PDO("mysql:host=localhost;dbname=ecommerce", "root", "");

// التحقق من وجود معرف المنتج
if (!isset($_GET['id'])) {
    echo "معرف المنتج غير موجود";
    exit;
}

$id = $_GET['id'];
$from = isset($_GET['from']) ? $_GET['from'] : 'index.php';

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "المنتج غير موجود.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تفاصيل المنتج</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="product.css">
</head>
<body>

<div class="container">
  <h2>تفاصيل المنتج</h2>

  <div class="product-card">
    <?php if (!empty($product['image'])): ?>
      <img src="<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="صورة المنتج">
    <?php else: ?>
      <img src="images/y.jpg" class="card-img-top" alt="صورة افتراضية">
    <?php endif; ?>

    <div class="card-body">
      <h5 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h5>
      <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
      <p class="price">السعر: <?php echo htmlspecialchars($product['price']); ?> ر.س</p>
      
      <a href="<?php echo $from; ?>" class="btn btn-secondary">الرجوع</a>

      <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-warning">تعديل المنتج</a>
      <?php endif; ?>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
