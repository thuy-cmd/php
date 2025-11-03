<?php
declare(strict_types=1);

/* ====== CONFIG DB (điền theo máy trẫm) ====== */
$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_PORT = (int)(getenv('DB_PORT') ?: 3306);
$DB_NAME = getenv('DB_NAME') ?: 'hocphp';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: ''; // XAMPP thường rỗng

/* ====== Helper ====== */
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function vnd($n){ return number_format((float)$n, 0, ',', '.') . ' đ'; }

/* ====== PDO connect (bắt lỗi rõ ràng) ====== */
try {
    $dsn = "mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};charset=utf8mb4";
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    die("Kết nối CSDL thất bại: " . h($e->getMessage()));
}

/* ====== Nhận tham số tìm kiếm / sắp xếp / phân trang ====== */
$q     = trim((string)($_GET['q'] ?? ''));             // từ khoá
$sort  = $_GET['sort'] ?? 'created_at';                // created_at | price_vnd | name
$dir   = strtolower($_GET['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';
$page  = max(1, (int)($_GET['page'] ?? 1));
$limit = min(max((int)($_GET['limit'] ?? 8), 5), 50);
$offset = ($page - 1) * $limit;

/* Chỉ cho phép cột sắp xếp hợp lệ (tránh SQL injection vào ORDER BY) */
$allowSort = ['created_at','price_vnd','name'];
if (!in_array($sort, $allowSort, true)) $sort = 'created_at';

/* ====== Build WHERE + params (prepared statements) ====== */
$where = '';
$params = [];
if ($q !== '') {
    $where = "WHERE (name LIKE :kw OR brand LIKE :kw OR category LIKE :kw)";
    $params[':kw'] = "%{$q}%";
}

/* ====== Đếm tổng & lấy dữ liệu ====== */
$total = (function() use ($pdo, $where, $params){
    $sql = "SELECT COUNT(*) FROM products {$where}";
    $st = $pdo->prepare($sql);
    $st->execute($params);
    return (int)$st->fetchColumn();
})();

$sql = "SELECT id, name, price_vnd, brand, category, image_url, description, created_at
        FROM products
        {$where}
        ORDER BY {$sort} {$dir}
        LIMIT :limit OFFSET :offset";
$st = $pdo->prepare($sql);
foreach ($params as $k=>$v) $st->bindValue($k, $v, PDO::PARAM_STR);
$st->bindValue(':limit',  $limit,  PDO::PARAM_INT);
$st->bindValue(':offset', $offset, PDO::PARAM_INT);
$st->execute();
$rows = $st->fetchAll();

$lastPage = max(1, (int)ceil($total / $limit));
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Products (MySQL + Alternative Syntax)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .card-product img {
        width: 72px;
        height: 72px;
        object-fit: cover;
        border-radius: .5rem
    }

    @media (max-width: 576px) {

        .filters .form-select,
        .filters .form-control {
            margin-top: .5rem
        }
    }
    </style>
</head>

<body class="bg-light">
    <div class="container py-4">
        <h1 class="h4 mb-3">Kết nối MySQL + Alternative Syntax</h1>

        <!-- FORM lọc/tìm kiếm -->
        <form method="get" class="row g-2 align-items-center mb-3 filters">
            <div class="col-12 col-sm">
                <input name="q" value="<?= h($q) ?>" class="form-control" placeholder="Từ khoá (tên/brand/category)">
            </div>
            <div class="col-6 col-sm-3">
                <select name="sort" class="form-select">
                    <option value="created_at" <?= $sort==='created_at'?'selected':'' ?>>Mới nhất</option>
                    <option value="price_vnd" <?= $sort==='price_vnd'?'selected':''  ?>>Giá</option>
                    <option value="name" <?= $sort==='name'?'selected':''       ?>>Tên</option>
                </select>
            </div>
            <div class="col-3 col-sm-2">
                <select name="dir" class="form-select">
                    <option value="desc" <?= $dir==='DESC'?'selected':'' ?>>↓</option>
                    <option value="asc" <?= $dir==='ASC'?'selected':''  ?>>↑</option>
                </select>
            </div>
            <div class="col-3 col-sm-2">
                <select name="limit" class="form-select">
                    <?php foreach ([6,8,10,12,20,50] as $opt): ?>
                    <option value="<?= $opt ?>" <?= $limit===$opt?'selected':'' ?>><?= $opt ?>/trang</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-sm-auto">
                <button class="btn btn-primary w-100">Lọc</button>
            </div>
        </form>

        <!-- Nếu không có dữ liệu -->
        <?php if ($total === 0): ?>
        <p class="text-secondary fst-italic">Chưa có sản phẩm nào. (Hãy chạy script SQL để seed dữ liệu.)</p>

        <!-- Có dữ liệu: render danh sách -->
        <?php else: ?>
        <div class="vstack gap-2">
            <?php foreach ($rows as $p): ?>
            <div class="card card-product shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <?php if (!empty($p['image_url'])): ?>
                    <img src="<?= h($p['image_url']) ?>" alt="Ảnh">
                    <?php endif; ?>

                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <h2 class="h6 mb-1"><?= h($p['name']) ?></h2>
                                <div class="text-secondary small">
                                    <?php if (!empty($p['brand'])): ?>Hãng: <?= h($p['brand']) ?> · <?php endif; ?>
                                    <?php if (!empty($p['category'])): ?>Loại: <?= h($p['category']) ?><?php endif; ?>
                                </div>
                            </div>
                            <span class="badge text-bg-primary"><?= vnd($p['price_vnd']) ?></span>
                        </div>

                        <?php if (!empty($p['description'])): ?>
                        <p class="mt-2 mb-0 text-muted"
                            style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                            <?= h($p['description']) ?>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Phân trang -->
        <?php $cur = $page; $prev = max(1, $cur-1); $next = min($lastPage, $cur+1); ?>
        <nav class="mt-3">
            <ul class="pagination">
                <li class="page-item <?= $cur<=1?'disabled':'' ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page'=>$prev])) ?>">«
                        Trước</a>
                </li>
                <li class="page-item disabled"><span class="page-link">Trang <?= $cur ?> / <?= $lastPage ?> — Tổng
                        <?= $total ?></span></li>
                <li class="page-item <?= $cur>=$lastPage?'disabled':'' ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page'=>$next])) ?>">Sau »</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</body>

</html>
