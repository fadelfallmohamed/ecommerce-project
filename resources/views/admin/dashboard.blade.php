@extends('layouts.admin')

@section('title', 'Tableau de bord Administrateur')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    :root {
        --primary: #4361ee;
        --primary-light: #eef2ff;
        --secondary: #3f37c9;
        --success: #10b981;
        --success-light: #d1fae5;
        --info: #3b82f6;
        --info-light: #dbeafe;
        --warning: #f59e0b;
        --warning-light: #fef3c7;
        --danger: #ef4444;
        --light: #f8fafc;
        --dark: #1e293b;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-600: #475569;
        --gray-700: #334155;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    body {
        background-color: #f8fafc;
        color: #475569;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    }
    
    .card {
        border: none;
        border-radius: 0.75rem;
        box-shadow: var(--shadow);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
        overflow: hidden;
        position: relative;
    }
    
    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }
    
    .card:hover::before {
        opacity: 1;
    }
    
    .card-header {
        background: white;
        border-bottom: 1px solid var(--gray-200);
        padding: 1.25rem 1.5rem;
        border-top-left-radius: 0.75rem !important;
        border-top-right-radius: 0.75rem !important;
    }
    
    .card-title {
        font-weight: 600;
        color: var(--gray-700);
        font-size: 1.125rem;
        margin-bottom: 0;
        letter-spacing: -0.01em;
    }
