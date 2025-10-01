<?php
    if (isset($_POST["name"]) && $_POST["name"] !== "") {
        $name = $_POST["name"];
    } else {
        $name = "Enter your name please";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>String PHP</title>
</head>

<body>
    <form method="POST">
        <input type="text" id="name" name="name" placeholder="Enter your name here">
        <input type="submit" value="Submit">
    </form>

    <?php
        if (!str_contains($name, "Enter")) {
            echo "Hello $name <br>";
        } else {
            echo $name . "<br>";
        }

        for ($i = 0; $i < mb_strlen($name); $i++) {
            echo "Ki tu $i : $name[$i] <br>";
        }
    ?>
    <script>
    window.addEventListener('submit', (e) => {
        e.preventDefault();
    })
    </script>
</body>

</html>