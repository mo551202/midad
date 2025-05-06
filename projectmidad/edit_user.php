<?php
session_start();

// التحقق من صلاحيات الوصول، إذا لم يكن المستخدم Admin نوجهه إلى صفحة تسجيل الدخول
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// الاتصال بقاعدة البيانات
$host = 'localhost';
$dbname = 'ecommerce';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// التحقق من وجود معرف المستخدم وتعديل بياناته
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // قراءة بيانات المستخدم
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        // تحديث بيانات المستخدم
        $stmt = $conn->prepare("UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id");
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'role' => $role,
            'id' => $id
        ]);

        header('Location: users.php'); // التوجيه بعد التعديل
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تعديل المستخدم</title>
    <link rel="stylesheet" href="edit_user.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>تعديل المستخدم</h1>
            <p><a href="users.php" class="back-link">الرجوع إلى قائمة المستخدمين</a></p>
        </header>

        <section class="form-section">
            <h2>تفاصيل المستخدم</h2>
            <form method="POST" action="">
                <label for="username">اسم المستخدم:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br><br>

                <label for="email">البريد الإلكتروني:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br><br>

                <label for="role">الدور:</label>
                <select id="role" name="role" required>
                    <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>مستخدم</option>
                    <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>مدير</option>
                </select><br><br>

                <input type="submit" value="تحديث" class="btn-submit">
            </form>
        </section>
    </div>
</body>
</html>
