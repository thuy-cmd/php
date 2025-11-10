<?php
// ========= Helpers =========
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function log_error($msg){
    $dir = __DIR__ . '/logs';
    if (!is_dir($dir)) @mkdir($dir, 0777, true);
    @error_log(date('c') . ' ' . $msg . PHP_EOL, 3, $dir . '/error.log');
}
function json_out($data, int $status=200){
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// ========= DB connect =========
$dsn = "mysql:host=localhost;dbname=onthi;charset=utf8mb4";
$user = "root";
$pass = "";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    log_error("DB connect failed: " . $e->getMessage());
    // Nếu gọi ở chế độ API thì trả JSON, còn không thì dừng trang
    if (isset($_GET['country_code'])) json_out(['error'=>'DB connection error'], 500);
    die('Có lỗi kết nối CSDL.');
}

// ========= API mode =========
// /countries_cities.php?country_code=GB
if (isset($_GET['country_code'])) {
    $code = trim((string)$_GET['country_code']);
    // Nếu dùng mã 2 ký tự, bỏ comment dòng dưới:
    // if (!preg_match('/^[A-Za-z]{2}$/', $code)) json_out([]);

    try {
        $stmt = $pdo->prepare("SELECT city_id, city_name FROM cities WHERE country_code = ? ORDER BY city_name");
        $stmt->execute([$code]);
        $cities = $stmt->fetchAll();
        json_out($cities);
    } catch (Throwable $e) {
        log_error("Query cities failed: " . $e->getMessage());
        json_out(['error'=>'Query failed'], 500);
    }
}

// ========= Page mode =========
$countries = [];
try {
    $countries = $pdo->query("SELECT country_code, country_name FROM countries ORDER BY country_name")->fetchAll();
} catch (Throwable $e) {
    log_error("Query countries failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>Chọn quốc gia và thành phố</title>
    <style>
    :root {
        color-scheme: light dark;
    }

    body {
        font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        line-height: 1.5;
        margin: 0;
    }

    .page {
        max-width: 720px;
        margin: 32px auto;
        padding: 0 16px;
        display: grid;
        gap: 12px;
    }

    h3 {
        margin: 0 0 8px;
        font-weight: 700;
    }

    label {
        font-weight: 600;
        margin-top: 8px;
        display: block;
    }

    select {
        width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
    }

    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        border: 0;
    }
    </style>
</head>

<body>
    <div class="page">
        <h3>Vui lòng chọn quốc gia và thành phố</h3>

        <form name="form" action="#" method="post" novalidate>
            <label for="countries">Quốc gia</label>
            <select id="countries" name="countries" onchange="getCities(this.value)" aria-describedby="countryHelp">
                <option value="">— Chọn quốc gia —</option>
                <?php if ($countries): ?>
                <?php foreach ($countries as $c): ?>
                <option value="<?= h($c['country_code']) ?>"><?= h($c['country_name']) ?></option>
                <?php endforeach; ?>
                <?php else: ?>
                <option value="">(Không có quốc gia nào)</option>
                <?php endif; ?>
            </select>
            <small id="countryHelp" class="sr-only">Chọn quốc gia để tải danh sách thành phố.</small>

            <label for="cities">Thành phố</label>
            <select id="cities" name="cities" disabled>
                <option value="">— Chọn thành phố —</option>
            </select>
        </form>
    </div>

    <script>
    const API = "<?= h($_SERVER['PHP_SELF']) ?>";

    function safe(s) {
        return String(s).replace(/[&<>"']/g, m => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        } [m]));
    }

    async function getCities(countryCode) {
        const cityDropdown = document.getElementById('cities');
        cityDropdown.disabled = true;
        cityDropdown.innerHTML = '<option value="">— Chọn thành phố —</option>';
        if (!countryCode || countryCode.trim() === "") return;

        try {
            const res = await fetch(`${API}?country_code=${encodeURIComponent(countryCode)}`);
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const cities = await res.json();

            if (!Array.isArray(cities) || cities.length === 0) {
                cityDropdown.innerHTML = '<option value="">(Không có thành phố)</option>';
                return;
            }

            let out = '<option value="">— Chọn thành phố —</option>';
            for (const c of cities) {
                const name = c.city_name ?? c.name ?? '';
                out += `<option value="${safe(name)}">${safe(name)}</option>`;
            }
            cityDropdown.innerHTML = out;
            cityDropdown.disabled = false;
        } catch (err) {
            console.error(err);
            cityDropdown.innerHTML = '<option value="">(Tải danh sách thất bại)</option>';
        }
    }
    </script>
</body>

</html>
