@extends('layouts.app')

@php
    $cartCount = $cartCount ?? 0; // Définit une valeur par défaut si $cartCount n'est pas défini
@endphp

@section('content')
<div class="container py-5">
    <!-- En-tête de bienvenue personnalisé -->
    <div class="welcome-card bg-gradient-primary text-white rounded-4 p-4 p-lg-5 mb-5 shadow-sm">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-5 fw-bold mb-3">Bonjour, {{ Auth::user()->prenom }} !</h1>
                <p class="lead mb-0">Bienvenue sur votre espace personnel. Gérez facilement vos commandes et vos informations.</p>
            </div>
            <div class="col-md-4 text-md-end mt-4 mt-md-0">
                <div class="bg-white bg-opacity-25 d-inline-block p-3 rounded-circle">
                    <i class="fas fa-user-circle fa-4x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Carte des commandes récentes -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Vos commandes récentes</h5>
                        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                    </div>
                </div>
                <div class="card-body">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° Commande</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                            <td>{{ number_format($order->total, 2, ',', ' ') }} €</td>
                                            <td>
                                                @php
                                                    $statusClasses = [
                                                        'en_attente' => 'bg-warning text-dark',
                                                        'en_cours_de_livraison' => 'bg-info text-white',
                                                        'livrée' => 'bg-success text-white',
                                                        'annulée' => 'bg-danger text-white',
                                                        'default' => 'bg-secondary text-white'
                                                    ];
                                                    $statusText = [
                                                        'en_attente' => 'En attente',
                                                        'en_cours_de_livraison' => 'En cours',
                                                        'livrée' => 'Livrée',
                                                        'annulée' => 'Annulée',
                                                        'default' => 'Inconnu'
                                                    ];
                                                    $status = $order->status ?? 'default';
                                                    $statusClass = $statusClasses[$status] ?? $statusClasses['default'];
                                                    $statusText = $statusText[$status] ?? $statusText['default'];
                                                @endphp
                                                <span class="badge {{ $statusClass }} px-2 py-1">{{ $statusText }}</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i> Voir
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-shopping-cart fa-3x text-muted"></i>
                            </div>
                            <h5 class="mb-3">Aucune commande récente</h5>
                            <p class="text-muted mb-4">Parcourez notre catalogue et découvrez nos produits.</p>
                            <a href="{{ route('catalogue.index') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-bag me-2"></i>Découvrir nos produits
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Carte de profil et actions rapides -->
        <div class="col-lg-4">
            <!-- Carte de profil -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-4">
                    <div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-user-circle fa-3x"></i>
                    </div>
                    <h5 class="mb-1">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</h5>
                    <p class="text-muted mb-3">{{ Auth::user()->email }}</p>
                    <a href="{{ route('profile.show') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-user-edit me-2"></i>Modifier mon profil
                    </a>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold">Actions rapides</h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('catalogue.index') }}" class="btn btn-outline-primary text-start">
                            <i class="fas fa-shopping-bag me-2"></i>Voir le catalogue
                        </a>
                        @if($cartCount > 0)
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-primary text-start">
                                <i class="fas fa-shopping-cart me-2"></i>Voir mon panier ({{ $cartCount }})
                            </a>
                        @endif
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-primary text-start">
                            <i class="fas fa-address-card me-2"></i>Mes informations personnelles
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .welcome-card {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        overflow: hidden;
        position: relative;
    }
    
    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        transform: rotate(30deg);
    }
    
    .card {
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .table th {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-top: none;
        border-bottom: 2px solid #f1f5f9;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
    }
    
    .btn-outline-primary {
        border-width: 1.5px;
    }
</style>

@if(session('success'))
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toast = new bootstrap.Toast(document.getElementById('successToast'));
                toast.show();
            });
        </script>
    @endpush
@endif

<!-- Toast de succès -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endsection