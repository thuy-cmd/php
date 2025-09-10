<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caculator</title>
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
        <h1>Mô phỏng máy tính điện tử</h1>
        <div class="form__group">
            <label class="form__label" for="numbera">Số thứ nhất: </label>
            <input type="number" name="numbera" id="numbera" class="form__input" required
                value="<?php echo isset($_POST["numbera"]) ? $_POST["numbera"] : "" ?>">
        </div>
        <div class="form__group">
            <label class="form__label" for="operator">Phép toán: </label>
            <select name="operator" id="operator" required class="form__input">
                <option value="+">+</option>
                <option value="-">-</option>
                <option value="*">*</option>
                <option value="/">/</option>
                <option value="%">%</option>
            </select>
        </div>
        <div class="form__group">
            <label class="form__label" for="numberb">Số thứ hai: </label>
            <input type="number" name="numberb" id="numberb" class="form__input" required
                value="<?php echo isset($_POST["numberb"]) ? $_POST["numberb"] : "" ?>">
        </div>
        <button type="submit" class="form__submit">Xem kết quả</button>
        <?php
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                $a = $_POST["numbera"];
                $b = $_POST["numberb"];
                $opr = $_POST["operator"];
                $result = 0;
                switch ($opr) {
                    case "+":
                        $result = $a + $b;
                        break;
                    case "-":
                        $result = $a - $b;
                        break;
                    case "*":
                        $result = $a * $b;
                        break;
                    case "/":
                        $result = $b != 0 ? $a / $b : "Khong the chia cho 0";
                        break;
                    default:
                        $result = "Phep toan khong hop le";
                }
                echo "<h3 class=\"result\">Kết quả: $a $opr $b = $result</h3>";
            }
        ?>
    </form>
</body>

</html>