<style>
    .stat-card {
        border-radius: 0.75rem;
        border: none;
        box-shadow: var(--shadow);
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-left: 4px solid;
        background: white;
        height: 100%;
    }
    
    .stat-card.primary { 
        border-color: var(--primary);
        background: linear-gradient(135deg, #f8faff 0%, #f0f4ff 100%);
    }
    
    .stat-card.success { 
        border-color: var(--success);
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    }
    
    .stat-card.warning { 
        border-color: var(--warning);
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    }
    
    .stat-card.info { 
        border-color: var(--info);
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    }
    
    .stat-card .stat-icon {
        position: absolute;
        right: 1.5rem;
        top: 1.5rem;
        font-size: 2.75rem;
        opacity: 0.15;
        color: currentColor;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover .stat-icon {
        transform: scale(1.1);
        opacity: 0.2;
    }
    
    .stat-card .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0.75rem 0 0.25rem;
        color: var(--gray-800);
        letter-spacing: -0.025em;
    }
    
    .stat-card .stat-label {
        font-size: 0.8125rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--gray-500);
        margin-bottom: 0.25rem;
        font-weight: 600;
    }
    
    .stat-card .stat-change {
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-weight: 600;
        margin-top: 0.5rem;
        background-color: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(4px);
        box-shadow: var(--shadow-sm);
    }
    
    .stat-card .stat-change.positive {
        color: #059669;
        background-color: rgba(16, 185, 129, 0.1);
    }
    
    .stat-card .stat-change.negative {
        color: #dc2626;
        background-color: rgba(220, 38, 38, 0.1);
    }
    
    .stat-card .stat-change.positive {
        background-color: rgba(74, 222, 128, 0.1);
        color: #10b981;
    }
    
    .stat-card .stat-change.negative {
        background-color: rgba(248, 113, 113, 0.1);
        color: #ef4444;
    }
    
    /* Indicateurs de légende */
    .legend-indicator {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 8px;
    }
    
    /* Cartes */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .card-header {
        background: white;
        border-bottom: 1px solid #edf2f7;
        padding: 1.25rem 1.5rem;
        border-radius: 12px 12px 0 0 !important;
    }
    
    .card-title {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0;
    }
    
    /* Conteneurs de graphiques */
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête avec animation -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5">
        <div class="mb-3 mb-md-0 animate__animated animate__fadeInLeft">
            <h1 class="h3 mb-1 fw-bold text-gray-800">Tableau de bord</h1>
            <p class="text-muted mb-0">Aperçu des performances et des statistiques</p>
            <p class="text-muted mb-0">Aperçu des performances et des statistiques</p>
        </div>
        <div>
            <button class="btn btn-primary d-flex align-items-center">
                <i class='bx bx-download mr-2'></i>
                <span>Exporter le rapport</span>
            </button>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row g-4 mb-4">
        <!-- Chiffre d'affaires -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card primary h-100">
                <div class="stat-label">Chiffre d'affaires</div>
                <div class="stat-value">{{ number_format($totalRevenue, 0, ',', ' ') }} €</div>
                <div class="stat-change positive mt-2">
                    <i class='bx bx-up-arrow-alt mr-1'></i> 24% ce mois-ci
                </div>
                <i class='bx bx-euro stat-icon'></i>
            </div>
        </div>

        <!-- Nombre total de commandes -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card success h-100">
                <div class="stat-label">Commandes totales</div>
                <div class="stat-value">{{ number_format($totalOrders, 0, ',', ' ') }}</div>
                <div class="stat-change positive mt-2">
                    <i class='bx bx-up-arrow-alt mr-1'></i> 12% ce mois-ci
                </div>
                <i class='bx bx-cart-alt stat-icon'></i>
            </div>
        </div>

        <!-- Commandes du mois -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card warning h-100">
                <div class="stat-label">Commandes ce mois-ci</div>
                <div class="stat-value">{{ number_format($monthlyOrders, 0, ',', ' ') }}</div>
                @php
                    $orderGrowth = $monthlyOrders > 0 ? round(($monthlyOrders / max(1, $monthlyOrders - 5)) * 100 - 100) : 0;
                    $isPositive = $orderGrowth >= 0;
                @endphp
                <div class="stat-change {{ $isPositive ? 'positive' : 'negative' }} mt-2">
                    <i class='bx {{ $isPositive ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }} mr-1'></i>
                    {{ abs($orderGrowth) }}% vs mois dernier
                </div>
                <i class='bx bx-calendar stat-icon'></i>
            </div>
        </div>

        <!-- Panier moyen -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card info h-100">
                <div class="stat-label">Panier moyen</div>
                <div class="stat-value">{{ $totalOrders > 0 ? number_format($totalRevenue / $totalOrders, 2, ',', ' ') : '0,00' }} €</div>
                <div class="stat-change positive mt-2">
                    <i class='bx bx-up-arrow-alt mr-1'></i> 8% ce mois-ci
                </div>
                <i class='bx bx-shopping-bag stat-icon'></i>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <!-- Graphique d'évolution du chiffre d'affaires -->
        <div class="col-xl-8">
            <div class="card h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center bg-white">
                    <h5 class="card-title mb-2 mb-md-0">Évolution du chiffre d'affaires</h5>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-secondary">Mois</button>
                        <button type="button" class="btn btn-outline-secondary active">Trimestre</button>
                        <button type="button" class="btn btn-outline-secondary">Année</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Répartition des commandes -->
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Statut des commandes</h5>
                </div>
                <div class="card-body d-flex flex-column">
                    @if(array_sum($orderStats) > 0)
                        <div class="chart-container" style="min-height: 200px; flex: 1;">
                            <canvas id="orderStatusChart"></canvas>
                        </div>
                        <div class="mt-4">
                            @foreach($orderStats as $status => $count)
                                @if($count > 0)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center">
                                            <span class="legend-indicator" style="background-color: {{ $status == 'en_attente' ? '#4e73df' : ($status == 'traitement' ? '#f6c23e' : ($status == 'expediee' ? '#36b9cc' : ($status == 'livree' ? '#1cc88a' : '#e74a3b'))) }}"></span>
                                            <span class="text-muted ml-2">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                                        </div>
                                        <span class="font-weight-bold">{{ $count }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">Aucune commande trouvée</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Produits les plus vendus -->
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Produits les plus vendus</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="productsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class='bx bx-dots-horizontal-rounded'></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="productsDropdown">
                            <li><a class="dropdown-item" href="#"><i class='bx bx-download mr-2'></i>Exporter en CSV</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.products.index') }}"><i class='bx bx-list-ul mr-2'></i>Voir tous les produits</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if(isset($bestSellingProducts) && count($bestSellingProducts) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-gray-50">
                                    <tr class="text-uppercase text-xs font-weight-bold text-muted">
                                        <th class="ps-4 py-3 text-start">Produit</th>
                                        <th class="text-end pe-4 py-3">Prix</th>
                                        <th class="text-end pe-4 py-3">Ventes</th>
                                        <th class="text-end pe-4 py-3">Revenu</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($bestSellingProducts as $product)
                                        <tr class="border-bottom border-light">
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    @if($product->image)
                                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="rounded-2 me-3" width="40" height="40" style="object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded-2 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                            <i class='bx bx-package text-muted'></i>
                                                        </div>
                                                    @endif
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-0 text-sm">{{ $product->name }}</h6>
                                                        <span class="text-xs text-muted">#{{ $product->id }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end pe-4 py-3">
                                                <span class="text-sm font-weight-bold">{{ number_format($product->price, 2, ',', ' ') }} €</span>
                                            </td>
                                            <td class="text-end pe-4 py-3">
                                                <span class="badge bg-primary bg-opacity-10 text-primary">{{ $product->total_quantity }}</span>
                                            </td>
                                            <td class="text-end pe-4 py-3">
                                                <span class="text-sm font-weight-bold">{{ number_format($product->total_revenue, 2, ',', ' ') }} €</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-3">
                                <i class='bx bx-package text-2xl'></i>
                            </div>
                            <p class="text-gray-500">Aucun produit vendu pour le moment</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Dernières commandes -->
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Dernières commandes</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class='bx bx-list-ul mr-1'></i> Voir tout
                    </a>
                </div>
                <div class="card-body p-0">
                    @if(count($recentOrders) > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($recentOrders as $order)
                                <div class="p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="font-medium text-gray-900">
                                            Commande #{{ $order->id }}
                                            @if($order->user)
                                                <span class="text-sm font-normal text-gray-500 ml-2">
                                                    par {{ $order->user->name }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                                $order->status == 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 
                                                ($order->status == 'traitement' ? 'bg-blue-100 text-blue-800' : 
                                                ($order->status == 'expediee' ? 'bg-indigo-100 text-indigo-800' : 
                                                ($order->status == 'livree' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'))) 
                                            }}">
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="text-lg font-semibold text-gray-900 mr-4">
                                                {{ number_format($order->total, 2, ',', ' ') }} €
                                            </span>
                                            <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">
                                                <i class='bx bx-chevron-right text-xl'></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-3">
                                <i class='bx bx-receipt text-2xl'></i>
                            </div>
                            <p class="text-gray-500">Aucune commande récente</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Chiffre d'affaires sur 6 mois
var revenueChartEl = document.getElementById('revenueChart');
if (revenueChartEl) {
    var ctx = revenueChartEl.getContext('2d');
    var revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($revenueLastSixMonths->pluck('month')) !!},
        datasets: [{
            label: "Chiffre d'affaires",
            lineTension: 0.3,
            backgroundColor: "rgba(78, 115, 223, 0.05)",
            borderColor: "rgba(78, 115, 223, 1)",
            pointRadius: 3,
            pointBackgroundColor: "rgba(78, 115, 223, 1)",
            pointBorderColor: "rgba(78, 115, 223, 1)",
            pointHoverRadius: 3,
            pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
            pointHoverBorderColor: "rgba(78, 115, 223, 1)",
            pointHitRadius: 10,
            pointBorderWidth: 2,
            data: {!! json_encode($revenueLastSixMonths->pluck('total')) !!},
        }],
    },
    options: {
        maintainAspectRatio: false,
        layout: {
            padding: {
                left: 10,
                right: 25,
                top: 25,
                bottom: 0
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value + ' €';
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Chiffre d\'affaires: ' + context.parsed.y.toFixed(2) + ' €';
                    }
                }
            }
        }
    });
}

