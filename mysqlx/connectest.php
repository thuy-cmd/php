<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "k9tin";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Không thể kết nối CSDL: " . mysqli_connect_error());
}
?>
