<?php
  function pow2($n1, $n2) {
    $res = 0;
    $n1 = $n1 * $n1;
    $n2 = $n2 * $n2;

    $res = $n1 + $n2;
    return $res;
  }

  function pow2plus(&$n1, &$n2) {
    $res = 0;
    $n1 = $n1 * $n1;
    $n2 = $n2 * $n2;

    $res = $n1 + $n2;
    return $res;
  }

  $n1 = 2;
  $n2 = 4;

  $num = pow2($n1, $n2);
  echo "Number: " . $num ."<br>";
  echo "n1: ". $n1 ."<br>";
  echo "n2: ". $n2 ."<br>";

  $result = pow2plus($n1, $n2);
  echo "Number: ". $result ."<br>";
  echo "n1: ". $n1 ."<br>";
  echo "n2: ". $n2 ."<br>";
  
  include "func.php";

  $z = 123;
  echo "Tong cac chu so cua so $z la: " . sumofnum($z) ."<br>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Main PHP</title>
</head>
<body>

</body>
</html>