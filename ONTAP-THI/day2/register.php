<?php
// register.php
session_start();
require 'db.php';
$conn = connectDB();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');

    if ($username === '' || $password === '' || $confirm === '') {
        $errors[] = "Vui lòng điền đầy đủ thông tin.";
    } elseif ($password !== $confirm) {
        $errors[] = "Mật khẩu không trùng khớp.";
    } else {
        // check username exists
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
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <div class="container__login">
        <form action="" method="post" class="form" novalidate>
            <h2 class="form__title">Chào mừng bạn mới</h2>

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

            <div class="form__group">
                <label for="confirm_password" class="form__label">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form__input"
                    placeholder="Confirm your password here" autocomplete="off" required>
                <p class="form__error">Mật khẩu không trùng khớp</p>
            </div>

            <input type="submit" value="Register" class="btn__submit">
            <p class="form__desc" style="margin-top:12px;color:var(--muted);font-size:13px">Đã có tài khoản? <a
                    href="login.php">Đăng nhập tại đây</a></p>
        </form>
    </div>

    <script>
    const username = document.getElementById("username");
    const password = document.getElementById("password");
    const confirm_password = document.getElementById("confirm_password");

    function validate(element, confirm = false) {
        element.addEventListener("blur", () => {
            const group = element.closest(".form__group");
            const error = group.querySelector(".form__error");
            if (confirm) {
                if (confirm_password.value.trim() === "" || password.value !== confirm_password.value) {
                    error.textContent = "Password không trùng khớp";
                    error.classList.add("show");
                } else {
                    error.classList.remove("show");
                }
                return;
            }
            if (element.value.trim() === "") {
                error.classList.add("show");
            } else {
                error.classList.remove("show");
            }
        });
    }

    validate(username);
    validate(password);
    validate(confirm_password, true);
    </script>
</body>

</html>
