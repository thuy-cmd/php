<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lý thuyết Web – Demo Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .top {

        position: fixed;
        top: 0;
        left: 0;
        right: 0;

        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 10;
    }

    .top a {
        font-size: 20px;
    }
    </style>
</head>

<body class="bg-light">
    <div class="container top bg-light">
        <h1>Bit</h1>
        <a href="giaothuc.docx" download="Giao thức">Tải tài liệu</a>
    </div>
    <div class="container py-4">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5>2.1. Giao thức</h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="protocolAccordion">

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="httpHeading">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#httpCollapse">
                                2.1.1. Giao thức HTTP
                            </button>
                        </h2>
                        <div id="httpCollapse" class="accordion-collapse collapse show"
                            data-bs-parent="#protocolAccordion">
                            <div class="accordion-body">
                                <ul>
                                    <li>HTTP là giao thức request–response.</li>
                                    <li>Client gửi <code>Request</code>, Server trả <code>Response</code>.</li>
                                    <li>Thành phần: URL, Method, Header, Body, Status Code.</li>
                                </ul>
                                <div class="alert alert-info">Ví dụ: GET /index.html HTTP/1.1</div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="getpostHeading">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#getpostCollapse">
                                2.1.2. Giao thức POST và GET
                            </button>
                        </h2>
                        <div id="getpostCollapse" class="accordion-collapse collapse"
                            data-bs-parent="#protocolAccordion">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card border-success mb-3">
                                            <div class="card-header">GET</div>
                                            <div class="card-body">
                                                <p>Gửi dữ liệu qua URL</p>
                                                <code>example.com?user=eric</code>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-warning mb-3">
                                            <div class="card-header">POST</div>
                                            <div class="card-body">
                                                <p>Gửi dữ liệu trong body</p>
                                                <code>{ username: "eric" }</code>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <pre class="bg-dark text-light p-3 rounded">
&lt;form method="POST"&gt;
  &lt;input name="username"&gt;
  &lt;button&gt;Gửi&lt;/button&gt;
&lt;/form&gt;</pre>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5>2.2. Xây dựng Web Form</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <div class="list-group-item">
                        <h6>2.2.1. Form đăng nhập hệ thống</h6>
                        <form class="row g-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Tên đăng nhập">
                            </div>
                            <div class="col-md-6">
                                <input type="password" class="form-control" placeholder="Mật khẩu">
                            </div>
                            <div class="col-12">
                                <button class="btn btn-success">Đăng nhập</button>
                            </div>
                        </form>
                    </div>
                    <div class="list-group-item">
                        <h6>2.2.2. Form hiển thị dữ liệu từ nhiều bảng</h6>
                        <table class="table table-striped table-bordered mt-2">
                            <thead class="table-dark">
                                <tr>
                                    <th>Tên</th>
                                    <th>Môn</th>
                                    <th>Điểm</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Lan</td>
                                    <td>Toán</td>
                                    <td>8.5</td>
                                </tr>
                                <tr>
                                    <td>Hải</td>
                                    <td>Lý</td>
                                    <td>7.8</td>
                                </tr>
                            </tbody>
                        </table>
                        <pre class="bg-dark text-light p-3 rounded">
$sql = "SELECT users.name, scores.score
        FROM users JOIN scores
        ON users.id = scores.user_id";</pre>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-warning">
                <h5>2.3. Cookie và Session</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6>2.3.1. Form nhớ mật khẩu (Cookie)</h6>
                    <form>
                        <input type="text" class="form-control mb-2" placeholder="Tên đăng nhập">
                        <input type="password" class="form-control mb-2" placeholder="Mật khẩu">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="remember">
                            <label class="form-check-label" for="remember">Nhớ mật khẩu</label>
                        </div>
                        <button class="btn btn-primary">Đăng nhập</button>
                    </form>
                    <pre class="bg-dark text-light p-3 rounded mt-2">
if(isset($_POST['remember'])){
  setcookie("username",$username,time()+3600,"/");
}</pre>
                </div>

                <div class="mb-3">
                    <h6>2.3.2. Giỏ hàng với Session</h6>
                    <button class="btn btn-outline-secondary btn-sm">Mua Táo</button>
                    <button class="btn btn-outline-secondary btn-sm">Mua Chuối</button>
                    <button class="btn btn-outline-secondary btn-sm">Mua Xoài</button>
                    <ul class="list-group mt-2">
                        <li class="list-group-item">Táo</li>
                        <li class="list-group-item">Chuối</li>
                    </ul>
                    <pre class="bg-dark text-light p-3 rounded mt-2">
session_start();
$_SESSION['cart'][] = "Táo";</pre>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
