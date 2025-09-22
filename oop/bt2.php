<?php
class PTB2 {
    private $a, $b, $c;

    public function __construct($a, $b, $c) {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }

    public function giai() {
        if ($this->a == 0) {
            return $this->b == 0 ? "Vô nghiệm" : "Nghiệm x = " . (-$this->c / $this->b);
        }
        $delta = $this->b*$this->b - 4*$this->a*$this->c;
        if ($delta < 0) return "Vô nghiệm";
        if ($delta == 0) return "Nghiệm kép x = " . (-$this->b/(2*$this->a));
        $x1 = (-$this->b - sqrt($delta))/(2*$this->a);
        $x2 = (-$this->b + sqrt($delta))/(2*$this->a);
        return "x1 = $x1, x2 = $x2";
    }
}


$pt = new PTB2(1, -3, 2); 
echo $pt->giai();
?>
