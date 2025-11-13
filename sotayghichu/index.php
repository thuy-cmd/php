<?php
// index.php
session_start();
// require_once __DIR__ . '/functions.php';
// functions.php

// ===== Cấu hình DB (đổi cho phù hợp môi trường của trẫm) =====
$DB_HOST = 'localhost';
$DB_NAME = 'k9tin';          // Có thể đổi thành notes_db
$DB_USER = 'root';
$DB_PASS = '';
$DB_CHAR = 'utf8mb4';

try {
    $dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset={$DB_CHAR}";
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    die('Không kết nối được CSDL: ' . htmlspecialchars($e->getMessage()));
}

// ===== Flash message đơn giản =====
function set_flash($type, $msg){
    $_SESSION['__flash'] = ['type'=>$type, 'msg'=>$msg];
}
function get_flash(){
    if (!empty($_SESSION['__flash'])) {
        $f = $_SESSION['__flash'];
        unset($_SESSION['__flash']);
        return $f;
    }
    return null;
}

// ===== CRUD =====
function create_note(PDO $pdo, string $title, string $content, string $label=''): void {
    $sql = "INSERT INTO notes(title, content, label) VALUES(:t, :c, NULLIF(:l,''))";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':t'=>$title, ':c'=>$content, ':l'=>$label]);
}

function update_note(PDO $pdo, int $id, string $title, string $content, string $label=''): void {
    $sql = "UPDATE notes SET title=:t, content=:c, label=NULLIF(:l,''), updated_at=NOW() WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':t'=>$title, ':c'=>$content, ':l'=>$label, ':id'=>$id]);
}

function delete_note(PDO $pdo, int $id): void {
    $stmt = $pdo->prepare("DELETE FROM notes WHERE id=:id");
    $stmt->execute([':id'=>$id]);
}

function get_notes(PDO $pdo, string $q='', string $label=''): array {
    $where = [];
    $params = [];
    if ($q !== '') {
        $where[] = "(title LIKE :kw OR content LIKE :kw OR label LIKE :kw)";
        $params[':kw'] = '%' . $q . '%';
    }
    if ($label !== '') {
        $where[] = "label = :label";
        $params[':label'] = $label;
    }
    $sql = "SELECT id, title, content, label, created_at, updated_at FROM notes";
    if ($where) $sql .= " WHERE " . implode(' AND ', $where);
    $sql .= " ORDER BY updated_at DESC, created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function get_all_labels(PDO $pdo): array {
    $stmt = $pdo->query("SELECT DISTINCT label FROM notes WHERE label IS NOT NULL AND label<>'' ORDER BY label");
    $labels = [];
    foreach ($stmt as $row) {
        $labels[] = $row['label'];
    }
    return $labels;
}


// Tạo CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

// Xử lý POST (Create / Update / Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $token  = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        set_flash('danger', 'CSRF token không hợp lệ.');
        header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?'));
        exit;
    }

    try {
        if ($action === 'create') {
            $title   = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $label   = trim($_POST['label'] ?? '');
            if ($title === '' || $content === '') {
                throw new Exception('Vui lòng nhập tiêu đề và nội dung.');
            }
            create_note($pdo, $title, $content, $label);
            set_flash('success', 'Đã thêm ghi chú.');
        } elseif ($action === 'update') {
            $id      = (int)($_POST['id'] ?? 0);
            $title   = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $label   = trim($_POST['label'] ?? '');
            if ($id <= 0) throw new Exception('ID không hợp lệ.');
            if ($title === '' || $content === '') {
                throw new Exception('Vui lòng nhập tiêu đề và nội dung.');
            }
            update_note($pdo, $id, $title, $content, $label);
            set_flash('success', 'Đã cập nhật ghi chú.');
        } elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) throw new Exception('ID không hợp lệ.');
            delete_note($pdo, $id);
            set_flash('success', 'Đã xóa ghi chú.');
        }
    } catch (Exception $e) {
        set_flash('danger', $e->getMessage());
    }
    header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Xử lý GET (tìm kiếm + lọc)
$q     = trim($_GET['q']     ?? '');
$label = trim($_GET['label'] ?? '');
$notes = get_notes($pdo, $q, $label);
$labels = get_all_labels($pdo);

