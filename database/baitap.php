<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm Tiêu dùng</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        background-color: #f4f6f7;
    }

    h2 {
        text-align: center;
        color: #333;
    }

    .khung {
        width: 80%;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    input,
    textarea {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    button {
        background-color: #28a745;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #218838;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        padding: 8px;
        text-align: center;
        border: 1px solid #ddd;
    }

    th {
        color: #218838;
        background-color: #f2f2f2;
    }

    a {
        color: #007bff;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
    </style>
</head>

<body>
    <div class="khung">
        <h2>Quản lý sản phẩm Tiêu dùng</h2>
        <form action="process_product.php" method="POST">
            <input type="text" name="product_name" placeholder="Tên sản phẩm" required>
            <input type="number" name="price" placeholder="Giá sản phẩm" required>
            <textarea name="description" placeholder="Mô tả sản phẩm" rows="4" required></textarea>
            <button type="submit">Thêm sản phẩm</button>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
            <?php
            $conn = new mysqli("localhost", "root", "", "k9tin");
            if ($conn->connect_error) {
                die("Kết nối thất bại: " . $conn->connect_error);
            }
            $sql = "SELECT * FROM products";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>".$row["id"]."</td>
                            <td>".$row["product_name"]."</td>
                            <td>".$row["price"]."</td>
                            <td>".$row["description"]."</td>
                            <td>
                                <a href='edit_product.php?id=".$row["id"]."'>Sửa</a> |
                                <a href='delete_product.php?id=".$row["id"]."'>Xóa</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Không có sản phẩm nào</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
</body>

</html>
