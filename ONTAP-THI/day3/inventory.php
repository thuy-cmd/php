<?php
// inventory.php
session_start();
require 'db.php';
$conn = connectDB();

$username = $_SESSION['username'] ?? '';
$manv = $_SESSION['manv'] ?? null;
if (!$username || !$manv) {
    header("Location: login_inv.php");
    exit;
}

$errors = [];
// Thêm sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $sku = trim($_POST['sku'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $qty  = (int)($_POST['qty'] ?? 0);
    $price = (float)($_POST['price'] ?? 0);
    $desc = trim($_POST['description'] ?? '');

    if ($sku === '' || $name === '') $errors[] = "SKU và tên sản phẩm không được để trống.";

    if (empty($errors)) {
        // đảm bảo sku unique
        $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE sku = ?");
        $stmt->execute([$sku]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "SKU đã tồn tại.";
        } else {
            $stmt = $conn->prepare("INSERT INTO products (manv, sku, name, qty, price, description) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$manv, $sku, $name, $qty, $price, $desc]);
            header("Location: inventory.php");
            exit;
        }
    }
}

// Xóa sản phẩm (chỉ owner mới xóa)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete' && !empty($_POST['id'])) {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ? AND manv = ?");
    $stmt->execute([$id, $manv]); // chỉ xóa nếu chính chủ
    header("Location: inventory.php");
    exit;
}

// Lấy danh sách sản phẩm (của chính user)
$stmt = $conn->prepare("SELECT id, sku, name, qty, price, description, created_at FROM products WHERE manv = ? ORDER BY created_at DESC");
$stmt->execute([$manv]);
$products = $stmt->fetchAll();

function e($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Inventory Manager</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <div class="container">
        <header class="header">
            <div class="brand">
                <div class="logo">IM</div>
                <div>
                    <h1>Inventory Manager</h1>
                    <div class="small">Xin chào, <strong><?= e($username) ?></strong></div>
                </div>
            </div>
            <div style="display:flex;gap:10px;align-items:center">
                <div class="small">Mã NV: <strong><?= e($manv) ?></strong></div>
                <a href="logout_inv.php" class="btn ghost" style="text-decoration:none">Đăng xuất</a>
            </div>
        </header>

        <?php if ($errors): ?>
        <div style="color:var(--danger);margin-bottom:12px">
            <?php foreach($errors as $err) echo '<div>'.e($err).'</div>'; ?>
        </div>
        <?php endif; ?>

        <main class="grid">
            <section class="card">
                <div style="display:flex;justify-content:space-between;align-items:center">
                    <div>
                        <h3 style="margin:0">Sản phẩm của bạn</h3>
                        <div class="small"><?= count($products) ?> sản phẩm</div>
                    </div>
                    <div>
                        <input id="search" type="search" class="input" placeholder="Tìm SKU hoặc tên">
                    </div>
                </div>

                <?php if (empty($products)): ?>
                <div class="empty">Chưa có sản phẩm — hãy thêm bên cạnh.</div>
                <?php else: ?>
                <table class="table" id="prodTable">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th>SKU / Tên</th>
                            <th style="width:110px">Số lượng</th>
                            <th style="width:110px">Giá</th>
                            <th style="width:180px">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=0; foreach($products as $p): $i++; ?>
                        <tr data-sku="<?= e(strtolower($p['sku'])) ?>" data-name="<?= e(strtolower($p['name'])) ?>">
                            <td><?= $i ?></td>
                            <td>
                                <div style="font-weight:600"><?= e($p['sku']) ?> — <?= e($p['name']) ?></div>
                                <div class="small"><?= e($p['description']) ?></div>
                            </td>
                            <td class="small"><?= (int)$p['qty'] ?></td>
                            <td class="small"><?= number_format((float)$p['price'],2) ?></td>
                            <td>
                                <div class="controls">
                                    <form action="edit_product.php" method="get" style="display:inline">
                                        <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                                        <button class="btn">Sửa</button>
                                    </form>
                                    <form action="" method="post" style="display:inline"
                                        onsubmit="return confirm('Xác nhận xóa sản phẩm này?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
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
                <h3 style="margin-top:0">Thêm sản phẩm mới</h3>
                <form method="post" action="">
                    <input type="hidden" name="action" value="add">
                    <div class="form__group"><label>SKU</label><input name="sku" type="text" required></div>
                    <div class="form__group"><label>Tên sản phẩm</label><input name="name" type="text" required></div>
                    <div class="form__group"><label>Số lượng</label><input name="qty" type="number" value="0" min="0">
                    </div>
                    <div class="form__group"><label>Giá (VNĐ)</label><input name="price" type="text" value="0.00"></div>
                    <div class="form__group"><label>Mô tả (tuỳ chọn)</label><textarea name="description"></textarea>
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
    // tìm kiếm client-side
    const q = document.getElementById('search');
    const rows = document.querySelectorAll('#prodTable tbody tr');
    if (q) q.addEventListener('input', () => {
        const v = q.value.trim().toLowerCase();
        rows.forEach(r => {
            const sku = r.dataset.sku || '';
            const name = r.dataset.name || '';
            r.style.display = (sku.indexOf(v) !== -1 || name.indexOf(v) !== -1) ? '' : 'none';
        });
    });
    </script>
</body>

</html>
