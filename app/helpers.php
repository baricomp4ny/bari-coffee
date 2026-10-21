<?php

if (! function_exists('rupiah')) {
    /**
     * Format angka menjadi Rupiah, mis. 22000 -> "Rp22.000".
     */
    function rupiah(int $amount): string
    {
        return 'Rp'.number_format($amount, 0, ',', '.');
    }
}
