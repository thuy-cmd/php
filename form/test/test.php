<?php
// demo.php — minh họa gửi thông tin đơn giản bằng GET & post
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['name'])) {
    $name = htmlspecialchars($_GET['name'] ?? '');
    $age  = (int)($_GET['age'] ?? 0);
    echo "GET → Hello, $name! Age: $age";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $msg = htmlspecialchars($_POST['message'] ?? '');
    echo "POST → Received message: $msg";
    exit;
}
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title>GET & POST demo</title>
</head>

<body>
    <h3>GET demo (tham số nằm trên URL)</h3>
    <form method="get" action="">
        <input name="name" placeholder="Your name">
        <input name="age" type="number" placeholder="Age">
        <button type="submit">Send GET</button>
    </form>

    <hr>

    <h3>POST demo (dữ liệu nằm trong body)</h3>
    <form method="post" action="">
        <input name="message" placeholder="Your message">
        <button type="submit">Send POST</button>
    </form>
</body>

</html>