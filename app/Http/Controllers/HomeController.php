<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil avec les produits populaires
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Vérifier si l'utilisateur est connecté pour afficher le tableau de bord personnalisé
        if (auth()->check()) {
            // Récupérer les 5 commandes les plus récentes de l'utilisateur
            $recentOrders = Order::where('user_id', auth()->id())
                ->with(['items.product'])
                ->latest()
                ->take(5)
                ->get();

            return view('dashboard', [
                'recentOrders' => $recentOrders
            ]);
        }

        // Pour les utilisateurs non connectés, afficher la page d'accueil standard
        $featuredProducts = Product::with('categories')
            ->latest()
            ->take(8)
            ->get();

        return view('welcome', [
            'featured_products' => $featuredProducts
        ]);
    }
}
