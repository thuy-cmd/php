<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "k9tin";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
