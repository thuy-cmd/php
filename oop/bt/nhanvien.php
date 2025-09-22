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

        public function setName($_name) {$this->$_name = $_name;}
        public function setPosition($_position) {$this->$_position = $_position;}
        public function setSalary($_baseSalary) {$this->baseSalary = $_baseSalary;}
        public function setIsLeader($_isLeader) {$this->isLeader = $_isLeader;}
        public function getName() { return $this->name; }
        public function getPosition() { return $this->position; }
        public function getSalary() { return $this->baseSalary; }
        public function getIsLeader() { return $this->isLeader; }

        public function sumSalary() {
            return $this->baseSalary + ( $this->isLeader ? 3000000 : 0);
        }
    }

    $nv1 = new Nhanvien("BitEric", "Truong phong ki thuat",  8000000, true );
    $nv2 = new Nhanvien("Alan", "Nhan vien",  9000000, false );
    $nv3 = new Nhanvien("ADC", "Truong phong ki thuat",  8000000, true );
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhan vien</title>
</head>

<body>
    <div>
        <h1>Thong tin nhan vien</h1>
        <p>Name: <?php echo $nv1->getName() ?></p>
        <p>Vi tri: <?php echo $nv1->getPosition() ?></p>
        <p>Luong: <?php echo $nv1->getSalary() ?></p>
        <hr>
        <p>Tong luon: <?php echo $nv1->sumSalary() ?></p>
    </div>
</body>

</html>
