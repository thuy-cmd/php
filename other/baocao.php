<?php
    function h($s) {
        return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
    }
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "k9tin";

    function connectDB($servername, $username, $password, $dbname) {
        try {
            $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        }
        catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    $conn = connectDB($servername, $username, $password, $dbname);

    $selectedType = $_POST['type'] ?? "DienThoai";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $selectedType = $_POST['type'] ?? "DienThoai";
        if ($selectedType === "DienThoai") {
            // Xử lý thêm điện thoại
            $ten = trim($_POST['TenDienThoai'] ?? '');
            $gia = (int)($_POST['DonGiaDienThoai'] ?? 0);
            $hang = trim($_POST['HangSX'] ?? '');
            $baohanh = (int)($_POST['BaoHanh'] ?? 0);

            $stmt = $conn->prepare("INSERT INTO products (type, ten, gia_vnd, hang_sx, bao_hanh_th) VALUES (:type, :ten, :gia_vnd, :hang_sx, :bao_hanh_th)");
            $stmt->execute(['type' => $selectedType, 'ten' => $ten, 'gia_vnd' => $gia, 'hang_sx' => $hang, 'bao_hanh_th' => $baohanh]);
            $stmt->closeCursor();
        } elseif ($selectedType === "Sach") {
            // Xử lý thêm sách
            $ten = trim($_POST['TenSach'] ?? '');
            $gia = (int)($_POST['DonGiaSach'] ?? 0);
            $tacgia = trim($_POST['TacGia'] ?? '');
            $sotrang = (int)($_POST['SoTrang'] ?? 0);
            $stmt = $conn->prepare("INSERT INTO products (type, ten, gia_vnd, tac_gia, so_trang) VALUES (:type, :ten, :gia_vnd, :tac_gia, :so_trang)");
            $stmt->execute(['type' => $selectedType, 'ten' => $ten, 'gia_vnd' => $gia, 'tac_gia' => $tacgia, 'so_trang' => $sotrang]);
            $stmt->closeCursor();
        }
    }

    $sql = $conn->query("SELECT * FROM products");

    $products = $sql->fetchAll();

?>

