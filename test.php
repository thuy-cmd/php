<?php
    $txt = "Hello World!";
    echo $txt;
    echo "<br>";
    echo "Do dai cua chuoi: " . strlen($txt) . "<br>"; // do dai chuoi
    echo "Chuoi dao nguoc: " . strrev($txt) . "<br>"; // dao nguoc chuoi
    echo "Vi tri cua chuoi World: " . strpos($txt, "World") . "<br>"; // vi tri chuoi con trong chuoi cha
    echo "Thay the chuoi World thanh PHP: " . str_replace("World", "PHP", $txt) . "<br>"; // thay the chuoi con trong chuoi cha
    if ($txt != "") {
        echo "Chuoi khong rong";
    } else {
        echo "Chuoi rong";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File test PHP</title>
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
    <form action="" method="post">
        <input type="text" placeholder="Nhap ten cua ban" id="name" name="name">
        <button type="submit">Show Name</button>
    </form>
    <div class="info__name">
        <span>Thong tin cua ban:</span>
        <br>
        <span id="showName">
            <?php
                $name = "";
                if (isset($_POST["name"])) {
                    $name = $_POST["name"];
                    if ($name == "") {
                        echo "Vui long nhap ten";
                    }
                    else {
                        echo "Ten cua ban la: " . $name;
                    }
                }
                else {
                    echo "Chua nhap ten";
                }
            ?>
        </span>
    </div>
    <script>
    window.addEventListener('load', (e) => e.preventDefault());
    </script>
</body>

</html>