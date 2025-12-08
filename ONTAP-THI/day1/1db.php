<?php
function h($string) {
    return htmlspecialchars((string)$string, ENT_QUOTES, "UTF-8");
}

function connectDB() {
    $servername = 'localhost';
    $dbname = 'onthi';
    $username = 'root';
    $password = '';
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        http_response_code(500);
        die("Connect faild" . $e->getMessage());
    }
}
