<?php
// edit.php
session_start();
require 'db.php';
$conn = connectDB();

$username = $_SESSION['username'] ?? '';
if ($username === '') {
    header("Location: login.php");
    exit;
}

// Lấy manv
$stmt = $conn->prepare("SELECT manv FROM nhanvien WHERE username = ?");
$stmt->execute([$username]);
$manv = $stmt->fetchColumn();
if (!$manv) {
    header("Location: logout.php");
    exit;
}

$errors = [];
$task = null;
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

// load task, chỉ khi thuộc manv
$stmt = $conn->prepare("SELECT id, task_title, task_desc FROM nhiemvu WHERE id = ? AND manv = ?");
$stmt->execute([$id, $manv]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$task) {
    // không tìm thấy hoặc không phải owner
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['task'] ?? '');
    $desc = trim($_POST['desc'] ?? '');

    if ($title === '') {
        $errors[] = "Tên nhiệm vụ không được để trống.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE nhiemvu SET task_title = ?, task_desc = ? WHERE id = ? AND manv = ?");
        $stmt->execute([$title, $desc, $id, $manv]);
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
    <title>Edit nhiệm vụ</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <div class="container__login">
        <form action="" method="post" class="form">
            <h2 class="form__title">Sửa nhiệm vụ</h2>

            <?php if (!empty($errors)): ?>
            <div style="color:var(--danger);margin-bottom:10px">
                <?php foreach($errors as $err) echo '<div>'.e($err).'</div>'; ?>
            </div>
            <?php endif; ?>

            <div class="form__group">
                <label class="form__label">Tên nhiệm vụ</label>
                <input name="task" class="form__input" value="<?= e($task['task_title']) ?>">
            </div>

            <div class="form__group">
                <label class="form__label">Mô tả</label>
                <textarea name="desc" class="form__input"
                    style="min-height:120px"><?= e($task['task_desc']) ?></textarea>
            </div>

            <div style="display:flex;gap:8px;justify-content:space-between">
                <a href="index.php" class="btn ghost"
                    style="text-decoration:none;padding:10px 14px;display:inline-block;text-align:center">Hủy</a>
                <button type="submit" class="btn primary">Lưu</button>
            </div>
        </form>
    </div>
</body>

</html>
