<?php
    echo "OOP PHP";
    class Person
    {
        public $name;
        private $age;

        function set_name($name) {
            $this->name = $name;
        }

        function set_age($age) {
            $this->age = $age;
        }

        function get_name() {
            return $this->name;
        }

        function get_age() {
            return $this->age;
        }


    }

    $person = new Person();
    $person->set_name("Nguyen Van A");
    $person->set_age(20);

    echo "<br>Name: ". $person->get_name() ."<br>";
    echo "Age: ". $person->get_age() ."<br>";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OOP PHP</title>
    <style>
    body {
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }
    </style>
</head>

<body>
    <form action="" method="post">
        <input type="text" name="name" id="name" class="name" placeholder="Nhap ten cua ban">
        <input type="number" name="age" id="age" class="age" placeholder="Nhap tuoi cua ban">
        <button type="submit">Gủi</button>
    </form>
    <div class="infor">
        <h1>Thông tin người dùng</h1>
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $person = new Person();
                $person->set_name($_POST["name"]);
                $person->set_age($_POST["age"]);

                echo "<h2>";
                echo "<strong>Name: </strong>". $person->get_name();
                echo "</h2>";
                echo "<p>";
                echo "<strong>Age: </strong>" . $person->get_age();
                echo "</p>";

                $person->name = "Eric";
                echo "<h2>";
                echo "<strong>Name: </strong>". $person->get_name();
                echo "</h2>";
            }
        ?>
    </div>
</body>

</html>