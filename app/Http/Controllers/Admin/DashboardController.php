<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord administrateur avec les indicateurs clés
     *
     * @return \Illuminate\View\View
     */
    /**
     * Retourne la classe CSS du badge en fonction du statut de la commande
     *
     * @param string $status
     * @return string
     */
    private function getStatusBadge($status)
    {
        $badges = [
            'en_attente' => 'bg-warning',
            'en_cours_de_traitement' => 'bg-info',
            'en_cours_de_livraison' => 'bg-primary',
            'livrée' => 'bg-success',
            'annulée' => 'bg-danger',
            'remboursée' => 'bg-secondary',
        ];

        return $badges[$status] ?? 'bg-secondary';
    }

    /**
     * Affiche le tableau de bord administrateur avec les indicateurs clés
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Date de début et de fin pour les calculs mensuels
            $startOfMonth = now()->startOfMonth();
            $endOfMonth = now()->endOfMonth();
            $lastMonthStart = now()->subMonth()->startOfMonth();
            $lastMonthEnd = now()->subMonth()->endOfMonth();
            
            // Chiffre d'affaires total
            $totalRevenue = Order::where('status', '!=', 'annulée')
                ->sum('total') ?? 0;
                
            // Chiffre d'affaires du mois
            $monthlyRevenue = Order::where('status', '!=', 'annulée')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('total') ?? 0;
                
            // Chiffre d'affaires du mois dernier
            $lastMonthRevenue = Order::where('status', '!=', 'annulée')
                ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
                ->sum('total') ?? 0;

            // Nombre total de commandes
            $totalOrders = Order::count();
            
            // Commandes du mois
            $monthlyOrders = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
                
            // Commandes du mois dernier
            $lastMonthOrders = Order::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
                ->count();
                
            // Variation du nombre de commandes
            $orderGrowth = $lastMonthOrders > 0 
                ? (($monthlyOrders - $lastMonthOrders) / $lastMonthOrders) * 100 
                : 100;

            // Produits les plus vendus
            $query = OrderItem::select(
                    'product_id',
                    'products.name',
                    'products.main_image as image',
                    DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_quantity'),
                    DB::raw('COALESCE(SUM(order_items.price * order_items.quantity), 0) as total_revenue')
                )
                ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
                ->groupBy('product_id', 'products.name', 'products.main_image')
                ->orderBy('total_quantity', 'desc')
                ->take(5);
                
            // Log de la requête SQL brute
            \Log::info('Requête produits les plus vendus:', [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);
            
            $bestSellingProducts = $query->get()
                ->map(function($item) {
                    $item->total_quantity = (int)$item->total_quantity;
                    $item->total_revenue = (float)$item->total_revenue;
                    $item->image_url = $item->image ? asset('storage/' . $item->image) : asset('images/default-product.png');
                    return $item;
                });
                
            // Log des résultats
            \Log::info('Résultats produits les plus vendus:', $bestSellingProducts->toArray());

            // Dernières commandes
            $recentOrders = Order::with(['user', 'items.product'])
                ->latest()
                ->take(5)
                ->get()
                ->map(function($order) {
                    $order->formatted_date = $order->created_at->format('d/m/Y H:i');
                    $order->status_badge = $this->getStatusBadge($order->status);
                    return $order;
                });

            // Statistiques des commandes par statut
            $orderStats = Order::select(
                    'status',
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();

            // Valeurs par défaut pour les statuts de commande
            $defaultStatuses = [
                'en_attente' => 0,
                'en_cours_de_livraison' => 0,
                'livrée' => 0,
                'annulée' => 0
            ];
            
            // Fusionner avec les valeurs par défaut
            $orderStats = array_merge($defaultStatuses, $orderStats);

            // Chiffre d'affaires des 6 derniers mois
            $revenueLastSixMonths = Order::select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('COALESCE(SUM(total), 0) as total')
                )
                ->where('created_at', '>=', now()->subMonths(6))
                ->where('status', '!=', 'annulée')
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->map(function($item) {
                    $item->total = (float)$item->total;
                    return $item;
                });

            // S'assurer qu'il y a des données pour les 6 derniers mois
            $months = collect(range(0, 5))->map(function ($months) {
                return now()->subMonths($months)->format('Y-m');
            })->reverse();

            $revenueData = [];
            foreach ($months as $month) {
                $revenue = $revenueLastSixMonths->firstWhere('month', $month);
                $revenueData[] = [
                    'month' => Carbon::createFromFormat('Y-m', $month)->format('M Y'),
                    'total' => $revenue ? $revenue->total : 0
                ];
            }

            // Récupérer les dernières commandes
            $recentOrders = Order::with(['user', 'items'])
                ->latest()
                ->take(5)
                ->get()
                ->map(function($order) {
                    $order->formatted_date = $order->created_at->format('d/m/Y H:i');
                    $order->status_badge = $this->getStatusBadge($order->status);
                    return $order;
                });

            return view('admin.dashboard', [
                'totalRevenue' => $totalRevenue,
                'totalOrders' => $totalOrders,
                'monthlyOrders' => $monthlyOrders,
                'bestSellingProducts' => $bestSellingProducts,
                'recentOrders' => $recentOrders,
                'orderStats' => $orderStats,
                'revenueLastSixMonths' => collect($revenueData)
            ]);
            
        } catch (\Exception $e) {
            // En cas d'erreur, retourner des valeurs par défaut
            \Log::error('Erreur dans le tableau de bord admin: ' . $e->getMessage());
            
            return view('admin.dashboard', [
                'totalRevenue' => 0,
                'totalOrders' => 0,
                'monthlyOrders' => 0,
                'bestSellingProducts' => collect(),
                'recentOrders' => collect(),
                'orderStats' => [
                    'en_attente' => 0,
                    'en_cours_de_livraison' => 0,
                    'livrée' => 0,
                    'annulée' => 0
                ],
                'revenueLastSixMonths' => collect(range(0, 5))->map(function ($months) {
                    return [
                        'month' => now()->subMonths(5 - $months)->format('Y-m'),
                        'total' => 0
                    ];
                })
            ]);
        }
    }
}
