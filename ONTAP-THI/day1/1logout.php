<?php
session_start();
require '1db.php';
$conn = connectDB();
$_SESSION = [];
session_destroy();

header("Location: 1login.php");
exit;
