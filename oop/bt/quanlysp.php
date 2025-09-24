<?php
class SanPham {
    protected $TenSanPham;
    protected $DonGia;

    public function __construct($TenSanPham, $DonGia) {
        $this->TenSanPham = $TenSanPham;
        $this->DonGia = $DonGia;
    }

    public function HienThi() {
        echo "Tên: {$this->TenSanPham}, Đơn giá: " . number_format($this->DonGia, 0, ',', '.') . " đ<br>";
    }
}

class Sach extends SanPham {
    private $TacGia;
    private $SoTrang;

    public function __construct($TenSanPham, $DonGia, $TacGia, $SoTrang) {
        parent::__construct($TenSanPham, $DonGia);
        $this->TacGia = $TacGia;
        $this->SoTrang = $SoTrang;
    }

    public function HienThi() {
        echo "📘 <strong>Sách</strong>: Tên: {$this->TenSanPham}, Tác giả: {$this->TacGia}, Số trang: {$this->SoTrang}, Đơn giá: " . number_format($this->DonGia, 0, ',', '.') . " đ<br>";
    }
}

class DienThoai extends SanPham {
    private $HangSX;
    private $BaoHanh;

    public function __construct($TenSanPham, $DonGia, $HangSX, $BaoHanh) {
        parent::__construct($TenSanPham, $DonGia);
        $this->HangSX = $HangSX;
        $this->BaoHanh = $BaoHanh;
    }

    public function HienThi() {
        echo "📱 <strong>Điện thoại</strong>: Tên: {$this->TenSanPham}, Hãng: {$this->HangSX}, Bảo hành: {$this->BaoHanh} tháng, Đơn giá: " . number_format($this->DonGia, 0, ',', '.') . " đ<br>";
    }
}

$danhSachSanPham = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';

    if ($type === 'Sach') {
        $ten = $_POST['TenSach'] ?? '';
        $gia = $_POST['DonGiaSach'] ?? 0;
        $tacgia = $_POST['TacGia'] ?? '';
        $sotrang = $_POST['SoTrang'] ?? 0;

        $sach = new Sach($ten, $gia, $tacgia, $sotrang);
        $danhSachSanPham[] = $sach;
    } elseif ($type === 'DienThoai') {
        $ten = $_POST['TenDienThoai'] ?? '';
        $gia = $_POST['DonGiaDienThoai'] ?? 0;
        $hang = $_POST['HangSX'] ?? '';
        $baohanh = $_POST['BaoHanh'] ?? 0;

        $dt = new DienThoai($ten, $gia, $hang, $baohanh);
        $danhSachSanPham = $dt;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>
    <style>
    .form-section {
        display: none;
        margin-top: 20px;
        padding: 10px;
        border: 1px solid #ccc;
        width: 350px;
    }

    body {
        font-family: Arial;
        padding: 20px;
    }
    </style>
</head>

<body>
    <h2>Thêm sản phẩm mới</h2>

    <form method="post" action="">
        <label for="type">Chọn loại sản phẩm:</label>
        <select name="type" id="type" onchange="showForm()">
            <option value="">-- Chọn loại sản phẩm --</option>
            <option value="DienThoai" <?= isset($_POST['type']) && $_POST['type'] === 'DienThoai' ? 'selected' : '' ?>>
                Điện thoại</option>
            <option value="Sach" <?= isset($_POST['type']) && $_POST['type'] === 'Sach' ? 'selected' : '' ?>>Sách
            </option>
        </select>

        <div id="formDienThoai" class="form-section">
            <h3>Thông tin điện thoại</h3>
            <label>Tên sản phẩm: <input type="text" name="TenDienThoai"></label><br><br>
            <label>Đơn giá: <input type="number" name="DonGiaDienThoai"></label><br><br>
            <label>Hãng sản xuất: <input type="text" name="HangSX"></label><br><br>
            <label>Bảo hành (tháng): <input type="number" name="BaoHanh"></label><br><br>
        </div>

        <div id="formSach" class="form-section">
            <h3>Thông tin sách</h3>
            <label>Tên sách: <input type="text" name="TenSach"></label><br><br>
            <label>Đơn giá: <input type="number" name="DonGiaSach"></label><br><br>
            <label>Tác giả: <input type="text" name="TacGia"></label><br><br>
            <label>Số trang: <input type="number" name="SoTrang"></label><br><br>
        </div>

        <input type="submit" value="Thêm sản phẩm">
    </form>

    <hr>

    <h2>Danh sách sản phẩm đã thêm:</h2>
    <?php
    if (count($danhSachSanPham) === 0) {
        echo "<i>Chưa có sản phẩm nào được thêm.</i>";
    } else {
        foreach ($danhSachSanPham as $sp) {
            $sp->HienThi();
        }
    }
    ?>

    <script>
    function showForm() {
        const type = document.getElementById('type').value;
        document.getElementById('formDienThoai').style.display = (type === 'DienThoai') ? 'block' : 'none';
        document.getElementById('formSach').style.display = (type === 'Sach') ? 'block' : 'none';
    }

    window.onload = showForm;
    </script>
</body>

</html>