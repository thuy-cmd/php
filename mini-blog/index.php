<?php
session_start();
// require_once __DIR__ . '/functions.php';
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;

    $host = 'localhost';
    $dbname = 'k9tin';
    $user = 'root';
    $pass = '';
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}

function ensureSchema(): void {
    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS mini_posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  tag   VARCHAR(50)  NOT NULL DEFAULT '',
  content TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
SQL;
    db()->exec($sql);
}

function seedSampleDataIfEmpty(): void {
    $count = (int)db()->query("SELECT COUNT(*) FROM mini_posts")->fetchColumn();
    if ($count > 0) return;

    $samples = [
        ['Nhật ký hồng pastel', 'life', "Ngày đầu thử mini blog ^^\nHôm nay trời đẹp, mình uống trà hoa hồng và học PHP."],
        ['Góc học tập mộng mơ', 'study', "Sắp xếp lại bàn học: sổ tay, bút màu, đèn vàng ấm.\nThử kỹ thuật pomodoro 25-5 nè!"],
        ['Một chút cảm xúc', 'love', "Gửi tớ của tương lai: hãy luôn dịu dàng với chính mình 💗"],
    ];
    $stmt = db()->prepare("INSERT INTO mini_posts (title, tag, content) VALUES (?,?,?)");
    foreach ($samples as $s) $stmt->execute($s);
}

function createPost(string $title, string $content, string $tag=''): int {
    $stmt = db()->prepare("INSERT INTO mini_posts (title, tag, content) VALUES (:t,:g,:c)");
    $stmt->execute([':t'=>$title, ':g'=>$tag, ':c'=>$content]);
    return (int)db()->lastInsertId();
}

function updatePost(int $id, string $title, string $content, string $tag=''): void {
    $stmt = db()->prepare("UPDATE mini_posts SET title=:t, tag=:g, content=:c WHERE id=:id");
    $stmt->execute([':t'=>$title, ':g'=>$tag, ':c'=>$content, ':id'=>$id]);
}

function deletePost(int $id): void {
    $stmt = db()->prepare("DELETE FROM mini_posts WHERE id=:id");
    $stmt->execute([':id'=>$id]);
}

