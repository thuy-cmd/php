<?php
    session_start();
    if (isset($_SESSION["username"]) && isset($_SESSION["password"])) {
        $username = $_SESSION["username"];
        $password = $_SESSION["password"];
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
    <header>
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
                            <a class="nav-link" href="test-get.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="test-post.php">Register</a>
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
                    echo "<p class='lead'>Hello, <strong>$username</strong> - <strong>$password</strong>! You are logged in.</p>";
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
