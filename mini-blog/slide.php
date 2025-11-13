<?php
// slide.php — Tài liệu thuyết trình ngắn gọn, đẹp mắt (1 file)
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Mini Blog — Slide thuyết trình</title>
    <style>
    :root {
        --bg: #0f1020;
        --card: #131437;
        --txt: #f8fafc;
        --muted: #cbd5e1;
        --brand1: #f472b6;
        --brand2: #a78bfa;
        --shadow: 0 18px 60px rgba(167, 139, 250, .25);
        --radius: 20px;
    }

    * {
        box-sizing: border-box
    }

    body {
        margin: 0;
        font-family: ui-sans-serif, system-ui, Segoe UI, Roboto, Arial;
        background:
            radial-gradient(1000px 500px at 0% -10%, rgba(244, 114, 182, .2), transparent 60%),
            radial-gradient(900px 400px at 120% 0%, rgba(167, 139, 250, .25), transparent 50%),
            linear-gradient(180deg, #0b0c1e, #0f1020 40%);
        color: var(--txt);
    }

    .wrap {
        max-width: 1000px;
        margin: 0 auto;
        padding: 24px
    }

    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(255, 255, 255, .03);
        backdrop-filter: blur(10px);
        padding: 16px;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, .05)
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 12px
    }

    .mark {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        color: white;
        font-weight: 800;
        background: linear-gradient(135deg, var(--brand1), var(--brand2));
        box-shadow: var(--shadow)
    }

    .grid {
        display: grid;
        gap: 16px
    }

    @media(min-width:900px) {
        .grid-3 {
            grid-template-columns: repeat(3, 1fr)
        }
    }

    .card {
        background: linear-gradient(180deg, rgba(255, 255, 255, .06), rgba(255, 255, 255, .02));
        border: 1px solid rgba(255, 255, 255, .08);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 18px
    }

    .title {
        margin: 0 0 6px 0
    }

    .muted {
        color: var(--muted)
    }

    code,
    pre {
        font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        background: rgba(255, 255, 255, .05);
        padding: 8px 10px;
        border-radius: 12px;
        display: block;
        overflow: auto
    }

    a {
        color: #93c5fd
    }

    .step {
        counter-increment: s;
        padding-left: 8px
    }

    .step::before {
        content: counter(s) ".";
        margin-right: 8px;
        color: #f9a8d4;
        font-weight: 700
    }

    footer {
        opacity: .8;
        text-align: center;
        padding: 28px 8px
    }
    </style>
</head>

<body>
    <div class="wrap">
        <header>
            <div class="logo">
                <div class="mark">MB</div>
                <div>
                    <h1 class="title">Mini Blog (PHP + MySQL)</h1>
                </div>
            </div>
            <a href="index.php">← Quay lại ứng dụng</a>
        </header>

        <section class="grid grid-3" style="margin-top:16px">
            <div class="card">
                <h3 class="title">Mục tiêu</h3>
                <ul class="muted">
                    <li>CRUD + Tìm kiếm (an toàn PDO)</li>
                    <li>UI pastel dịu, phù hợp nữ</li>
                    <li>Tự tạo bảng & seed mẫu</li>
                </ul>
            </div>
            <div class="card">
                <h3 class="title">Công nghệ</h3>
                <ul class="muted">
                    <li>PHP 7.4+ (thuần)</li>
                    <li>MySQL/MariaDB (DB: <em>k9tin</em>)</li>
                    <li>PDO + Prepared statements</li>
                </ul>
            </div>
            <div class="card">
                <h3 class="title">Bảo mật</h3>
                <ul class="muted">
                    <li>CSRF token cho POST</li>
                    <li>Escape XSS với <code>h()</code></li>
                    <li>Không dùng query string ghép chuỗi</li>
                </ul>
            </div>
        </section>

        <section class="card" style="margin-top:16px">
            <h3 class="title">Kiến trúc & Luồng xử lý</h3>
            <ol>
                <li class="step">Người dùng gửi yêu cầu từ <code>index.php</code> (tạo/sửa/xóa/tìm kiếm).</li>
                <li class="step"><code>index.php</code> gọi hàm trong <code>functions.php</code> (CRUD).</li>
                <li class="step"><code>functions.php</code> kết nối DB (PDO), đảm bảo bảng <code>mini_posts</code>, seed
                    nếu trống.</li>
                <li class="step">Kết quả trả về được render (mobile-first, accessible).</li>
            </ol>
        </section>

        <section class="card" style="margin-top:16px">
            <h3 class="title">Schema bảng</h3>
            <pre>mini_posts(
  id INT PK AUTO_INCREMENT,
  title VARCHAR(150) NOT NULL,
  tag   VARCHAR(50)  NOT NULL DEFAULT '',
  content TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci;</pre>
        </section>

        <section class="grid grid-3" style="margin-top:16px">
            <div class="card">
                <h3 class="title">Cách chạy</h3>
                <ol class="muted">
                    <li>Khai báo DB <strong>k9tin</strong> tồn tại (XAMPP/MAMP/LAMP).</li>
                    <li>Đặt 3 file vào 1 thư mục (htdocs/mini-blog).</li>
                    <li>Mở <code>http://localhost/mini-blog/index.php</code>.</li>
                </ol>
            </div>
            <div class="card">
                <h3 class="title">Demo thao tác</h3>
                <ul class="muted">
                    <li>Thêm bài: tiêu đề + nội dung + tag.</li>
                    <li>Sửa/Xóa trực tiếp mỗi bài.</li>
                    <li>Tìm kiếm theo title/content/tag.</li>
                </ul>
            </div>
            <div class="card">
                <h3 class="title">Mở rộng gợi ý</h3>
                <ul class="muted">
                    <li>Phân trang, upload ảnh (table media)</li>
                    <li>Đăng nhập (password_hash)</li>
                    <li>Like/Comment, bookmarks</li>
                </ul>
            </div>
        </section>

        <section class="card" style="margin-top:16px">
            <h3 class="title">Điểm nhấn UI</h3>
            <ul class="muted">
                <li>Gradient hồng-tím, bo góc lớn</li>
                <li>Thẻ (tag) đáng yêu, emoji cảm xúc</li>
            </ul>
        </section>

        <footer>Mini Blog • 💗</footer>
    </div>
</body>

</html>
