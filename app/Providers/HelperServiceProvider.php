<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\NumberToWordsHelper;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Enregistre les services de l'application.
     *
     * @return void
     */
    public function register()
    {
        // Inclure le fichier helper de formatage des prix
        require_once app_path('Helpers/CurrencyHelper.php');
        
        // Enregistre la fonction helper numberToWords
        if (!function_exists('numberToWords')) {
            function numberToWords($number)
            {
                return NumberToWordsHelper::convert($number);
            }
        }
    }

    /**
     * Bootstrap les services de l'application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
