<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Template Ôn Thi PHP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    :root {
        --bg: #f3f4f6;
        --card-bg: #ffffff;
        --primary: #6366f1;
        --primary-soft: #e0e7ff;
        --border: #e5e7eb;
        --text-main: #111827;
        --text-muted: #6b7280;
        --danger: #dc2626;
        --radius-lg: 12px;
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        background: radial-gradient(circle at top, #e5e7eb, #f3f4f6);
        color: var(--text-main);
    }

    .app {
        min-height: 100vh;
        padding: 16px;
        max-width: 1200px;
        margin: 0 auto;
    }

    header {
        margin-bottom: 16px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    header h1 {
        font-size: 1.4rem;
        margin: 0;
    }

    header p {
        margin: 0;
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .badge {
        padding: 4px 10px;
        border-radius: 999px;
        background: var(--primary-soft);
        color: var(--primary);
        font-size: 0.8rem;
        font-weight: 600;
    }

    main {
        display: grid;
        gap: 16px;
    }

    @media (min-width: 880px) {
        main {
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.4fr);
            align-items: flex-start;
        }
    }

    .card {
        background: var(--card-bg);
        border-radius: var(--radius-lg);
        padding: 16px 18px 18px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
    }

    .card h2 {
        margin-top: 0;
        margin-bottom: 6px;
        font-size: 1.1rem;
    }

    .card p.subtitle {
        margin: 0 0 12px;
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .field {
        margin-bottom: 10px;
    }

    .field label {
        display: block;
        font-size: 0.88rem;
        margin-bottom: 4px;
    }

    .field input,
    .field select,
    .field textarea {
        width: 100%;
        padding: 8px 10px;
        border-radius: 8px;
        border: 1px solid var(--border);
        font-size: 0.95rem;
        outline: none;
        background: #f9fafb;
    }

    .field textarea {
        resize: vertical;
        min-height: 60px;
    }

    .field input:focus,
    .field select:focus,
    .field textarea:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 1px rgba(99, 102, 241, 0.25);
        background: #ffffff;
    }

    .field small.error {
        display: block;
        margin-top: 2px;
        font-size: 0.78rem;
        color: var(--danger);
        min-height: 14px;
    }

    .btn-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
    }

    button,
    .btn {
        border-radius: 999px;
        border: none;
        padding: 8px 14px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: transform 0.08s ease, box-shadow 0.08s ease, background 0.1s ease;
    }

    .btn-primary {
        background: var(--primary);
        color: #ffffff;
        box-shadow: 0 3px 10px rgba(79, 70, 229, 0.35);
    }

    .btn-primary:hover,
    .btn-primary:focus-visible {
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(79, 70, 229, 0.45);
    }

    .btn-ghost {
        background: transparent;
        color: var(--text-main);
        border: 1px solid var(--border);
    }

    .btn-ghost:hover,
    .btn-ghost:focus-visible {
        background: #f3f4f6;
    }

    .btn-danger {
        background: #fee2e2;
        color: #b91c1c;
    }

    .btn-danger:hover,
    .btn-danger:focus-visible {
        background: #fecaca;
    }

    button:active {
        transform: translateY(0);
        box-shadow: none;
    }

    .toolbar {
        margin-bottom: 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: space-between;
        align-items: center;
    }

    .toolbar .search-box {
        flex: 1;
        min-width: 180px;
        position: relative;
    }

    .toolbar .search-box input {
        width: 100%;
        padding-left: 30px;
    }

    .toolbar .search-box span {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: #f9fafb;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
        min-width: 560px;
    }

    thead {
        background: #e5e7eb;
    }

    th,
    td {
        padding: 8px 10px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }

    th {
        font-weight: 600;
        font-size: 0.82rem;
        color: #374151;
    }

    tbody tr:nth-child(even) {
        background: #f9fafb;
    }

    tbody tr.hidden-row {
        display: none;
    }

    .tag {
        display: inline-flex;
        align-items: center;
        padding: 2px 8px;
        border-radius: 999px;
        border: 1px solid var(--border);
        background: #fff;
        font-size: 0.75rem;
        color: var(--text-muted);
        gap: 4px;
    }

    .tag-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--primary);
    }

    .muted {
        font-size: 0.8rem;
        color: var(--text-muted);
    }
    </style>
</head>

