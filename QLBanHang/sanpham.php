<?php include "db.php"; ?>
<h2>Quản lý sản phẩm</h2>

<form method="post">
    Mã SP: <input type="text" name="masp" value="<?php echo isset($_GET['masp']) ? $_GET['masp'] : ''; ?>"><br><br>
    Tên SP: <input type="text" name="tensp" value="<?php echo isset($_GET['tensp']) ? $_GET['tensp'] : ''; ?>"><br><br>
    Đơn giá: <input type="text" name="dongia" value="<?php echo isset($_GET['dongia']) ? $_GET['dongia'] : ''; ?>"><br><br>
    Số lượng: <input type="text" name="soluong" value="<?php echo isset($_GET['soluong']) ? $_GET['soluong'] : ''; ?>"><br><br>
    <input type="submit" name="them" value="Thêm">
    <input type="submit" name="sua" value="Sửa">
</form>
<hr>

<?php
// ===== THÊM SẢN PHẨM =====
if (isset($_POST['them'])) {
    $masp = $_POST['masp'];
    $tensp = $_POST['tensp'];
    $dongia = $_POST['dongia'];
    $soluong = $_POST['soluong'];
    $conn->query("INSERT INTO sanpham VALUES ('$masp','$tensp',$dongia,$soluong)");
}

// ===== XOÁ SẢN PHẨM =====
if (isset($_GET['xoa'])) {
    $masp = $_GET['xoa'];
    $conn->query("DELETE FROM sanpham WHERE masp='$masp'");
}

// ===== SỬA SẢN PHẨM =====
if (isset($_POST['sua'])) {
    $masp = $_POST['masp'];
    $tensp = $_POST['tensp'];
    $dongia = $_POST['dongia'];
    $soluong = $_POST['soluong'];
    $conn->query("UPDATE sanpham SET tensp='$tensp', dongia=$dongia, soluong=$soluong WHERE masp='$masp'");
}
?>

<table border="1" cellpadding="6" style="margin-top:10px; border-collapse:collapse;">
<tr style="background:#007bff;color:white;">
    <th>Mã SP</th><th>Tên SP</th><th>Đơn giá</th><th>Số lượng</th><th>Thao tác</th>
</tr>

<?php
$result = $conn->query("SELECT * FROM sanpham");
while ($row = $result->fetch_assoc()) {
    $editLink = "?masp={$row['masp']}&tensp={$row['tensp']}&dongia={$row['dongia']}&soluong={$row['soluong']}";
    echo "<tr>
        <td>{$row['masp']}</td>
        <td>{$row['tensp']}</td>
        <td>{$row['dongia']}</td>
        <td>{$row['soluong']}</td>
        <td>
            <a href='$editLink'>Sửa</a> |
            <a href='?xoa={$row['masp']}' onclick=\"return confirm('Bạn có chắc muốn xoá?')\">Xoá</a>
        </td>
    </tr>";
}
?>
</table>

<br><a href="index.php">⬅️ Về trang chủ</a>
