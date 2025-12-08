<?php
session_start();
require '1db.php';
$conn = connectDB();

// Hàm escape nếu chưa có
if (!function_exists('h')) {
    function h($s) {
        return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
    }
}

$username = $_SESSION['username'] ?? '';
$role     = $_SESSION['role'] ?? 'user';

// Chưa login -> đá về 1login
if ($username === '') {
    header("Location: 1login.php");
    exit;
}

// Lấy id user hiện tại
$stmt = $conn->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
$stmt->execute([':username' => $username]);
$userRow = $stmt->fetch();

if (!$userRow) {
    session_unset();
    session_destroy();
    header("Location: 1login.php");
    exit;
}

$id        = (int)$userRow['id'];
$action    = '';
$error     = '';
$editPost  = null;      // lưu bài đang sửa (nếu có)
$search    = '';        // từ khóa tìm kiếm
$posts     = [];        // danh sách bài viết

// Xử lý các action POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = trim($_POST['action'] ?? '');

    // 1) Thêm bài mới
    if ($action === 'add') {
        $postname = trim($_POST['postname'] ?? '');
        $postdesc = trim($_POST['postdesc'] ?? '');

        if ($postname === '' || $postdesc === '') {
            $error = "Vui lòng nhập đầy đủ post name và post desc.";
        } else {
            $stmt = $conn->prepare("
                INSERT INTO posts (user_id, postname, postdesc)
                VALUES (:user_id, :postname, :postdesc)
            ");
            $stmt->execute([
                ':user_id'   => $id,
                ':postname'  => $postname,
                ':postdesc'  => $postdesc,
            ]);

            header("Location: 1index.php");
            exit;
        }
    }

    // 2) Bấm nút Edit -> load dữ liệu lên form
    elseif ($action === 'start_edit') {
        $edit_id = (int)($_POST['id'] ?? 0);

        if ($edit_id > 0) {
            $stmt = $conn->prepare("
                SELECT id, postname, postdesc
                FROM posts
                WHERE id = :id AND user_id = :user_id
            ");
            $stmt->execute([
                ':id'      => $edit_id,
                ':user_id' => $id,
            ]);
            $editPost = $stmt->fetch();
        }
    }

    // 3) Submit form cập nhật
    elseif ($action === 'update') {
        $edit_id  = (int)($_POST['id'] ?? 0);
        $postname = trim($_POST['postname'] ?? '');
        $postdesc = trim($_POST['postdesc'] ?? '');

        if ($edit_id <= 0) {
            $error = "Bài viết không hợp lệ.";
        } elseif ($postname === '' || $postdesc === '') {
            $error = "Vui lòng nhập đầy đủ post name và post desc.";
        } else {
            $stmt = $conn->prepare("
                UPDATE posts
                SET postname = :postname,
                    postdesc = :postdesc
                WHERE id = :id AND user_id = :user_id
            ");
            $stmt->execute([
                ':postname' => $postname,
                ':postdesc' => $postdesc,
                ':id'       => $edit_id,
                ':user_id'  => $id,
            ]);

            header("Location: 1index.php");
            exit;
        }
    }

    // 4) Delete
    elseif ($action === 'delete') {
        $delete_id = (int)($_POST['id'] ?? 0);

        if ($delete_id > 0) {
            $stmt = $conn->prepare("
                DELETE FROM posts
                WHERE id = :id AND user_id = :user_id
            ");
            $stmt->execute([
                ':id'      => $delete_id,
                ':user_id' => $id,
            ]);
        }

        header("Location: 1index.php");
        exit;
    }

    // 5) Search
    elseif ($action === 'search') {
        $search = trim($_POST['search'] ?? '');
        // không query ở đây, chỉ lưu lại từ khóa,
        // lát nữa SELECT chung 1 lần ở dưới cho gọn
    }
}

/*
 * Lấy danh sách bài của user hiện tại,
 * nếu có $search thì filter theo LIKE.
 */
$sql = "
    SELECT p.id, p.user_id, u.username, p.postname, p.postdesc
    FROM users AS u
    JOIN posts AS p ON u.id = p.user_id
    WHERE u.id = :id
";

$params = [':id' => $id];

if ($search !== '') {
    $sql .= " AND (p.postname LIKE :kw OR p.postdesc LIKE :kw)";
    $params[':kw'] = '%' . $search . '%';
}

$sql .= " ORDER BY p.id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ON THI BUOI 1</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <div class="container">
        <div>
            <h1>Hello, <?= h($username) ?>, welcome to our website!</h1>
            <p>Your role of the website is: <strong><?= h($role) ?></strong></p>
            <p>Your user id of posts is: <strong><?= h($id) ?></strong></p>

            <!-- FORM SEARCH -->
            <form action="" method="post" class="form" style="margin-bottom: 10px;">
                <input type="hidden" name="action" value="search">
                <label for="search">Search: </label>
                <input type="text" name="search" id="search" class="form__input"
                    placeholder="Search by post name or post desc" value="<?= h($search) ?>">
                <button type="submit" class="form__submit">Search</button>
            </form>

            <?php if ($error !== ''): ?>
            <p style="color:red;"><?= h($error) ?></p>
            <?php endif; ?>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>User name</th>
                        <th>Post name</th>
                        <th>Post desc</th>
                        <th>Control</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($posts)): ?>
                    <tr>
                        <td colspan="6">Chưa có bài viết nào.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?= h($post['id']) ?></td>
                        <td><?= h($post['user_id']) ?></td>
                        <td><?= h($post['username']) ?></td>
                        <td><?= h($post['postname']) ?></td>
                        <td><?= h($post['postdesc']) ?></td>
                        <td class="form__control">
                            <!-- Nút Edit: chỉ load dữ liệu lên form -->
                            <form action="" method="post" style="display:inline;">
                                <input type="hidden" name="action" value="start_edit">
                                <input type="hidden" name="id" value="<?= h($post['id']) ?>">
                                <button type="submit">Edit</button>
                            </form>

                            <!-- Nút Delete -->
                            <form action="" method="post" style="display:inline;"
                                onsubmit="return confirm('Delete this post?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= h($post['id']) ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div>
            <h2><?= $editPost ? 'Edit post' : 'Add new post' ?></h2>
            <form action="" method="post" class="form">
                <?php if ($editPost): ?>
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?= h($editPost['id']) ?>">
                <?php else: ?>
                <input type="hidden" name="action" value="add">
                <?php endif; ?>

                <div class="form__group">
                    <label for="postname">Post name</label>
                    <input type="text" class="form__input" name="postname" placeholder="Post name"
                        value="<?= $editPost ? h($editPost['postname']) : '' ?>">
                </div>
                <div class="form__group">
                    <label for="postdesc">Post desc</label>
                    <input type="text" class="form__input" name="postdesc" placeholder="Post desc"
                        value="<?= $editPost ? h($editPost['postdesc']) : '' ?>">
                </div>
                <button type="submit" class="form__submit">
                    <?= $editPost ? 'Update post' : 'Add post' ?>
                </button>
            </form>
        </div>
    </div>

    <hr>
    <a class="logout" href="1logout.php">Logout</a>
</body>

</html>
