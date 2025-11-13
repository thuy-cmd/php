<?php
include "connectest.php";

$mabn = ($_GET['mabn']);
$sql = mysqli_query($conn, "SELECT * FROM dkykham WHERE mabn = $mabn");
$row = mysqli_fetch_assoc($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật</title>
    <link rel="stylesheet" href="all.css">
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="update.css">
</head>
<body>
    <div class="all">
        <div class="header">
            <p class="r">PHẦN MỀM QUẢN LÝ KHÁM CHỮA BỆNH - Phòng khám Đa khoa Lạng Sơn</p>
            <p class="l">ver 301.3.2.09</p>
        </div>
        <h2>Cập nhật phiếu khám</h2>
        <form method="POST">
            <label for="ten">Họ và tên</label>
            <input type="text" name="ten" value="<?= htmlspecialchars($row['ten']) ?>" required style="width: 170px; height: 15px">
            <label for="gtinh">Giới tính</label>
            <input type="text" name="gtinh" value="<?= htmlspecialchars($row['gtinh']) ?>" required style="width: 50px; height: 15px">
            <label for="tuoi">Tuổi</label>
            <input type="number" name="tuoi" value="<?= $row['tuoi'] ?>" required style="width: 30px; height: 15px">
            <label for="dchi">Địa chỉ</label>
            <input type="text" name="dchi" value="<?= htmlspecialchars($row['dchi']) ?>" required style="width: 200px; height: 15px">
            <label for="skhoe">Tình trạng SK</label>
            <input type="text" name="skhoe" value="<?= htmlspecialchars($row['skhoe']) ?>" required style="width: 150px; height: 15px">
            <label for="lkham">Loại khám</label>
            <input type="text" name="lkham" value="<?= htmlspecialchars($row['lkham']) ?>" required style="width: 110px; height: 15px">
            <label for="pkham">Phòng khám</label>
            <input type="text" name="pkham" value="<?= htmlspecialchars($row['pkham']) ?>" required style="width: 60px; height: 15px">
            <label for="bsi">Bác sĩ thăm khám</label>
            <input type="text" name="bsi" value="<?= htmlspecialchars($row['bsi']) ?>" required style="width: 170px; height: 15px">
            <label for="tkham">Phí khám bệnh</label>
            <input type="number" name="tkham" value="<?= $row['tkham'] ?>" placeholder="VND" required style="width: 120px; height: 15px">
            <label for="gchu" style="">Ghi chú</label>
            <textarea name="gchu" style="width: 250px; height: 50px"><?= htmlspecialchars($row['gchu']) ?></textarea>
            <button type="submit" name="btnCapNhat">Lưu thay đổi</button>
        </form>
    </div>
</body>
</html>

<?php
if (isset($_POST['btnCapNhat'])) {
    $ten = $_POST['ten'];
    $gtinh = $_POST['gtinh'];
    $tuoi = $_POST['tuoi'];
    $dchi = $_POST['dchi'];
    $skhoe = $_POST['skhoe'];
    $lkham = $_POST['lkham'];
    $pkham = $_POST['pkham'];
    $bsi = $_POST['bsi'];
    $gchu = $_POST['gchu'];
    $tkham = $_POST['tkham'];

    $sql_update = "UPDATE dkykham SET 
        ten='$ten', gtinh='$gtinh', tuoi=$tuoi, dchi='$dchi', skhoe='$skhoe',
        lkham='$lkham', pkham='$pkham', bsi='$bsi', gchu='$gchu', tkham=$tkham
        WHERE mabn=$mabn";

    mysqli_query($conn, $sql_update);
    header("Location: list.php");
    exit;
}
?>
