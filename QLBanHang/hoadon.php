<?php include "db.php"; ?>

<h2>Lập hóa đơn bán hàng</h2>

<form method="post">
    Mã hóa đơn: <input type="text" name="mahd"><br><br>
    Mã khách hàng: <input type="text" name="makh"><br><br>
    Mã sản phẩm: <input type="text" name="masp"><br><br>
    Số lượng bán: <input type="number" name="soluong"><br><br>
    <input type="submit" name="lap" value="Lập hóa đơn">
</form>

<?php
if (isset($_POST['lap'])) {
    $mahd = $_POST['mahd'];
    $makh = $_POST['makh'];
    $masp = $_POST['masp'];
    $soluong = $_POST['soluong'];
    $ngaylap = date("Y-m-d");

    // Lấy giá sản phẩm
    $result = $conn->query("SELECT dongia FROM sanpham WHERE masp='$masp'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $dongia = $row['dongia'];
        $thanhtien = $soluong * $dongia;

        // Thêm hóa đơn và chi tiết
        $conn->query("INSERT INTO hoadon VALUES ('$mahd','$makh','$ngaylap',$thanhtien)");
        $conn->query("INSERT INTO chitiethd VALUES ('$mahd','$masp',$soluong,$dongia,$thanhtien)");

        echo "<p style='color:green'>✅ Lập hóa đơn thành công! Tổng tiền: $thanhtien VNĐ</p>";
    } else {
        echo "<p style='color:red'>❌ Mã sản phẩm không tồn tại!</p>";
    }
}

$result = $conn->query("SELECT * FROM hoadon");
echo "<h3>Danh sách hóa đơn</h3>";
echo "<table border='1' cellpadding='6'>
<tr><th>Mã HĐ</th><th>Mã KH</th><th>Ngày lập</th><th>Tổng tiền</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['mahd']}</td>
        <td>{$row['makh']}</td>
        <td>{$row['ngaylap']}</td>
        <td>{$row['tongtien']}</td>
    </tr>";
}
echo "</table>";
?>
<br><a href="index.php">Về trang chủ</a>
