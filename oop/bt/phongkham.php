<?php
interface ThongBao {
    public function gui(string $noiDung): void;
}

class ThongBaoEmail implements ThongBao {
    public function gui(string $noiDung): void {
        echo "[Email] $noiDung<br>";
    }
}

class ThongBaoSMS implements ThongBao {
    public function gui(string $noiDung): void {
        echo "[SMS] $noiDung<br>";
    }
}

abstract class LichKham {
    public string $maLich;
    public string $benhNhan;
    public string $bacSi;
    public DateTime $thoiGian;
    public string $chuyenKhoa;

    public function __construct($maLich, $benhNhan, $bacSi, $thoiGian, $chuyenKhoa) {
        $this->maLich = $maLich;
        $this->benhNhan = $benhNhan;
        $this->bacSi = $bacSi;
        $this->thoiGian = new DateTime($thoiGian);
        $this->chuyenKhoa = $chuyenKhoa;
    }

    public static function KiemTraTrungLich(array $dsLichDaCo, LichKham $lichMoi): bool {
        foreach ($dsLichDaCo as $lich) {
            if ($lich->bacSi === $lichMoi->bacSi) {
                $diff = abs($lich->thoiGian->getTimestamp() - $lichMoi->thoiGian->getTimestamp());
                if ($diff <= 30 * 60) return true; 
            }
        }
        return false;
    }

    abstract public function XuLyTruocKhiKham(): void;

    public function XacNhan(ThongBao $kenh): void {
        $kenh->gui("Lịch khám cho {$this->benhNhan} với bác sĩ {$this->bacSi} lúc {$this->thoiGian->format('Y-m-d H:i')} đã được xác nhận.");
    }
}


class LichKhamNoiTongQuat extends LichKham {
    public function XuLyTruocKhiKham(): void {
        echo " Yêu cầu nhịn ăn trước khi khám Nội tổng quát.<br>";
    }
}

class LichKhamNhi extends LichKham {
    public function XuLyTruocKhiKham(): void {
        echo " Kiểm tra chiều cao, cân nặng trước khi khám Nhi.<br>";
    }
}

class LichKhamDaLieu extends LichKham {
    public function XuLyTruocKhiKham(): void {
        echo " Không trang điểm trước khi khám Da liễu.<br>";
    }
}

class QuanLyLich {
    private array $dsLich = [];

    public function DatLich(LichKham $lich): void {
        if (LichKham::KiemTraTrungLich($this->dsLich, $lich)) {
            throw new RuntimeException("Lịch khám bị trùng cho bác sĩ {$lich->bacSi}!");
        }
        $this->dsLich[] = $lich;
        echo " Đặt lịch thành công cho bệnh nhân {$lich->benhNhan}<br>";
    }

    public function hienThiTatCa(): void {
        echo "<h3> Danh sách lịch khám:</h3>";
        foreach ($this->dsLich as $lich) {
            echo "- Bệnh nhân: {$lich->benhNhan}, Bác sĩ: {$lich->bacSi}, Thời gian: {$lich->thoiGian->format('Y-m-d H:i')}, Khoa: {$lich->chuyenKhoa}<br>";
        }
    }
}


$quanLy = new QuanLyLich();


$email = new ThongBaoEmail();
$sms = new ThongBaoSMS();

$lich1 = new LichKhamNoiTongQuat("L001", "Nguyễn Văn A", "Dr. Long", "2025-10-02 08:00", "Nội tổng quát");
$lich2 = new LichKhamDaLieu("L002", "Trần Thị B", "Dr. Long", "2025-10-02 08:20", "Da liễu"); // trùng lịch
$lich3 = new LichKhamNhi("L003", "Lê Văn C", "Dr. Hoa", "2025-10-02 09:00", "Nhi");


try {
    $quanLy->DatLich($lich1);
    $lich1->XuLyTruocKhiKham();
    $lich1->XacNhan($email);

    $quanLy->DatLich($lich2); 
    $lich2->XuLyTruocKhiKham();
    $lich2->XacNhan($sms);
} catch (RuntimeException $e) {
    echo "<span style='color:red;'>Lỗi: {$e->getMessage()}</span><br>";
}


try {
    $quanLy->DatLich($lich3);
    $lich3->XuLyTruocKhiKham();
    $lich3->XacNhan($sms);
} catch (RuntimeException $e) {
    echo "<span style='color:red;'>Lỗi: {$e->getMessage()}</span><br>";
}

$quanLy->hienThiTatCa();
?>

