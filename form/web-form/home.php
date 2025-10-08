<?php
    session_start();
    if (isset($_SESSION["username"]) && isset($_SESSION["password"]) && isset($_SESSION["email"])) {
        $username = $_SESSION["username"];
        $password = $_SESSION["password"];
        $email = $_SESSION["email"];

    } else {
        echo "No user is logged in.";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">MyApp</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="home.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div class="container mt-5">
        <div class="jumbotron">
            <h1 class="display-4">Welcome to MyApp!</h1>
            <?php
                if (isset($username) && isset($password)) {
                    echo "<div>";
                    echo "<h1>Hello, welcome back!</h1>";
                    echo "<p class='lead'>You are logged in.</p>";
                    echo "<p>Your information is.";
                    echo "<p>Usename: <strong>$username</strong></p>";
                    echo "<p>Email: <strong>$email</strong></p>";
                    echo "<p>Password: <strong>$password</strong></p>";
                    echo "</div>";
                } else {
                    echo "<p class='lead'>Hello, Guest! Please log in or register.</p>";
                }
            ?>
            <p class="lead">This is a simple home page.</p>
            <hr class="my-4">
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
