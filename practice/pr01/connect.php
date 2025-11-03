<?php
    function h($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    try {
        $dsn = 'mysql:host=localhost;dbname=hocphp;charset=utf8mb4';
        $pdo = new PDO($dsn, 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        echo "Connection successful!";
    }
    catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    $sql = "SELECT * FROM products";
    $stmt = $pdo->query($sql);
    $products = $stmt->fetchAll();
    var_dump($products);
