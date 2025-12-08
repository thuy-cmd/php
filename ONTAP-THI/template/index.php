<?php
// index.php
session_start();
require 'db.php';
$conn = connectDB();

$username = $_SESSION['username'] ?? '';
$manv = $_SESSION['manv'] ?? null;
if (!$username || !$manv) {
    header("Location: login.php");
    exit;
}

$errors = [];
// Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $meta = trim($_POST['meta'] ?? '');

    if ($title === '') $errors[] = "Tiêu đề không được bỏ trống.";

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO items (manv, title, content, meta) VALUES (?, ?, ?, ?)");
        $stmt->execute([$manv, $title, $content, $meta]);
        header("Location: index.php");
        exit;
    }
}

// Delete (chỉ owner)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete' && !empty($_POST['id'])) {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("DELETE FROM items WHERE id = ? AND manv = ?");
    $stmt->execute([$id, $manv]);
    header("Location: index.php");
    exit;
}

// Fetch items of this user
$stmt = $conn->prepare("SELECT id, title, content, meta, created_at FROM items WHERE manv = ? ORDER BY created_at DESC");
$stmt->execute([$manv]);
$items = $stmt->fetchAll();

function e($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard — Template</title>
    <link rel="stylesheet" href="assets/style_neutral.css">
</head>

<body>
    <div class="container">
        <header class="header">
            <div class="brand">
                <div class="logo">TM</div>
                <div class="title">
                    <h1>Template Manager</h1>
                    <p class="meta">Xin chào, <strong><?= e($username) ?></strong></p>
                </div>
            </div>
            <div class="row">
                <div class="small">Mã NV: <span class="pill"><?= e($manv) ?></span></div>
                <a href="logout.php" class="btn ghost" style="text-decoration:none">Đăng xuất</a>
            </div>
        </header>

        <?php if ($errors): ?>
        <div style="color:var(--danger);margin-bottom:12px">
            <?php foreach ($errors as $err) echo '<div>'.e($err).'</div>'; ?>
        </div>
        <?php endif; ?>

        <main class="grid">
            <section class="card">
                <div class="toolbar">
                    <div>
                        <h3 style="margin:0">Danh sách</h3>
                        <div class="small"><?= count($items) ?> mục</div>
                    </div>
                    <div class="row">
                        <input id="q" class="input" placeholder="Tìm theo tiêu đề hoặc meta">
                        <button id="clear" class="btn ghost">Xóa</button>
                    </div>
                </div>

                <?php if (empty($items)): ?>
                <div class="empty">Chưa có mục nào — thêm bằng form bên phải.</div>
                <?php else: ?>
                <table class="table" id="list">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Tiêu đề</th>
                            <th style="width:180px">Ngày tạo</th>
                            <th style="width:160px">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=0; foreach($items as $it): $i++; ?>
                        <tr data-title="<?= e(mb_strtolower($it['title'])) ?>"
                            data-meta="<?= e(mb_strtolower($it['meta'])) ?>">
                            <td><?= $i ?></td>
                            <td>
                                <div style="font-weight:600"><?= e($it['title']) ?></div>
                                <div class="small"><?= e($it['content']) ?></div>
                                <?php if ($it['meta']): ?><div class="small">Meta: <?= e($it['meta']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="small"><?= e($it['created_at']) ?></td>
                            <td>
                                <div class="row col-right">
                                    <form action="edit.php" method="get" style="display:inline">
                                        <input type="hidden" name="id" value="<?= (int)$it['id'] ?>">
                                        <button class="btn">Sửa</button>
                                    </form>
                                    <form action="" method="post" style="display:inline"
                                        onsubmit="return confirm('Xác nhận xóa?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= (int)$it['id'] ?>">
                                        <button class="btn">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </section>

            <aside class="card">
                <h3 style="margin-top:0">Thêm mục mới</h3>
                <form method="post">
                    <input type="hidden" name="action" value="add">
                    <div class="form__group"><label>Tiêu đề</label><input name="title" type="text" required></div>
                    <div class="form__group"><label>Nội dung</label><textarea name="content"></textarea></div>
                    <div class="form__group"><label>Meta (ví dụ: tag, sku...)</label><input name="meta" type="text">
                    </div>
                    <div style="display:flex;gap:8px;justify-content:flex-end">
                        <button type="reset" class="btn ghost">Reset</button>
                        <button type="submit" class="btn primary">Thêm</button>
                    </div>
                </form>
            </aside>
        </main>
    </div>

    <script>
    // client-side search (title/meta)
    const q = document.getElementById('q'),
        clear = document.getElementById('clear');
    const rows = document.querySelectorAll('#list tbody tr');
    if (q) q.addEventListener('input', () => {
        const v = q.value.trim().toLowerCase();
        rows.forEach(r => {
            const title = r.dataset.title || '',
                meta = r.dataset.meta || '';
            r.style.display = (title.indexOf(v) !== -1 || meta.indexOf(v) !== -1) ? '' : 'none';
        });
    });
    if (clear) clear.addEventListener('click', () => {
        q.value = '';
        q.dispatchEvent(new Event('input'));
    });
    </script>
</body>

</html>