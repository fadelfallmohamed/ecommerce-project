<?php

namespace App\Helpers;

class NumberToWordsHelper
{
    /**
     * Convertit un nombre en lettres (en français)
     *
     * @param float $number Le nombre à convertir
     * @return string Le nombre en toutes lettres
     */
    public static function convert($number)
    {
        $hyphen      = '-'; // Trait d'union
        $conjunction = ' et '; // Conjonction pour les unités
        $separator   = ' '; // Séparateur entre les mots
        $negative    = 'moins '; // Moins pour les nombres négatifs
        $decimal     = ' virgule '; // Séparateur décimal
        
        // Tableaux des nombres en lettres
        $dictionary  = [
            0                   => 'zéro',
            1                   => 'un',
            2                   => 'deux',
            3                   => 'trois',
            4                   => 'quatre',
            5                   => 'cinq',
            6                   => 'six',
            7                   => 'sept',
            8                   => 'huit',
            9                   => 'neuf',
            10                  => 'dix',
            11                  => 'onze',
            12                  => 'douze',
            13                  => 'treize',
            14                  => 'quatorze',
            15                  => 'quinze',
            16                  => 'seize',
            20                  => 'vingt',
            30                  => 'trente',
            40                  => 'quarante',
            50                  => 'cinquante',
            60                  => 'soixante',
            70                  => 'soixante-dix',
            80                  => 'quatre-vingt',
            90                  => 'quatre-vingt-dix',
            100                 => 'cent',
            1000                => 'mille',
            1000000             => 'million',
            1000000000          => 'milliard',
            1000000000000       => 'billion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        ];

        // Vérification du signe
        if ($number < 0) {
            return $negative . self::convert(abs($number));
        }
        
        $string = $fraction = null;
        
        // Gestion des décimales
        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number, 2);
            $fraction = (int) (rtrim($fraction, '0') ?: '0');
        }
        
        // Conversion du nombre entier
        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
                
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
                
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[100];
                if ($hundreds > 1) {
                    $string = $dictionary[$hundreds] . ' ' . $string;
                }
                if ($remainder) {
                    $string .= ' ' . self::convert($remainder);
                }
                break;
                
            default:
                $baseUnit = 1000 ** (int) log($number, 1000);
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                
                if ($baseUnit == 1000) {
                    $string = self::convert($numBaseUnits) . ' ' . $dictionary[1000];
                } else {
                    $string = self::convert($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                    if ($numBaseUnits > 1) {
                        $string .= 's'; // Pluriel pour les millions, milliards, etc.
                    }
                }
                
                if ($remainder) {
                    $string .= ' ' . self::convert($remainder);
                }
                break;
        }
        
        // Ajout des décimales si nécessaire
        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $string .= self::convert($fraction);
        }
        
        return $string;
    }
}
