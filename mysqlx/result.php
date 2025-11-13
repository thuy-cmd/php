<?php
include "connectest.php";

$mabn = intval($_GET['mabn']);
$sql = mysqli_query($conn, "SELECT * FROM dkykham WHERE mabn = $mabn");
$row = mysqli_fetch_assoc($sql);

if (isset($_POST['btnDone'])) {
    $kqua = $_POST['kqua'];
    $cdoan = $_POST['cdoan'];
    $kthuoc = $_POST['kthuoc'];
    $hdon = $_POST['hdon'];
    $gchux = $_POST['gchux'];

    $sql = "INSERT INTO ketqua (mabn, kqua, cdoan, kthuoc, hdon, gchux)
            VALUES ('$mabn', '$kqua', '$cdoan', '$kthuoc', '$hdon', '$gchux')";
    mysqli_query($conn, $sql);
    header("Location: listr.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả khám bệnh</title>
    <link rel="stylesheet" href="all.css">
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="result.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
<div class="all">
    <div class="header">
        <p class="r">PHẦN MỀM QUẢN LÝ KHÁM CHỮA BỆNH - Phòng khám Đa khoa Lạng Sơn</p>
        <p class="l">ver 301.3.2.09</p>
    </div>
    <h2>Nhập kết quả khám bệnh</h2>
    <div class="form">
        <div class="space">
            <form method="POST">
                 <div class="info">
                    <label>Mã BN:</label>
                    <p><?= $row['mabn'] ?></p>
                    <label>Họ tên:</label>
                    <p><?= htmlspecialchars($row['ten']) ?></p>
                    <label>Tuổi:</label>
                    <p><?= htmlspecialchars($row['tuoi']) ?></p>
                    <label>Địa chỉ:</label>
                    <p><?= htmlspecialchars($row['dchi']) ?></p>
                </div>
                <div class="iput">
                    <label for="kqua">Kết quả khám:</label>
                    <input type="text" name="kqua" required style="width: 280px; height: 20px">
                    <label for="cdoan">Chẩn đoán:</label>
                    <input type="text" name="cdoan" required style="width: 220px; height: 20px">
                    <label for="kthuoc">Kê thuốc:</label>
                    <input type="text" name="kthuoc" required style="width: 250px; height: 20px">
                    <label for="hdon">Tiền thuốc:</label>
                    <input type="number" name="hdon" placeholder="VND" required style="width: 120px; height: 20px;">
                    <label for="gchux" style="margin-top: 25px">Ghi chú:</label>
                    <textarea name="gchux" style="width: 250px; height: 50px" style="margin-top: 0"></textarea>
                    <button type="submit" name="btnDone" style="margin-top: 40px">Hoàn thành</button>
                </div>
            </form>
            <h3><a href="listr.php">Xem danh sách kết quả <i class="fa-solid fa-arrow-right"></i></a></h3>
        </div>
    </div>
</div>
</body>
</html>