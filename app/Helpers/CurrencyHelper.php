<?php

if (!function_exists('format_price')) {
    /**
     * Formate un prix en FCFA
     *
     * @param  float  $amount
     * @param  bool  $withCurrency  Ajouter le symbole FCFA
     * @return string
     */
    function format_price($amount, $withCurrency = true)
    {
        $formatted = number_format($amount, 0, ',', ' ');
        
        if ($withCurrency) {
            return $formatted . ' FCFA';
        }
        
        return $formatted;
    }
}

if (!function_exists('convert_euro_to_fcfa')) {
    /**
     * Convertit un montant d'euros en FCFA (1 EUR = 655.957 FCFA)
     *
     * @param  float  $amount
     * @return float
     */
    function convert_euro_to_fcfa($amount)
    {
        return $amount * 655.957;
    }
}
