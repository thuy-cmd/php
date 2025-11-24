<?php
    function h($str) {
        return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
    }
    function connectdb() {
        $servername = 'localhost';
        $dbname = 'test';
        $username = 'root';
        $password = '';

        $dsn= "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";

        try {
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            echo "Connected";
            return $pdo;
        } catch (PDOException $e) {
            http_response_code(500);
            die("Connect faild" . $e->getMessage());
        }
    }