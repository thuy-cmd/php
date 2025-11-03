<?php
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

/* ===== DB connect ===== */
$DB = [
  'host' => 'localhost',
  'name' => 'k9tin',
  'user' => 'root',
  'pass' => '',
];

function connectDB($cfg){
  try{
    $dsn = "mysql:host={$cfg['host']};dbname={$cfg['name']};charset=utf8mb4";
    $pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $pdo;
  }catch(PDOException $e){
    http_response_code(500);
    die("Không thể kết nối CSDL.");
  }
}

$conn = connectDB($DB);

/* ===== Actions (create/update/delete) ===== */
$notice = null;
$selectedType = $_POST['type'] ?? "DienThoai";
$action = $_POST['action'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action) {
  if ($action === 'create') {
    if ($selectedType === 'DienThoai') {
      $ten = trim($_POST['TenDienThoai'] ?? '');
      $gia = (int)($_POST['DonGiaDienThoai'] ?? 0);
      $hang = trim($_POST['HangSX'] ?? '');
      $baohanh = (int)($_POST['BaoHanh'] ?? 0);
      $stmt = $conn->prepare("INSERT INTO products (type, ten, gia_vnd, hang_sx, bao_hanh_th) VALUES ('DienThoai', :ten, :gia, :hang, :bh)");
      $stmt->execute(['ten'=>$ten, 'gia'=>$gia, 'hang'=>$hang, 'bh'=>$baohanh]);
      $notice = "Đã thêm điện thoại.";
    } elseif ($selectedType === 'Sach') {
      $ten = trim($_POST['TenSach'] ?? '');
      $gia = (int)($_POST['DonGiaSach'] ?? 0);
      $tacgia = trim($_POST['TacGia'] ?? '');
      $sotrang = (int)($_POST['SoTrang'] ?? 0);
      $stmt = $conn->prepare("INSERT INTO products (type, ten, gia_vnd, tac_gia, so_trang) VALUES ('Sach', :ten, :gia, :tg, :st)");
      $stmt->execute(['ten'=>$ten, 'gia'=>$gia, 'tg'=>$tacgia, 'st'=>$sotrang]);
      $notice = "Đã thêm sách.";
    }
  }
  if ($action === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $type = $_POST['type'] ?? '';
    if ($id > 0 && $type === 'DienThoai') {
      $ten = trim($_POST['TenDienThoai'] ?? '');
      $gia = (int)($_POST['DonGiaDienThoai'] ?? 0);
      $hang = trim($_POST['HangSX'] ?? '');
      $baohanh = (int)($_POST['BaoHanh'] ?? 0);
      $stmt = $conn->prepare("UPDATE products SET ten=:ten, gia_vnd=:gia, hang_sx=:hang, bao_hanh_th=:bh WHERE id=:id AND type='DienThoai'");
      $stmt->execute(['ten'=>$ten,'gia'=>$gia,'hang'=>$hang,'bh'=>$baohanh,'id'=>$id]);
      $notice = "Đã cập nhật điện thoại #$id.";
    } elseif ($id > 0 && $type === 'Sach') {
      $ten = trim($_POST['TenSach'] ?? '');
      $gia = (int)($_POST['DonGiaSach'] ?? 0);
      $tacgia = trim($_POST['TacGia'] ?? '');
      $sotrang = (int)($_POST['SoTrang'] ?? 0);
      $stmt = $conn->prepare("UPDATE products SET ten=:ten, gia_vnd=:gia, tac_gia=:tg, so_trang=:st WHERE id=:id AND type='Sach'");
      $stmt->execute(['ten'=>$ten,'gia'=>$gia,'tg'=>$tacgia,'st'=>$sotrang,'id'=>$id]);
      $notice = "Đã cập nhật sách #$id.";
    }
  }
  if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
      $stmt = $conn->prepare("DELETE FROM products WHERE id=:id");
      $stmt->execute(['id'=>$id]);
      $notice = "Đã xoá sản phẩm #$id.";
    }
  }
}

