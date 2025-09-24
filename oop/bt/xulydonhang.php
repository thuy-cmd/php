<?php
interface ThanhToan {
    public function thanhToan($soTien);
}

abstract class DonHang {
    protected $MaDonHang;
    protected $TongTien;

    public function __construct($MaDonHang, $TongTien) {
        $this->MaDonHang = $MaDonHang;
        $this->TongTien = $TongTien;
    }

    public function getTongTien() {
        return $this->TongTien;
    }

    public function HienThiDonHang() {
        echo "<strong>Mã đơn hàng:</strong> {$this->MaDonHang}<br>";
        echo "<strong>Tổng tiền:</strong> " . number_format($this->TongTien, 0, ',', '.') . " VNĐ<br>";
    }

    abstract public function XuLyDonHang();
}

class DonHangCOD extends DonHang implements ThanhToan {
    public function XuLyDonHang() {
        echo "➡️ Đơn hàng {$this->MaDonHang} đang kiểm tra tồn kho và chuẩn bị giao hàng.<br>";
    }

    public function thanhToan($soTien) {
        echo "💰 Thanh toán khi nhận hàng: " . number_format($soTien, 0, ',', '.') . " VNĐ<br>";
    }
}

class DonHangOnline extends DonHang implements ThanhToan {
    public function XuLyDonHang() {
        echo "🧾 Đơn hàng {$this->MaDonHang} đang xử lý thanh toán điện tử và chuẩn bị giao.<br>";
    }

    public function thanhToan($soTien) {
        echo "✅ Đã thanh toán online: " . number_format($soTien, 0, ',', '.') . " VNĐ<br>";
    }
}

    $danhSachDonHang = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $type = $_POST['type'] ?? '';
        $ma = $_POST['MaDonHang'] ?? '';
        $tongTien = $_POST['TongTien'] ?? 0;

        if ($type === 'COD') {
            $don = new DonHangCOD($ma, $tongTien);
        } elseif ($type === 'Online') {
            $don = new DonHangOnline($ma, $tongTien);
        }

        if (isset($don)) {
            $danhSachDonHang[] = $don;
        }
    }

    $doncod = new DonHangCOD("COD02", 599000);
    $donol = new DonHangOnline("ONL02", 3000000);
    $danhSachDonHang[] = $doncod;
    $danhSachDonHang[] = $donol;
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đơn hàng tạm thời (không session)</title>
    <style>
    body {
        font-family: Arial;
        background: #f9f9f9;
        padding: 20px;
    }

    .form-section,
    .donhang {
        background: #fff;
        border: 1px solid #ccc;
        padding: 15px;
        margin-bottom: 20px;
        width: 400px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .donhang.online {
        border-left: 6px solid green;
    }

    .donhang.cod {
        border-left: 6px solid #007BFF;
    }
    </style>
</head>

<body>

    <h2>📥 Thêm đơn hàng mới</h2>

    <form method="post">
        <div class="form-section">
            <label>Loại đơn hàng:</label>
            <select name="type" id="type" required>
                <option value="">-- Chọn loại --</option>
                <option value="COD" <?= isset($_POST['type']) && $_POST['type'] === 'COD' ? 'selected' : '' ?>>COD
                </option>
                <option value="Online" <?= isset($_POST['type']) && $_POST['type'] === 'Online' ? 'selected' : '' ?>>
                    Online</option>
            </select><br><br>

            <label>Mã đơn hàng: <input type="text" name="MaDonHang" required></label><br><br>
            <label>Tổng tiền: <input type="number" name="TongTien" required></label><br><br>

            <input type="submit" value="Thêm đơn hàng">
        </div>
    </form>

    <h2>📦 Danh sách đơn hàng đã nhập (trong phiên hiện tại)</h2>

    <?php
if (!empty($danhSachDonHang)) {
    foreach ($danhSachDonHang as $don) {
        $class = get_class($don) === 'DonHangOnline' ? 'online' : 'cod';

        echo "<div class='donhang $class'>";
        $don->HienThiDonHang();
        $don->XuLyDonHang();
        $don->thanhToan($don->getTongTien());
        echo "</div>";
    }
} else {
    echo "<i>Chưa có đơn hàng nào được thêm.</i>";
}
?>

</body>

</html>