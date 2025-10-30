<?php
    function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }


    // (CHỖ TRỐNG 1: KẾT NỐI DATABASE)
    try{
        $conn = new PDO("mysql:host=localhost;dbname=k9tin;charset=utf8mb4", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        echo "Kết nối thành công!";
    }
    catch(PDOException $e){
        http_response_code(500);
        die("Lỗi kết nối CSDL: " . $e->getMessage());
    }

    $notice = null;
    $selectedType = $_POST['type'] ?? 'DienThoai'; // Giữ lại state của form
    $action = $_POST['action'] ?? null;
    $products = [];
    // (CHỖ TRỐNG 2: XỬ LÝ POST - THÊM/XÓA)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action) {
        if ($action === 'create') {
            if ($selectedType === 'DienThoai') {
                $tendt = $_POST['TenDienThoai'] ?? '';
                $dongiadienthoai = (int)($_POST['DonGiaDienThoai'] ?? 0);
                $hangsx = $_POST['HangSX'] ?? '';
                $baohanh = (int)($_POST['BaoHanh'] ?? 0);
                // Thêm điện thoại vào DB
                $stmt = $conn->prepare("INSERT INTO products (type, ten, gia_vnd, hang_sx, bao_hanh_th) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute(['DienThoai', $tendt, $dongiadienthoai, $hangsx, $baohanh]);
                $notice = "Đã thêm điện thoại: " . h($tendt);
            }
            elseif ($selectedType === 'Sach') {
                $tensach = $_POST['TenSach'] ?? '';
                $dongiasach = (int)($_POST['DonGiaSach'] ?? 0);
                $tacgia = $_POST['TacGia'] ?? '';
                $sotrang = (int)($_POST['SoTrang'] ?? 0);
                // Thêm sách vào DB
                $stmt = $conn->prepare("INSERT INTO products (type, ten, gia_vnd, tac_gia, so_trang) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute(['Sach', $tensach, $dongiasach, $tacgia, $sotrang]);
                $notice = "Đã thêm sách: " . h($tensach);
            }
        }
        elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            // Xoá sản phẩm khỏi DB
            $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $notice = "Đã xoá sản phẩm có ID: " . $id;
        }
    }

    // (CHỖ TRỐNG 3: LẤY DỮ LIỆU TỪ DB)
    $stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
    $products = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý sản phẩm (Demo)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .card-sticky {
        position: sticky;
        top: 20px
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
                                    <option value="DienThoai" <?= $selectedType==='DienThoai'?'selected':'' ?>>Điện
                                        thoại</option>
                                    <option value="Sach" <?= $selectedType==='Sach'?'selected':'' ?>>Sách</option>
                                </select>
                            </div>

                            <!-- Điện thoại -->
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

                            <!-- Sách -->
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
                        <?php if (!$products): ?>
                        <p class="text-secondary fst-italic mb-0">Chưa có sản phẩm nào được thêm.</p>
                        <?php else: foreach($products as $p): ?>
                        <?php if ($p['type']==='DienThoai'): ?>
                        <div
                            class="border rounded p-3 mb-2 bg-body-tertiary d-flex gap-3 align-items-center justify-content-between">
                            <div class="d-flex gap-3 align-items-start">
                                <span class="fs-3">📱</span>
                                <div>
                                    <p class="mb-1"><strong>Điện thoại</strong>: <?= h($p['ten']) ?></p>
                                    <p class="text-secondary small mb-1">Hãng: <?= h($p['hang_sx'] ?? '') ?> • Bảo hành:
                                        <?= (int)($p['bao_hanh_th'] ?? 0) ?> tháng</p>
                                    <p class="mb-1">Đơn giá: <span
                                            class="badge text-bg-primary"><?= number_format((int)$p['gia_vnd'], 0, ',', '.') ?>
                                            đ</span></p>
                                </div>
                            </div>
                            <form method="post" onsubmit="return confirm('Xoá sản phẩm này?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                                <button class="btn btn-outline-danger">Xoá</button>
                            </form>
                        </div>
                        <?php else: ?>
                        <div
                            class="border rounded p-3 mb-2 bg-body-tertiary d-flex gap-3 align-items-center justify-content-between">
                            <div class="d-flex gap-3 align-items-start">
                                <span class="fs-3">📘</span>
                                <div>
                                    <p class="mb-1"><strong>Sách</strong>: <?= h($p['ten']) ?></p>
                                    <p class="text-secondary small mb-1">Tác giả: <?= h($p['tac_gia'] ?? '') ?> • Số
                                        trang: <?= (int)($p['so_trang'] ?? 0) ?> trang</p>
                                    <p class="mb-1">Đơn giá: <span
                                            class="badge text-bg-primary"><?= number_format((int)$p['gia_vnd'], 0, ',', '.') ?>
                                            đ</span></p>
                                </div>
                            </div>
                            <form method="post" onsubmit="return confirm('Xoá sản phẩm này?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                                <button class="btn btn-outline-danger">Xoá</button>
                            </form>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function setEnabled(containerId, enabled) {
        document.querySelectorAll(`#${containerId} input, #${containerId} select, #${containerId} textarea`)
            .forEach(el => el.disabled = !enabled);
    }

    function showForm() {
        const type = document.getElementById('type').value;
        const isPhone = (type === 'DienThoai');
        document.getElementById('formDienThoai').classList.toggle('d-none', !isPhone);
        document.getElementById('formSach').classList.toggle('d-none', isPhone);
        setEnabled('formDienThoai', isPhone);
        setEnabled('formSach', !isPhone);
    }
    window.addEventListener('DOMContentLoaded', showForm);
    </script>
</body>

</html>x