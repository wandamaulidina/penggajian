<?php

if (!function_exists('rupiah_format')) {
    function rupiah_format($angka)
    {
        $rupiah = number_format($angka, 0, ',', '.');
        return "Rp " . $rupiah;
    }
}
