<?php
// edit.php
session_start();
require 'db.php';
$conn = connectDB();

$username = $_SESSION['username'] ?? '';
$manv = $_SESSION['manv'] ?? null;
if (!$username || !$manv) { header("Location: login.php"); exit; }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header("Location: index.php"); exit; }

// Lấy record và bảo đảm là owner
$stmt = $conn->prepare("SELECT id, title, content, meta FROM items WHERE id = ? AND manv = ?");
$stmt->execute([$id, $manv]);
$item = $stmt->fetch();
if (!$item) { header("Location: index.php"); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $meta = trim($_POST['meta'] ?? '');

    if ($title === '') $errors[] = "Tiêu đề không được bỏ trống.";

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE items SET title = ?, content = ?, meta = ? WHERE id = ? AND manv = ?");
        $stmt->execute([$title, $content, $meta, $id, $manv]);
        header("Location: index.php");
        exit;
    }
}

function e($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sửa mục</title>
    <link rel="stylesheet" href="assets/style_neutral.css">
</head>

<body>
    <div class="container__auth">
        <form method="post" class="form">
            <h2 class="form__title">Sửa mục</h2>

            <?php if ($errors): ?><div style="color:var(--danger);margin-bottom:10px">
                <?php foreach($errors as $err) echo '<div>'.e($err).'</div>'; ?></div><?php endif; ?>

            <div class="form__group"><label>Tiêu đề</label><input name="title" value="<?= e($item['title']) ?>"
                    required></div>
            <div class="form__group"><label>Nội dung</label><textarea
                    name="content"><?= e($item['content']) ?></textarea></div>
            <div class="form__group"><label>Meta</label><input name="meta" value="<?= e($item['meta']) ?>"></div>

            <div style="display:flex;gap:8px;justify-content:space-between">
                <a href="index.php" class="btn ghost" style="text-decoration:none;padding:8px 12px">Hủy</a>
                <button type="submit" class="btn primary">Lưu</button>
            </div>
        </form>
    </div>
</body>

</html>