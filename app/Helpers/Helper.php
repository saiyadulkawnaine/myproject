<?php
if (!function_exists('print_with_bracket')) {

    function print_with_bracket($val, $decimals = 2)
    {
        return $val < 0 ? "(".number_format(abs($val),$decimals,'.',',').")" : number_format($val,$decimals,'.',',');
    }
}