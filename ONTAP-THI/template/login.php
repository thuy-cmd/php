<?php
// login.php
session_start();
require 'db.php';
$conn = connectDB();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $errors[] = "Vui lòng nhập đầy đủ thông tin.";
    } else {
        $stmt = $conn->prepare("SELECT manv, username, password FROM nhanvien WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            // set session
            $_SESSION['username'] = $user['username'];
            $_SESSION['manv'] = $user['manv'];
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Tên đăng nhập hoặc mật khẩu không đúng.";
        }
    }
}

function e($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="assets/style_neutral.css">
</head>

<body>
    <div class="container__auth">
        <form method="post" class="form" novalidate>
            <h2 class="form__title">Đăng nhập</h2>

            <?php if ($errors): ?>
            <div style="color:var(--danger);margin-bottom:10px">
                <?php foreach ($errors as $err) echo '<div>'.e($err).'</div>'; ?>
            </div>
            <?php endif; ?>

            <div class="form__group"><label>Username</label><input name="username" type="text" required></div>
            <div class="form__group"><label>Password</label><input name="password" type="password" required></div>

            <div style="display:flex;gap:8px;justify-content:flex-end">
                <a href="register.php" class="btn ghost" style="text-decoration:none;padding:8px 12px">Register</a>
                <button type="submit" class="btn primary">Login</button>
            </div>
        </form>
    </div>
</body>

</html>