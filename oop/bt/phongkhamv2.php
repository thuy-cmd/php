<?php
declare(strict_types=1);
session_start();

interface ThongBao {
    public function gui(string $noiDung): void;
}

final class ThongBaoEmail implements ThongBao {
    public function gui(string $noiDung): void {
        echo '<div class="alert alert-primary mb-2"><i class="bi bi-envelope"></i> Email: ' .
             htmlspecialchars($noiDung) . '</div>';
    }
}

final class ThongBaoSMS implements ThongBao {
    public function gui(string $noiDung): void {
        echo '<div class="alert alert-success mb-2"><i class="bi bi-chat-dots"></i> SMS: ' .
             htmlspecialchars($noiDung) . '</div>';
    }
}

abstract class LichKham {
    public string $maLich;
    public string $benhNhan;
    public string $bacSi;
    public DateTime $gioKham;
    public string $chuyenKhoa;
    public string $kenhThongBao;
    public int $thuTu;

    public function __construct(
        string $benhNhan,
        string $bacSi,
        DateTime $gioKham,
        string $chuyenKhoa,
        string $kenhThongBao = 'email'
    ) {
        $this->maLich = 'LK-' . strtoupper(bin2hex(random_bytes(3)));
        $this->benhNhan = $benhNhan;
        $this->bacSi = $bacSi;
        $this->gioKham = $gioKham;
        $this->chuyenKhoa = $chuyenKhoa;
        $this->kenhThongBao = $kenhThongBao;
        $this->thuTu = 0;
    }

    final public function KiemTraTrungLich(array $dsLich): bool {
        foreach ($dsLich as $l) {
            /** @var LichKham $l */
            if ($l->bacSi === $this->bacSi) {
                $diff = abs($l->gioKham->getTimestamp() - $this->gioKham->getTimestamp());
                if ($diff <= 30 * 60) return true;
            }
        }
        return false;
    }

    abstract public function XuLyTruocKhiKham(): string;

    public function XacNhan(ThongBao $kenh): void {
        $msg = "Đặt lịch #{$this->maLich} - STT {$this->thuTu} cho {$this->benhNhan}, ".
               "Bác sĩ {$this->bacSi} ({$this->chuyenKhoa}) lúc ".
               $this->gioKham->format('d/m/Y H:i');
        $kenh->gui($msg);
    }
}

final class LichKhamNoiTongQuat extends LichKham {
    public function XuLyTruocKhiKham(): string {
        return "Nhịn ăn 6h trước khi khám tổng quát, mang theo kết quả cũ (nếu có).";
    }
}
final class LichKhamNhi extends LichKham {
    public function XuLyTruocKhiKham(): string {
        return "Đo nhiệt độ trẻ tại nhà, ghi chú dị ứng thuốc. Mang sổ tiêm chủng.";
    }
}
final class LichKhamDaLieu extends LichKham {
    public function XuLyTruocKhiKham(): string {
        return "Không bôi kem/thuốc lên vùng tổn thương trong 12h trước khi khám.";
    }
}

final class QuanLyLich {
    /** @var LichKham[] */
    public array $dsLich = [];

    public function __construct(array $init = []) {
        $this->dsLich = $init;
    }

    public function DatLich(LichKham $lich): void {
        $h = (int)$lich->gioKham->format('H') * 60 + (int)$lich->gioKham->format('i');
        $inLunch = $h >= (11*60+30) && $h < (13*60+30);
        if ($inLunch) {
            throw new RuntimeException("Giờ nghỉ trưa (11:30–13:30), vui lòng chọn khung khác.");
        }
        if ($lich->KiemTraTrungLich($this->dsLich)) {
            throw new RuntimeException("Trùng lịch cùng bác sĩ trong ±30 phút.");
        }
        $lich->thuTu = $this->TinhSoThuTu($lich->gioKham);
        $this->dsLich[] = $lich;
    }

    private function TinhSoThuTu(DateTime $gio): int {
        $sameDay = array_values(array_filter(
            $this->dsLich,
            fn($l) => $l->gioKham->format('Y-m-d') === $gio->format('Y-m-d')
        ));
        usort($sameDay, fn($a,$b) => $a->gioKham <=> $b->gioKham);
        return count($sameDay) + 1;
    }

    /** @return LichKham[] */
    public function TimTheoBacSi(string $ten): array {
        return array_values(array_filter($this->dsLich, fn($l) => $l->bacSi === $ten));
    }

    /** @return LichKham[] */
    public function TatCa(): array {
        usort($this->dsLich, fn($a,$b) => $a->gioKham <=> $b->gioKham);
        return $this->dsLich;
    }
}

