<?php
    $courses = array("PHP", "Laravel", "ReactJS");
    $numbers = [4,2,1,66,44,23];
    $core = [2,3,5,1,3,5,7,2];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Array in PHP</title>
</head>

<body>
    <?php
        foreach ($courses as $course) {
            echo "Course: ". $course ."<br>";
        }
        echo "<br>";
        for ($i = 0; $i < count($courses); $i++) {
            echo "Courses $i: ". $courses[$i] ."<br>";   
        }
        $pop = array_pop($numbers); // Xóa phần tử ở cuối
        echo "<br>" > $pop;

        $shift = array_shift($numbers); // Xóa phần tử ở đầu
        echo "<br>" . $shift;
        echo "<br>";
        $sum = array_sum($core);
        $lengh = count($core);
        $max = max($core);
        $min = min($core);

        echo "Trung binh: " . $sum / $lengh . "<br>";
        echo "Tong: " . $sum . "<br>";
        echo "Max: " . $max . "<br>";
        echo "Min: " . $min . "<br>";

        echo "Kiem tra khoa key co trong mang array khong <br>";
        if (array_key_exists("2", $core)) {
            echo "Exists ". $core["2"] ."<br>";
        }
        else {
            echo "Not exists". $core["2"] ."<br>";
        }
        
        echo $str = implode("-->", $courses) . "<br>";
        $txt = "LEARN PHP JAVACORE";
        $subtxt = explode(" ", $txt);
        for ($i = 0; $i < count($subtxt); $i++) {
            echo $subtxt[$i] ."<br>";
        }

        foreach ($subtxt as $sub) {
            echo $sub ."<br>";
        }

        echo "Truy xuất các phần tử của mảng <br>";
        echo "Current: " . current($courses) . "<br>";
        echo "Next: " . next($courses) . "<br>";
        echo "Current: " . current($courses) . "<br>";
        echo "Current: " . current($courses) . "<br>";

        echo "So sanh khac nhau <br>";
        $a1 = array("name" => "PHP", "time" => 120, "zend", "joomla");
        $a2 = array("PHP", 100);

        $diff = array_intersect($a1, $a2);

        echo "<pre>";
        print_r($a1);
        echo "</pre>";

        echo "<pre>";
        print_r($a2);
        echo "</pre>";

        echo "<pre>";
        print_r( $diff);
        echo "</pre>";
    ?>
</body>

</html>