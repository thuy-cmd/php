<?php
/* ==================== CẤU HÌNH KẾT NỐI ==================== */
$DB_HOST = 'localhost';
$DB_NAME = 'k9tin';
$DB_USER = 'root';
$DB_PASS = ''; // đổi theo máy trạm
$DSN     = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4";

/* ==================== KHỞI TẠO ==================== */
session_start();

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function pdo(): PDO {
    global $DSN, $DB_USER, $DB_PASS;
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO($DSN, $DB_USER, $DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}

function flash_set(string $msg, string $type='success'){
    $_SESSION['flash'] = ['msg'=>$msg,'type'=>$type];
}
function flash_get(){
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash']; unset($_SESSION['flash']); return $f;
    }
    return null;
}

/* ==================== CSRF ==================== */
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$CSRF = $_SESSION['csrf'];

function require_csrf(){
    if (($_POST['csrf'] ?? '') !== ($_SESSION['csrf'] ?? '')) {
        http_response_code(400);
        die('CSRF token không hợp lệ.');
    }
}

/* ==================== XỬ LÝ POST (CREATE/UPDATE/DELETE) ==================== */
$action = $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD']==='POST' && $action) {
    require_csrf();
    try {
        $pdo = pdo();

        if ($action === 'create' || $action === 'update') {
            // Lấy & kiểm dữ liệu
            $id    = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            $title = trim($_POST['title'] ?? '');
            $author= trim($_POST['author'] ?? '');
            $year  = trim($_POST['published_year'] ?? '');
            $genre = trim($_POST['genre'] ?? '');
            $stock = trim($_POST['stock'] ?? '1');

            if ($title === '' || $author === '') {
                flash_set('Vui lòng nhập đủ Tên sách và Tác giả.', 'danger');
                header("Location: ".$_SERVER['PHP_SELF']); exit;
            }

            // Validate kiểu số
            $year  = ($year === '' ? null : filter_var($year, FILTER_VALIDATE_INT));
            $stock = filter_var($stock, FILTER_VALIDATE_INT);
            if ($stock === false || $stock < 0) $stock = 0;

            if ($action === 'create') {
                $stmt = $pdo->prepare("
                    INSERT INTO books (title, author, published_year, genre, stock)
                    VALUES (:title, :author, :year, :genre, :stock)
                ");
                $stmt->execute([
                    ':title'=>$title, ':author'=>$author, ':year'=>$year,
                    ':genre'=>$genre, ':stock'=>$stock
                ]);
                flash_set('Đã thêm sách thành công!');
            } else { // update
                if ($id <= 0) { flash_set('ID không hợp lệ.', 'danger'); header("Location: ".$_SERVER['PHP_SELF']); exit; }
                $stmt = $pdo->prepare("
                    UPDATE books
                    SET title=:title, author=:author, published_year=:year, genre=:genre, stock=:stock
                    WHERE id=:id
                ");
                $stmt->execute([
                    ':title'=>$title, ':author'=>$author, ':year'=>$year,
                    ':genre'=>$genre, ':stock'=>$stock, ':id'=>$id
                ]);
                flash_set('Đã cập nhật sách!');
            }

        } elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $stmt = $pdo->prepare("DELETE FROM books WHERE id=:id");
                $stmt->execute([':id'=>$id]);
                flash_set('Đã xóa sách.');
            } else {
                flash_set('ID không hợp lệ.', 'danger');
            }
        }

    } catch (Throwable $e) {
        flash_set('Lỗi: '.$e->getMessage(), 'danger');
    }
    // PRG
    header("Location: ".$_SERVER['PHP_SELF']); exit;
}

/* ==================== LẤY DỮ LIỆU HIỂN THỊ ==================== */
$pdo = pdo();
$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$editing = null;
if ($editId > 0) {
    $s = $pdo->prepare("SELECT * FROM books WHERE id=:id");
    $s->execute([':id'=>$editId]);
    $editing = $s->fetch() ?: null;
}