$DOCTORS = [
    "Nguyễn An"   => "Nội tổng quát",
    "Trần Bình"   => "Nhi",
    "Phạm Chi"    => "Da liễu",
    "Vũ Dũng"     => "Nội tổng quát",
];

$SPECIALTIES = ["Nội tổng quát","Nhi","Da liễu"];

if (!isset($_SESSION['dsLich'])) $_SESSION['dsLich'] = [];

$ds = array_map(function($raw){
    switch ($raw['type']) {
        case 'noi': $obj = new LichKhamNoiTongQuat(...$raw['ctor']); break;
        case 'nhi': $obj = new LichKhamNhi(...$raw['ctor']); break;
        default:    $obj = new LichKhamDaLieu(...$raw['ctor']); break;
    }
    $obj->maLich  = $raw['ma'];
    $obj->thuTu   = $raw['stt'];
    return $obj;
}, $_SESSION['dsLich']);

$ql = new QuanLyLich($ds);

$flash = ['type'=>'','msg'=>''];
$toastConfirm = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $benhNhan = trim($_POST['benhNhan'] ?? '');
    $chuyenKhoa = $_POST['chuyenKhoa'] ?? '';
    $bacSi = $_POST['bacSi'] ?? '';
    $kenh = $_POST['kenh'] ?? 'email';
    $gioRaw = $_POST['gioKham'] ?? '';

    try {
        if ($benhNhan==='' || $bacSi==='' || $chuyenKhoa==='' || $gioRaw==='') {
            throw new RuntimeException('Vui lòng nhập đầy đủ thông tin.');
        }
        $gio = new DateTime($gioRaw);
        switch ($chuyenKhoa) {
            case 'Nội tổng quát':
                $lich = new LichKhamNoiTongQuat($benhNhan, $bacSi, $gio, $chuyenKhoa, $kenh); break;
            case 'Nhi':
                $lich = new LichKhamNhi($benhNhan, $bacSi, $gio, $chuyenKhoa, $kenh); break;
            default:
                $lich = new LichKhamDaLieu($benhNhan, $bacSi, $gio, $chuyenKhoa, $kenh); break;
        }

        if ($lich->KiemTraTrungLich($ql->dsLich)) {
            $suggest = clone $gio;
            for ($i=0; $i<6; $i++) {
                $suggest->modify('+45 minutes');
                $tmp = clone $lich;
                $tmp->gioKham = $suggest;
                $h = (int)$suggest->format('H')*60+(int)$suggest->format('i');
                $inLunch = $h >= (11*60+30) && $h < (13*60+30);
                if (!$inLunch && !$tmp->KiemTraTrungLich($ql->dsLich)) break;
            }
            throw new RuntimeException(
                'Trùng lịch cùng bác sĩ trong ±30’. Gợi ý khung gần nhất: '.
                $suggest->format('d/m/Y H:i')
            );
        }

        $ql->DatLich($lich);

        $_SESSION['dsLich'][] = [
            'type' => $chuyenKhoa==='Nội tổng quát' ? 'noi' : ($chuyenKhoa==='Nhi'?'nhi':'da'),
            'ctor' => [$benhNhan, $bacSi, $gio, $chuyenKhoa, $kenh],
            'ma'   => $lich->maLich,
            'stt'  => $lich->thuTu,
        ];

        ob_start();
        $kenhObj = $kenh==='sms' ? new ThongBaoSMS() : new ThongBaoEmail();
        $lich->XacNhan($kenhObj);
        $toastConfirm = ob_get_clean();

        $flash = ['type'=>'success','msg'=>'Đặt lịch thành công!'];
    } catch (RuntimeException $e) {
        $flash = ['type'=>'danger','msg'=>$e->getMessage()];
    }
}
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý lịch khám</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    body {
        background:
            radial-gradient(1000px 600px at 0% 0%, #f8fbff, #ffffff) fixed;
    }

    .glass {
        backdrop-filter: blur(8px);
        background: rgba(255, 255, 255, .7);
    }
    </style>
</head>

<body>
    <div class="container py-4">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card shadow-sm glass">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-calendar2-check"></i> Đặt lịch khám</h5>
                    </div>
                    <div class="card-body">
                        <?php if($flash['type']): ?>
                        <div class="alert alert-<?= $flash['type'] ?>"><?= htmlspecialchars($flash['msg']) ?></div>
                        <?php endif; ?>

                        <form method="post" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label class="form-label">Tên bệnh nhân</label>
                                <input name="benhNhan" class="form-control" required placeholder="VD: Lê Minh">
                                <div class="invalid-feedback">Nhập tên bệnh nhân</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Chuyên khoa</label>
                                <select name="chuyenKhoa" id="chuyenKhoa" class="form-select" required>
                                    <option value="">-- Chọn --</option>
                                    <?php foreach($SPECIALTIES as $sp): ?>
                                    <option value="<?= $sp ?>"><?= $sp ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Mỗi khoa có hướng dẫn riêng trước khi khám.</div>
                                <div class="invalid-feedback">Chọn chuyên khoa</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Bác sĩ</label>
                                <select name="bacSi" id="bacSi" class="form-select" required>
                                    <option value="">-- Chọn --</option>
                                    <?php foreach($DOCTORS as $bs=>$khoa): ?>
                                    <option data-khoa="<?= $khoa ?>" value="<?= $bs ?>"><?= $bs ?> — <?= $khoa ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Chọn bác sĩ</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Thời gian khám</label>
                                <input type="datetime-local" name="gioKham" class="form-control" required>
                                <div class="form-text">Tự động từ chối 11:30–13:30, thời lượng mặc định 60’.</div>
                                <div class="invalid-feedback">Chọn thời gian</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-block">Kênh thông báo</label>
                                <div class="btn-group" role="group">
                                    <input type="radio" class="btn-check" name="kenh" id="kenh1" autocomplete="off"
                                        value="email" checked>
                                    <label class="btn btn-outline-primary" for="kenh1"><i class="bi bi-envelope"></i>
                                        Email</label>
                                    <input type="radio" class="btn-check" name="kenh" id="kenh2" autocomplete="off"
                                        value="sms">
                                    <label class="btn btn-outline-success" for="kenh2"><i class="bi bi-chat-dots"></i>
                                        SMS</label>
                                </div>
                            </div>

                            <button class="btn btn-primary w-100">
                                <i class="bi bi-magic"></i> Đặt lịch ngay
                            </button>
                        </form>
                    </div>
                    <div class="card-footer bg-white">
                        <small class="text-muted">• Kiểm tra trùng lịch ±30’ theo bác sĩ • Tự gán STT trong ngày • Gợi ý
                            giờ gần nhất nếu bị trùng.</small>
                    </div>
                </div>

                <?php if($toastConfirm): ?>
                <div class="mt-3">
                    <h6 class="mb-2">Xác nhận đã gửi:</h6>
                    <?= $toastConfirm ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-7">
                <div class="card shadow-sm glass">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between">
                        <h5 class="mb-0"><i class="bi bi-journal-text"></i> Lịch đã đặt</h5>
                    </div>
                    <div class="card-body">
                        <?php
          if (isset($_GET['clear'])) { $_SESSION['dsLich'] = []; header('Location: ./'); exit; }
          $all = $ql->TatCa();
          if (!$all): ?>
                        <div class="text-muted">Chưa có lịch nào. Hãy đặt lịch ở khung bên trái.</div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>STT</th>
                                        <th>Mã lịch</th>
                                        <th>Thời gian</th>
                                        <th>Bác sĩ</th>
                                        <th>Khoa</th>
                                        <th>Bệnh nhân</th>
                                        <th>Hướng dẫn</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($all as $l): ?>
                                    <tr>
                                        <td><span class="badge text-bg-secondary"><?= $l->thuTu ?></span></td>
                                        <td><code><?= $l->maLich ?></code></td>
                                        <td><i class="bi bi-clock"></i> <?= $l->gioKham->format('d/m/Y H:i') ?></td>
                                        <td><?= htmlspecialchars($l->bacSi) ?></td>
                                        <td><span class="badge bg-info-subtle text-dark"><?= $l->chuyenKhoa ?></span>
                                        </td>
                                        <td><?= htmlspecialchars($l->benhNhan) ?></td>
                                        <td><?= htmlspecialchars($l->XuLyTruocKhiKham()) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="alert alert-info mt-3">
                    <div class="d-flex">
                        <div class="me-2"><i class="bi bi-lightbulb"></i></div>
                        <div>
                            <strong>Mẹo kiểm thử nhanh:</strong> chọn cùng một bác sĩ và đặt các mốc cách nhau ≤ 30 phút
                            sẽ báo trùng (đề yêu cầu).
                            Nếu chọn 12:00 sẽ bị chặn do giờ nghỉ trưa.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    const selKhoa = document.getElementById('chuyenKhoa');
    const selBS = document.getElementById('bacSi');

    function filterBS() {
        const k = selKhoa.value;
        [...selBS.options].forEach((o, i) => {
            if (i === 0) return;
            o.hidden = (k && o.dataset.khoa !== k);
        });
    }
    selKhoa?.addEventListener('change', filterBS);
    filterBS();

    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
    </script>
</body>

</html>
