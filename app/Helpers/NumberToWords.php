<?php

if (!function_exists('numberToWords')) {
    /**
     * Convertit un nombre en lettres (version simplifiée en français)
     *
     * @param float $number Le nombre à convertir
     * @return string Le nombre en toutes lettres
     */
    function numberToWords($number) {
        $decimal = round($number - floor($number), 2);
        $entier = (int)$number;
        
        $units = ['', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf'];
        $teens = ['dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize', 'dix-sept', 'dix-huit', 'dix-neuf'];
        $tens = ['', 'dix', 'vingt', 'trente', 'quarante', 'cinquante', 'soixante', 'soixante-dix', 'quatre-vingt', 'quatre-vingt-dix'];
        
        $result = '';
        
        // Gestion des nombres négatifs
        if ($entier < 0) {
            $result .= 'moins ';
            $entier = abs($entier);
        }
        
        // Conversion du nombre entier
        if ($entier === 0) {
            $result = 'zéro';
        } else {
            $result .= convertNumberToWords($entier);
        }
        
        // Ajout des centimes si nécessaire
        if ($decimal > 0) {
            $result .= ' virgule ';
            $cents = (int)($decimal * 100);
            $result .= convertNumberToWords($cents);
        }
        
        return ucfirst(trim($result));
    }
    
    /**
     * Convertit un nombre en lettres (fonction récursive)
     *
     * @param int $number Le nombre à convertir
     * @param bool $useAnd Si vrai, ajoute un "et" avant "un" (pour 21, 31, etc.)
     * @return string Le nombre en toutes lettres
     */
    function convertNumberToWords($number, $useAnd = false) {
        $units = ['', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf'];
        $teens = ['dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize', 'dix-sept', 'dix-huit', 'dix-neuf'];
        $tens = ['', 'dix', 'vingt', 'trente', 'quarante', 'cinquante', 'soixante', 'soixante-dix', 'quatre-vingt', 'quatre-vingt-dix'];
        
        if ($number < 10) {
            return $number === 1 && $useAnd ? 'et-un' : $units[$number];
        } elseif ($number < 20) {
            return $teens[$number - 10];
        } elseif ($number < 100) {
            $tensIndex = (int)($number / 10);
            $remainder = $number % 10;
            $separator = '';
            
            // Cas spéciaux pour 71, 72, 73, 74, 75, 76, 77, 78, 79, 91, 92, 93, 94, 95, 96, 97, 98, 99
            if (($tensIndex === 7 || $tensIndex === 9) && $remainder > 0) {
                $tensIndex--;
                $remainder += 10;
            }
            
            if ($remainder === 0) {
                return $tens[$tensIndex];
            } elseif ($remainder === 1) {
                $separator = $tensIndex === 8 ? '-' : '-et-';
                return $tens[$tensIndex] . $separator . 'un';
            } else {
                $separator = $tensIndex === 8 && $remainder === 0 ? 's' : '-';
                return $tens[$tensIndex] . $separator . $units[$remainder];
            }
        } elseif ($number < 1000) {
            $hundreds = (int)($number / 100);
            $remainder = $number % 100;
            $result = $hundreds === 1 ? 'cent' : $units[$hundreds] . ' cent';
            
            if ($remainder > 0) {
                $result .= ' ' . convertNumberToWords($remainder);
            } elseif ($hundreds > 1) {
                $result .= 's'; // Pour "deux cents", "trois cents", etc.
            }
            
            return $result;
        } elseif ($number < 1000000) {
            $thousands = (int)($number / 1000);
            $remainder = $number % 1000;
            $result = '';
            
            if ($thousands === 1) {
                $result = 'mille';
            } else {
                $result = convertNumberToWords($thousands) . ' mille';
            }
            
            if ($remainder > 0) {
                $result .= ' ' . convertNumberToWords($remainder);
            }
            
            return $result;
        } else {
            // Pour les nombres plus grands que 999 999, on utilise une version simplifiée
            $millions = (int)($number / 1000000);
            $remainder = $number % 1000000;
            $result = convertNumberToWords($millions) . ' million' . ($millions > 1 ? 's' : '');
            
            if ($remainder > 0) {
                $result .= ' ' . convertNumberToWords($remainder);
            }
            
            return $result;
        }
    }
}
