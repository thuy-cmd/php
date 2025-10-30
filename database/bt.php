<?php
    $tasks = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['task'] ?? '');

    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="" method="post">
        <input type="text" name="task" placeholder="Nhập nhiệm vụ cần làm">
        <button>Gửi</button>
    </form>
</body>

</html>
