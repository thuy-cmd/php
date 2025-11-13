<?php
include "connectest.php";

$sql = "SELECT a.mabn, b.ten, b.tuoi, b.dchi, a.kqua, a.cdoan, a.kthuoc, 
               a.hdon, a.gchux
        FROM ketqua a
        JOIN dkykham b ON a.mabn = b.mabn
        ORDER BY a.mabn DESC";
$result = mysqli_query($conn, $sql);

if (isset($_POST['btnXoa']) && isset($_POST['chon'])) {
    $mabn = ($_POST['chon']);
    mysqli_query($conn, "DELETE FROM ketqua WHERE mabn = $mabn");
    header("Location: listr.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách kết quả khám bệnh</title>
    <link rel="stylesheet" href="all.css">
    <link rel="stylesheet" href="list.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
<div class="all">
    <div class="header">
        <p class="r">PHẦN MỀM QUẢN LÝ KHÁM CHỮA BỆNH - Phòng khám Đa khoa Lạng Sơn</p>
        <p class="l">ver 301.3.2.09</p>
    </div>
    <h2>Danh sách kết quả khám bệnh</h2>
    <form method="post">
        <table cellpadding="8" cellspacing="0">
            <tr>
                <th style="width: 40px">Mã BN</th>
                <th style="width: 150px">Họ tên</th>
                <th style="width: 50px">Tuổi</th>
                <th style="width: 180px">Địa chỉ</th>
                <th style="width: 150px">Kết quả khám</th>
                <th style="width: 150px">Chẩn đoán</th>
                <th style="width: 120px">Kê thuốc</th>
                <th style="width: 90px">Tiền thuốc</th>
                <th style="width: 120px">Ghi chú</th>
                <th style="width: 5px">Chọn</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['mabn'] ?></td>
                <td><?= htmlspecialchars($row['ten']) ?></td>
                <td><?= $row['tuoi'] ?></td>
                <td><?= htmlspecialchars($row['dchi']) ?></td>
                <td><?= htmlspecialchars($row['kqua']) ?></td>
                <td><?= htmlspecialchars($row['cdoan']) ?></td>
                <td><?= htmlspecialchars($row['kthuoc']) ?></td>
                <td><?= number_format($row['hdon']) ?> đ</td>
                <td><?= htmlspecialchars($row['gchux']) ?></td>
                <td><input type="radio" name="chon" value="<?= $row['mabn'] ?>"></td>
            </tr>
            <?php } ?>
        </table>

        <div class="footer">
            <div class="lspc">
                <h3><a href="list.php"><i class="fa-solid fa-arrow-left"></i> Quay lại danh sách đăng ký</a></h3>
            </div>
            <div class="rspc">
                <button type="submit" name="btnXoa" class="btn-delete" 
                        onclick="return confirm('Loại bỏ dữ liệu?')">In và Xoá</button>
            </div>
        </div>
    </form>
</div>
</body>
</html>