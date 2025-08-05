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
        // Récupérer 3 produits en vedette pour la page d'accueil
        $featuredProducts = Product::with('categories')
            ->where('is_featured', true) // Seulement les produits en vedette
            ->latest()
            ->take(3)
            ->get();
            
        // Si moins de 3 produits en vedette, compléter avec les derniers produits
        if ($featuredProducts->count() < 3) {
            $additionalProducts = Product::with('categories')
                ->whereNotIn('id', $featuredProducts->pluck('id'))
                ->latest()
                ->take(3 - $featuredProducts->count())
                ->get();
                
            $featuredProducts = $featuredProducts->merge($additionalProducts);
        }

        // Si l'utilisateur est connecté, récupérer également les informations du panier
        if (auth()->check()) {
            try {
                // Récupérer le panier depuis la session
                $cart = Session::get('cart', []);
                $cartCount = array_sum(array_column($cart, 'quantity'));
                
                // Récupérer les commandes récentes pour la section du tableau de bord
                $recentOrders = Order::where('user_id', auth()->id())
                    ->with(['items.product'])
                    ->latest()
                    ->take(5)
                    ->get();
                
                return view('welcome', [
                    'featured_products' => $featuredProducts,
                    'cartCount' => $cartCount,
                    'recentOrders' => $recentOrders
                ]);
                
            } catch (\Exception $e) {
                // En cas d'erreur, logger l'erreur mais continuer avec les produits en vedette
                \Log::error('Erreur dans HomeController@index: ' . $e->getMessage());
            }
        }

        // Pour les utilisateurs non connectés, afficher la page d'accueil standard
        return view('welcome', [
            'featured_products' => $featuredProducts,
            'cartCount' => 0,
            'recentOrders' => collect()
        ]);
    }
}