$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $sql = "SELECT * FROM books
            WHERE title LIKE :kw OR author LIKE :kw OR genre LIKE :kw
            ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $kw = "%$q%";
    $stmt->execute([':kw'=>$kw]);
} else {
    $stmt = $pdo->query("SELECT * FROM books ORDER BY id DESC");
}
$books = $stmt->fetchAll();
$flash = flash_get();
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý Thư viện (PHP + PDO)</title>
    <!-- Bootstrap 5 (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        /* background: #0b1220; */
        color: #e5e7eb;
    }

    .card {
        /* background: #111827; */
        border: 1px solid #1f2937;
    }

    .form-control,
    .form-select {
        /* background: #0f172a; */
        color: #0f172a;
        border-color: #374151;
    }

    a,
    .btn-link {
        text-decoration: none;
    }

    .table> :not(caption)>*>* {
        background: transparent !important;
        /* color: #e5e7eb; */
    }

    h2 {
        color: #e5e7eb;
    }

    .title {
        color: #1f2937;
    }

    input {
        color: #000;
    }
    </style>
</head>

<body>
    <div class="container py-4">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
            <h2 class="h3 m-0 title">📚 Quản lý Thư viện</h1>
                <form class="d-flex" role="search" method="get">
                    <input class="form-control me-2" name="q" value="<?=h($q)?>"
                        placeholder="Tìm theo tên/tác giả/thể loại">
                    <button class="btn btn-outline-dark" type="submit">Tìm</button>
                    <?php if($q!==''): ?>
                    <a class="btn btn-link text-secondary ms-2" href="<?=h($_SERVER['PHP_SELF'])?>">Xóa lọc</a>
                    <?php endif; ?>
                </form>
        </div>

        <?php if($flash): ?>
        <div class="alert alert-<?=h($flash['type'])?>"><?=h($flash['msg'])?></div>
        <?php endif; ?>

        <div class="row g-3">
            <!-- Form -->
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="h5 mb-3 "><?= $editing ? '✏️ Sửa sách #'.(int)$editing['id'] : '➕ Thêm sách mới' ?>
                        </h2>
                        <form method="post" class="vstack gap-3">
                            <input type="hidden" name="csrf" value="<?=h($CSRF)?>">
                            <?php if($editing): ?>
                            <input type="hidden" name="id" value="<?= (int)$editing['id'] ?>">
                            <?php endif; ?>

                            <div>
                                <label class="form-label">Tên sách <span class="text-danger">*</span></label>
                                <input name="title" class="form-control" required
                                    value="<?=h($editing['title'] ?? '')?>">
                            </div>

                            <div>
                                <label class="form-label">Tác giả <span class="text-danger">*</span></label>
                                <input name="author" class="form-control" required
                                    value="<?=h($editing['author'] ?? '')?>">
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label">Năm XB</label>
                                    <input name="published_year" class="form-control" inputmode="numeric"
                                        pattern="\d{0,4}" value="<?=h($editing['published_year'] ?? '')?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Số lượng</label>
                                    <input name="stock" class="form-control" inputmode="numeric" pattern="\d+"
                                        value="<?=h($editing['stock'] ?? '1')?>">
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Thể loại</label>
                                <input name="genre" class="form-control" value="<?=h($editing['genre'] ?? '')?>">
                            </div>

                            <div class="d-flex gap-2">
                                <button class="btn btn-light" name="action"
                                    value="<?= $editing ? 'update' : 'create' ?>">
                                    <?= $editing ? 'Lưu thay đổi' : 'Thêm sách' ?>
                                </button>
                                <?php if($editing): ?>
                                <a class="btn btn-outline-secondary" href="<?=h($_SERVER['PHP_SELF'])?>">Hủy</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Danh sách -->
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h2 class="h5 m-0">Danh sách sách (<?=count($books)?>)</h2>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên sách</th>
                                        <th>Tác giả</th>
                                        <th>Năm</th>
                                        <th>Thể loại</th>
                                        <th>SL</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($books)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-secondary">Chưa có dữ liệu.</td>
                                    </tr>
                                    <?php else: foreach($books as $b): ?>
                                    <tr>
                                        <td><?= (int)$b['id'] ?></td>
                                        <td class="fw-semibold"><?= h($b['title']) ?></td>
                                        <td><?= h($b['author']) ?></td>
                                        <td><?= h($b['published_year']) ?></td>
                                        <td><?= h($b['genre']) ?></td>
                                        <td><?= (int)$b['stock'] ?></td>
                                        <td class="text-end">
                                            <a class="btn btn-sm btn-outline-dark"
                                                href="<?=h($_SERVER['PHP_SELF'])?>?edit=<?= (int)$b['id'] ?>">Sửa</a>
                                            <form class="d-inline" method="post"
                                                onsubmit="return confirm('Xóa sách này?');">
                                                <input type="hidden" name="csrf" value="<?=$CSRF?>">
                                                <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
                                                <button class="btn btn-sm btn-outline-danger" name="action"
                                                    value="delete">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
