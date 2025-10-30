<?php
    function marks() {
        static $marks = 87;
        // Biến static sẽ được lưu lại giá trị sau mỗi lần gọi hàm marks()
        $marks++;
        echo "Static variable: $marks<br>";
    }
    marks();
    marks();
    marks();
    // Echo và Print
    $res = print 'Hello World<br>';
    echo $res;
    function student($name, $age) {
        if ($name == '' || $age == '') {
            echo "<p>Please enter your name and age.</p>";
            return;
        }
        echo "<p>Hello $name, you are $age years old.</p>";
        if ((int)$age >= 18) {
            echo "<p>You are allowed to enter.</p>";
        }
        else if ((int)$age >= 15) {
            echo "<p>You need an a passport and an adult card</p>";
        }
        else {
            echo" <p>You are not allowed to enter.</p>";

        }
    }
    echo '<br>Hello World<br>';
    $str = 'Hello World';
    function strings($str) {
        echo $str;
        echo '<br>';
        echo strlen($str);
        echo '<br>';
        echo strrev($str);
        echo '<br>';
        echo str_word_count($str);
        echo '<br>';
    }
    strings('Hello Bit');
    $fruits = ['Apple', 'Banana', 'Orange'];
    foreach ($fruits as $fruit) {
        echo $fruit;
        echo '<br>';
    }
    echo $fruits[2];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning PHP from scratch</title>
</head>

<body>
    <h1>Hello World</h1>
    <div>
        <form action="" method="post">
            <input type="text" name="name" id="name" placeholder="Enter your name">
            <input type="number" name="age" id="age" placeholder="Enter your age">
            <button>Submit</button>
        </form>
        <div>
            <?php
                if (isset($_POST['name']) && isset($_POST['age'])) {
                    student($_POST['name'], $_POST['age']);
                }
            ?>
        </div>
    </div>
</body>

</html>
