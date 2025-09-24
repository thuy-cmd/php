<?php
class Nhanvien {
    private $name, $position, $baseSalary;
    private $isLeader;

    public function __construct($_name, $_position, $_baseSalary, $_isLeader = false) {
        $this->name = $_name;
        $this->position = $_position;
        $this->baseSalary = $_baseSalary;
        $this->isLeader = $_isLeader;
    }

    public function setName($_name) { $this->name = $_name; }
    public function setPosition($_position) { $this->position = $_position; }
    public function setSalary($_baseSalary) { $this->baseSalary = $_baseSalary; }
    public function setIsLeader($_isLeader) { $this->isLeader = $_isLeader; }

    public function getName() { return $this->name; }
    public function getPosition() { return $this->position; }
    public function getSalary() { return $this->baseSalary; }
    public function getIsLeader() { return $this->isLeader; }

    public function sumSalary() {
        return $this->baseSalary + ( $this->isLeader ? 3000000 : 0);
    }

    public function displayInfor() {
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin-top: 10px'>";
        echo "<p><strong>Tên:</strong> {$this->name}</p>";
        echo "<p><strong>Vị trí:</strong> {$this->position}</p>";
        echo "<p><strong>Lương cơ bản:</strong> {$this->baseSalary} VNĐ</p>";
        echo "<p><strong>Chức vụ:</strong> " . ($this->isLeader ? "Trưởng phòng" : "Nhân viên") . "</p>";
        echo "<p><strong>Tổng lương:</strong> " . $this->sumSalary() . " VNĐ</p>";
        echo "</div>";
    }
}

    $nv1 = new Nhanvien("BitEric", "Trưởng phòng kỹ thuật", 8000000, true);
    $nv2 = new Nhanvien("Alan", "Nhân viên", 9000000, false);
    $nv3 = new Nhanvien("ADC", "Trưởng phòng kỹ thuật", 8000000, true);

    $newNV = null;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"];
        $position = $_POST["position"];
        $salary = (int)$_POST["salary"];
        $is_leader = $_POST["is_leader"] === "true" ? true : false;

        $newNV = new Nhanvien($name, $position, $salary, $is_leader);
    }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhan vien</title>
</head>

<body>
    <form action="" method="post">
        <input type="text" name="name" id="name" placeholder="Nhap ho ten">
        <input type="text" name="position" id="position" placeholder="Nhap vi tri">
        <input type="number" name="salary" id="salary" placeholder="Nhap tien Luong">
        <select name="is_leader" id="is_leader">
            <option value="true">Truong phong</option>
            <option value="false">Nhanvien</option>
        </select>
        <button>Send</button>
    </form>
    <div>
        <h1>Thong tin nhan vien</h1>
        <p>Name: <?php echo $nv1->getName() ?></p>
        <p>Vi tri: <?php echo $nv1->getPosition() ?></p>
        <p>Luong: <?php echo $nv1->getSalary() ?></p>
        <hr>
        <p>Tong luon: <?php echo $nv1->sumSalary() ?></p>
    </div>
    <?php
        if ($newNV) {
            $newNV->displayInfor();

        }
    ?>
</body>

</html>