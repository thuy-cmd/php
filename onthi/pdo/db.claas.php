<?php
abstract class Db {
    private static $username = "root";
    private static $password = "";
    private static $dns = "mysql:host=localhost;dbname=onthi;charset=utf8";
    public static $affected_rows;

    public static function connect () {
        try {
            $pdo = new PDO(self::$dns, self::$username, self::$password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        } catch (PDOException $e) {
            error_log(date('Y-m-d H:i:s') . " - Connection failed: " . $e->getMessage() . "\n", 3, __DIR__ . "/../logs/error.log");
            throw new Exception("Error Processing Request");
        }
        return $pdo;
    }
}