<body>
    <div class="app">
        <header>
            <div>
                <h1>Template giao diện kiểm tra PHP</h1>
                <p>Login + Session • Thêm / Hiển thị / Xóa • Tìm kiếm • Validate JS</p>
            </div>
            <span class="badge">HTML / CSS / JS thuần</span>
        </header>

        <main>
            <!-- ========= 1. FORM ĐĂNG NHẬP ========= -->
            <section class="card" id="loginCard">
                <h2>Đăng nhập hệ thống</h2>
                <p class="subtitle">Form này trẫm chỉ cần đổi <strong>action=""</strong> và xử lý PHP là dùng được.</p>

                <!-- action="" => khi đổi sang .php thì để action="login.php" / "" tùy trẫm -->
                <form id="loginForm" action="" method="post" novalidate>
                    <div class="field">
                        <label for="login-username">Tên đăng nhập <span class="muted">(tối thiểu 3 ký tự)</span></label>
                        <input type="text" id="login-username" name="username" autocomplete="off"
                            placeholder="Nhập username...">
                        <small class="error"></small>
                    </div>

                    <div class="field">
                        <label for="login-password">Mật khẩu <span class="muted">(tối thiểu 6 ký tự)</span></label>
                        <input type="password" id="login-password" name="password" autocomplete="off"
                            placeholder="Nhập password...">
                        <small class="error"></small>
                    </div>

                    <div class="field">
                        <label for="login-role">Vai trò (tùy đề bài có yêu cầu hay không)</label>
                        <select id="login-role" name="role">
                            <option value="">-- Chọn role --</option>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                        <small class="error"></small>
                    </div>

                    <div class="btn-row">
                        <button type="submit" class="btn-primary">Đăng nhập</button>
                        <button type="reset" class="btn-ghost">Xóa dữ liệu</button>
                    </div>

                    <p class="muted" style="margin-top:8px;">
                        Gợi ý PHP: sau khi validate server thành công, dùng
                        <code>$_SESSION</code> + <code>header("Location: index.php")</code>.
                    </p>
                </form>
            </section>

            <!-- ========= 2. QUẢN LÝ DỮ LIỆU (ADD / LIST / SEARCH / DELETE) ========= -->
            <section class="card">
                <h2>Quản lý dữ liệu chung</h2>
                <p class="subtitle">
                    Dùng được cho mọi đề: sản phẩm, sinh viên, bài viết, khách hàng...
                    Chỉ cần đổi tên cột + name của input.
                </p>

                <!-- Thanh công cụ: tìm kiếm + info -->
                <div class="toolbar">
                    <!-- Form search: JS sẽ lọc ngay trên bảng, PHP có thể dùng GET ?q=... -->
                    <form class="search-box" method="get" action="">
                        <span>🔍</span>
                        <input type="text" id="searchInput" name="q" placeholder="Tìm kiếm theo tên / mô tả...">
                    </form>

                    <div class="tag">
                        <span class="tag-dot"></span>
                        <span>CSDL: thêm • hiển thị • xóa</span>
                    </div>
                </div>

                <!-- Form thêm / chỉnh sửa bản ghi (frontend) -->
                <!-- Khi làm bài PHP: thêm input hidden action="add" / "update" tùy trẫm -->
                <form id="itemForm" action="" method="post" class="card" style="padding:12px 14px; margin-bottom:14px;"
                    novalidate>
                    <h3 style="margin:0 0 6px; font-size:1rem;">Thêm / sửa bản ghi</h3>

                    <div class="field">
                        <label for="item-name">Tên bản ghi (ví dụ: tên sản phẩm / sinh viên / bài viết)</label>
                        <input type="text" id="item-name" name="item_name" placeholder="Nhập tên...">
                        <small class="error"></small>
                    </div>

                    <div class="field">
                        <label for="item-category">Loại / Nhóm (ví dụ: loại sản phẩm, lớp, danh mục...)</label>
                        <input type="text" id="item-category" name="item_category" placeholder="Nhập loại / nhóm...">
                        <small class="error"></small>
                    </div>

                    <div class="field">
                        <label for="item-note">Mô tả ngắn / Ghi chú</label>
                        <textarea id="item-note" name="item_note" placeholder="Nhập mô tả ngắn..."></textarea>
                        <small class="error"></small>
                    </div>

                    <!-- Ví dụ chỗ này trong PHP:
                     <input type="hidden" name="action" value="add">
                     Hoặc value="update" kèm id ẩn -->
                    <div class="btn-row">
                        <button type="submit" class="btn-primary">Lưu dữ liệu</button>
                        <button type="reset" class="btn-ghost">Xóa form</button>
                    </div>
                </form>

                <!-- Bảng hiển thị dữ liệu -->
                <div class="table-wrapper">
                    <table id="itemTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên</th>
                                <th>Loại / Nhóm</th>
                                <th>Mô tả</th>
                                <th style="width:140px;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--
                        Khi làm bằng PHP:
                        - Lặp qua mảng / kết quả SELECT để in từng <tr>.
                        - Mỗi dòng có form Delete + Edit riêng.
                    -->
                            <tr>
                                <td>1</td>
                                <td>Sản phẩm mẫu A</td>
                                <td>Danh mục 1</td>
                                <td>Ví dụ bản ghi hiển thị từ CSDL.</td>
                                <td>
                                    <div class="btn-row" style="gap:4px;">
                                        <!-- Form DELETE (PHP): -->
                                        <!--
                                <form action="" method="post" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button class="btn btn-danger" type="submit">Xóa</button>
                                </form>
                                -->
                                        <button type="button" class="btn-ghost" disabled>Sửa</button>
                                        <button type="button" class="btn-danger" disabled>Xóa</button>
                                    </div>
                                </td>
                            </tr>
                            <!-- Thêm vài dòng minh họa để thấy search hoạt động -->
                            <tr>
                                <td>2</td>
                                <td>Sinh viên Nguyễn Văn B</td>
                                <td>Lớp K9Tin</td>
                                <td>Điểm PHP cần cải thiện.</td>
                                <td>
                                    <div class="btn-row" style="gap:4px;">
                                        <button type="button" class="btn-ghost" disabled>Sửa</button>
                                        <button type="button" class="btn-danger" disabled>Xóa</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Bài viết giới thiệu trường</td>
                                <td>Blog</td>
                                <td>Demo cho phần bài viết.</td>
                                <td>
                                    <div class="btn-row" style="gap:4px;">
                                        <button type="button" class="btn-ghost" disabled>Sửa</button>
                                        <button type="button" class="btn-danger" disabled>Xóa</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p class="muted" style="margin-top:8px;">
                    Gợi ý PHP:
                    <br>- Kết nối PDO → <code>SELECT * FROM ten_bang</code> để đổ dữ liệu vào &lt;tbody&gt;.
                    <br>- Nút Xóa: form POST, có <code>action=delete</code> và <code>id</code> → chạy câu
                    <code>DELETE</code>.
                    <br>- Tìm kiếm: dùng <code>$_GET['q']</code> làm điều kiện <code>WHERE ... LIKE</code>.
                </p>
            </section>
        </main>
    </div>

    <script>
    // ========== VALIDATE LOGIN ==========
    (function() {
        const loginForm = document.getElementById('loginForm');
        if (!loginForm) return;

        const usernameInput = document.getElementById('login-username');
        const passwordInput = document.getElementById('login-password');
        const roleSelect = document.getElementById('login-role');

        function setError(input, message) {
            const field = input.closest('.field');
            const errorEl = field ? field.querySelector('.error') : null;
            if (errorEl) errorEl.textContent = message || '';
        }

        loginForm.addEventListener('submit', function(e) {
            let valid = true;

            const username = usernameInput.value.trim();
            const password = passwordInput.value.trim();
            const role = roleSelect.value.trim();

            if (username.length < 3) {
                setError(usernameInput, 'Username phải có ít nhất 3 ký tự.');
                valid = false;
            } else {
                setError(usernameInput, '');
            }

            if (password.length < 6) {
                setError(passwordInput, 'Password phải có ít nhất 6 ký tự.');
                valid = false;
            } else {
                setError(passwordInput, '');
            }

            // Role có thể tùy đề, nếu không bắt buộc thì comment phần này
            if (role === '') {
                setError(roleSelect, 'Vui lòng chọn role (hoặc bỏ validate nếu đề không yêu cầu).');
                valid = false;
            } else {
                setError(roleSelect, '');
            }

            if (!valid) {
                e.preventDefault(); // chỉ chặn khi sai; đúng thì để submit qua PHP
            }
        });
    })();

    // ========== VALIDATE FORM THÊM / SỬA DỮ LIỆU ==========
    (function() {
        const itemForm = document.getElementById('itemForm');
        if (!itemForm) return;

        const nameInput = document.getElementById('item-name');
        const catInput = document.getElementById('item-category');
        const noteInput = document.getElementById('item-note');

        function setError(input, message) {
            const field = input.closest('.field');
            const errorEl = field ? field.querySelector('.error') : null;
            if (errorEl) errorEl.textContent = message || '';
        }

        itemForm.addEventListener('submit', function(e) {
            let valid = true;

            const name = nameInput.value.trim();
            const cat = catInput.value.trim();
            const note = noteInput.value.trim();

            if (name === '') {
                setError(nameInput, 'Không được để trống.');
                valid = false;
            } else {
                setError(nameInput, '');
            }

            if (cat === '') {
                setError(catInput, 'Không được để trống.');
                valid = false;
            } else {
                setError(catInput, '');
            }

            if (note.length < 3) {
                setError(noteInput, 'Mô tả nên có ít nhất 3 ký tự.');
                valid = false;
            } else {
                setError(noteInput, '');
            }

            if (!valid) {
                e.preventDefault(); // sai thì không cho submit
            }
        });
    })();

    // ========== TÌM KIẾM (SEARCH) TRÊN BẢNG ==========
    (function() {
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('itemTable');
        if (!searchInput || !table) return;

        const tbody = table.querySelector('tbody');

        function normalize(str) {
            return (str || '').toString().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        }

        searchInput.addEventListener('input', function() {
            const keyword = normalize(this.value.trim());
            const rows = tbody.querySelectorAll('tr');

            rows.forEach(function(row) {
                const text = normalize(row.textContent);
                if (!keyword || text.includes(keyword)) {
                    row.classList.remove('hidden-row');
                } else {
                    row.classList.add('hidden-row');
                }
            });
        });
    })();
    </script>
</body>

</html>
