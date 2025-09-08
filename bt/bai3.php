<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bai 3</title>
</head>
<body>
    <h3>Bài 3 — Bảng cửu chương (2 → 9)</h3>
    <pre><?php
        for ($i = 2; $i <= 9; $i++) {
            echo "Bảng nhân {$i}:\n";
            for ($j = 1; $j <= 10; $j++) {
            echo sprintf("%d x %d = %d\n", $i, $j, $i * $j);
            }
            echo "\n";
        }
    ?></pre>
</body>
</html>