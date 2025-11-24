<?php
session_start();
require 'db.php';

$errors = '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($username === '' || $password === '') {
        $errors = 'Vui lòng nhập đầy đủ username và password.';
    } else {
        $pdo = connectDB();

        // tìm user theo username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :u LIMIT 1");
        $stmt->execute([':u' => $username]);
        $user = $stmt->fetch();

        if (!$user) {
            $errors = 'Sai username hoặc password.';
        } else {
            // nếu dùng plain text (demo)
            if ($password === $user['password']) {
                // nếu dùng hash thì: if (password_verify($password, $user['password'])) { ... }

                // lưu session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // redirect sang dashboard
                header('Location: dashboard.php');
                exit;
            } else {
                $errors = 'Sai username hoặc password.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    /* mobile-first, đơn giản */
    body {
        font-family: Arial, sans-serif;
        background: #f3f4f6;
    }

    .login-box {
        max-width: 360px;
        margin: 40px auto;
        padding: 20px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 6px;
        border: 1px solid #ddd;
    }

    button {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 6px;
        background: #2563eb;
        color: #fff;
        font-weight: 600;
    }

    button:active {
        transform: scale(0.98);
    }

    .error {
        color: #dc2626;
        margin-bottom: 10px;
    }
    </style>
</head>

<body>
    <div class="login-box">
        <h2>Đăng nhập</h2>

        <?php if ($errors !== ''): ?>
        <p class="error"><?php echo htmlspecialchars($errors, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <form action="" method="post">
            <input type="text" name="username" placeholder="Username"
                value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="password" name="password" placeholder="Password">
            <button type="submit">Đăng nhập</button>
        </form>
    </div>
</body>

</html>