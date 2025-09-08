<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bai 1</title>
</head>

<body>
    <h1>Bai tap 1</h1>
    <form action="" method="post">
        <input type="number" name="numbera" id="numA" placeholder="Nhap so A">
        <input type="number" name="numberb" id="numB" placeholder="Nhap so B">
        <button type="submit" name="submit">Tinh toan</button>
    </form>
    <pre>
        <?php
            if (isset($_POST["submit"])) {
                $a = $_POST["numbera"];
                $b = $_POST["numberb"];
                echo "<h3>Ket qua:</h3>";
                echo "Tong: $a + $b = " . ($a + $b) . "<br>";
                echo "Hieu: $a - $b = " . ($a - $b) . "<br>";
                echo "Tich: $a * $b = " . ($a * $b) . "<br>";
                if ($b != 0) {
                    echo "Thuong: $a / $b = " . ($a / $b) . "<br>";
                }
                else echo "Khong the chia cho 0";
            }
        ?>
    </pre>
</body>

</html>