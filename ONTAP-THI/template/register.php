<?php
// register.php
session_start();
require 'db.php';
$conn = connectDB();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm'] ?? '');

    if ($username === '' || $password === '' || $confirm === '') {
        $errors[] = "Vui lòng điền đầy đủ thông tin.";
    } elseif ($password !== $confirm) {
        $errors[] = "Mật khẩu không khớp.";
    } else {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM nhanvien WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Username đã tồn tại.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO nhanvien (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hash]);
            header("Location: login.php");
            exit;
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
    <title>Register</title>
    <link rel="stylesheet" href="assets/style_neutral.css">
</head>

<body>
    <div class="container__auth">
        <form method="post" class="form" novalidate>
            <h2 class="form__title">Tạo tài khoản</h2>

            <?php if ($errors): ?>
            <div style="color:var(--danger);margin-bottom:10px">
                <?php foreach ($errors as $err) echo '<div>'.e($err).'</div>'; ?>
            </div>
            <?php endif; ?>

            <div class="form__group">
                <label>Username</label>
                <input name="username" type="text" required>
            </div>

            <div class="form__group">
                <label>Password</label>
                <input name="password" type="password" required>
            </div>

            <div class="form__group">
                <label>Confirm Password</label>
                <input name="confirm" type="password" required>
            </div>

            <div style="display:flex;gap:8px;justify-content:flex-end">
                <a href="login.php" class="btn ghost" style="text-decoration:none;padding:8px 12px">Back</a>
                <button type="submit" class="btn primary">Register</button>
            </div>
        </form>
    </div>
</body>

</html>