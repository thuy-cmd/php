<?php
session_start();

// nếu chưa login thì đá về login
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'] ?? 'Guest';
$role     = $_SESSION['role'] ?? 'user';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trang quản trị</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <h1>Xin chào, <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>!</h1>
    <p>Role: <?php echo htmlspecialchars($role, ENT_QUOTES, 'UTF-8'); ?></p>

    <p><a href="logout.php">Đăng xuất</a></p>
</body>

</html>