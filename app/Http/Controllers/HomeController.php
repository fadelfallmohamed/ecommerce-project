<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
            try {
                // Récupérer les 5 commandes les plus récentes de l'utilisateur
                $recentOrders = Order::where('user_id', auth()->id())
                    ->with(['items.product'])
                    ->latest()
                    ->take(5)
                    ->get();

                // Récupérer le panier depuis la session
                $cart = Session::get('cart', []);
                $cartCount = array_sum(array_column($cart, 'quantity'));
                
                return view('dashboard', [
                    'recentOrders' => $recentOrders,
                    'cartCount' => $cartCount
                ]);
            } catch (\Exception $e) {
                // En cas d'erreur, logger l'erreur et retourner une vue avec un message d'erreur
                \Log::error('Erreur dans HomeController@index: ' . $e->getMessage());
                return view('dashboard', [
                    'recentOrders' => collect(),
                    'cartCount' => 0,
                    'error' => $e->getMessage()
                ]);
            }
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
