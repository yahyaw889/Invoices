<?php

if (!function_exists('formatNumberShort')) {
    function formatNumberShort($number , $decimals = 2): string
    {
        if ($number >= 1000000000) {
            return  number_format($number / 1000000000, $decimals) . 'B';
        } elseif ($number >= 1000000) {
            return  number_format($number / 1000000, $decimals) . 'M';
        } elseif ($number >= 1000) {
            return  number_format($number / 1000, $decimals) . 'K';
        }
        return  $number;
    }
}
