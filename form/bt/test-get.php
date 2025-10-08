<?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET["username"]) && isset($_GET["password"])) {
            $username = trim($_GET["username"]);
            $password = trim($_GET["password"]);
            $_SESSION["username"] = $username;
            header("Location: home.php");
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <h1 class="text-center">Hello, World!</h1>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Login</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="GET">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                            <div class="mt-3 text-center action-links">
                                <a href="register.php">Don't have an account? Register here.</a>
                            </div>
                        </form>
                    </div>
                    <div class="mt-3 text-center">
                        <?php
                                if (isset($username) && isset($password)) {
                                    echo "<p class='text-success'>Logged in as: <strong>$username</strong></p>";
                                    echo "<p class='text-success'>Password: <strong>$password</strong></p>";
                                }
                                else {
                                    echo "<p class='text-danger'>Please provide both username and password.</p>";
                                }
                            ?>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET["username"]) && isset($_GET["password"])) {
            $username = trim($_GET["username"]);
            $password = trim($_GET["password"]);
            $_SESSION["username"] = $username;
            $_SESSION["password"] = $password;
            header("Location: home.php");
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <h1 class="text-center">Hello, World!</h1>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Login</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="GET">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                            <div class="mt-3 text-center action-links">
                                <a href="register.php">Don't have an account? Register here.</a>
                            </div>
                        </form>
                    </div>
                    <div class="mt-3 text-center">
                        <?php
                                if (isset($username) && isset($password)) {
                                    echo "<p class='text-success'>Logged in as: <strong>$username</strong></p>";
                                    echo "<p class='text-success'>Password: <strong>$password</strong></p>";
                                }
                                else {
                                    echo "<p class='text-danger'>Please provide both username and password.</p>";
                                }
                            ?>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