/* ===== Read list ===== */
$sql = $conn->query("SELECT * FROM products ORDER BY id DESC");
$products = $sql->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .card-sticky {
        position: sticky;
        top: 20px;
    }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-primary navbar-dark">
            <div class="container">
                <span class="navbar-brand fw-bold">Quản lý sản phẩm</span>
            </div>
        </nav>
    </header>

    <main class="container py-4">
        <?php if ($notice): ?>
        <div class="alert alert-success"><?= h($notice) ?></div>
        <?php endif; ?>

        <div class="row g-3">
            <!-- LEFT: Create form -->
            <div class="col-12 col-lg-5">
                <div class="card card-sticky shadow-sm">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span class="fw-semibold">Thêm sản phẩm mới</span>
                    </div>
                    <div class="card-body">
                        <form method="post" id="productForm">
                            <input type="hidden" name="action" value="create">
                            <div class="mb-3">
                                <label for="type" class="form-label">Chọn loại sản phẩm</label>
                                <select name="type" id="type" class="form-select" onchange="showForm()">
                                    <option value="DienThoai" <?= $selectedType === "DienThoai" ? "selected" : "" ?>>
                                        Điện thoại</option>
                                    <option value="Sach" <?= $selectedType === "Sach" ? "selected" : "" ?>>Sách</option>
                                </select>
                            </div>

                            <!-- Điện thoại (create) -->
                            <div id="formDienThoai" class="<?= $selectedType==='DienThoai'?'':'d-none' ?>">
                                <div class="mb-3">
                                    <label class="form-label">Tên sản phẩm</label>
                                    <input type="text" name="TenDienThoai" class="form-control"
                                        placeholder="Ví dụ: Galaxy A35" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Đơn giá (đ)</label>
                                    <input type="number" name="DonGiaDienThoai" class="form-control" min="0" step="1000"
                                        placeholder="12.990.000" required>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-7">
                                        <label class="form-label">Hãng sản xuất</label>
                                        <input type="text" name="HangSX" class="form-control"
                                            placeholder="Samsung / Apple / ...">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Bảo hành (tháng)</label>
                                        <input type="number" name="BaoHanh" class="form-control" min="0" step="1"
                                            value="12">
                                    </div>
                                </div>
                            </div>

                            <!-- Sách (create) -->
                            <div id="formSach" class="<?= $selectedType==='Sach'?'':'d-none' ?>">
                                <div class="mb-3">
                                    <label class="form-label">Tên sách</label>
                                    <input type="text" name="TenSach" class="form-control"
                                        placeholder="Ví dụ: Lập trình PHP hiện đại" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Đơn giá (đ)</label>
                                    <input type="number" name="DonGiaSach" class="form-control" min="0" step="1000"
                                        placeholder="95.000" required>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-7">
                                        <label class="form-label">Tác giả</label>
                                        <input type="text" name="TacGia" class="form-control"
                                            placeholder="Nguyễn Văn A">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Số trang</label>
                                        <input type="number" name="SoTrang" class="form-control" min="1" step="1"
                                            placeholder="320">
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Thêm sản phẩm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- RIGHT: List -->
            <div class="col-12 col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <span>Danh sách sản phẩm đã thêm</span>
                    </div>
                    <div class="card-body">
                        <?php if (count($products)===0): ?>
                        <p class="text-secondary fst-italic mb-0">Chưa có sản phẩm nào được thêm.</p>
                        <?php else: ?>
                        <?php foreach($products as $p): ?>
                        <?php if ($p['type']==='DienThoai'): ?>
                        <div
                            class="border rounded p-3 mb-2 bg-body-tertiary d-flex gap-3 align-items-center justify-content-between">
                            <div class="d-flex gap-3 align-items-start">
                                <span class="fs-3">📱</span>
                                <div>
                                    <p class="mb-1"><strong>Điện thoại</strong>: <?= h($p['ten']) ?></p>
                                    <p class="text-secondary small mb-1">Hãng: <?= h($p['hang_sx'] ?? '') ?> • Bảo hành:
                                        <?= h($p['bao_hanh_th'] ?? 0) ?> tháng</p>
                                    <p class="mb-1">Đơn giá: <span
                                            class="badge text-bg-primary"><?= number_format((int)$p['gia_vnd'], 0, ',', '.') ?>
                                            đ</span></p>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#editModal" data-id="<?= (int)$p['id'] ?>" data-type="DienThoai"
                                    data-ten="<?= h($p['ten']) ?>" data-gia="<?= (int)$p['gia_vnd'] ?>"
                                    data-hang="<?= h($p['hang_sx'] ?? '') ?>"
                                    data-bh="<?= (int)($p['bao_hanh_th'] ?? 0) ?>">Sửa</button>

                                <form method="post" onsubmit="return confirm('Xoá sản phẩm này?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                                    <button class="btn btn-outline-danger">Xoá</button>
                                </form>
                            </div>
                        </div>
                        <?php elseif ($p['type']==='Sach'): ?>
                        <div
                            class="border rounded p-3 mb-2 bg-body-tertiary d-flex gap-3 align-items-center justify-content-between">
                            <div class="d-flex gap-3 align-items-start">
                                <span class="fs-3">📘</span>
                                <div>
                                    <p class="mb-1"><strong>Sách</strong>: <?= h($p['ten']) ?></p>
                                    <p class="text-secondary small mb-1">Tác giả: <?= h($p['tac_gia'] ?? '') ?> • Số
                                        trang: <?= h($p['so_trang'] ?? 0) ?> trang</p>
                                    <p class="mb-1">Đơn giá: <span
                                            class="badge text-bg-primary"><?= number_format((int)$p['gia_vnd'], 0, ',', '.') ?>
                                            đ</span></p>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#editModal" data-id="<?= (int)$p['id'] ?>" data-type="Sach"
                                    data-ten="<?= h($p['ten']) ?>" data-gia="<?= (int)$p['gia_vnd'] ?>"
                                    data-tg="<?= h($p['tac_gia'] ?? '') ?>"
                                    data-st="<?= (int)($p['so_trang'] ?? 0) ?>">Sửa</button>

                                <form method="post" onsubmit="return confirm('Xoá sản phẩm này?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                                    <button class="btn btn-outline-danger">Xoá</button>
                                </form>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="post" id="editForm">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="editId">
                <input type="hidden" name="type" id="editType">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Chỉnh sửa sản phẩm</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>

                <div class="modal-body">
                    <!-- Điện thoại (edit) -->
                    <div id="editFormDienThoai" class="d-none">
                        <div class="mb-3">
                            <label class="form-label">Tên sản phẩm</label>
                            <input type="text" name="TenDienThoai" id="editTenDT" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Đơn giá (đ)</label>
                            <input type="number" name="DonGiaDienThoai" id="editGiaDT" class="form-control" min="0"
                                step="1000" required>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label">Hãng sản xuất</label>
                                <input type="text" name="HangSX" id="editHangDT" class="form-control">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Bảo hành (tháng)</label>
                                <input type="number" name="BaoHanh" id="editBHDt" class="form-control" min="0" step="1">
                            </div>
                        </div>
                    </div>

                    <!-- Sách (edit) -->
                    <div id="editFormSach" class="d-none">
                        <div class="mb-3">
                            <label class="form-label">Tên sách</label>
                            <input type="text" name="TenSach" id="editTenSach" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Đơn giá (đ)</label>
                            <input type="number" name="DonGiaSach" id="editGiaSach" class="form-control" min="0"
                                step="1000" required>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label">Tác giả</label>
                                <input type="text" name="TacGia" id="editTacGia" class="form-control">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Số trang</label>
                                <input type="number" name="SoTrang" id="editSoTrang" class="form-control" min="1"
                                    step="1">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function setEnabled(containerId, enabled) {
        document.querySelectorAll(`#${containerId} input, #${containerId} select, #${containerId} textarea`)
            .forEach(el => el.disabled = !enabled);
    }

    /* Tạo mới (form bên trái) */
    function showForm() {
        const type = document.getElementById('type').value;
        const isPhone = (type === 'DienThoai');

        document.getElementById('formDienThoai').classList.toggle('d-none', !isPhone);
        document.getElementById('formSach').classList.toggle('d-none', isPhone);

        setEnabled('formDienThoai', isPhone);
        setEnabled('formSach', !isPhone);
    }
    window.addEventListener('DOMContentLoaded', showForm);

    /* Chỉnh sửa (modal) */
    const editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', (ev) => {
        const btn = ev.relatedTarget;
        if (!btn) return;

        const type = btn.getAttribute('data-type');
        const id = btn.getAttribute('data-id');

        document.getElementById('editType').value = type;
        document.getElementById('editId').value = id;

        const isPhone = (type === 'DienThoai');

        // Toggle hiển thị hai nhóm trong modal
        document.getElementById('editFormDienThoai').classList.toggle('d-none', !isPhone);
        document.getElementById('editFormSach').classList.toggle('d-none', isPhone);

        // Chỉ enable nhóm tương ứng, disable nhóm còn lại để tránh required/min chặn submit
        setEnabled('editFormDienThoai', isPhone);
        setEnabled('editFormSach', !isPhone);

        if (isPhone) {
            document.getElementById('editTenDT').value = btn.getAttribute('data-ten') || '';
            document.getElementById('editGiaDT').value = btn.getAttribute('data-gia') || '';
            document.getElementById('editHangDT').value = btn.getAttribute('data-hang') || '';
            document.getElementById('editBHDt').value = btn.getAttribute('data-bh') || '';
        } else {
            document.getElementById('editTenSach').value = btn.getAttribute('data-ten') || '';
            document.getElementById('editGiaSach').value = btn.getAttribute('data-gia') || '';
            document.getElementById('editTacGia').value = btn.getAttribute('data-tg') || '';
            document.getElementById('editSoTrang').value = btn.getAttribute('data-st') || '';
        }
    });
    </script>

</body>

</html>
