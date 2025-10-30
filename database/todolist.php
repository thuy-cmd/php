<?php
/* ============================================================
 * TodoList PHP thuần – 1 file duy nhất (todolist.php)
 * Hướng dẫn nhanh:
 * 1) Sửa thông số DB ngay bên dưới.
 * 2) Chạy: php -S localhost:5500
 * 3) Mở:  http://localhost:5500/todolist.php
 * ------------------------------------------------------------
 * Tính năng:
 * - Kết nối MySQL bằng PDO, auto tạo database/bảng nếu chưa có.
 * - CRUD: thêm / sửa tên / toggle hoàn thành / xoá.
 * - PRG (Post/Redirect/Get) tránh submit lại form khi refresh.
 * - CSRF token đơn giản bằng session.
 * - Mobile-first UI, không cần lib ngoài.
 * ============================================================ */

ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Bangkok');
session_start();

/* ========== 0) Cấu hình DB (SỬA CHO PHÙ HỢP) ========== */
$DB_HOST = '127.0.0.1';   // ví dụ: 'db.lce.vn'
$DB_PORT = 3306;          // ví dụ: 33067 nếu dùng port khác
$DB_NAME = 'k9tin';
$DB_USER = 'root';
$DB_PASS = '';
$DB_CHAR = 'utf8mb4';

/* Tự tạo database & bảng nếu thiếu (cần quyền) */
$AUTO_MIGRATE = true;

/* ========== 1) Kết nối PDO, auto-migrate nếu cần ========== */
function pdo_connect()
{
    global $DB_HOST, $DB_PORT, $DB_NAME, $DB_USER, $DB_PASS, $DB_CHAR, $AUTO_MIGRATE;

    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $dsnWithDb = "mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};charset={$DB_CHAR}";
    try {
        $pdo = new PDO($dsnWithDb, $DB_USER, $DB_PASS, $opt);
    } catch (PDOException $e) {
        // Nếu lỗi "Unknown database", thử tạo DB rồi kết nối lại
        if ($AUTO_MIGRATE && (strpos($e->getMessage(), 'Unknown database') !== false || $e->getCode() == 1049)) {
            $dsnNoDb = "mysql:host={$DB_HOST};port={$DB_PORT};charset={$DB_CHAR}";
            $tmp = new PDO($dsnNoDb, $DB_USER, $DB_PASS, $opt);
            $tmp->exec("CREATE DATABASE IF NOT EXISTS `{$DB_NAME}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            $tmp = null;
            $pdo = new PDO($dsnWithDb, $DB_USER, $DB_PASS, $opt);
        } else {
            throw $e;
        }
    }

    if ($AUTO_MIGRATE) {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS tasks (
                id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                is_done TINYINT(1) NOT NULL DEFAULT 0,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }

    return $pdo;
}
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

/* ========== 2) CSRF token ========== */
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}
function check_csrf() {
    if (($_POST['csrf'] ?? '') !== ($_SESSION['csrf'] ?? '')) {
        throw new RuntimeException('CSRF token không hợp lệ.');
    }
}

/* ========== 3) Nhận tham số filter/search từ GET ========== */
$q = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? 'all';
if (!in_array($status, ['all','open','done'], true)) $status = 'all';

/* ========== 4) Xử lý hành động POST (PRG) ========== */
$flash = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        check_csrf();
        $pdo = pdo_connect();
        $action = $_POST['action'] ?? '';

        if ($action === 'create') {
            $title = trim($_POST['title'] ?? '');
            if ($title === '') throw new RuntimeException('Tiêu đề không được trống.');
            if (mb_strlen($title) > 255) throw new RuntimeException('Tiêu đề quá dài (<=255 ký tự).');
            $st = $pdo->prepare("INSERT INTO tasks (title) VALUES (?)");
            $st->execute([$title]);
            $flash = 'Đã thêm việc mới.';

        } elseif ($action === 'toggle') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) throw new RuntimeException('ID không hợp lệ.');
            $pdo->beginTransaction();
            $st = $pdo->prepare("SELECT is_done FROM tasks WHERE id=? FOR UPDATE");
            $st->execute([$id]);
            $row = $st->fetch();
            if (!$row) { $pdo->rollBack(); throw new RuntimeException('Không tìm thấy công việc.'); }
            $new = $row['is_done'] ? 0 : 1;
            $up = $pdo->prepare("UPDATE tasks SET is_done=? WHERE id=?");
            $up->execute([$new, $id]);
            $pdo->commit();
            $flash = $new ? 'Đã đánh dấu hoàn thành.' : 'Đã bỏ hoàn thành.';

        } elseif ($action === 'update') {
            $id = (int)($_POST['id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            if ($id <= 0) throw new RuntimeException('ID không hợp lệ.');
            if ($title === '') throw new RuntimeException('Tiêu đề không được trống.');
            if (mb_strlen($title) > 255) throw new RuntimeException('Tiêu đề quá dài (<=255 ký tự).');
            $st = $pdo->prepare("UPDATE tasks SET title=? WHERE id=?");
            $st->execute([$title, $id]);
            $flash = 'Đã lưu tiêu đề.';

        } elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) throw new RuntimeException('ID không hợp lệ.');
            $st = $pdo->prepare("DELETE FROM tasks WHERE id=?");
            $st->execute([$id]);
            $flash = 'Đã xoá công việc.';

        } else {
            throw new RuntimeException('Hành động không hợp lệ.');
        }

        $_SESSION['flash'] = $flash;
        // Giữ lại bộ lọc hiện có
        $back_q = urlencode($_POST['_q'] ?? '');
        $back_status = urlencode($_POST['_status'] ?? 'all');
        header("Location: ?q={$back_q}&status={$back_status}");
        exit;
    } catch (Throwable $e) {
        $_SESSION['flash'] = 'Lỗi: ' . $e->getMessage();
        header("Location: ?q=" . urlencode($_POST['_q'] ?? '') . "&status=" . urlencode($_POST['_status'] ?? 'all'));
        exit;
    }
}

