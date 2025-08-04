@extends('admin.layouts.app')

@section('title', 'Tableau de bord des stocks')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Tableau de bord des stocks</h4>
                <div class="page-title-right">
                    <a href="{{ route('admin.stocks.index') }}" class="btn btn-soft-primary">
                        <i class="ri-list-check-2 align-middle me-1"></i> Voir tous les stocks
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Produits en stock</p>
                            <h4 class="mb-2">{{ $stockStats['total_products'] }}</h4>
                            <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2">
                                <i class="ri-arrow-right-up-line me-1 align-middle"></i></span>
                                Gestion des stocks
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary text-primary rounded-3">
                                <i class="ri-stack-line font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">En stock</p>
                            <h4 class="mb-2">{{ $stockStats['in_stock'] }}</h4>
                            <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2">
                                <i class="ri-checkbox-circle-line me-1 align-middle"></i>Bon niveau</span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success text-success rounded-3">
                                <i class="ri-checkbox-circle-line font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Stock bas</p>
                            <h4 class="mb-2">{{ $stockStats['low_stock'] }}</h4>
                            <p class="text-muted mb-0"><span class="text-warning fw-bold font-size-12 me-2">
                                <i class="ri-alert-line me-1 align-middle"></i>À réapprovisionner</span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning text-warning rounded-3">
                                <i class="ri-alert-line font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Rupture de stock</p>
                            <h4 class="mb-2">{{ $stockStats['out_of_stock'] }}</h4>
                            <p class="text-muted mb-0"><span class="text-danger fw-bold font-size-12 me-2">
                                <i class="ri-close-circle-line me-1 align-middle"></i>Urgent</span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-danger text-danger rounded-3">
                                <i class="ri-close-circle-line font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Produits à réapprovisionner -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Produits à réapprovisionner</h4>
                        <a href="{{ route('admin.stocks.index', ['status' => 'low_stock']) }}" class="btn btn-sm btn-soft-primary">
                            Voir tout <i class="ri-arrow-right-line align-middle"></i>
                        </a>
                    </div>
                    
                    @if($lowStockItems->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-centered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produit</th>
                                        <th>Stock actuel</th>
                                        <th>Seuil d'alerte</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lowStockItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->product->image)
                                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="" class="avatar-xs rounded me-2">
                                                    @else
                                                        <div class="avatar-xs bg-soft-primary rounded me-2">
                                                            <span class="avatar-title">{{ substr($item->product->name, 0, 2) }}</span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0 font-size-14">{{ $item->product->name }}</h6>
                                                        <small class="text-muted">{{ $item->sku ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge {{ $item->isOutOfStock() ? 'bg-danger' : 'bg-warning' }}">
                                                    {{ $item->quantity }} unités
                                                </span>
                                            </td>
                                            <td>{{ $item->alert_quantity }} unités</td>
                                            <td>
                                                <a href="{{ route('admin.stocks.adjust', $item) }}" class="btn btn-sm btn-soft-primary" data-bs-toggle="tooltip" title="Ajuster le stock">
                                                    <i class="ri-add-line"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="text-muted">
                                <i class="ri-checkbox-circle-line display-5"></i>
                                <h5 class="mt-2 mb-0">Aucun produit à réapprovisionner</h5>
                                <p class="mb-0">Tous vos produits sont correctement approvisionnés.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Dernières mises à jour de stock -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Dernières mises à jour</h4>
                        <a href="{{ route('admin.stocks.index') }}" class="btn btn-sm btn-soft-primary">
                            Voir tout <i class="ri-arrow-right-line align-middle"></i>
                        </a>
                    </div>
                    
                    @if($recentlyUpdated->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-centered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produit</th>
                                        <th>Quantité</th>
                                        <th>Statut</th>
                                        <th>Mis à jour</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentlyUpdated as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->product->image)
                                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="" class="avatar-xs rounded me-2">
                                                    @else
                                                        <div class="avatar-xs bg-soft-primary rounded me-2">
                                                            <span class="avatar-title">{{ substr($item->product->name, 0, 2) }}</span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0 font-size-14">{{ $item->product->name }}</h6>
                                                        <small class="text-muted">{{ $item->sku ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-medium">{{ $item->quantity }} unités</span>
                                            </td>
                                            <td>
                                                @if($item->isOutOfStock())
                                                    <span class="badge bg-danger">Rupture</span>
                                                @elseif($item->isLow())
                                                    <span class="badge bg-warning">Stock bas</span>
                                                @else
                                                    <span class="badge bg-success">En stock</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-nowrap">
                                                    {{ $item->updated_at->diffForHumans() }}
                                                </div>
                                                <small class="text-muted">
                                                    par {{ $item->updatedBy ? $item->updatedBy->name : 'Système' }}
                                                </small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="text-muted">
                                <i class="ri-information-line display-5"></i>
                                <h5 class="mt-2 mb-0">Aucune mise à jour récente</h5>
                                <p class="mb-0">Les mises à jour de stock apparaîtront ici.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques et statistiques -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Statistiques des stocks</h4>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-secondary">7j</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary active">30j</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">90j</button>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="chart-container" style="position: relative; height: 300px;">
                                <!-- Le graphique sera inséré ici par JavaScript -->
                                <canvas id="stockMovementChart"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mt-4 mt-md-0">
                                <h5 class="font-size-16 mb-3">Répartition des stocks</h5>
                                <div class="chart-container" style="position: relative; height: 250px;">
                                    <!-- Le graphique en camembert sera inséré ici par JavaScript -->
                                    <canvas id="stockDistributionChart"></canvas>
                                </div>
                                <div class="mt-3 text-center">
                                    <div class="d-flex justify-content-center gap-4">
                                        <div>
                                            <span class="badge bg-success me-1"></span>
                                            <span class="text-muted">En stock</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-warning me-1"></span>
                                            <span class="text-muted">Stock bas</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-danger me-1"></span>
                                            <span class="text-muted">Rupture</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
document.addEventListener('DOMContentLoaded', function() {
    // Activer les tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Données factices pour les graphiques (à remplacer par des données réelles du contrôleur)
    var stockMovementData = {
        labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30'],
        datasets: [
            {
                label: 'Entrées de stock',
                data: [12, 19, 3, 5, 2, 3, 15, 7, 8, 9, 10, 12, 15, 10, 8, 12, 10, 14, 12, 11, 9, 13, 15, 10, 12, 11, 9, 7, 8, 10],
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Sorties de stock',
                data: [8, 15, 2, 4, 3, 5, 12, 5, 7, 6, 8, 10, 12, 8, 6, 10, 8, 12, 10, 9, 7, 11, 13, 8, 10, 9, 7, 5, 6, 8],
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.4,
                fill: true
            }
        ]
    };

    var stockDistributionData = {
        labels: ['En stock', 'Stock bas', 'Rupture'],
        datasets: [{
            data: [65, 25, 10],
            backgroundColor: [
                'rgba(40, 167, 69, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(220, 53, 69, 0.8)'
            ],
            borderColor: [
                'rgba(40, 167, 69, 1)',
                'rgba(255, 193, 7, 1)',
                'rgba(220, 53, 69, 1)'
            ],
            borderWidth: 1
        }]
    };

    // Options communes pour les graphiques
    var chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleFont: { size: 14 },
                bodyFont: { size: 13 },
                padding: 12,
                usePointStyle: true,
                callbacks: {
                    label: function(context) {
                        var label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed.y !== null) {
                            label += context.parsed.y + ' mouvements';
                        }
                        return label;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    display: true,
                    color: 'rgba(0, 0, 0, 0.05)'
                },
                ticks: {
                    stepSize: 5
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    };

    // Graphique des mouvements de stock (ligne)
    var stockMovementCtx = document.getElementById('stockMovementChart').getContext('2d');
    var stockMovementChart = new Chart(stockMovementCtx, {
        type: 'line',
        data: stockMovementData,
        options: {
            ...chartOptions,
            plugins: {
                ...chartOptions.plugins,
                title: {
                    display: true,
                    text: 'Mouvements de stock sur 30 jours',
                    font: {
                        size: 16
                    },
                    padding: {
                        bottom: 20
                    }
                }
            }
        }
    });

    // Graphique de répartition des stocks (camembert)
    var stockDistributionCtx = document.getElementById('stockDistributionChart').getContext('2d');
    var stockDistributionChart = new Chart(stockDistributionCtx, {
        type: 'doughnut',
        data: stockDistributionData,
        options: {
            ...chartOptions,
            cutout: '70%',
            plugins: {
                ...chartOptions.plugins,
                legend: {
                    ...chartOptions.plugins.legend,
                    position: 'bottom'
                },
                tooltip: {
                    ...chartOptions.plugins.tooltip,
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            var value = context.raw || 0;
                            var total = context.dataset.data.reduce((a, b) => a + b, 0);
                            var percentage = Math.round((value / total) * 100);
                            return `${label}: ${percentage}% (${value} produits)`;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
