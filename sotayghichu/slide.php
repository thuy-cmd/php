<?php
// slide.php — Thuyết trình dự án "Sổ tay Ghi Chú" (PHP + MySQL + Bootstrap)
// Đặt file này cùng thư mục với index.php. Không cần sửa gì thêm.
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Slides — Sổ tay Ghi Chú (PHP + MySQL)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    :root {
        --pad: clamp(16px, 3vw, 32px);
    }

    html,
    body {
        height: 100%;
        scroll-behavior: smooth;
    }

    .slide {
        min-height: 100svh;
        display: flex;
        align-items: center;
        position: relative;
        padding-block: max(56px, 8vh);
        /* chừa chỗ cho navbar */
    }

    .slide .container {
        max-width: 980px;
    }

    .hero-grad {
        background: radial-gradient(80% 120% at 0% 0%, #e8f1ff 0, transparent 60%),
            radial-gradient(80% 120% at 100% 0%, #f5ecff 0, transparent 60%);
    }

    .card {
        border-radius: 1.25rem;
    }

    .kbd {
        border: 1px solid #ccc;
        border-bottom-width: 2px;
        padding: .15rem .4rem;
        border-radius: .4rem;
        font-size: .9rem;
    }

    .actions {
        position: fixed;
        right: 1rem;
        bottom: 1rem;
        z-index: 1040;
        display: flex;
        flex-direction: column;
        gap: .5rem;
    }

    .actions .btn {
        border-radius: 1rem;
        min-height: 44px
    }

    pre.code {
        background: #0f172a;
        color: #e2e8f0;
        border-radius: 1rem;
        padding: 1rem 1.25rem;
        overflow: auto;
        font-size: .9rem;
    }

    .section-title {
        font-weight: 800;
    }

    .lead {
        font-size: 1.1rem;
    }

    /* In ra PDF: bỏ min-height để gộp nội dung, chèn ngắt trang mượt */
    @media print {
        .slide {
            min-height: auto;
            padding-block: 1rem;
        }

        .no-print {
            display: none !important;
        }

        a[href]:after {
            content: "";
        }

        .page-break {
            break-after: page;
        }

        body {
            background: #fff;
        }
    }
    </style>
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg bg-white border-bottom fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">📝 Sổ tay Ghi Chú — Slides</a>
            <div class="ms-auto d-flex gap-2">
                <a class="btn btn-outline-secondary" href="index.php" role="button">Xem ứng dụng</a>
                <button class="btn btn-primary" onclick="window.print()" type="button">In ra PDF</button>
            </div>
        </div>
    </nav>

    <!-- Slide 1: Bìa -->
    <section id="s1" class="slide hero-grad">
        <div class="container">
            <div class="text-center">
                <h1 class="display-5 fw-bold mb-3">Sổ tay Ghi Chú (Mini Project)</h1>
                <p class="lead text-secondary mb-4">PHP + PDO + MySQL • CRUD + Tìm kiếm • Bootstrap 5 • Mobile-first</p>
                <div class="d-inline-flex gap-2 flex-wrap">
                    <span class="badge text-bg-primary">Bảo vệ CSRF</span>
                    <span class="badge text-bg-success">XSS Escape</span>
                    <span class="badge text-bg-info">Prepared Statements</span>
                    <span class="badge text-bg-dark">Responsive</span>
                </div>
                <div class="mt-4 small text-secondary">
                    Dùng <span class="kbd">←</span>/<span class="kbd">→</span> hoặc <span class="kbd">PgUp</span>/<span
                        class="kbd">PgDn</span> để chuyển slide
                </div>
            </div>
        </div>
    </section>

    <!-- Slide 2: Mục tiêu -->
    <section id="s2" class="slide bg-white">
        <div class="container">
            <h2 class="section-title mb-3">1) Mục tiêu & Bối cảnh</h2>
            <div class="row g-3">
                <div class="col-12 col-md-7">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="fw-bold">Mục tiêu</h5>
                            <ul class="mb-3">
                                <li>Tạo ứng dụng ghi chú gọn nhẹ: thêm/sửa/xóa/tìm kiếm.</li>
                                <li>Áp dụng chuẩn <strong>PDO + Prepared statements</strong>, <strong>CSRF</strong>,
                                    <strong>XSS escape</strong>.</li>
                                <li>Giao diện đơn giản, phù hợp thuyết trình, chạy được ngay trên localhost.</li>
                            </ul>
                            <h5 class="fw-bold">Đối tượng</h5>
                            <ul class="mb-0">
                                <li>Sinh viên/nhóm học PHP, cần mẫu CRUD sạch, dễ nâng cấp.</li>
                                <li>Demo nộp môn, hoặc khởi đầu cho app ghi chú cá nhân.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-5">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="fw-bold">Kết quả chính (Demo)</h5>
                            <ol class="mb-0">
                                <li>Thêm ghi chú qua Modal.</li>
                                <li>Sửa/Xóa từng ghi chú.</li>
                                <li>Tìm kiếm theo tiêu đề/nội dung/nhãn.</li>
                                <li>Lọc theo nhãn (datalist + select).</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Slide 3: Kiến trúc -->
    <section id="s3" class="slide">
        <div class="container">
            <h2 class="section-title mb-3">2) Kiến trúc & Tổ chức mã nguồn</h2>
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="fw-bold">Cấu trúc file</h5>
                            <ul class="mb-0">
                                <li><strong>index.php</strong> — Giao diện + nhận request CRUD + tìm kiếm.</li>
                                <li><strong>functions.php</strong> — Kết nối PDO, hàm CRUD, flash message.</li>
                                <li><strong>schema.sql</strong> — Tạo DB/bảng <code>notes</code> + dữ liệu mẫu.</li>
                                <li><strong>slide.php</strong> — File thuyết trình (chính là trang này).</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="fw-bold">Luồng xử lý</h5>
                            <ol class="mb-0">
                                <li>Người dùng thao tác (Thêm/Sửa/Xóa/Tìm) → form/URL.</li>
                                <li><code>index.php</code> kiểm tra CSRF → gọi hàm trong <code>functions.php</code>.
                                </li>
                                <li>PDO chạy SQL an toàn (prepared) → trả về kết quả.</li>
                                <li>Xử lý flash message → render danh sách ghi chú (Bootstrap cards).</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Slide 4: Bảo mật & Chuẩn mã -->
    <section id="s4" class="slide bg-white">
        <div class="container">
            <h2 class="section-title mb-3">3) Bảo mật & Chuẩn mã</h2>
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="fw-bold">Bảo mật quan trọng</h5>
                            <ul class="mb-0">
                                <li><strong>CSRF token</strong> cho mọi POST: chống giả mạo yêu cầu.</li>
                                <li><strong>XSS escape</strong> mọi output bằng <code>htmlspecialchars</code>.</li>
                                <li><strong>Prepared statements</strong> cho tất cả truy vấn SQL.</li>
                                <li>Giới hạn quyền DB user theo nguyên tắc <em>least privilege</em>.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="fw-bold">Chất lượng & UX</h5>
                            <ul class="mb-0">
                                <li>Mobile-first, nút tối thiểu 44px, card có đổ bóng nhẹ.</li>
                                <li>Modal thêm/sửa, datalist gợi ý nhãn, tìm kiếm tức thì.</li>
                                <li>Thông báo thân thiện (flash), định dạng ngày giờ rõ ràng.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Slide 5: CSDL -->
    <section id="s5" class="slide">
        <div class="container">
            <h2 class="section-title mb-3">4) Cơ sở dữ liệu</h2>
            <p class="text-secondary">DB mẫu dùng <strong>k9tin</strong>, bảng <code>notes</code>. Có thể đổi tên DB
                trong <code>functions.php</code>.</p>
            <pre class="code"><code>CREATE DATABASE IF NOT EXISTS k9tin
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE k9tin;

