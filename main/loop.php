<?php
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vòng lặp trong PHP</title>
    <style>
    * {
        margin: 0;
        padding: 0;
    }

    html {
        font-size: 62.5%;
    }

    body {
        font-size: 1.6rem;
        padding: 20px;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }
    </style>
</head>

<body>
    <h1>Vòng lặp trong PHP</h1>
    <form action="" method="post">
        <input type="number" name="number" id="number" placeholder="Nhập số lần muốn in">
        <button type="submit">Click me</button>
    </form>
    <?php
        if (!empty($_POST["number"])) {
            $num = $_POST["number"];
            for ($i = 0; $i < $num; $i++) {
                echo "Tớ rất thích cậu, Chi ơi!" . "<br>";
            }
        }
        else {
            echo "Chưa nhập số";
        }
    ?>
    <ul>
        <?php
            $numbers = [1,2,3,4,5];
            // foreach($numbers as $number) {
            //     echo "<li>". $number ."</li>";
            // }
            array_map(function($n) {
                echo "<li>". $n ."</li>";
            },$numbers);
        ?>
    </ul>
</body>

</html>