<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thuyết trình: Ứng dụng Quản lý bán hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background: linear-gradient(to bottom right, #f8f9fa, #e9ecef);
        font-family: 'Segoe UI', sans-serif;
    }

    .slide {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 40px;
        margin-bottom: 40px;
    }

    h2 {
        color: #007bff;
        font-weight: 700;
    }

    h3 {
        color: #343a40;
        margin-top: 20px;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .emoji {
        font-size: 22px;
    }
    </style>
</head>

<body>

    <div class="container mt-4 mb-5">

        <div class="text-center mb-5">
            <h1 class="text-primary fw-bold">💻 ĐỀ TÀI: ỨNG DỤNG QUẢN LÝ BÁN HÀNG (CỬA HÀNG MINI)</h1>
            <p class="text-muted">Bài thuyết trình - PHP & MySQL</p>
        </div>

        <!-- Slide 1 -->
        <div class="slide">
            <h2>1️⃣. Lý do chọn đề tài</h2>
            <ul>
                <li>Hiện nay, hầu hết các cửa hàng đều cần quản lý hàng hóa và hóa đơn hiệu quả.</li>
                <li>Nếu làm thủ công bằng giấy tờ thì dễ sai sót, mất thời gian.</li>
                <li>Vì vậy, em chọn làm phần mềm quản lý bán hàng mini để hỗ trợ việc nhập, lưu, chỉnh sửa và theo
                    dõi hàng hóa dễ dàng hơn.</li>
            </ul>
        </div>

        <!-- Slide 2 -->
        <div class="slide">
            <h2>2️⃣. Mục tiêu của đề tài</h2>
            <ul>
                <li>Giúp quản lý sản phẩm, thêm – xóa – sửa thông tin sản phẩm nhanh chóng.</li>
                <li>Hỗ trợ người bán xem danh sách sản phẩm rõ ràng, dễ thao tác.</li>
                <li>Lập hóa đơn</li>
            </ul>
        </div>

        <!-- Slide 3 -->
        <div class="slide">
            <h2>3️⃣. Công nghệ sử dụng</h2>
            <ul>
                <li><strong>Ngôn ngữ lập trình:</strong> PHP</li>
                <li><strong>Cơ sở dữ liệu:</strong> MySQL</li>
                <li><strong>Giao diện:</strong> HTML + CSS cơ bản</li>
                <li><strong>Công cụ phát triển:</strong> Visual Studio Code / XAMPP</li>
            </ul>
        </div>

        <!-- Slide 4 -->
        <div class="slide">
            <h2>4️⃣. Các chức năng chính</h2>
            <table class="table table-bordered table-striped">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Chức năng</th>
                        <th>Mô tả</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="emoji">➕ Thêm sản phẩm</td>
                        <td>Nhập thông tin: mã, tên, giá, số lượng</td>
                    </tr>
                    <tr>
                        <td class="emoji">🗑️ Xóa sản phẩm</td>
                        <td>Xóa 1 sản phẩm khỏi danh sách</td>
                    </tr>
                    <tr>
                        <td class="emoji">✏️ Sửa thông tin</td>
                        <td>Cập nhật lại tên, giá, số lượng sản phẩm</td>
                    </tr>
                    <tr>
                        <td class="emoji">📋 Hiển thị danh sách</td>
                        <td>Xem toàn bộ sản phẩm trong bảng</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Slide 5 -->
        <div class="slide">
            <h2>5️⃣. Giao diện chương trình</h2>
            <ul>
                <li>Trang chính hiển thị danh sách sản phẩm.</li>
                <li>Có form thêm, sửa thông tin ngay trên cùng trang.</li>
                <li>Các nút “Thêm”, “Sửa”, “Xóa” dễ thao tác.</li>
                <li>Kết nối trực tiếp với cơ sở dữ liệu <strong>ql_banhang</strong>.</li>
            </ul>
        </div>

        <!-- Slide 6 -->
        <div class="slide">
            <h2>6️⃣. Kết quả đạt được</h2>
            <ul>
                <li>Chương trình hoạt động ổn định, dễ sử dụng.</li>
                <li>Có thể lưu trữ và chỉnh sửa thông tin sản phẩm nhanh chóng.</li>
                <li>Phù hợp với cửa hàng nhỏ hoặc cá nhân kinh doanh.</li>
            </ul>
        </div>

        <!-- Slide 7 -->
        <div class="slide">
            <h2>7️⃣. Hướng phát triển</h2>
            <ul>
                <li>Thêm chức năng quản lý khách hàng và hóa đơn.</li>
                <li>Tạo báo cáo doanh thu theo ngày/tháng.</li>
                <li>Thiết kế lại giao diện đẹp và thân thiện hơn (dùng Bootstrap).</li>
                <li>Nâng cấp thành ứng dụng web bán hàng trực tuyến.</li>
            </ul>
        </div>

        <!-- Slide 8 -->
        <div class="slide">
            <h2>💬 8️⃣. Kết luận</h2>
            <p>Phần mềm Quản lý bán hàng giúp đơn giản hóa công việc quản lý sản phẩm, tiết kiệm thời gian và giảm sai
                sót.</p>
            <p>Dự án tuy nhỏ nhưng giúp em hiểu rõ hơn về cách kết nối PHP – MySQL và thao tác với dữ liệu thực tế.</p>
        </div>

        <!-- Footer -->
        <div class="text-center text-muted mt-5">
            <p>© 2025 - Nhóm sinh viên thực hiện đề tài PHP MySQL</p>
        </div>

    </div>

</body>

</html>
