<?php
    function h($s) {return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');}

    $servername = "localhost";
    $dbname = "onthi";
    $username = "root";
    $password = "";

    try {
        $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
        echo "Connect fail: " . $e->getMessage();
        die("Connect fail");
    }

    $movies = [];
    $search = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $search = trim($_POST['search']);

        if ($search !== '') {
            $like = '%' . str_replace(['\\','%','_'], ['\\\\','\\%','\\_'], $search) . '%';
            $sql  = "SELECT * FROM movies WHERE title LIKE :kw ESCAPE '\\\\' ORDER BY title ASC";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':kw' => $like]);
            $movies = $stmt->fetchAll();
        }
        else {
            $sql = "SELECT * FROM movies ORDER BY id DESC";
            $stmt = $conn->query($sql);
            $movies = $stmt->fetchAll();
        }
    }
    else {
        $sql = "SELECT * FROM movies ORDER BY id DESC";
        $stmt = $conn->query($sql);
        $movies = $stmt->fetchAll();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Day 1</title>
    <style>
    li {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 0;
    }
    </style>
</head>

<body>
    <form action="" method="post">
        <label for="search">Tim kiem</label>
        <input type="text" name="search" placeholder="Nhap thong tin phim" id="search" value="<?= h($search) ?>">
        <button type="submit">Tìm kiếm</button>
    </form>
    <ul>
        <?php if($movies): ?>
        <?php foreach($movies as $movie): ?>
        <li>
            <strong><?= h($movie['title']) ?></strong> - <?= h($movie['genre']) ?> - <?= h($movie['year']) ?>
            <div>
                <button>Sửa</button>
                <button>Xóa</button>
            </div>
        </li>
        <?php endforeach ?>
        <?php else: ?>
        <li>Chưa có bộ phim nào được thêm</li>
        <?php endif; ?>
    </ul>
</body>

</html>