CREATE TABLE IF NOT EXISTS notes (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  title      VARCHAR(200) NOT NULL,
  content    TEXT         NOT NULL,
  label      VARCHAR(50)  NULL,
  created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO notes (title, content, label) VALUES
('Ý tưởng UI', 'Dùng tông lam nhạt, card bo góc, padding thoáng, icon nhỏ.', 'design'),
('Checklist học PHP', "- PDO\n- Prepared statements\n- CRUD\n- CSRF token\n- XSS escape", 'study'),
('Việc hôm nay', "1) Viết báo cáo\n2) Gọi điện cô giáo\n3) Ôn Bootstrap 5", 'daily');</code></pre>
            <div class="small text-secondary">Gợi ý: tạo user MySQL riêng chỉ có quyền SELECT/INSERT/UPDATE/DELETE cho
                bảng <code>notes</code>.</div>
        </div>
    </section>

    <!-- Slide 6: Hướng dẫn chạy -->
    <section id="s6" class="slide bg-white">
        <div class="container">
            <h2 class="section-title mb-3">5) Hướng dẫn chạy nhanh (Local)</h2>
            <ol class="lead">
                <li>Mở MySQL/phpMyAdmin → chạy file <strong>schema.sql</strong>.</li>
                <li>Mở <strong>functions.php</strong> → chỉnh <code>$DB_HOST / $DB_NAME / $DB_USER / $DB_PASS</code>.
                </li>
                <li>Đặt 4 file (<em>index.php, functions.php, schema.sql, slide.php</em>) vào cùng thư mục web root.
                </li>
                <li>Truy cập: <code>http://localhost/.../index.php</code> (app) hoặc <code>slide.php</code> (thuyết
                    trình).</li>
            </ol>
            <div class="alert alert-info mt-3 mb-0">
                Mẹo: Bấm <strong>In ra PDF</strong> trên thanh trên cùng để xuất slide thành PDF nộp lớp.
            </div>
        </div>
    </section>

    <!-- Slide 7: Demo flow -->
    <section id="s7" class="slide">
        <div class="container">
            <h2 class="section-title mb-3">6) Demo Flow (CRUD + Tìm kiếm)</h2>
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="fw-bold">Thêm / Sửa / Xóa</h5>
                            <ul class="mb-0">
                                <li><strong>Thêm</strong>: nút “+ Thêm ghi chú” → Modal → Lưu (CSRF).</li>
                                <li><strong>Sửa</strong>: nút “Sửa” trên mỗi card → Modal đổ sẵn dữ liệu.</li>
                                <li><strong>Xóa</strong>: nút “Xóa” → xác nhận → flash “Đã xóa ghi chú”.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="fw-bold">Tìm kiếm & Lọc</h5>
                            <ul class="mb-0">
                                <li>Ô tìm: khớp <em>tiêu đề / nội dung / nhãn</em> (LIKE %q%).</li>
                                <li>Lọc nhãn: <em>select</em> và <em>datalist</em> gợi ý.</li>
                                <li>Sắp xếp theo <code>updated_at</code> → ghi chú mới/sửa gần nhất lên trên.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <p class="small text-secondary mt-3 mb-0">Xem chi tiết trong <code>get_notes()</code>,
                <code>create_note()</code>, <code>update_note()</code>, <code>delete_note()</code> (file
                <code>functions.php</code>).</p>
        </div>
    </section>

    <!-- Slide 8: Mở rộng -->
    <section id="s8" class="slide bg-white">
        <div class="container">
            <h2 class="section-title mb-3">7) Hướng mở rộng</h2>
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="fw-bold">Tính năng</h5>
                            <ul class="mb-0">
                                <li>Phân trang, gắn sao (priority), nhắc hẹn (deadline + badge).</li>
                                <li>Đính kèm ảnh/tệp, kéo-thả sắp xếp.</li>
                                <li>Xuất CSV/PDF, API REST nhỏ cho mobile.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="fw-bold">Kỹ thuật</h5>
                            <ul class="mb-0">
                                <li>Đăng nhập (bcrypt, session), phân quyền theo người dùng.</li>
                                <li>Chống spam (rate-limit), audit log, Content Security Policy.</li>
                                <li>Triển khai VPS/Nginx + HTTPS, sao lưu định kỳ (mysqldump).</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <p class="small text-secondary mt-3">Có thể nâng cấp UI sang Tailwind/Alpine, nhưng hiện tại Bootstrap 5 đã
                đủ nhanh gọn cho demo.</p>
        </div>
    </section>

    <!-- Nút điều hướng nhanh -->
    <div class="actions no-print">
        <button class="btn btn-outline-secondary" id="prevBtn" type="button" aria-label="Trang trước">← Trước</button>
        <button class="btn btn-primary" id="nextBtn" type="button" aria-label="Trang sau">Sau →</button>
    </div>

    <footer class="text-center text-secondary py-4 small">
        PHP + PDO + MySQL • Bootstrap 5 • Responsive • Bảo vệ CSRF/XSS — © <?= date('Y') ?>
    </footer>

    <script>
    // Điều hướng slide: nút & phím mũi tên
    const slides = Array.from(document.querySelectorAll('.slide'));

    function currentIndex() {
        // Tìm slide gần vị trí hiện tại nhất
        const y = window.scrollY + window.innerHeight / 2;
        let idx = 0,
            best = Infinity;
        slides.forEach((s, i) => {
            const r = s.getBoundingClientRect();
            const sy = r.top + window.scrollY;
            const d = Math.abs(sy - y);
            if (d < best) {
                best = d;
                idx = i;
            }
        });
        return idx;
    }

    function goto(i) {
        if (i < 0) i = 0;
        if (i >= slides.length) i = slides.length - 1;
        slides[i].scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
    document.getElementById('prevBtn').onclick = () => goto(currentIndex() - 1);
    document.getElementById('nextBtn').onclick = () => goto(currentIndex() + 1);
    window.addEventListener('keydown', (e) => {
        const prevKeys = ['ArrowLeft', 'ArrowUp', 'PageUp'];
        const nextKeys = ['ArrowRight', 'ArrowDown', 'PageDown', ' '];
        if (prevKeys.includes(e.key)) {
            e.preventDefault();
            goto(currentIndex() - 1);
        }
        if (nextKeys.includes(e.key)) {
            e.preventDefault();
            goto(currentIndex() + 1);
        }
    });

    // Sao chép code (nếu cần mở rộng): có thể thêm nút copy cho <pre.code>
    </script>
</body>

</html>
