<?php
    // $host = "localhost";
    // $dbname = "k9tin";
    // $username = "root";
    // $password = "";

    try {
        // $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $conn = new PDO("mysql:host=localhost;dbname=k9tin", "root", "");

        // Bật chế độ báo lỗi
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Kết nối thành công!";
    } catch (PDOException $e) {
        echo "Kết nối thất bại: " . $e->getMessage() . "Vui lòng thử lại";
    }   
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
    html {
        font-size: 62.5%;
    }

    body {
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        display: flex;
        height: 100vh;
        align-items: center;
        justify-content: center;
    }

    form {
        width: 500px;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #ccc;
        box-shadow: 5px 5px 1px rgba(0, 0, 0, 0.20),
            10px 10px 5px rgba(0, 0, 0, 0.20);
    }

    .form__group {
        display: flex;
        flex-direction: column;
        font-size: 1.8rem;
    }

    .form__group+.form__group {
        margin-top: 20px;
    }

    .form__label {
        margin-bottom: 4px;
        display: block;
    }

    .form__input {
        padding: 8px 0px 8px 8px;
        border-radius: 12px;
        outline: none;
        border: 1.5px solid #ccc;
    }

    .form__submit {
        width: 100%;
        padding: 12px 0;
        cursor: pointer;
        margin-top: 20px;
        border-radius: 12px;
        background-color: ivory;
        border: 1.5px solid #ccc;
    }

    .result,
    .error {
        font-size: 2rem;
        text-align: center;
        background-color: aqua;
        padding: 12px;
        border-radius: 12px;
    }

    .error {
        color: #fff;
        background-color: crimson;
    }
    </style>
</head>

<body>
    <form action="" method="post" autocomplete="off">
        <h1 class="title">Login</h1>
        <div class="form__group">
            <label class="form__label" for="username">Username: </label>
            <input type="text" name="username" id="username" class="form__input" required autocomplete="off">
        </div>
        <!-- <div class="form__group">
            <label class="form__label" for="email">Email: </label>
            <input type="email" name="email" id="email" class="form__input" required autocomplete="off">
        </div> -->
        <div class="form__group">
            <label class="form__label" for="password">Password: </label>
            <input type="password" name="password" id="password" class="form__input" required autocomplete="off">
        </div>
        <button type="submit" class="form__submit">Login</button>
        <?php
            function test_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

            
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = test_input($_POST["username"]);
                // $email = $_POST["email"];
                $password = $_POST["password"];

                if (($username == "biteric") && ($password == "1234")) {
                    echo "<p class=\"result\">Đăng nhập thanh công!</p>";
                }
                else {
                    echo "<p class=\"error\"'>Đăng nhập thất bại</p>";
                }
            }
        ?>
    </form>
</body>

</html>