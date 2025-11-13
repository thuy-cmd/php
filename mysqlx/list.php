<?php
include "connectest.php";

$sql = "SELECT * FROM dkykham ORDER BY mabn DESC";
$kq = mysqli_query($conn, $sql);

if (isset($_POST['btnSua']) && isset($_POST['chon'])) {
    $mabn = ($_POST['chon']);
    header("Location: update.php?mabn=$mabn");
    exit;
}

if (isset($_POST['btnXoa']) && isset($_POST['chon'])) {
    $mabn = ($_POST['chon']);
    mysqli_query($conn, "DELETE FROM dkykham WHERE mabn = $mabn");
    header("Location: list.php");
    exit;
}

if (isset($_POST['btnKQua']) && isset($_POST['chon'])) {
    $mabn = ($_POST['chon']);
    header("Location: result.php?mabn=$mabn");
    exit;
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách phiếu khám</title>
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
    <h2>Danh sách phiếu khám</h2>
    <form method="post">
        <table cellpadding="8" cellspacing="0">
            <tr>
                <th style="width: 40px">Mã BN</th>
                <th style="width: 120px">Họ tên</th>
                <th style="width: 50px">Giới tính</th>
                <th style="width: 30px">Tuổi</th>
                <th style="width: 150px">Địa chỉ</th>
                <th style="width: 120px">Tình trạng</th>
                <th style="width: 70px">Loại khám</th>
                <th style="width: 50px">P.khám</th>
                <th style="width: 120px">Bác sĩ</th>
                <th style="width: 180px">Ghi chú</th>
                <th style="width: 100px">Phí khám</th>
                <th style="width: 5px">Chọn</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($kq)) { ?>
            <tr>
                <td><?= $row['mabn'] ?></td>
                <td><?= htmlspecialchars($row['ten']) ?></td>
                <td><?= htmlspecialchars($row['gtinh']) ?></td>
                <td><?= $row['tuoi'] ?></td>
                <td><?= htmlspecialchars($row['dchi']) ?></td>
                <td><?= htmlspecialchars($row['skhoe']) ?></td>
                <td><?= htmlspecialchars($row['lkham']) ?></td>
                <td><?= htmlspecialchars($row['pkham']) ?></td>
                <td><?= htmlspecialchars($row['bsi']) ?></td>
                <td><?= htmlspecialchars($row['gchu']) ?></td>
                <td><?= number_format($row['tkham']) ?> đ</td>
                <td><input type="radio" name="chon" value="<?= $row['mabn'] ?>"></td>
            </tr>
            <?php } ?>
        </table>
        <div class="footer">
            <div class="lspc" style="display: flex; flex-direction: row">
                <h3><a href="form.php"><i class="fa-solid fa-arrow-left" style="font-size: 16px"></i> Quay lại form đăng ký</a></h3>
                <button type="submit" name="btnKQua" class="btn-result" style="margin-left: 50px">Kết quả khám</button>
            </div>
            <div class="rspc">
                <button type="submit" name="btnSua" class="btn-update">Cập nhật</button>
                <button type="submit" name="btnXoa" class="btn-delete" 
                onclick="return confirm('Xoá dữ liệu này?')">Xóa</button>     
            </div>
        </div>
    </form>
</div>
</body>
</html>