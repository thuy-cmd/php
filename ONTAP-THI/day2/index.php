<?php
session_start();
require 'db.php';
$conn = connectDB();

// Kiểm tra đăng nhập
$username = $_SESSION['username'] ?? '';
if ($username === '') {
    header("Location: login.php");
    exit;
}

// Lấy manv từ bảng nhanvien
$stmt = $conn->prepare("SELECT manv FROM nhanvien WHERE username = ?");
$stmt->execute([$username]);
$manv = $stmt->fetchColumn();
if (!$manv) {
    // Nếu không tìm thấy manv, logout hoặc báo lỗi
    header("Location: logout.php");
    exit;
}

// Xử lý POST: add task hoặc delete task
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Thêm nhiệm vụ
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $tennv = trim($_POST['task'] ?? '');
        $mota = trim($_POST['desc'] ?? '');

        if ($tennv === '') {
            $errors[] = "Tên nhiệm vụ không được để trống.";
        }

        if (empty($errors)) {
            $stmt = $conn->prepare("INSERT INTO nhiemvu (manv, task_title, task_desc, create_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$manv, $tennv, $mota]);
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    // Xóa nhiệm vụ
    if (isset($_POST['action']) && $_POST['action'] === 'delete' && !empty($_POST['delete_id'])) {
        $delId = (int)$_POST['delete_id'];
        // Chỉ xóa nếu nhiệm vụ thuộc về manv hiện tại
        $stmt = $conn->prepare("DELETE FROM nhiemvu WHERE id = ? AND manv = ?");
        $stmt->execute([$delId, $manv]);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    // Nâng cấp: xử lý edit có thể thêm sau
}

// Lấy danh sách nhiệm vụ của user
$stmt = $conn->prepare("SELECT id, task_title, task_desc, create_at FROM nhiemvu WHERE manv = ? ORDER BY create_at DESC");
$stmt->execute([$manv]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

function e($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>

<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trang chủ - Nhiệm vụ</title>
    <link rel="stylesheet" href="./style.css">
    <style>
    :root {
        --bg: #0f1724;
        --card: #0b1220;
        --muted: #94a3b8;
        --accent: #06b6d4;
        --accent-2: #7c3aed;
        --glass: rgba(255, 255, 255, 0.04);
        --success: #10b981;
        --danger: #ef4444;
        --shadow: 0 6px 18px rgba(2, 6, 23, 0.6);
        font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    }

    /* Reset nhỏ */
    * {
        box-sizing: border-box
    }

    html,
    body {
        height: 100%
    }

    body {
        margin: 0;
        background: linear-gradient(180deg, #071026 0%, #0b1220 100%);
        color: #e6eef6;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        padding: 20px;
    }

    .container {
        max-width: 1100px;
        margin: 0 auto;
    }

    header.app-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
    }

    .brand {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .logo {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--accent), var(--accent-2));
        display: grid;
        place-items: center;
        box-shadow: var(--shadow);
        font-weight: 700;
        color: white;
    }

    .title {
        line-height: 1;
    }

    .title h1 {
        margin: 0;
        font-size: 18px;
    }

    .title p {
        margin: 0;
        font-size: 12px;
        color: var(--muted);
    }

    .actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .btn {
        border: 0;
        background: var(--glass);
        color: inherit;
        padding: 8px 12px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: transform .12s ease, background .12s;
    }

    .btn:hover {
        transform: translateY(-2px)
    }

    .btn.primary {
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        box-shadow: 0 6px 18px rgba(124, 58, 237, 0.16);
        color: white;
    }

    .btn.ghost {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.06);
    }

    /* Layout */
    .grid {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 24px;
        align-items: start;
    }

    /* Card */
    .card {
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.02), rgba(255, 255, 255, 0.01));
        border-radius: 12px;
        padding: 18px;
        box-shadow: var(--shadow);
        border: 1px solid rgba(255, 255, 255, 0.03);
    }

    /* Task list */
    .tasks-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .search {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .input {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.04);
        padding: 8px 10px;
        border-radius: 8px;
        color: inherit;
        min-width: 200px;
    }

    .task-list {
        width: 100%;
        border-collapse: collapse;
        margin-top: 6px;
    }

    .task-list thead th {
        text-align: left;
        font-size: 12px;
        color: var(--muted);
        padding: 10px 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
    }

    .task-list tbody tr {
        transition: background .08s;
    }

    .task-list tbody tr:hover {
        background: rgba(255, 255, 255, 0.02);
    }

    .task-list td {
        padding: 12px;
        vertical-align: middle;
        font-size: 14px;
    }

    .meta {
        font-size: 12px;
        color: var(--muted)
    }

    .controls {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    .pill {
        font-size: 12px;
        padding: 6px 10px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.02);
    }

    /* Form */
    .form .form-row {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 12px
    }

    label {
        font-size: 13px;
        color: var(--muted)
    }

    input[type="text"],
    textarea {
        padding: 10px 12px;
        border-radius: 8px;
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.04);
        color: inherit;
        font-size: 14px;
        width: 100%;
    }

    textarea {
        min-height: 100px;
        resize: vertical
    }

    .form .submit-row {
        display: flex;
        gap: 8px;
        align-items: center;
        justify-content: flex-end;
        margin-top: 6px
    }

    .empty {
        padding: 24px;
        text-align: center;
        color: var(--muted);
    }

    /* Responsive */
    @media (max-width:900px) {
        .grid {
            grid-template-columns: 1fr;
        }

        .actions {
            flex-wrap: wrap
        }

        .title h1 {
            font-size: 16px
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <header class="app-header">
            <div class="brand">
                <div class="logo">NV</div>
                <div class="title">
                    <h1>Quản lý nhiệm vụ</h1>
                    <p>Danh sách nhiệm vụ của <strong><?= e($username) ?></strong></p>
                </div>
            </div>
            <div class="actions">
                <div style="text-align:right;color:var(--muted);font-size:13px">
                    <div>Xin chào, <strong><?= e($username) ?></strong></div>
                    <div class="meta">Mã NV: <?= e($manv) ?></div>
                </div>
                <a href="logout.php" class="btn ghost" title="Đăng xuất">Đăng xuất</a>
            </div>
        </header>

        <?php if (!empty($errors)): ?>
        <div style="margin-bottom:12px;color:var(--danger);">
            <?php foreach($errors as $err) echo '<div>'.e($err).'</div>'; ?>
        </div>
        <?php endif; ?>

        <main class="grid">
            <section class="card">
                <div class="tasks-header">
                    <div>
                        <h3 style="margin:0">Danh sách nhiệm vụ</h3>
                        <div class="meta">Tổng: <?= count($tasks) ?> nhiệm vụ</div>
                    </div>
                    <div class="search">
                        <input id="q" class="input" placeholder="Tìm nhiệm vụ (tiêu đề hoặc mô tả)">
                        <button class="btn" id="clearSearch" type="button">Xóa</button>
                    </div>
                </div>

                <?php if (count($tasks) === 0): ?>
                <div class="empty card">Chưa có nhiệm vụ nào — thêm nhiệm vụ ở bên phải.</div>
                <?php else: ?>
                <table class="task-list" id="taskTable">
                    <thead>
                        <tr>
                            <th style="width:48px">#</th>
                            <th>Tên nhiệm vụ</th>
                            <th style="width:180px">Ngày tạo</th>
                            <th style="width:170px;text-align:right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=0; foreach($tasks as $t): $i++; ?>
                        <tr data-title="<?= e(mb_strtolower($t['task_title'])) ?>"
                            data-desc="<?= e(mb_strtolower($t['task_desc'])) ?>">
                            <td style="text-align:center"><?= $i ?></td>
                            <td>
                                <div style="font-weight:600"><?= e($t['task_title']) ?></div>
                                <div class="meta"><?= e($t['task_desc']) ?></div>
                            </td>
                            <td class="meta"><?= e($t['create_at']) ?></td>
                            <td>
                                <div class="controls">
                                    <form style="margin:0" action="edit.php" method="get">
                                        <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
                                        <button class="btn" type="submit">Sửa</button>
                                    </form>

                                    <form style="margin:0" action="" method="post"
                                        onsubmit="return confirmDelete(event, <?= (int)$t['id'] ?>)">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="delete_id" value="<?= (int)$t['id'] ?>">
                                        <button class="btn" type="submit">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </section>

            <aside class="card form" aria-labelledby="form-title">
                <h3 id="form-title" style="margin-top:0;margin-bottom:6px">Thêm nhiệm vụ mới</h3>
                <p class="meta" style="margin-top:0;margin-bottom:12px">Điền tên và mô tả, sau đó bấm Send.</p>

                <form action="" method="post" id="addForm">
                    <input type="hidden" name="action" value="add">
                    <div class="form-row">
                        <label for="task">Tên nhiệm vụ</label>
                        <input id="task" name="task" type="text" placeholder="Ví dụ: Làm báo cáo doanh thu">
                    </div>

                    <div class="form-row">
                        <label for="desc">Mô tả nhiệm vụ</label>
                        <textarea id="desc" name="desc" placeholder="Mô tả ngắn gọn (tuỳ chọn)"></textarea>
                    </div>

                    <div class="submit-row">
                        <button type="button" class="btn ghost"
                            onclick="document.getElementById('addForm').reset()">Reset</button>
                        <button type="submit" class="btn primary">Send</button>
                    </div>
                </form>
            </aside>
        </main>
    </div>

    <script>
    // Tìm kiếm client-side đơn giản
    const q = document.getElementById('q');
    const clearBtn = document.getElementById('clearSearch');
    const table = document.getElementById('taskTable');
    const rows = table ? Array.from(table.querySelectorAll('tbody tr')) : [];

    function filterTasks() {
        const v = q.value.trim().toLowerCase();
        rows.forEach(r => {
            const title = r.getAttribute('data-title') || '';
            const desc = r.getAttribute('data-desc') || '';
            const show = title.indexOf(v) !== -1 || desc.indexOf(v) !== -1;
            r.style.display = show ? '' : 'none';
        });
    }

    if (q) q.addEventListener('input', filterTasks);
    if (clearBtn) clearBtn.addEventListener('click', () => {
        q.value = '';
        filterTasks();
    });

    // confirm delete (hiển thị confirm native)
    function confirmDelete(e, id) {
        if (!confirm('Xác nhận xóa nhiệm vụ #' + id + '?')) {
            e.preventDefault();
            return false;
        }
        return true;
    }
    </script>
</body>

</html>
