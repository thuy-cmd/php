<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài học: Hoàn Thiện Demo CRUD PHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Chosen Palette: Calm Harmony (Nền: Neutral-50, Tiêu đề: Blue-800, Text: Gray-800, Nhấn: Blue-700) -->
    <!-- Application Structure Plan: Cấu trúc trang này là một bài "tutorial" (hướng dẫn) chia thành 4 bước chính. Nó sử dụng file "khung" (starter code) do người dùng cung cấp và hướng dẫn sinh viên cách thêm 3 khối logic PHP (Kết nối, Xử lý POST, Truy vấn SELECT) vào file đó. -->
    <!-- Visualization & Content Choices: Sử dụng các khối <pre> để cung cấp code PHP cần điền. Các sơ đồ trực quan về luồng dữ liệu (DB Connection, HTTP POST) được thêm vào để làm rõ các khái niệm trừu tượng, giúp sinh viên hiểu rõ hơn về những gì họ đang code. -->
    <!-- CONFIRMATION: NO SVG graphics used. NO Mermaid JS used. -->
    <style>
    body {
        font-family: 'Inter', sans-serif;
        scroll-behavior: smooth;
    }

    .step-container {
        background-color: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
        margin-bottom: 2rem;
        border-left: 5px solid #1d4ed8;
        /* Blue-700 accent */
    }

    pre {
        white-space: pre-wrap;
        word-wrap: break-word;
        background-color: #f3f4f6;
        /* gray-100 */
        padding: 1rem;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
        /* gray-200 */
        position: relative;
    }

    .step-header {
        font-size: 1.875rem;
        /* text-3xl */
        font-weight: 700;
        /* font-bold */
        color: #1e3a8a;
        /* blue-800 */
        border-bottom: 2px solid #e0e7ff;
        /* blue-100 */
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }

    .copy-btn {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background-color: #3b82f6;
        /* blue-500 */
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .copy-btn:hover {
        background-color: #2563eb;
        /* blue-600 */
    }

    .code-placeholder {
        background-color: #fffbeb;
        /* yellow-50 */
        color: #b45309;
        /* amber-700 */
        border: 1px dashed #fcd34d;
        /* amber-300 */
        padding: 1rem;
        border-radius: 0.5rem;
        font-family: monospace;
        font-weight: 600;
    }

    .explanation {
        margin-top: 1.5rem;
        background-color: #f9fafb;
        /* gray-50 */
        border: 1px solid #e5e7eb;
        /* gray-200 */
        padding: 1rem;
        border-radius: 0.5rem;
    }

    .explanation h4 {
        font-size: 1.25rem;
        /* text-xl */
        font-weight: 600;
        /* font-semibold */
        color: #111827;
        /* gray-900 */
        margin-bottom: 0.75rem;
    }

    .explanation ul {
        list-style-type: disc;
        list-style-position: inside;
        padding-left: 0.5rem;
    }

    .explanation code {
        background-color: #e0e7ff;
        /* blue-100 */
        color: #1e3a8a;
        /* blue-800 */
        font-weight: 600;
        padding: 0.125rem 0.375rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
    }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased">

    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4">
            <h1 class="text-3xl font-bold text-blue-800">Bài học: Tìm hiểu cách kết nối PHP với Database</h1>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

        <!-- ============================================= -->
        <!-- BƯỚC 1: LẤY CODE "KHUNG" (STARTER CODE)        -->
        <!-- ============================================= -->
        <div class="step-container">
            <h2 class="step-header">Bước 1: Lấy Code "Khung"</h2>
            <p class="text-gray-600 mb-4">Đây là file `index.php` chúng ta sẽ dùng.</p>
            <pre><button class="copy-btn" onclick="copyCode(this, 'code-starter')">Copy</button><code id="code-starter">&lt;?php
    function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

    // (CHỖ TRỐNG 1: KẾT NỐI DATABASE)

    $notice = null;
    $selectedType = $_POST['type'] ?? 'DienThoai'; // Giữ lại state của form
    $action = $_POST['action'] ?? null;
    $products = []; // Tạm thời rỗng

    // (CHỖ TRỐNG 2: XỬ LÝ POST - THÊM/XÓA)

    // (CHỖ TRỐNG 3: LẤY DỮ LIỆU TỪ DB)

?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="vi" data-bs-theme="light"&gt;

&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1"&gt;
    &lt;title&gt;Quản lý sản phẩm&lt;/title&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
    &lt;style&gt;
    .card-sticky {
        position: sticky;
        top: 20px
    }
    &lt;/style&gt;
&lt;/head&gt;

&lt;body&gt;
    &lt;header&gt;
        &lt;nav class="navbar navbar-expand-lg bg-primary navbar-dark"&gt;
            &lt;div class="container"&gt;
                &lt;span class="navbar-brand fw-bold"&gt;Quản lý sản phẩm&lt;/span&gt;
            &lt;/div&gt;
        &lt;/nav&gt;
    &lt;/header&gt;

    &lt;main class="container py-4"&gt;
        &lt;?php if ($notice): ?&gt;
        &lt;div class="alert alert-success"&gt;&lt;?= h($notice) ?&gt;&lt;/div&gt;
        &lt;?php endif; ?&gt;

        &lt;div class="row g-3"&gt;
            &lt;!-- LEFT: Create form --&gt;
            &lt;div class="col-12 col-lg-5"&gt;
                &lt;div class="card card-sticky shadow-sm"&gt;
                    &lt;div class="card-header d-flex align-items-center justify-content-between"&gt;
                        &lt;span class="fw-semibold"&gt;Thêm sản phẩm mới&lt;/span&gt;
                    &lt;/div&gt;
                    &lt;div class="card-body"&gt;
                        &lt;form method="post" id="productForm"&gt;
                            &lt;input type="hidden" name="action" value="create"&gt;
                            &lt;div class="mb-3"&gt;
                                &lt;label for="type" class="form-label"&gt;Chọn loại sản phẩm&lt;/label&gt;
                                &lt;select name="type" id="type" class="form-select" onchange="showForm()"&gt;
                                    &lt;option value="DienThoai" &lt;?= $selectedType==='DienThoai'?'selected':'' ?&gt;&gt;Điện
                                        thoại&lt;/option&gt;
                                    &lt;option value="Sach" &lt;?= $selectedType==='Sach'?'selected':'' ?&gt;&gt;Sách&lt;/option&gt;
                                &lt;/select&gt;
                            &lt;/div&gt;

                            &lt;!-- Điện thoại --&gt;
                            &lt;div id="formDienThoai" class="&lt;?= $selectedType==='DienThoai'?'':'d-none' ?&gt;"&gt;
                                &lt;div class="mb-3"&gt;
                                    &lt;label class="form-label"&gt;Tên sản phẩm&lt;/label&gt;
                                    &lt;input type="text" name="TenDienThoai" class="form-control"
                                        placeholder="Ví dụ: Galaxy A35" required&gt;
                                &lt;/div&gt;
                                &lt;div class="mb-3"&gt;
                                    &lt;label class="form-label"&gt;Đơn giá (đ)&lt;/label&gt;
                                    &lt;input type="number" name="DonGiaDienThoai" class="form-control" min="0" step="1000"
                                        placeholder="12.990.000" required&gt;
                                &lt;/div&gt;
                                &lt;div class="row g-3"&gt;
                                    &lt;div class="col-md-7"&gt;
                                        &lt;label class="form-label"&gt;Hãng sản xuất&lt;/label&gt;
                                        &lt;input type="text" name="HangSX" class="form-control"
                                            placeholder="Samsung / Apple / ..."&gt;
                                    &lt;/div&gt;
                                    &lt;div class="col-md-5"&gt;
                                        &lt;label class="form-label"&gt;Bảo hành (tháng)&lt;/label&gt;
                                        &lt;input type="number" name="BaoHanh" class="form-control" min="0" step="1"
                                            value="12"&gt;
                                    &lt;/div&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;

                            &lt;!-- Sách --&gt;
                            &lt;div id="formSach" class="&lt;?= $selectedType==='Sach'?'':'d-none' ?&gt;"&gt;
                                &lt;div class="mb-3"&gt;
                                    &lt;label class="form-label"&gt;Tên sách&lt;/label&gt;
                                    &lt;input type="text" name="TenSach" class="form-control"
                                        placeholder="Ví dụ: Lập trình PHP hiện đại" required&gt;
                                &lt;/div&gt;
                                &lt;div class="mb-3"&gt;
                                    &lt;label class="form-label"&gt;Đơn giá (đ)&lt;/label&gt;
                                    &lt;input type="number" name="DonGiaSach" class="form-control" min="0" step="1000"
                                        placeholder="95.000" required&gt;
                                &lt;/div&gt;
                                &lt;div class="row g-3"&gt;
                                    &lt;div class="col-md-7"&gt;
                                        &lt;label class="form-label"&gt;Tác giả&lt;/label&gt;
                                        &lt;input type="text" name="TacGia" class="form-control"
                                            placeholder="Nguyễn Văn A"&gt;
                                    &lt;/div&gt;
                                    &lt;div class="col-md-5"&gt;
                                        &lt;label class="form-label"&gt;Số trang&lt;/label&gt;
                                        &lt;input type="number" name="SoTrang" class="form-control" min="1" step="1"
                                            placeholder="320"&gt;
                                    &lt;/div&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;

                            &lt;div class="d-grid mt-4"&gt;
                                &lt;button type="submit" class="btn btn-primary btn-lg"&gt;Thêm sản phẩm&lt;/button&gt;
                            &lt;/div&gt;
                        &lt;/form&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            &lt;/div&gt;

            &lt;!-- RIGHT: List --&gt;
            &lt;div class="col-12 col-lg-7"&gt;
                &lt;div class="card shadow-sm"&gt;
                    &lt;div class="card-header"&gt;
                        &lt;span&gt;Danh sách sản phẩm đã thêm&lt;/span&gt;
                    &lt;/div&gt;
                    &lt;div class="card-body"&gt;
                        &lt;?php if (!$products): ?&gt;
                        &lt;p class="text-secondary fst-italic mb-0"&gt;Chưa có sản phẩm nào được thêm.&lt;/p&gt;
                        &lt;?php else: foreach($products as $p): ?&gt;
                        &lt;?php if ($p['type']==='DienThoai'): ?&gt;
                        &lt;div
                            class="border rounded p-3 mb-2 bg-body-tertiary d-flex gap-3 align-items-center justify-content-between"&gt;
                            &lt;div class="d-flex gap-3 align-items-start"&gt;
                                &lt;span class="fs-3"&gt;📱&lt;/span&gt;
                                &lt;div&gt;
                                    &lt;p class="mb-1"&gt;&lt;strong&gt;Điện thoại&lt;/strong&gt;: &lt;?= h($p['ten']) ?&gt;&lt;/p&gt;
                                    &lt;p class="text-secondary small mb-1"&gt;Hãng: &lt;?= h($p['hang_sx'] ?? '') ?&gt; • Bảo hành:
                                        &lt;?= (int)($p['bao_hanh_th'] ?? 0) ?&gt; tháng&lt;/p&gt;
                                    &lt;p class="mb-1"&gt;Đơn giá: &lt;span
                                            class="badge text-bg-primary"&gt;&lt;?= number_format((int)$p['gia_vnd'], 0, ',', '.') ?&gt;
                                            đ&lt;/span&gt;&lt;/p&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                            &lt;form method="post" onsubmit="return confirm('Xoá sản phẩm này?')"&gt;
                                &lt;input type="hidden" name="action" value="delete"&gt;
                                &lt;input type="hidden" name="id" value="&lt;?= (int)$p['id'] ?&gt;"&gt;
                                &lt;button class="btn btn-outline-danger"&gt;Xoá&lt;/button&gt;
                            &lt;/form&gt;
                        &lt;/div&gt;
                        &lt;?php else: ?&gt;
                        &lt;div
                            class="border rounded p-3 mb-2 bg-body-tertiary d-flex gap-3 align-items-center justify-content-between"&gt;
                            &lt;div class="d-flex gap-3 align-items-start"&gt;
                                &lt;span class="fs-3"&gt;📘&lt;/span&gt;
                                &lt;div&gt;
                                    &lt;p class="mb-1"&gt;&lt;strong&gt;Sách&lt;/strong&gt;: &lt;?= h($p['ten']) ?&gt;&lt;/p&gt;
                                    &lt;p class="text-secondary small mb-1"&gt;Tác giả: &lt;?= h($p['tac_gia'] ?? '') ?&gt; • Số
                                        trang: &lt;?= (int)($p['so_trang'] ?? 0) ?&gt; trang&lt;/p&gt;
                                    &lt;p class="mb-1"&gt;Đơn giá: &lt;span
                                            class="badge text-bg-primary"&gt;&lt;?= number_format((int)$p['gia_vnd'], 0, ',', '.') ?&gt;
                                            đ&lt;/span&gt;&lt;/p&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                            &lt;form method="post" onsubmit="return confirm('Xoá sản phẩm này?')"&gt;
                                &lt;input type="hidden" name="action" value="delete"&gt;
                                &lt;input type="hidden" name="id" value="&lt;?= (int)$p['id'] ?&gt;"&gt;
                                &lt;button class="btn btn-outline-danger"&gt;Xoá&lt;/button&gt;
                            &lt;/form&gt;
                        &lt;/div&gt;
                        &lt;?php endif; ?&gt;
                        &lt;?php endforeach; endif; ?&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/main&gt;

    &lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;
    &lt;script&gt;
    function setEnabled(containerId, enabled) {
        document.querySelectorAll(`#${containerId} input, #${containerId} select, #${containerId} textarea`)
            .forEach(el =&gt; el.disabled = !enabled);
    }

    function showForm() {
        const type = document.getElementById('type').value;
        const isPhone = (type === 'DienThoai');
        document.getElementById('formDienThoai').classList.toggle('d-none', !isPhone);
        document.getElementById('formSach').classList.toggle('d-none', isPhone);
        setEnabled('formDienThoai', isPhone);
        setEnabled('formSach', !isPhone);
    }
    window.addEventListener('DOMContentLoaded', showForm);
    &lt;/script&gt;
&lt;/body&gt;

&lt;/html&gt;
</code></pre>
        </div>

        <!-- ============================================= -->
        <!-- BƯỚC 2: CÀI ĐẶT MÔI TRƯỜNG & DATABASE          -->
        <!-- ============================================= -->
        <div class="step-container">
            <h2 class="step-header">Bước 2: Cài đặt Môi trường & Database</h2>
            <p class="text-gray-600 mb-4">Giống như trước, bạn cần bật server và chuẩn bị database.</p>
            <ol class="list-decimal list-inside space-y-2">
                <li>Khởi động <strong>WAMPP SERVER</strong>.</li>
                <li>Đặt file `index.php` (từ Bước 1) vào thư mục `C:\wamp64\www\demo`.</li>
                <li>
                    Vào `phpMyAdmin` (http://localhost/phpmyadmin), tạo database `k9tin` với collation
                    `utf8mb4_general_ci`.
                </li>
                <li>
                    Chạy mã SQL sau để tạo bảng `products` (Copy code bên dưới và dán vào tab `SQL` của `k9tin`).
                </li>
            </ol>
            <h3 class="text-xl font-semibold text-blue-700 mt-6 mb-3">Mã SQL để tạo bảng `products`</h3>
            <pre><button class="copy-btn" onclick="copyCode(this, 'code-sql')">Copy</button><code id="code-sql">CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('DienThoai','Sach') COLLATE utf8mb4_general_ci NOT NULL,
  `ten` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `gia_vnd` int(11) NOT NULL,
  `hang_sx` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bao_hanh_th` int(11) DEFAULT NULL,
  `tac_gia` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `so_trang` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
</code></pre>
        </div>

        <!-- ============================================= -->
        <!-- BƯỚC 3: HOÀN THIỆN CODE PHP (BÀI HỌC CHÍNH)    -->
        <!-- ============================================= -->
        <div class="step-container border-l-4 border-yellow-500">
            <h2 class="step-header text-yellow-700">Bước 3: Hoàn thiện Code PHP (Bài học chính)</h2>
            <p class="text-gray-600 mb-4">Bây giờ, hãy mở file `index.php` của bạn và điền vào 3 "chỗ trống".</p>

            <!-- CHỖ TRỐNG 1 -->
            <h3 class="text-2xl font-semibold text-blue-700 mt-6 mb-3">3.1. Kết nối Database (Slide 4)</h3>
            <p class="text-gray-600 mb-4">Tìm dòng `// (CHỖ TRỐNG 1: KẾT NỐI DATABASE)`.</p>
            <p class="text-gray-600 mb-4">Đây là nơi PHP giao tiếp với MySQL. Hãy dán đoạn code `try...catch` sau vào vị
                trí đó để tạo kết nối PDO an toàn.

            </p>
            <pre><button class="copy-btn" onclick="copyCode(this, 'code-pdo')">Copy</button><code id="code-pdo">try {
    // Tự thay đổi 'root' và mật khẩu rỗng "" cho phù hợp với XAMPP của bạn
    $conn = new PDO("mysql:host=localhost;dbname=k9tin;charset=utf8mb4", "root", "");
    $conn-&gt;setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn-&gt;setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    http_response_code(500);
    die("Kết nối CSDL thất bại: " . h($e-&gt;getMessage()) . ". Bạn đã tạo database 'k9tin' chưa (Xem Bước 2)?");
}</code></pre>

            <div class="explanation">
                <h4>Giải thích chi tiết</h4>
                <ul>
                    <li><code>try { ... } catch (PDOException $e) { ... }</code><br>Chúng ta bọc code kết nối trong
                        <code>try</code>. Nếu có lỗi xảy ra (ví dụ: sai mật khẩu, sai tên db), PHP sẽ "ném" ra một
                        <code>PDOException</code>. Khối <code>catch</code> sẽ "bắt" lấy lỗi đó và xử lý một cách an
                        toàn, thay vì làm sập trang.
                    </li>

                    <li><code>$conn = new PDO(...)</code><br>Đây là hành động "tạo" một kết nối. <code>PDO</code> là một
                        "cầu nối" (driver) an toàn và linh hoạt để PHP nói chuyện với CSDL.</li>

                    <li><code>"mysql:host=localhost;dbname=k9tin;charset=utf8mb4"</code><br>Đây là DSN (Data Source
                        Name):
                        <ul>
                            <li><code>host=localhost</code>: Server CSDL đang chạy ở máy của bạn.</li>
                            <li><code>dbname=k9tin</code>: Tên database chúng ta muốn kết nối (đã tạo ở Bước 2).</li>
                            <li><code>charset=utf8mb4</code>: **Cực kỳ quan trọng!** Báo với CSDL rằng chúng ta dùng
                                Tiếng Việt (và cả emoji), tránh lỗi hiển thị ký tự.</li>
                        </ul>
                    </li>

                    <li><code>$conn-&gt;setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)</code><br>Báo với PDO:
                        "Nếu có lỗi, hãy ném ra Exception" (để khối <code>catch</code> ở trên bắt được). Nếu không có
                        dòng này, PDO sẽ im lặng, rất khó gỡ lỗi.</li>

                    <li><code>$conn-&gt;setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC)</code><br>Báo với
                        PDO: "Khi lấy dữ liệu, hãy trả về dạng mảng kết hợp (tên cột => giá trị)". Ví dụ:
                        <code>['ten' => 'Sách PHP', 'gia_vnd' => 95000]</code>. Tiện lợi hơn nhiều.
                    </li>

                    <li><code>die("Kết nối CSDL thất bại: " . h($e-&gt;getMessage()))</code><br>Nếu <code>catch</code>
                        bắt được lỗi, dừng chương trình ngay lập tức (<code>die</code>) và in ra thông báo lỗi an toàn
                        (đã được hàm <code>h()</code> xử lý chống XSS).</li>
                </ul>
            </div>

            <!-- CHỖ TRỐNG 2 -->
            <h3 class="text-2xl font-semibold text-blue-700 mt-8 mb-3">3.2. Xử lý Form POST</h3>
            <p class="text-gray-600 mb-4">Tìm dòng `// (CHỖ TRỐNG 2: XỬ LÝ POST - THÊM/XÓA)`.</p>
            <p class="text-gray-600 mb-4">Đây là "bộ não" của ứng dụng. Khi người dùng nhấn nút "Thêm" hoặc "Xoá", form
                sẽ được gửi (POST) và code này sẽ chạy.

                Hãy dán code xử lý `create` và `delete` sau vào vị trí đó. Chú ý cách chúng ta dùng **prepared
                statements** (`?`) để chống SQL Injection.</p>
            <pre><button class="copy-btn" onclick="copyCode(this, 'code-post')">Copy</button><code id="code-post">if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action) {
    if ($action === 'create') {
        if ($selectedType === 'DienThoai') {
            $ten = $_POST['TenDienThoai'] ?? '';
            $donGia = (int)($_POST['DonGiaDienThoai'] ?? 0);
            $hang = $_POST['HangSX'] ?? '';
            $baoHanh = (int)($_POST['BaoHanh'] ?? 0);
            // Chống SQL Injection bằng Prepared Statement
            $stmt = $conn-&gt;prepare("INSERT INTO products (type, ten, gia_vnd, hang_sx, bao_hanh_th) VALUES (?, ?, ?, ?, ?)");
            $stmt-&gt;execute(['DienThoai', $ten, $donGia, $hang, $baoHanh]);
            $notice = "Đã thêm điện thoại.";
        }
        elseif ($selectedType === 'Sach') {
            $ten = $_POST['TenSach'] ?? '';
            $donGia = (int)($_POST['DonGiaSach'] ?? 0);
            $tacGia = $_POST['TacGia'] ?? '';
            $soTrang = (int)($_POST['SoTrang'] ?? 0);
            // Chống SQL Injection bằng Prepared Statement
            $stmt = $conn-&gt;prepare("INSERT INTO products (type, ten, gia_vnd, tac_gia, so_trang) VALUES (?, ?, ?, ?, ?)");
            $stmt-&gt;execute(['Sach', $ten, $donGia, $tacGia, $soTrang]);
            $notice = "Đã thêm sách.";
        }
    }
    elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            // Chống SQL Injection bằng Prepared Statement
            $stmt = $conn-&gt;prepare("DELETE FROM products WHERE id = ?");
            $stmt-&gt;execute([$id]);
            $notice = "Đã xoá sản phẩm.";
        }
    }
}</code></pre>

            <div class="explanation">
                <h4>Giải thích chi tiết</h4>
                <ul>
                    <li><code>if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action)</code><br>Kiểm tra 2 điều: 1) Yêu
                        cầu có phải là <code>POST</code> (do nhấn nút <code>submit</code>) không? 2) Biến
                        <code>$action</code> (từ <code>&lt;input type="hidden"&gt;</code>) có tồn tại không? Đảm bảo
                        code này chỉ chạy khi người dùng thực sự gửi form.
                    </li>

                    <li><code>if ($action === 'create')</code><br>Phân luồng logic. Nếu là hành động "tạo" (đến từ form
                        thêm).</li>

                    <li><code>$ten = $_POST['TenDienThoai'] ?? ''</code><br>Lấy dữ liệu từ mảng <code>$_POST</code>. Dấu
                        <code>?? ''</code> (Null Coalescing) là cách viết ngắn gọn của: "Lấy
                        <code>$_POST['TenDienThoai']</code>, nhưng nếu nó không tồn tại, hãy dùng chuỗi rỗng
                        <code>''</code>". Điều này giúp tránh lỗi "Undefined index".
                    </li>

                    <li><code>$donGia = (int)($_POST['DonGiaDienThoai'] ?? 0)</code><br>Tương tự, nhưng ép kiểu sang
                        <code>(int)</code> (số nguyên) để đảm bảo an toàn. Nếu người dùng nhập "abc", nó sẽ trở thành
                        <code>0</code>.
                    </li>

                    <li><code>$stmt = $conn-&gt;prepare("... VALUES (?, ?, ?, ?, ?)")</code><br>Đây là **Bước 1** của
                        Prepared Statement (chống SQL Injection). Chúng ta "chuẩn bị" một câu lệnh SQL với các "chỗ
                        trống" (<code>?</code>).</li>

                    <li><code>$stmt-&gt;execute(['DienThoai', $ten, $donGia, $hang, $baoHanh])</code><br>Đây là **Bước
                        2**. Chúng ta thực thi (<code>execute</code>) câu lệnh đã chuẩn bị, và truyền vào một mảng chứa
                        dữ liệu. PDO sẽ tự động xử lý an toàn, gán <code>$ten</code> vào dấu <code>?</code> thứ hai,
                        <code>$donGia</code> vào dấu <code>?</code> thứ ba... Kẻ tấn công không thể chèn mã độc vào đây.
                    </li>

                    <li><code>elseif ($action === 'delete')</code><br>Phân luồng logic. Nếu là hành động "xoá".</li>

                    <li><code>$id = (int)($_POST['id'] ?? 0)</code><br>Luôn ép kiểu <code>id</code> sang số nguyên để
                        đảm bảo an toàn tuyệt đối.</li>

                    <li><code>$stmt = $conn-&gt;prepare("DELETE FROM products WHERE id = ?");</code><br>Chuẩn bị câu
                        lệnh xoá với 1 chỗ trống cho <code>id</code>.</li>

                    <li><code>$stmt-&gt;execute([$id]);</code><br>Thực thi an toàn, truyền vào <code>id</code> đã được
                        ép kiểu.</li>
                </ul>
            </div>

            <!-- CHỖ TRỐNG 3 -->
            <h3 class="text-2xl font-semibold text-blue-700 mt-8 mb-3">3.3. Lấy Dữ liệu (SELECT)</h3>
            <p class="text-gray-600 mb-4">Tìm dòng `// (CHỖ TRỐNG 3: LẤY DỮ LIỆU TỪ DB)`.</p>
            <p class="text-gray-600 mb-4">Sau khi Thêm/Xóa, chúng ta phải lấy lại dữ liệu mới nhất từ CSDL để hiển thị
                ra danh sách.</p>
            <pre><button class="copy-btn" onclick="copyCode(this, 'code-select')">Copy</button><code id="code-select">// Luôn SELECT lại dữ liệu mới nhất
