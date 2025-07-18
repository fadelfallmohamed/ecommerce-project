<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil avec les produits populaires
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupérer les produits marqués comme populaires (par exemple, avec une note moyenne élevée ou un nombre de vues)
        // Pour l'instant, on prend simplement les 8 derniers produits ajoutés
        $featuredProducts = Product::with('categories')
            ->latest()
            ->take(8)
            ->get();

        return view('welcome', [
            'featured_products' => $featuredProducts
        ]);
    }
}
