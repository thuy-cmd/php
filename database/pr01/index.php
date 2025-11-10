<?php
    function h($s) {
        return htmlspecialchars((string)($s ?? ''), ENT_QUOTES, 'UTF-8');
    }

    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $dbname     = "k9tin";

    function createConnection($servername, $username, $password, $dbname) {
        $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }

    try {
        $conn = createConnection($servername, $username, $password, $dbname);
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Kết nối thất bại: " . h($e->getMessage());
        exit;
    }

    $action = '';
    $editStudent = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        switch ($action) {
            case 'add':
                $name  = $_POST['name']  ?? '';
                $age   = (int)($_POST['age'] ?? 0);
                $email = $_POST['email'] ?? '';
                $stmt = $conn->prepare("INSERT INTO sinhvien (Name, Age, Email) VALUES (?, ?, ?)");
                $stmt->execute([$name, $age, $email]);
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;

            case 'edit':
                $id = (int)($_POST['id'] ?? 0);
                $stmt = $conn->prepare("SELECT * FROM sinhvien WHERE ID = ?");
                $stmt->execute([$id]);
                $editStudent = $stmt->fetch() ?: null;
                break;

            case 'update':
                $id    = (int)($_POST['id'] ?? 0);
                $name  = $_POST['name']  ?? '';
                $age   = (int)($_POST['age'] ?? 0);
                $email = $_POST['email'] ?? '';
                $stmt = $conn->prepare("UPDATE sinhvien SET Name = ?, Age = ?, Email = ? WHERE ID = ?");
                $stmt->execute([$name, $age, $email, $id]);
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;

            case 'delete':
                $id = (int)($_POST['id'] ?? 0);
                $stmt = $conn->prepare("DELETE FROM sinhvien WHERE ID = ?");
                $stmt->execute([$id]);
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
        }
    }

    $stmt = $conn->query("SELECT * FROM sinhvien ORDER BY ID DESC");
    $students = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luyện tập kết nối database</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .title {
        text-align: center;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .container {
        width: 1170px;
        margin: 0 auto;
        padding: 20px;
        display: flex;
        gap: 50px;
    }
    </style>
</head>

<body>
    <h1 class="title">Luyện tập kết nối database</h1>
    <div class="container">
        <div>
            <h2>Thêm sinh viên mới</h2>
            <form action="" method="post">
                <input type="hidden" name="action" value="add">
                <div>
                    <label for="name">Họ tên:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div>
                    <label for="age">Tuổi:</label>
                    <input type="number" id="age" name="age" required>
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div>
                    <button type="submit">Thêm sinh viên</button>
                </div>
            </form>
        </div>
        <div>
            <h2>Danh sách sinh viên</h2>
            <table>
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Họ tên</td>
                        <td>Tuổi</td>
                        <td>Email</td>
                        <td>Hành động</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= h($student['ID']) ?></td>
                        <td><?= h($student['Name']) ?></td>
                        <td><?= h($student['Age']) ?></td>
                        <td><?= h($student['Email']) ?></td>
                        <td style="display: flex; gap: 10px;">
                            <form action="" method="post">
                                <input type="hidden" name="id" value="<?= h($student['ID']) ?>">
                                <input type="hidden" name="action" value="edit">
                                <button type="submit">Chỉnh sửa</button>
                            </form>
                            <form action="" method="post"
                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?')">
                                <input type="hidden" name="id" value="<?= h($student['ID']) ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div>
            <h2>Chỉnh sửa sinh viên</h2>
            <?php if ($editStudent): ?>
            <form action="" method="post">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?= h($editStudent['ID'] ?? '') ?>">
                <div>
                    <label for="name">Họ tên:</label>
                    <input type="text" id="name" name="name" value="<?= h($editStudent['Name'] ?? '') ?>" required>
                </div>
                <div>
                    <label for="age">Tuổi:</label>
                    <input type="number" id="age" name="age" value="<?= h($editStudent['Age'] ?? '') ?>" required>
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= h($editStudent['Email'] ?? '') ?>" required>
                </div>
                <div>
                    <button type="submit" <?= $editStudent ? '' : 'disabled' ?>>Cập nhật sinh viên</button>
                </div>
            </form>
            <?php else: ?>
            <p>Chọn một sinh viên ở danh sách để chỉnh sửa.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
