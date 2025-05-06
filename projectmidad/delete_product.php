<?php
session_start();
include 'db.php';

// التأكد من أن المستخدم مسجل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'] ?? 'user'; // 'admin' أو 'user'

    // التحقق إذا كان المنتج يخص المستخدم أو إذا كان المدير
    if ($role === 'admin') {
        // إذا كان المستخدم مديرًا، يمكنه حذف أي منتج
        $query = "SELECT * FROM products WHERE id = ?";
    } else {
        // إذا كان المستخدم عاديًا، يمكنه حذف منتجاته فقط
        $query = "SELECT * FROM products WHERE id = ? AND user_id = ?";
    }

    $stmt = $conn->prepare($query);
    if ($role === 'admin') {
        $stmt->bind_param("i", $product_id);
    } else {
        $stmt->bind_param("ii", $product_id, $user_id);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // حذف المنتج
        $delete_query = "DELETE FROM products WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $product_id);
        $delete_stmt->execute();

        // إعادة التوجيه بعد الحذف
        header("Location: manage_products.php?action=deleted");
        exit;
    } else {
        echo "لا يمكنك حذف هذا المنتج.";
    }
}
?>