function getPostById(int $id): ?array {
    $stmt = db()->prepare("SELECT * FROM mini_posts WHERE id=:id");
    $stmt->execute([':id'=>$id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function getPosts(string $q=''): array {
    if ($q === '') {
        $stmt = db()->query("SELECT * FROM mini_posts ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    $like = '%' . str_replace(['\\','%','_'], ['\\\\','\\%','\\_'], $q) . '%';
    $sql = "SELECT * FROM mini_posts
            WHERE title LIKE :kw1 OR content LIKE :kw2 OR tag LIKE :kw3
            ORDER BY id DESC";
    $stmt = db()->prepare($sql);
    $stmt->execute([':kw1'=>$like, ':kw2'=>$like, ':kw3'=>$like]);
    return $stmt->fetchAll();
}


if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];

ensureSchema();
seedSampleDataIfEmpty();

$action = $_GET['action'] ?? '';
$msg = $_GET['msg'] ?? '';
$error = '';

try {
    // Xử lý POST (Create/Update/Delete)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
            throw new Exception('Yêu cầu không hợp lệ (CSRF).');
        }

        $todo = $_POST['todo'] ?? '';

        if ($todo === 'create') {
            $title = trim($_POST['title'] ?? '');
            $tag = trim($_POST['tag'] ?? '');
            $content = trim($_POST['content'] ?? '');
            if ($title === '' || $content === '') {
                throw new Exception('Vui lòng nhập tiêu đề và nội dung.');
            }
            createPost($title, $content, $tag);
            header('Location: index.php?msg=' . urlencode('Đã thêm bài viết.'));
            exit;
        }

        if ($todo === 'update') {
            $id = (int)($_POST['id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            $tag = trim($_POST['tag'] ?? '');
            $content = trim($_POST['content'] ?? '');
            if ($id <= 0) throw new Exception('Thiếu ID hợp lệ.');
            if ($title === '' || $content === '') {
                throw new Exception('Vui lòng nhập tiêu đề và nội dung.');
            }
            updatePost($id, $title, $content, $tag);
            header('Location: index.php?msg=' . urlencode('Đã cập nhật bài viết.'));
            exit;
        }

        if ($todo === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) throw new Exception('Thiếu ID hợp lệ.');
            deletePost($id);
            header('Location: index.php?msg=' . urlencode('Đã xóa bài viết.'));
            exit;
        }
    }
} catch (Throwable $e) {
    $error = $e->getMessage();
}

$q = trim($_GET['q'] ?? '');
$posts = getPosts($q);
$edit = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $edit = getPostById((int)$_GET['id']);
}

function active($cond) { return $cond ? 'aria-current="page"' : ''; }

?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Mini Blog • K9Tin</title>
    <style>
    :root {
        --bg: #fff1f7;
        --card: #ffffff;
        --txt: #1f2937;
        --muted: #6b7280;
        --brand1: #f9a8d4;
        --brand2: #a78bfa;
        --accent: #f472b6;
        --ok: #10b981;
        --warn: #f59e0b;
        --err: #ef4444;
        --shadow: 0 6px 30px rgba(167, 139, 250, .25);
        --radius: 18px;
    }

    * {
        box-sizing: border-box
    }

    body {
        margin: 0;
        font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial;
        background:
            radial-gradient(1200px 600px at 20% -10%, rgba(249, 168, 212, .35), transparent 60%),
            radial-gradient(900px 400px at 110% 10%, rgba(167, 139, 250, .30), transparent 50%),
            linear-gradient(180deg, var(--bg), #ffffff 30%);
        color: var(--txt);
    }

    .container {
        max-width: 980px;
        margin: 0 auto;
        padding: 16px
    }

    header {
        display: flex;
        gap: 12px;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        background: var(--card);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        position: sticky;
        top: 12px;
        z-index: 20;
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 10px
    }

    .logo {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--brand1), var(--brand2));
        display: grid;
        place-items: center;
        color: white;
        font-weight: 700;
        box-shadow: var(--shadow);
    }

    .brand h1 {
        font-size: 18px;
        margin: 0;
        line-height: 1.1
    }

    .brand small {
        color: var(--muted)
    }

    nav a {
        text-decoration: none;
        color: #4b5563;
        font-weight: 600;
        padding: 10px 14px;
        border-radius: 12px;
    }

    nav a:hover {
        background: #faf5ff
    }

    .badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 999px;
        background: #fdf2f8;
        color: #be185d;
        font-weight: 600;
        font-size: 12px
    }

    .grid {
        display: grid;
        gap: 16px
    }

    @media (min-width: 900px) {
        .grid-2 {
            grid-template-columns: 1fr 1fr
        }
    }

    .card {
        background: var(--card);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 16px
    }

    .title {
        font-size: 20px;
        margin: 0 0 4px 0
    }

    .muted {
        color: var(--muted);
        font-size: 13px
    }

    form input[type="text"],
    textarea {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        outline: none;
        font-size: 15px;
        background: #fff;
        box-shadow: inset 0 1px 0 #f3f4f6;
    }

    textarea {
        min-height: 120px;
        resize: vertical
    }

    label {
        display: block;
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 6px
    }

    .row {
        display: grid;
        gap: 12px
    }

    @media(min-width:680px) {
        .row-2 {
            grid-template-columns: 1fr 1fr
        }
    }

    .btn {
        appearance: none;
        border: 0;
        padding: 12px 16px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--brand1), var(--brand2));
        color: white;
    }

    .btn-outline {
        background: #fff;
        border: 1px solid #e5e7eb
    }

    .btn-danger {
        background: var(--err);
        color: #fff
    }

    .toolbar {
        display: flex;
        gap: 8px;
        flex-wrap: wrap
    }

    .list {
        display: grid;
        gap: 12px
    }

    .post {
        padding: 14px;
        border: 1px solid #f3e8ff;
        background: linear-gradient(180deg, #ffffff, #fff7fb);
        border-radius: 14px
    }

    .post h3 {
        margin: 0 0 6px 0;
        font-size: 18px
    }

    .tag {
        display: inline-block;
        background: #f5f3ff;
        color: #6d28d9;
        padding: 3px 10px;
        border-radius: 999px;
        font-weight: 600;
        font-size: 12px
    }

    .flash {
        padding: 10px 14px;
        border-radius: 12px;
        margin: 12px 0;
        font-weight: 600
    }

    .ok {
        background: #ecfdf5;
        color: #065f46
    }

    .err {
        background: #fef2f2;
        color: #7f1d1d
    }

    footer {
        color: #9ca3af;
        font-size: 12px;
        text-align: center;
        padding: 28px 8px
    }

    .search {
        display: flex;
        gap: 8px;
        align-items: center
    }

    .search input {
        flex: 1
    }

    .empty {
        text-align: center;
        padding: 24px 12px;
        color: #9ca3af
    }

    .kbd {
        font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-bottom-width: 2px;
        padding: 2px 6px;
        border-radius: 6px
    }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <div class="brand">
                <div class="logo">MB</div>
                <div>
                    <h1>Mini Blog</h1>
                    <small class="muted">CRUD + Tìm kiếm • DB: <span class="badge">k9tin</span></small>
                </div>
            </div>
            <nav>
                <a href="index.php" <?= active($action === '') ?>>Trang chủ</a>
                <a href="slide.php">Slide thuyết trình</a>
            </nav>
        </header>

        <?php if ($msg): ?>
        <div class="flash ok">✅ <?= h($msg) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
        <div class="flash err">⚠️ <?= h($error) ?></div>
        <?php endif; ?>

        <div class="grid grid-2" style="margin-top:16px">
            <section class="card">
                <h2 class="title">📝 Thêm bài viết</h2>
                <p class="muted" style="margin-bottom:12px">Nhập tiêu đề & nội dung — Chia sẻ cảm xúc 💖
                </p>
                <form method="post" class="row">
                    <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
                    <input type="hidden" name="todo" value="create">
                    <div>
                        <label for="title">Tiêu đề</label>
                        <input id="title" name="title" type="text" maxlength="150"
                            placeholder="Ví dụ: Nhật ký nhỏ xinh của mình..." required>
                    </div>
                    <div class="row row-2">
                        <div>
                            <label for="tag">Chủ đề (tag)</label>
                            <input id="tag" name="tag" type="text" maxlength="50" placeholder="life, study, love...">
                        </div>
                    </div>
                    <div>
                        <label for="content">Nội dung</label>
                        <textarea id="content" name="content" placeholder="Viết điều bạn muốn chia sẻ..."
                            required></textarea>
                    </div>
                    <div class="toolbar">
                        <button class="btn btn-primary" type="submit">➕ Thêm bài</button>
                        <button class="btn btn-outline" type="reset">↺ Xóa nhập</button>
                    </div>
                </form>
            </section>

            <section class="card">
                <h2 class="title">🔍 Tìm kiếm</h2>
                <p class="muted" style="margin-bottom:12px">Tìm theo tiêu đề, nội dung hoặc thẻ (tag).</p>
                <form class="search" method="get">
                    <input type="text" name="q" value="<?= h($q) ?>" placeholder="Nhập từ khóa...">
                    <button class="btn btn-outline" type="submit">Tìm</button>
                </form>
            </section>
        </div>

        <?php if ($edit): ?>
        <section class="card" style="margin-top:16px">
            <h2 class="title">✏️ Sửa bài #<?= (int)$edit['id'] ?></h2>
            <form method="post" class="row">
                <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
                <input type="hidden" name="todo" value="update">
                <input type="hidden" name="id" value="<?= (int)$edit['id'] ?>">
                <div>
                    <label for="etitle">Tiêu đề</label>
                    <input id="etitle" name="title" type="text" value="<?= h($edit['title']) ?>" required>
                </div>
                <div class="row row-2">
                    <div>
                        <label for="etag">Chủ đề (tag)</label>
                        <input id="etag" name="tag" type="text" value="<?= h($edit['tag']) ?>">
                    </div>
                </div>
                <div>
                    <label for="econtent">Nội dung</label>
                    <textarea id="econtent" name="content" required><?= h($edit['content']) ?></textarea>
                </div>
                <div class="toolbar">
                    <button class="btn btn-primary" type="submit">💾 Lưu thay đổi</button>
                    <a class="btn btn-outline" href="index.php">Hủy</a>
                </div>
            </form>
        </section>
        <?php endif; ?>

        <section class="card" style="margin-top:16px">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:8px">
                <h2 class="title">🧾 Danh sách bài viết (<?= count($posts) ?>)</h2>
                <?php if ($q): ?><span class="tag">Từ khóa: <?= h($q) ?></span><?php endif; ?>
            </div>
            <?php if (!$posts): ?>
            <div class="empty">Chưa có bài viết nào. Hãy chia sẻ điều bạn nghĩ nhé 💗</div>
            <?php else: ?>
            <div class="list">
                <?php foreach ($posts as $p): ?>
                <article class="post">
                    <h3><?= h($p['title']) ?></h3>
                    <p class="muted" style="margin:6px 0 10px 0">
                        #<?= (int)$p['id'] ?> • <?= h(date('d/m/Y H:i', strtotime($p['created_at']))) ?>
                        <?php if ($p['tag']): ?> • <span class="tag">#<?= h($p['tag']) ?></span><?php endif; ?>
                    </p>
                    <div style="white-space:pre-wrap; line-height:1.6"><?= nl2br(h($p['content'])) ?></div>
                    <div class="toolbar" style="margin-top:10px">
                        <a class="btn btn-outline" href="index.php?action=edit&id=<?= (int)$p['id'] ?>">✏️ Sửa</a>
                        <form method="post" onsubmit="return confirm('Xóa bài này?');">
                            <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
                            <input type="hidden" name="todo" value="delete">
                            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                            <button class="btn btn-danger" type="submit">🗑️ Xóa</button>
                        </form>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </section>

        <footer>
            Made with 💜
        </footer>
    </div>

    <script>
    document.addEventListener('keydown', (e) => {
        if (e.key === '/' && !/input|textarea|select/i.test(document.activeElement.tagName)) {
            const box = document.querySelector('input[name="q"]');
            if (box) {
                e.preventDefault();
                box.focus();
            }
        }
    });
    </script>
</body>

</html>