// Répartition des commandes par statut
var orderStatusChartEl = document.getElementById('orderStatusChart');
if (orderStatusChartEl) {
    var orderStatusChart = new Chart(orderStatusChartEl, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(collect($orderStats)->keys()->map(function($status) { 
            return ucfirst(str_replace('_', ' ', $status)); 
        })) !!},
        datasets: [{
            data: {!! json_encode(collect($orderStats)->values()) !!},
            backgroundColor: ['#f6c23e', '#36b9cc', '#1cc88a', '#e74a3b'],
            hoverBackgroundColor: ['#f8d06b', '#5bc0de', '#4fd1c5', '#ee5f5b'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
        }],
    },
    options: {
        maintainAspectRatio: false,
        plugins: {
            tooltip: {
                backgroundColor: "rgb(255,255,255)",
                bodyColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                padding: 10,
                callbacks: {
                    label: function(context) {
                        var label = context.label || '';
                        var value = context.raw || 0;
                        var total = context.dataset.data.reduce((a, b) => a + b, 0);
                        var percentage = Math.round((value / total) * 100);
                        return label + ': ' + value + ' (' + percentage + '%)';
                    }
                }
            },
            legend: {
                display: false
            },
        },
        cutout: '70%',
    },
});
}
if ($orderStats->isEmpty()) {
    document.getElementById('orderStatusChart').style.display = 'none';
}
</script>
@endpush
