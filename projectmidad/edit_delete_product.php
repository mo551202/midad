<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$product_id = $_GET['id'];
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "المنتج غير موجود.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تعديل وحذف المنتج - معرض السطوف</title>
    <link rel="stylesheet" href="edit_delete_product.css"> <!-- ملف CSS المخصص -->
</head>
<body>

<div class="container">
    <h2>تعديل أو حذف المنتج</h2>
    <div class="product-card">
        <div class="product-image">
            <?php if (!empty($product['image'])): ?>
                <img src="uploads/<?= htmlspecialchars($product['image']) ?>" alt="صورة المنتج">
            <?php else: ?>
                <img src="images/default.png" alt="صورة افتراضية">
            <?php endif; ?>
        </div>
        
        <div class="product-details">
            <form action="update_product.php" method="POST">
                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                
                <div class="form-group">
                    <label for="product_name">اسم المنتج:</label>
                    <input type="text" id="product_name" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description">الوصف:</label>
                    <textarea id="description" name="description" required><?= htmlspecialchars($product['description']) ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="price">السعر:</label>
                    <input type="number" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
                </div>

                <button type="submit" class="btn update-btn">تعديل المنتج</button>
            </form>
            
            <form action="delete_product.php" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                <button type="submit" class="btn delete-btn">حذف المنتج</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
