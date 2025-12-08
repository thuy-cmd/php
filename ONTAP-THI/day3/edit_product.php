<?php
// edit_product.php
session_start();
require 'db.php';
$conn = connectDB();

$username = $_SESSION['username'] ?? '';
$manv = $_SESSION['manv'] ?? null;
if (!$username || !$manv) { header("Location: login_inv.php"); exit; }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header("Location: inventory.php"); exit; }

// lấy product và kiểm tra quyền
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND manv = ?");
$stmt->execute([$id, $manv]);
$product = $stmt->fetch();
if (!$product) { header("Location: inventory.php"); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sku = trim($_POST['sku'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $qty = (int)($_POST['qty'] ?? 0);
    $price = (float)($_POST['price'] ?? 0);
    $desc = trim($_POST['description'] ?? '');

    if ($sku === '' || $name === '') $errors[] = "SKU và tên không được để trống.";

    if (empty($errors)) {
        // nếu sku thay đổi, kiểm tra unique
        if ($sku !== $product['sku']) {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE sku = ? AND id <> ?");
            $stmt->execute([$sku, $id]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = "SKU đã tồn tại.";
            }
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE products SET sku = ?, name = ?, qty = ?, price = ?, description = ? WHERE id = ? AND manv = ?");
        $stmt->execute([$sku, $name, $qty, $price, $desc, $id, $manv]);
        header("Location: inventory.php");
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
    <title>Sửa sản phẩm</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <div class="container" style="max-width:640px;margin:40px auto">
        <div class="card">
            <h2 style="margin:0 0 10px">Sửa sản phẩm</h2>
            <?php if($errors): ?><div style="color:var(--danger);margin-bottom:10px">
                <?php foreach($errors as $err) echo '<div>'.e($err).'</div>'; ?></div><?php endif; ?>

            <form method="post">
                <div class="form__group"><label>SKU</label><input name="sku" value="<?= e($product['sku']) ?>"
                        type="text" required></div>
                <div class="form__group"><label>Tên</label><input name="name" value="<?= e($product['name']) ?>"
                        type="text" required></div>
                <div class="form__group"><label>Số lượng</label><input name="qty" value="<?= (int)$product['qty'] ?>"
                        type="number" min="0"></div>
                <div class="form__group"><label>Giá</label><input name="price"
                        value="<?= number_format((float)$product['price'],2,'.','') ?>" type="text"></div>
                <div class="form__group"><label>Mô tả</label><textarea
                        name="description"><?= e($product['description']) ?></textarea></div>

                <div style="display:flex;gap:8px;justify-content:space-between">
                    <a href="inventory.php" class="btn ghost" style="text-decoration:none;padding:8px 12px">Hủy</a>
                    <button type="submit" class="btn primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
