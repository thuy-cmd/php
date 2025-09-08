<?php
    function sumofnum($value) {
        $res = 0;
        while ($value > 0) {
            $res += $value % 10;
            $value = intdiv($value,10);
        }
        return $res;
    }

?>