/* ========== 5) Truy vấn danh sách theo filter ========== */
$pdo = pdo_connect();
$sql = "SELECT id, title, is_done, created_at, updated_at FROM tasks";
$where = [];
$params = [];
if ($q !== '') { $where[] = "title LIKE ?"; $params[] = "%{$q}%"; }
if ($status === 'open') { $where[] = "is_done=0"; }
if ($status === 'done') { $where[] = "is_done=1"; }
if ($where) $sql .= " WHERE " . implode(" AND ", $where);
$sql .= " ORDER BY is_done ASC, id DESC";
$st = $pdo->prepare($sql);
$st->execute($params);
$tasks = $st->fetchAll();
$open = 0; $done = 0;
foreach ($tasks as $t) { $t['is_done'] ? $done++ : $open++; }

/* ========== 6) Lấy flash message (nếu có) ========== */
if (!empty($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>TodoList – PHP thuần + MySQL (PDO)</title>
    <style>
    :root {
        --bg: #0b1220;
        --card: #111a2b;
        --accent: #60a5fa;
        --text: #e5e7eb;
        --muted: #94a3b8;
        --line: #1e293b;
    }

    * {
        box-sizing: border-box
    }

    body {
        margin: 0;
        background: linear-gradient(135deg, #2563eb22, #60a5fa11), var(--bg);
        color: var(--text);
        font-family: system-ui, Segoe UI, Arial, sans-serif
    }

    .wrap {
        max-width: 760px;
        margin: 0 auto;
        padding: 16px
    }

    .title {
        font-size: 26px;
        font-weight: 800;
        margin: 6px 0 14px
    }

    .card {
        background: var(--card);
        border-radius: 16px;
        padding: 12px;
        box-shadow: 0 10px 30px #0003
    }

    .row {
        display: flex;
        gap: 8px;
        flex-wrap: wrap
    }

    input,
    button,
    select {
        border: none;
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 15px
    }

    input,
    select {
        flex: 1;
        min-width: 120px;
        background: #0f172a;
        color: var(--text);
        outline: none
    }

    button {
        background: var(--accent);
        color: #0b1220;
        font-weight: 700;
        cursor: pointer
    }

    button:disabled {
        opacity: .6;
        cursor: not-allowed
    }

    .tasks {
        margin-top: 10px
    }

    .task {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px;
        border-bottom: 1px solid var(--line)
    }

    .task:last-child {
        border-bottom: none
    }

    .title-edit {
        flex: 1;
        background: transparent;
        color: var(--text);
        border: 1px solid #334155
    }

    .muted {
        color: var(--muted);
        font-size: 13px
    }

    .pill {
        font-size: 12px;
        color: #0b1220;
        background: #93c5fd;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 999px
    }

    .controls {
        display: flex;
        gap: 6px
    }

    .toggle {
        background: #16a34a;
        color: white
    }

    .delete {
        background: #ef4444;
        color: white
    }

    .update {
        background: #f59e0b;
        color: #0b1220
    }

    .footer {
        margin-top: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center
    }

    .flash {
        margin: 10px 0;
        padding: 10px;
        border-radius: 12px;
        background: #0ea5e9;
        color: #0b1220;
        font-weight: 700
    }

    .hint {
        font-size: 12px;
        color: #9aa7bd;
        margin-top: 6px
    }

    @media (min-width:640px) {
        .row>* {
            flex: 1
        }

        .row>button {
            flex: 0
        }
    }

    a.reset {
        color: #93c5fd;
        text-decoration: none
    }

    a.reset:hover {
        text-decoration: underline
    }

    form.inline {
        display: inline
    }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="title">✅ TodoList — PHP thuần + MySQL (PDO)</div>

        <?php if ($flash): ?>
        <div class="flash"><?=h($flash)?></div>
        <?php endif; ?>

        <div class="card" style="margin-bottom:10px">
            <!-- Form thêm mới -->
            <form method="post" class="row" style="margin-bottom:8px">
                <input type="hidden" name="csrf" value="<?=h($_SESSION['csrf'])?>">
                <input type="hidden" name="_q" value="<?=h($q)?>">
                <input type="hidden" name="_status" value="<?=h($status)?>">
                <input name="title" placeholder="Việc cần làm..." autocomplete="off" required />
                <input type="hidden" name="action" value="create" />
                <button>Thêm</button>
            </form>

            <!-- Tìm kiếm / Lọc -->
            <form method="get" class="row">
                <input name="q" value="<?=h($q)?>" placeholder="Tìm theo tiêu đề..." />
                <select name="status">
                    <option value="all" <?= $status==='all' ? 'selected':'' ?>>Tất cả</option>
                    <option value="open" <?= $status==='open'? 'selected':'' ?>>Chưa xong</option>
                    <option value="done" <?= $status==='done'? 'selected':'' ?>>Đã xong</option>
                </select>
                <button>Lọc</button>
                <a class="reset" href="?">Đặt lại</a>
            </form>

            <div class="footer">
                <div class="muted">Chưa xong: <?=$open?> • Đã xong: <?=$done?> • Tổng: <?=($open+$done)?></div>
                <div class="pill">Sẵn sàng</div>
            </div>
            <div class="hint">Mẹo: nhập tiêu đề rồi nhấn <b>Enter</b> để thêm. Sửa tên xong bấm <b>Lưu tên</b>.</div>
        </div>

        <div class="card">
            <div class="tasks">
                <?php if (!$tasks): ?>
                <div class="muted" style="padding:12px">Chưa có công việc nào.</div>
                <?php else: foreach ($tasks as $t): ?>
                <div class="task">
                    <!-- Nút Toggle -->
                    <form method="post" class="inline">
                        <input type="hidden" name="csrf" value="<?=h($_SESSION['csrf'])?>">
                        <input type="hidden" name="_q" value="<?=h($q)?>">
                        <input type="hidden" name="_status" value="<?=h($status)?>">
                        <input type="hidden" name="action" value="toggle">
                        <input type="hidden" name="id" value="<?=$t['id']?>">
                        <button class="toggle"><?= $t['is_done'] ? 'Bỏ hoàn thành' : 'Hoàn thành' ?></button>
                    </form>

                    <!-- Ô sửa tiêu đề -->
                    <form method="post" class="inline" style="flex:1;display:flex;gap:8px">
                        <input type="hidden" name="csrf" value="<?=h($_SESSION['csrf'])?>">
                        <input type="hidden" name="_q" value="<?=h($q)?>">
                        <input type="hidden" name="_status" value="<?=h($status)?>">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?=$t['id']?>">
                        <input class="title-edit" name="title" value="<?=h($t['title'])?>" aria-label="Sửa tiêu đề" />
                        <button class="update">Lưu tên</button>
                    </form>

                    <!-- Xoá -->
                    <form method="post" class="inline" onsubmit="return confirm('Xoá việc này?');">
                        <input type="hidden" name="csrf" value="<?=h($_SESSION['csrf'])?>">
                        <input type="hidden" name="_q" value="<?=h($q)?>">
                        <input type="hidden" name="_status" value="<?=h($status)?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?=$t['id']?>">
                        <button class="delete">Xoá</button>
                    </form>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>

        <div class="muted" style="margin-top:10px">
            Bệ hạ học cốt lõi: PDO + Prepared Statements + PRG + CSRF nhỏ gọn.
        </div>
    </div>
</body>

</html>
