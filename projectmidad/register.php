<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=ecommerce", "root", "");

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        $errors[] = "جميع الحقول مطلوبة.";
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        $errors[] = "البريد الإلكتروني مستخدم بالفعل.";
    }

    if (strlen($password) < 6) {
        $errors[] = "كلمة المرور يجب أن تكون 6 أحرف على الأقل.";
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->execute([$username, $email, $hashedPassword]);

        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إنشاء حساب</title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
  <link rel="stylesheet" href="register.css">
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">

</head>
<body>
  <div class="r">
    <div class="form-box">
      <h2 class="text-center mb-4">إنشاء حساب جديد</h2>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
              <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label for="username" class="form-label">اسم المستخدم</label>
          <input type="text" name="username" id="username" class="form-control" required value="<?php echo htmlspecialchars($username ?? '') ?>">
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">البريد الإلكتروني</label>
          <input type="email" name="email" id="email" class="form-control" required value="<?php echo htmlspecialchars($email ?? '') ?>">
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">كلمة المرور</label>
          <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">تسجيل</button>
      </form>
      <p class="mt-3 text-center">هل لديك حساب؟ <a href="login.php">سجّل الدخول</a></p>
    </div>
  </div>
</body>
</html>
