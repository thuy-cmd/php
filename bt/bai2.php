<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bai 2</title>
</head>

<body>
    <h1>Bai tap 2</h1>
    <form action="" method="post">
        <input type="text" name="year" id="year" placeholder="VD: (2024)">
        <button type="submit" name="check">Kiem tra</button>
    </form>
    <pre>
        <?php
            if (isset($_POST["check"])) {
                $year = $_POST["year"];
                if (($year % 4 === 0 && $year % 100 !== 0) || ($year % 400 === 0)) {
                    echo "Nam $year la nam nhuan <br>";
                }
                else echo "Nam $year la nam khong nhuan <br>";
            }
        ?>
    </pre>
</body>

</html>