<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        background-color: #f4f4f4;
        color: #333;
    }

    header {
        background-color: #35424a;
        color: #ffffff;
        padding: 10px 0;
        text-align: center;
    }

    nav ul {
        list-style: none;
        padding: 0;
    }

    nav ul li {
        display: inline;
        margin: 0 15px;
    }

    nav ul li a {
        color: #ffffff;
        text-decoration: none;
        font-weight: bold;
    }

    .container {
        width: 80%;
        margin: 20px auto;
        overflow: hidden;
    }

    .main_title {
        text-align: center;
        margin-bottom: 30px;
        font-size: 2em;
        color: #35424a;
    }

    .title {
        text-align: center;
        margin-bottom: 20px;
    }

    .wrap {
        display: flex;
        justify-content: space-between;
    }

    .card_col {
        gap: 20px;
        flex-direction: column;
    }

    .card {
        background: #fff;
        padding: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        width: 30%;
    }

    .card_col .card {
        display: flex;
        width: 100%;
        gap: 20px;
    }

    .card_title {
        font-size: 1.5em;
        margin-bottom: 10px;
    }

    .card_img {
        width: 100%;
        height: auto;
        margin-bottom: 10px;
    }

    .card_desc {
        font-size: 1em;
        color: #666;
    }
    </style>
</head>

<body>
    <header>
        <nav>
            <ul>
                <li>
                    <a href="#!">Trang chủ</a>
                </li>
                <li>
                    <a href="#!">Tin tức</a>
                </li>
                <li>
                    <a href="#!">Du lịch</a>
                </li>
                <li>
                    <a href="#!">Ẩm thực</a>
                </li>
                <li>
                    <a href="#!">Hỏi đáp</a>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="banner">
            <img src="https://dulichokela.com/wp-content/uploads/2024/01/top-10-dia-diem-du-lich-lang-son.jpg" +
                alt="Banner" style="width: 100%; height: auto;">
        </div>
        <h1 class="main_title">XU HƯỚNG THIẾT KẾ</h1>
        <div class="container">
            <h1 class="title">BỐ CỤC CỘT DỌC</h1>
            <div class="wrap">
                <div class="card">
                    <h2 class="card_title">Cột cờ Phai Vệ</h2>
                    <img src="https://luhanhvietnam.com.vn/du-lich/vnt_upload/news/10_2021/cot-co-phai-ve_3-min.jpg"
                        alt="" class="card_img">
                    <p class="card_desc">Nằm trên núi Phai Vệ với độ cao khoảng 80m, cột cờ này bao gồm 535 bậc đá được
                        xây
                        dựng chắc chắn. Toàn bộ cột cờ này được xây dựng bằng các vật liệu có tình bền như bê tông ốp
                        đá.
                    </p>
                </div>
                <div class="card">
                    <h2 class="card_title">Thành nhà Mạc</h2>
                    <img src="https://scontent.iocvnpt.com/resources/portal//Images/TQG/ttcntt.tqg/Dia%20Diem/Thanh%20Nha%20Mac/6._thanh_nha_mac_637013945423403297.jpg"
                        alt="" class="card_img">
                    <p class="card_desc">
                        Thành nhà Mạc là quần thể di tích lịch sử văn hóa có quy mô lớn, mang giá trị lịch sử, kiến trúc
                        và
                        nghệ thuật độc đáo, tiêu biểu cho nghệ thuật quân sự thời Lê - Mạc.
                    </p>
                </div>
                <div class="card">
                    <h2 class="card_title">Núi Tô Thị</h2>
                    <img src="https://mia.vn/media/uploads/blog-du-lich/nui-to-thi-1746332254.jpg" alt=""
                        class="card_img">
                    <p class="card_desc">
                        Tương truyền ngày xưa, có sự tích về #Núi Tô Thị liên quan đến một nàng công chúa tên Tô Thị.
                        Nàng
                        đã hóa đá sau khi chờ đợi người yêu trở về.
                    </p>
                </div>
            </div>
        </div>
        <div class="container">
            <h1 class="title">BỐ CỤC HÀNG NGANG</h1>
            <div class="wrap card_col">
                <div class="card">
                    <img src="https://luhanhvietnam.com.vn/du-lich/vnt_upload/news/10_2021/cot-co-phai-ve_3-min.jpg"
                        alt="" class="card_img">
                    <div class="card_content">
                        <h2 class="card_title">Cột cờ Phai Vệ</h2>
                        <p class="card_desc">Nằm trên núi Phai Vệ với độ cao khoảng 80m, cột cờ này bao gồm 535 bậc đá
                            được
                            xây
                            dựng chắc chắn. Toàn bộ cột cờ này được xây dựng bằng các vật liệu có tình bền như bê tông
                            ốp
                            đá.
                        </p>
                    </div>
                </div>
                <div class="card">
                    <img src="https://scontent.iocvnpt.com/resources/portal//Images/TQG/ttcntt.tqg/Dia%20Diem/Thanh%20Nha%20Mac/6._thanh_nha_mac_637013945423403297.jpg"
                        alt="" class="card_img">
                    <div class="card_content">
                        <h2 class="card_title">Thành nhà Mạc</h2>
                        <p class="card_desc">
                            Thành nhà Mạc là quần thể di tích lịch sử văn hóa có quy mô lớn, mang giá trị lịch sử, kiến
                            trúc
                            và
                            nghệ thuật độc đáo, tiêu biểu cho nghệ thuật quân sự thời Lê - Mạc.
                        </p>
                    </div>
                </div>
                <div class="card">
                    <img src="https://mia.vn/media/uploads/blog-du-lich/nui-to-thi-1746332254.jpg" alt=""
                        class="card_img">
                    <div class="card_content">
                        <h2 class="card_title">Núi Tô Thị</h2>
                        <p class="card_desc">
                            Tương truyền ngày xưa, có sự tích về #Núi Tô Thị liên quan đến một nàng công chúa tên Tô
                            Thị.
                            Nàng
                            đã hóa đá sau khi chờ đợi người yêu trở về.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <p style="text-align: center; padding: 20px; background-color: #35424a; color: white;">&copy; 2025 My Website
        </p>
    </footer>
</body>

</html>