$stmt = $conn-&gt;query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt-&gt;fetchAll();</code></pre>

            <div class="explanation">
                <h4>Giải thích chi tiết</h4>
                <ul>
                    <li><code>$stmt = $conn-&gt;query(...)</code><br>Vì câu lệnh <code>SELECT</code> này không chứa bất
                        kỳ dữ liệu nào từ người dùng (không có dấu <code>?</code>), chúng ta có thể dùng
                        <code>query()</code> để chạy trực tiếp cho đơn giản.
                    </li>

                    <li><code>ORDER BY id DESC</code><br>Sắp xếp kết quả. <code>DESC</code> (Descending) nghĩa là giảm
                        dần, đưa sản phẩm có <code>id</code> cao nhất (mới nhất) lên đầu danh sách.</li>

                    <li><code>$products = $stmt-&gt;fetchAll()</code><br>Sau khi chạy <code>query</code>, kết quả
                        (<code>$stmt</code>) cần được "lấy ra". <code>fetchAll()</code> sẽ lấy tất cả các hàng dữ liệu
                        và đưa vào biến <code>$products</code> dưới dạng một mảng lớn. Biến <code>$products</code> này
                        sẽ được dùng ở phần HTML (bên dưới) để <code>foreach</code> và hiển thị.</li>
                </ul>
            </div>
        </div>

        <!-- ============================================= -->
        <!-- BƯỚC 4: CHẠY DEMO!                            -->
        <!-- ============================================= -->
        <div class="step-container">
            <h2 class="step-header">Bước 4: Chạy Demo!</h2>
            <p class="text-gray-600 mb-4">Lưu file `index.php` của bạn lại sau khi đã điền đủ 3 phần.</p>
            <p class="text-gray-600 mb-4">Mở trình duyệt và truy cập: `http://localhost/demo/index.php`</p>
            <p class="text-gray-600 mb-4">Thử thêm một "Điện thoại" và một "Sách". Mọi thứ sẽ hoạt động!</p>
        </div>

        <!-- ============================================= -->
        <!-- 5 ĐIỀU CẦN NHỚ (TÓM TẮT)                      -->
        <!-- ============================================= -->
        <div class="step-container border-l-4 border-green-600">
            <h2 class="slide-header text-green-700">Tóm tắt: 5 Kiến thức cốt lõi</h2>
            <p class="text-gray-600 mb-4">Đây là 5 điều quan trọng nhất đã học từ bài học này.</p>
            <ol class="list-decimal list-inside space-y-3 font-semibold">
                <li>Học được cách kết nối với Database bằng PDO.</li>
                <li>Thực hiện các thao tác với Database.</li>
                <li>PDO + Prepared Statements (Bước 3.2): Cách duy nhất để chống SQL Injection. Tách biệt câu lệnh (`?`)
                    và dữ liệu (`execute([...])`).</li>
                <li>Escape Output (`h()`): Luôn dùng `htmlspecialchars` (hàm `h()`) khi `echo` dữ liệu ra HTML (có sẵn
                    trong file khung) để chống XSS.</li>
                <li>Ẩn Form phải `disabled` (JS, Slide 10): Chỉ ẩn bằng CSS là chưa đủ, phải dùng JavaScript (có sẵn
                    trong file khung) để vô hiệu hóa input, tránh lỗi `required`.</li>
                <li>`utf8mb4` End-to-End: Dùng từ kết nối PDO (Bước 3.1) đến collation của Database (Bước 2) để đảm bảo
                    Tiếng Việt hiển thị đúng.</li>
                <li>Luồng POST -> SELECT -> Render: Sau khi xử lý POST (Bước 3.2), luôn phải `SELECT` lại dữ liệu mới
                    nhất (Bước 3.3) trước khi render HTML.</li>
            </ol>
        </div>

    </main>

    <footer class="text-center text-gray-500 py-6 text-sm">
        Chúc bạn thành công!
    </footer>

    <script>
    function copyCode(button, elementId) {
        const codeEl = document.getElementById(elementId);
        let text = codeEl.innerText;

        // Xử lý đặc biệt cho code PHP demo để loại bỏ các ký tự HTML escape
        if (elementId === 'code-starter') {
            text = text.replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&amp;/g, '&');
        }

        navigator.clipboard.writeText(text).then(() => {
            button.innerText = 'Đã copy!';
            setTimeout(() => {
                button.innerText = 'Copy';
            }, 2000);
        }).catch(err => {
            console.error('Không thể copy: ', err);
        });
    }
    </script>

</body>

</html>