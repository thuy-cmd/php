<?php
include "connectest.php";

if (isset($_POST['btnThem'])) {
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

    $sql = "INSERT INTO dkykham(ten, gtinh, tuoi, dchi, skhoe, lkham, pkham, bsi, gchu, tkham)
            VALUES('$ten', '$gtinh', '$tuoi', '$dchi', '$skhoe', '$lkham', '$pkham', '$bsi', '$gchu', '$tkham')";
    mysqli_query($conn, $sql);

    header("Location: list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Phòng khám</title>
    <link rel="stylesheet" href="all.css">
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
<div class="all">
    <div class="header">
        <p class="r">PHẦN MỀM QUẢN LÝ KHÁM CHỮA BỆNH - Phòng khám Đa khoa Lạng Sơn</p>
        <p class="l">ver 301.3.2.09</p>
    </div>
    <h2>Tạo phiếu đăng ký khám bệnh</h2>
    <div class="form">
        <div class="space">
            <form method="POST">
                <label for="ten">Họ và tên</label>
                <input type="text" name="ten" required style="width: 170px; height: 15px">
                <label for="gtinh">Giới tính</label>
                <input type="text" name="gtinh" required style="width: 50px; height: 15px">
                <label for="tuoi">Tuổi</label>
                <input type="text" name="tuoi" required style="width: 30px; height: 15px">
                <label for="dchi">Địa chỉ</label>
                <input type="text" name="dchi" required style="width: 200px; height: 15px">
                <label for="skhoe">Tình trạng SK</label>
                <input type="text" name="skhoe" required style="width: 150px; height: 15px">
                <label for="lkham">Loại khám</label>
                <input type="text" name="lkham" required style="width: 110px; height: 15px">
                <label for="pkham">Phòng khám</label>
                <input type="text" name="pkham" required style="width: 60px; height: 15px">
                <label for="bsi">Bác sĩ thăm khám</label>
                <input type="text" name="bsi" required style="width: 170px; height: 15px">
                <label for="tkham">Phí khám bệnh</label>
                <input type="text" name="tkham" placeholder="VND" required style="width: 120px; height: 15px">
                <label for="gchu" style="">Ghi chú</label>
                <textarea name="gchu" style="width: 250px; height: 50px"></textarea>
                <button type="submit" name="btnThem">Thêm</button>
            </form>
            <p><h3><a href="list.php">Xem danh sách phiếu khám <i class="fa-solid fa-arrow-right"></i></a></h3></p>
        </div>
    </div>
</div>
</body>
</html>