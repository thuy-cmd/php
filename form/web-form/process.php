<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"])) {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        $email = trim($_POST["email"]);

        if ($username && $password && $email) {
            $_SESSION["username"] = $username;
            $_SESSION["password"] = $password;
            $_SESSION["email"] = $email;
            header("Location: home.php");
            exit();
        } else {
            header("Location: login.php?error=Tên đăng nhập hoặc mật khẩu không đúng");
            exit();
        }
    } else {
        header("Location: login.php?error=Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>
