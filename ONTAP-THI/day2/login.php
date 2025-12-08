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
        $errors[] = "Vui lòng nhập username và password.";
    } else {
        $stmt = $conn->prepare("SELECT username, password FROM nhanvien WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($account && password_verify($password, $account['password'])) {
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Thông tin đăng nhập không đúng.";
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
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <div class="container__login">
        <form action="" method="post" class="form" novalidate>
            <h2 class="form__title">Chào mừng bạn trở lại</h2>

            <?php if (!empty($errors)): ?>
            <div style="color:var(--danger);margin-bottom:10px">
                <?php foreach($errors as $err) echo '<div>'.e($err).'</div>'; ?>
            </div>
            <?php endif; ?>

            <div class="form__group">
                <label for="username" class="form__label">Username</label>
                <input type="text" name="username" id="username" class="form__input" placeholder="Enter your name here"
                    autocomplete="off" required>
                <p class="form__error">Username không được để trống</p>
            </div>

            <div class="form__group">
                <label for="password" class="form__label">Password</label>
                <input type="password" name="password" id="password" class="form__input"
                    placeholder="Enter your password here" autocomplete="off" required>
                <p class="form__error">Mật khẩu không được để trống</p>
            </div>

            <input type="submit" value="Login" class="btn__submit">
            <p class="form__desc" style="margin-top:12px;color:var(--muted);font-size:13px">Chưa có tài khoản? <a
                    href="register.php">Đăng ký tại đây</a></p>
        </form>
    </div>

    <script>
    const username = document.getElementById("username");
    const password = document.getElementById("password");

    function validate(element) {
        element.addEventListener("blur", () => {
            const group = element.closest(".form__group");
            const error = group.querySelector(".form__error");
            if (element.value.trim() === "") {
                error.classList.add("show");
            } else {
                error.classList.remove("show");
            }
        });
    }

    validate(username);
    validate(password);
    </script>
</body>

</html>
