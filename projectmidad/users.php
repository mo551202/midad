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

// التحقق من إجراء الحذف أو التعديل
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    // حذف المستخدم
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute(['id' => $delete_id]);
    header('Location: users.php');
    exit;
}

// إضافة مستخدم جديد
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // تشفير كلمة المرور
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // إدخال المستخدم الجديد
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
    $stmt->execute([
        'username' => $username,
        'email' => $email,
        'password' => $hashed_password,
        'role' => $role
    ]);
    header('Location: users.php');
    exit;
}

// قراءة جميع المستخدمين
$stmt = $conn->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إدارة المستخدمين</title>
    <link rel="stylesheet" href="users.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>إدارة المستخدمين</h1>
            <p><a href="admin_dashboard.php" class="back-link">الرجوع إلى لوحة التحكم</a></p>
        </header>

        <section class="add-user">
            <h2>إضافة مستخدم جديد</h2>
            <form method="POST" action="">
                <label for="username">اسم المستخدم:</label>
                <input type="text" id="username" name="username" required><br><br>

                <label for="email">البريد الإلكتروني:</label>
                <input type="email" id="email" name="email" required><br><br>

                <label for="password">كلمة المرور:</label>
                <input type="password" id="password" name="password" required><br><br>

                <label for="role">الدور:</label>
                <select id="role" name="role" required>
                    <option value="user">مستخدم</option>
                    <option value="admin">مدير</option>
                </select><br><br>

                <input type="submit" name="add_user" value="إضافة مستخدم" class="btn-submit">
            </form>
        </section>

        <section class="user-list">
            <h2>قائمة المستخدمين</h2>
            <table>
                <thead>
                    <tr>
                        <th>اسم المستخدم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الدور</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn-edit">تعديل</a>
                            <a href="users.php?delete_id=<?php echo $user['id']; ?>" onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');" class="btn-delete">حذف</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