// Helper escape
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sổ tay ghi chú</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .note-content {
        display: -webkit-box;
        -webkit-line-clamp: 6;
        -webkit-box-orient: vertical;
        overflow: hidden;
        white-space: pre-wrap;
    }

    .card-hover:hover {
        transform: translateY(-2px);
        transition: .2s;
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .08);
    }

    .form-control,
    .form-select {
        min-height: 44px;
    }

    .btn {
        border-radius: 1rem;
    }

    .badge.round {
        border-radius: 1rem;
    }
    </style>
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">📝 Sổ tay ghi chú</a>
            <div class="ms-auto">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">+ Thêm ghi
                    chú</button>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <?php if ($flash = get_flash()): ?>
        <div class="alert alert-<?= h($flash['type']) ?>"><?= h($flash['msg']) ?></div>
        <?php endif; ?>

        <!-- Thanh tìm kiếm & lọc -->
        <form class="row g-2 align-items-center mb-4" method="get" role="search" aria-label="Tìm kiếm ghi chú">
            <div class="col-12 col-md-6">
                <input name="q" value="<?= h($q) ?>" class="form-control"
                    placeholder="Tìm theo tiêu đề, nội dung, nhãn..." />
            </div>
            <div class="col-8 col-md-3">
                <select name="label" class="form-select" aria-label="Lọc theo nhãn">
                    <option value="">— Tất cả nhãn —</option>
                    <?php foreach($labels as $lb): ?>
                    <option value="<?= h($lb) ?>" <?= $lb===$label?'selected':'' ?>><?= h($lb) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-4 col-md-3 d-grid">
                <button class="btn btn-outline-secondary" type="submit">Tìm</button>
            </div>
        </form>

        <!-- Danh sách ghi chú -->
        <?php if (count($notes)===0): ?>
        <div class="text-center text-muted py-5">
            <p class="mb-2">Chưa có ghi chú nào phù hợp.</p>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Thêm ghi chú đầu
                tiên</button>
        </div>
        <?php else: ?>
        <div class="row g-3">
            <?php foreach($notes as $n): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 card-hover">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="card-title mb-2"><?= h($n['title']) ?></h5>
                            <?php if ($n['label']): ?>
                            <span class="badge text-bg-info round ms-2"><?= h($n['label']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="small text-secondary mb-2">
                            Tạo: <?= h(date('d/m/Y H:i', strtotime($n['created_at']))) ?> ·
                            Sửa: <?= h(date('d/m/Y H:i', strtotime($n['updated_at']))) ?>
                        </div>
                        <div class="note-content flex-grow-1"><?= h($n['content']) ?></div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0 pb-3">
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#editModal" data-id="<?= (int)$n['id'] ?>"
                                data-title="<?= h($n['title']) ?>" data-content="<?= h($n['content']) ?>"
                                data-label="<?= h($n['label']) ?>"
                                aria-label="Sửa ghi chú: <?= h($n['title']) ?>">Sửa</button>

                            <form method="post" onsubmit="return confirm('Xóa ghi chú này?');" class="ms-auto">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int)$n['id'] ?>">
                                <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
                                <button class="btn btn-sm btn-outline-danger" type="submit">Xóa</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>

    <!-- Modal: Thêm -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm ghi chú</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="create">
                    <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề</label>
                        <input name="title" class="form-control" maxlength="200" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nội dung</label>
                        <textarea name="content" class="form-control" rows="6" required></textarea>
                    </div>
                    <div>
                        <label class="form-label">Nhãn (tùy chọn)</label>
                        <input name="label" class="form-control" list="labels">
                        <datalist id="labels">
                            <?php foreach($labels as $lb): ?>
                            <option value="<?= h($lb) ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Hủy</button>
                    <button class="btn btn-primary" type="submit">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Sửa -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa ghi chú</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" id="edit-id">
                    <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề</label>
                        <input name="title" id="edit-title" class="form-control" maxlength="200" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nội dung</label>
                        <textarea name="content" id="edit-content" class="form-control" rows="6" required></textarea>
                    </div>
                    <div>
                        <label class="form-label">Nhãn (tùy chọn)</label>
                        <input name="label" id="edit-label" class="form-control" list="labels">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Hủy</button>
                    <button class="btn btn-primary" type="submit">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>

    <footer class="border-top bg-white py-3 mt-4 text-center">
        <div class="container small text-secondary text-center">
            Xây dựng bằng PHP + PDO + Bootstrap 5 · Mobile-first · Bảo vệ CSRF/XSS
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // JS thuần: đổ dữ liệu vào modal Sửa
    const editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', (event) => {
        const btn = event.relatedTarget;
        document.getElementById('edit-id').value = btn.dataset.id || '';
        document.getElementById('edit-title').value = btn.dataset.title || '';
        document.getElementById('edit-content').value = btn.dataset.content || '';
        document.getElementById('edit-label').value = btn.dataset.label || '';
    });
    </script>
</body>

</html>
