<?php
    class PhepToan {
        private $a;
        protected $b;
        public $c = 34;
        public $d = 45;

        public function seta($_a) {
            $this->a = $_a;
        }

        public function setb($_b) {
            $this->b = $_b;
        }

        public function geta() {
            return $this->a;
        }

        public function getb() {
            return $this->b;
        }

        public function Tong($x, $y) {
            return $x + $y;
        }
    }
    $toan = new PhepToan();
    $toan->seta(-45);
    $toan->setb(60);
    echo "Phep toan 1: " . $toan->geta() . " + ". $toan->getb() ." = " . $toan->Tong($toan->geta(), $toan->getb()) ."<br>";
    echo "Phep toan 2: " . $toan->c . " + ". $toan->d ." = " . $toan->Tong($toan->c, $toan->d) ."<br>";

    class Nhanvien {
        public $name;
        public $salary;

        function __construct($name = "Guess", $salary = 0) {
            echo "<br>Constructer<br>";
            $this->name = $name;
            $this->salary = $salary;
        }

        function displayInfor() {
            echo "Name: " . $this->name .". Salary: ". $this->salary ."vnd<br>";
        }
    }
    $nhanvien = new Nhanvien();
    $nhanvien->displayInfor();
    $nhanviena = new Nhanvien("Nguyen Van A", 2000000000);
    $nhanviena->displayInfor();
    $nhanvienb = new Nhanvien("Nguyen Van B", 2000000000);
    $nhanvienb->displayInfor();
    $nhanvienc = new Nhanvien("Nguyen Van C", 2000000000);
    $nhanvienc->displayInfor();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phep toán</title>
    <style>
    html {
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }
    </style>
</head>

<body>
    <form action="">

    </form>
</body>

</html>