<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=`device-width`, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <div class="row g-3">
            <!-- Form -->
            <div class="col-12 col-lg-5">
                <div class="card card-sticky shadow-sm">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span class="fw-semibold">Thêm sản phẩm mới</span>
                    </div>
                    <div class="card-body">
                        <form method="post" action="" id="productForm" novalidate>
                            <div class="mb-3">
                                <label for="type" class="form-label">Chọn loại sản phẩm</label>
                                <select name="type" id="type" class="form-select" onchange="showForm()">
                                    <option value="DienThoai" <?=$selectedType === "DienThoai" ? "selected" : "" ?>>Điện
                                        thoại</option>
                                    <option value="Sach" <?= $selectedType === "Sach" ? "selected" : "" ?>>Sách</option>
                                </select>
                            </div>

                            <!-- Điện thoại -->
                            <div id="formDienThoai" class="d-none">
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
                            <div id="formSach" class="d-none">
                                <div class="mb-3">
                                    <label class="form-label">Tên sách</label>
                                    <input type="text" name="TenSach" class="form-control"
                                        placeholder="Ví dụ: Lập trình PHP hiện đại">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Đơn giá (đ)</label>
                                    <input type="number" name="DonGiaSach" class="form-control" min="0" step="1000"
                                        placeholder="95.000">
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
            <div class="col-12 col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <span>Danh sách sản phẩm đã thêm</span>
                    </div>
                    <div class="card-body">
                        <?php
                            if (count($products) === 0) {
                                echo '<p class="text-secondary fst-italic mb-0">Chưa có sản phẩm nào được thêm.</p>';
                            }
                            else {
                                foreach ($products as $product) {
                                    if ($product['type'] === 'DienThoai') {
                                        echo '<div class="border rounded p-3 mb-2 bg-body-tertiary d-flex gap-3 align-items-center justify-content-between">';
                                        echo '<div class="d-flex gap-3 align-items-start">';
                                        echo '<span class="fs-3">📱</span>';
                                        echo '<div>';
                                        echo '<p class="mb-1"><strong>Điện thoại</strong>: ' . h($product['ten']) . '</p>';
                                        echo '<p class="text-secondary small mb-1">Hãng: ' . h($product['hang_sx']) . ' • Bảo hành: ' . h($product['bao_hanh_th']) . ' tháng</p>';
                                        echo '<p class="mb-1">Đơn giá: <span class="badge text-bg-primary">' . number_format($product['gia_vnd'], 0, ',', '.') . ' đ</span></p>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '<div class="d-flex gap-3 align-items-center justify-content-between">';
                                        echo '<button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Chỉnh sửa</button>';
                                        echo '<button class="btn btn-outline-danger">Xóa</button>';
                                        echo '</div>';
                                        echo '</div>';
                                    }
                                    elseif ($product['type'] === 'Sach') {
                                        echo '<div class="border rounded p-3 mb-2 bg-body-tertiary d-flex gap-3 align-items-center justify-content-between">';
                                        echo '<div class="d-flex gap-3 align-items-start">';
                                        echo '<span class="fs-3">📘</span>';
                                        echo '<div>';
                                        echo '<p class="mb-1"><strong>Sách</strong>: ' . h($product['ten']) . '</p>';
                                        echo '<p class="text-secondary small mb-1">Tác giả: ' . h($product['tac_gia']) . ' • Số trang: ' . h($product['so_trang']) . ' trang</p>';
                                        echo '<p class="mb-1">Đơn giá: <span class="badge text-bg-primary">' . number_format($product['gia_vnd'], 0, ',', '.') . ' đ</span></p>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '<div class="d-flex gap-3 align-items-center justify-content-between">';
                                        echo '<button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Chỉnh sửa</button>';
                                        echo '<button class="btn btn-outline-danger">Xóa</button>';
                                        echo '</div>';
                                        echo '</div>';
                                    }
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Chỉnh sửa sản phẩm</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                        if ($selectedType === "DienThoai") {
                            echo '<div id="formDienThoai">';
                            echo '<div class="mb-3">';
                            echo '<label class="form-label">Tên sản phẩm</label>';
                            echo '<input type="text" name="TenDienThoai" class="form-control" placeholder="Ví dụ: Galaxy A35" required>';
                            echo '</div>';
                            echo '<div class="mb-3">';
                            echo '<label class="form-label">Đơn giá (đ)</label>';
                            echo '<input type="number" name="DonGiaDienThoai" class="form-control" min="0" step="1000" placeholder="12.990.000" required>';
                            echo '</div>';
                            echo '<div class="row g-3">';
                            echo '<div class="col-md-7">';
                            echo '<label class="form-label">Hãng sản xuất</label>';
                            echo '<input type="text" name="HangSX" class="form-control" placeholder="Samsung / Apple / ...">';
                            echo '</div>';
                            echo '<div class="col-md-5">';
                            echo '<label class="form-label">Bảo hành (tháng)</label>';
                            echo '<input type="number" name="BaoHanh" class="form-control" min="0" step="1" value="12">';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        } elseif ($selectedType === "Sach") {
                            echo '<div id="formSach">';
                            echo '<div class="mb-3">';
                            echo '<label class="form-label">Tên sách</label>';
                            echo '<input type="text" name="TenSach" class="form-control" placeholder="Ví dụ: Lập trình PHP hiện đại">';
                            echo '</div>';
                            echo '<div class="mb-3">';
                            echo '<label class="form-label">Đơn giá (đ)</label>';
                            echo '<input type="number" name="DonGiaSach" class="form-control" min="0" step="1000" placeholder="95.000" required>';
                            echo '</div>';
                            echo '<div class="row g-3">';
                            echo '<div class="col-md-7">';
                            echo '<label class="form-label">Tác giả</label>';
                            echo '<input type="text" name="TacGia" class="form-control" placeholder="Nguyễn Văn A">';
                            echo '</div>';
                            echo '<div class="col-md-5">';
                            echo '<label class="form-label">Số trang</label>';
                            echo '<input type="number" name="SoTrang" class="form-control" min="0" step="1" placeholder="320">';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function showForm() {
        const type = document.getElementById('type').value;
        document.getElementById('formDienThoai').classList.toggle('d-none', type !== 'DienThoai');
        document.getElementById('formSach').classList.toggle('d-none', type !== 'Sach');
    }
    // mặc định hiển thị Điện thoại
    window.addEventListener('DOMContentLoaded', () => {
        showForm();
    });
    </script>
</body>

</html>
