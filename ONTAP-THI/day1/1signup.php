<?php
session_start();
require '1db.php';
$conn = connectDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ??'';
    $role = $_POST['role'] ?? '';

    // $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
    // $stmt->execute([':username' => $username, ':password' => $password, ':role' => $role]);
    $stmt = $conn->prepare("SELECT username, password, role FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $account = $stmt->fetch();

    if ($account['password'] === $password) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        header("Location: 1index.php");
        exit;
    }
    else {
        echo "Thong tin tai khoan mat khau khong chinh xac, vui long thu lai";
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ON THI BUOI 1 | Login</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body class="body__login">
    <div class="form__wrap">
        <h2 class="form__title">Welcome to our website</h2>
        <form action="" method="post" class="form">
            <div class="form__group">
                <label for="username" class="form__label">Username:</label>
                <input type="text" name="username" id="username" class="form__input" placeholder="Username" required
                    autocomplete="off">
            </div>
            <div class="form__group">
                <label for="password" class="form__label">Password:</label>
                <input type="password" name="password" id="password" class="form__input" placeholder="Password" required
                    autocomplete="off">
            </div>
            <div class="form__group">
                <label for="role" class="form__label">Role:</label>
                <select name="role" id="role" class="form__input">
                    <option value="">---Role---</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="form__submit">Send</button>
        </form>
    </div>
</body>

</html>
