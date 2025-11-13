<?php include "db.php"; ?>
<h2>Quản lý khách hàng</h2>

<form method="post">
    Mã KH: <input type="text" name="makh" value="<?php echo isset($_GET['makh']) ? $_GET['makh'] : ''; ?>"><br><br>
    Tên KH: <input type="text" name="tenkh" value="<?php echo isset($_GET['tenkh']) ? $_GET['tenkh'] : ''; ?>"><br><br>
    SĐT: <input type="text" name="sdt" value="<?php echo isset($_GET['sdt']) ? $_GET['sdt'] : ''; ?>"><br><br>
    <input type="submit" name="them" value="Thêm">
    <input type="submit" name="sua" value="Sửa">
</form>
<hr>

<?php
// ===== THÊM KHÁCH HÀNG =====
if (isset($_POST['them'])) {
    $makh = $_POST['makh'];
    $tenkh = $_POST['tenkh'];
    $sdt = $_POST['sdt'];
    $conn->query("INSERT INTO khachhang VALUES ('$makh','$tenkh','$sdt')");
}

// ===== XOÁ KHÁCH HÀNG =====
if (isset($_GET['xoa'])) {
    $makh = $_GET['xoa'];
    $conn->query("DELETE FROM khachhang WHERE makh='$makh'");
}

// ===== SỬA KHÁCH HÀNG =====
if (isset($_POST['sua'])) {
    $makh = $_POST['makh'];
    $tenkh = $_POST['tenkh'];
    $sdt = $_POST['sdt'];
    $conn->query("UPDATE khachhang SET tenkh='$tenkh', sdt='$sdt' WHERE makh='$makh'");
}
?>

<table border="1" cellpadding="6" style="margin-top:10px; border-collapse:collapse;">
<tr style="background:#007bff;color:white;">
    <th>Mã KH</th><th>Tên KH</th><th>SĐT</th><th>Thao tác</th>
</tr>

<?php
$result = $conn->query("SELECT * FROM khachhang");
while ($row = $result->fetch_assoc()) {
    $editLink = "?makh={$row['makh']}&tenkh={$row['tenkh']}&sdt={$row['sdt']}";
    echo "<tr>
        <td>{$row['makh']}</td>
        <td>{$row['tenkh']}</td>
        <td>{$row['sdt']}</td>
        <td>
            <a href='$editLink'>Sửa</a> |
            <a href='?xoa={$row['makh']}' onclick=\"return confirm('Bạn có chắc muốn xoá?')\">Xoá</a>
        </td>
    </tr>";
}
?>
</table>

<br><a href="index.php">⬅️ Về trang chủ</a>
