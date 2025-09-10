<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giải phương trình bặc 2</title>
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
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #ccc;
        box-shadow: 5px 5px 1px rgba(0, 0, 0, 0.20),
            10px 10px 5px rgba(0, 0, 0, 0.20);
    }

    .form__group {
        display: flex;
        gap: 10px;
        align-items: center;
        font-size: 1.8rem;
    }

    .form__group+.form__group {
        margin-top: 20px;
    }

    .form__input {
        flex: 1;
        width: 12px;
        padding: 8px;
        border-radius: 12px;
        outline: none;
        border: 1.5px solid #ccc;
    }

    .form__submit {
        width: 100%;
        padding: 12px 0;
        margin-top: 20px;
        border-radius: 12px;
        border: 1.5px solid #ccc;
        background-color: ivory;
        cursor: pointer;
    }

    .result {
        font-size: 2rem;
        text-align: center;
        background-color: aqua;
        padding: 12px;
        border-radius: 12px;
    }
    </style>
</head>

<body>
    <form action="" method="post">
        <h1>Giải phương trình bậc 2</h1>
        <div class="form__group">
            <label class="form__label" for="numbera">Nhập hệ số a: </label>
            <input type="number" name="numbera" id="numbera" class="form__input" required
                value="<?php echo isset($_POST["numbera"]) ? $_POST["numbera"] : "" ?>">
        </div>
        <div class="form__group">
            <label class="form__label" for="numberb">Nhập hệ số b: </label>
            <input type="number" name="numberb" id="numberb" class="form__input" required
                value="<?php echo isset($_POST["numberb"]) ? $_POST["numberb"] : "" ?>">
        </div>
        <div class="form__group">
            <label class="form__label" for="numberc">Nhập hệ số c: </label>
            <input type="number" name="numberc" id="numberc" class="form__input" required
                value="<?php echo isset($_POST["numberc"]) ? $_POST["numberc"] : "" ?>">
        </div>
        <button type="submit" class="form__submit">Xem kết quả</button>
        <?php
            function giaiPTB2($a, $b, $c) {
                if ($a == 0) {
                    if ($b == 0) {
                        return $c == 0 ? "Phương trình vô số nghiệm" : "Phương trình vô nghiệm";
                    } else {
                        $x = -$c / $b;
                        return "Phương trình bậc nhất, nghiệm: x = $x";
                    }
                }

                $delta = $b * $b - 4 * $a * $c;

                if ($delta > 0) {
                    $x1 = (-$b + sqrt($delta)) / (2 * $a);
                    $x2 = (-$b - sqrt($delta)) / (2 * $a);
                    return "Phương trình có 2 nghiệm phân biệt: x1 = $x1, x2 = $x2";
                } elseif ($delta == 0) {
                    $x = -$b / (2 * $a);
                    return "Phương trình có nghiệm kép: x = $x";
                } else {
                    return "Phương trình vô nghiệm (không có nghiệm thực)";
                }
            }

            if($_SERVER["REQUEST_METHOD"] == "POST") {
                $a = $_POST["numbera"];
                $b = $_POST["numberb"];
                $c = $_POST["numberc"];
                $result = giaiPTB2($a, $b, $c);
                echo "<h3 class=\"result\">$result</h3>";
            }
        ?>
    </form>
</body>

</html>