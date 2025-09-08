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
    ?>
</body>
</html>