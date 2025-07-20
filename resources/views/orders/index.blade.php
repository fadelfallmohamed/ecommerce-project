@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Mes commandes</h1>
            <p class="text-muted mb-0">Consultez l'historique et le suivi de vos commandes</p>
        </div>
        <div>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour à l'accueil
            </a>
            <a href="{{ route('catalogue.index') }}" class="btn btn-primary ms-2">
                <i class="fas fa-shopping-bag me-1"></i> Nouvelle commande
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">N° Commande</th>
                            <th class="border-0">Date</th>
                            <th class="border-0 text-end">Total</th>
                            <th class="border-0 text-center">Statut</th>
                            <th class="border-0">Paiement</th>
                            <th class="border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr class="position-relative">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-light text-dark me-2">#{{ $order->id }}</span>
                                        @if($order->created_at->isToday())
                                            <span class="badge bg-success bg-opacity-10 text-success">Aujourd'hui</span>
                                        @elseif($order->created_at->isYesterday())
                                            <span class="badge bg-primary bg-opacity-10 text-primary">Hier</span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end fw-bold">{{ number_format($order->total, 2, ',', ' ') }} €</td>
                                <td class="text-center">
                                    @php
                                        $statusClasses = [
                                            'en_attente' => 'bg-warning bg-opacity-15 text-warning',
                                            'en_cours_de_livraison' => 'bg-info bg-opacity-15 text-info',
                                            'livrée' => 'bg-success bg-opacity-15 text-success',
                                            'annulée' => 'bg-danger bg-opacity-15 text-danger',
                                            'default' => 'bg-secondary bg-opacity-10 text-secondary'
                                        ];
                                        $statusIcons = [
                                            'en_attente' => 'fa-clock',
                                            'en_cours_de_livraison' => 'fa-truck',
                                            'livrée' => 'fa-check-circle',
                                            'annulée' => 'fa-times-circle',
                                            'default' => 'fa-question-circle'
                                        ];
                                        $statusText = [
                                            'en_attente' => 'En attente',
                                            'en_cours_de_livraison' => 'En cours de livraison',
                                            'livrée' => 'Livrée',
                                            'annulée' => 'Annulée',
                                            'default' => 'Inconnu'
                                        ];
                                        $status = $order->status ?? 'default';
                                        $statusClass = $statusClasses[$status] ?? $statusClasses['default'];
                                        $statusIcon = $statusIcons[$status] ?? $statusIcons['default'];
                                        $statusDisplay = $statusText[$status] ?? ucfirst(str_replace('_', ' ', $status));
                                    @endphp
                                    <span class="badge {{ $statusClass }} px-3 py-2 d-inline-flex align-items-center">
                                        <i class="fas {{ $statusIcon }} me-1"></i>
                                        {{ $statusDisplay }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $paymentClass = $order->payment_method === 'online' 
                                            ? 'bg-primary bg-opacity-10 text-primary' 
                                            : 'bg-secondary bg-opacity-10 text-secondary';
                                        $paymentIcon = $order->payment_method === 'online' 
                                            ? 'fa-credit-card' 
                                            : 'fa-money-bill-wave';
                                        $paymentText = $order->payment_method === 'online' 
                                            ? 'Payé en ligne' 
                                            : 'Paiement à la livraison';
                                    @endphp
                                    <span class="badge {{ $paymentClass }} px-3 py-2">
                                        <i class="fas {{ $paymentIcon }} me-1"></i>
                                        {{ $paymentText }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('orders.show', $order) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Voir les détails"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-eye"></i>
                                            <span class="d-none d-md-inline ms-1">Détails</span>
                                        </a>
                                        
                                        @if($order->invoice && $order->invoice->status === 'signed')
                                            <a href="{{ route('invoices.download', $order->invoice) }}" 
                                               class="btn btn-sm btn-outline-success"
                                               title="Télécharger la facture"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-file-pdf"></i>
                                                <span class="d-none d-md-inline ms-1">Facture</span>
                                            </a>
                                        @elseif($order->invoice)
                                            <span class="btn btn-sm btn-outline-warning" 
                                                  title="Facture en attente de signature"
                                                  data-bs-toggle="tooltip">
                                                <i class="fas fa-file-signature"></i>
                                                <span class="d-none d-md-inline ms-1">En attente</span>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="card-footer bg-white border-top-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Affichage de <span class="fw-semibold">{{ $orders->firstItem() }}</span> à 
                            <span class="fw-semibold">{{ $orders->lastItem() }}</span> sur 
                            <span class="fw-semibold">{{ $orders->total() }}</span> commandes
                        </div>
                        <div>
                            <nav>
                                {{ $orders->onEachSide(1)->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="text-center p-5">
                <div class="mb-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                        <i class="fas fa-inbox fa-3x text-muted"></i>
                    </div>
                </div>
                <h5 class="text-muted mb-2">Aucune commande trouvée</h5>
                <p class="text-muted mb-4">Vous n'avez pas encore passé de commande dans notre boutique.</p>
                <a href="{{ route('catalogue.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag me-2"></i>Découvrir nos produits
                </a>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .table th {
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom-width: 1px;
        white-space: nowrap;
    }
    
    .table td {
        vertical-align: middle;
        padding: 1.25rem 0.75rem;
    }
    
    .table > :not(:first-child) {
        border-top: 1px solid #f1f5f9;
    }
    
    .table > :not(caption) > * > * {
        padding: 1rem 0.75rem;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.5em 0.9em;
        font-size: 0.85em;
        letter-spacing: 0.5px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
    }
    
    .btn-group .btn {
        border-radius: 6px !important;
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
        transition: all 0.2s ease-in-out;
    }
    
    .btn-group .btn:not(:last-child) {
        margin-right: 0.35rem;
    }
    
    .btn-group .btn i {
        font-size: 0.9em;
    }
    
    .btn-outline-primary {
        border-width: 1.5px;
    }
    
    .card {
        border-radius: 12px;
        overflow: hidden;
    }
    
    .card-header {
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .pagination {
        margin-bottom: 0;
    }
    
    .page-link {
        border-radius: 6px !important;
        margin: 0 3px;
        min-width: 38px;
        text-align: center;
        border: 1px solid #e2e8f0;
        color: #4b5563;
        font-weight: 500;
    }
    
    .page-item.active .page-link {
        background-color: #4361ee;
        border-color: #4361ee;
    }
    
    .page-item.disabled .page-link {
        color: #9ca3af;
    }
    
    /* Effet de survol sur les lignes du tableau */
    .table-hover > tbody > tr {
        transition: all 0.2s ease;
    }
    
    .table-hover > tbody > tr:hover {
        background-color: #f8fafc;
        transform: translateX(4px);
    }
    
    /* Style pour les écrans mobiles */
    @media (max-width: 767.98px) {
        .table-responsive {
            border-radius: 0.5rem;
        }
        
        .table thead {
            display: none;
        }
        
        .table, .table tbody, .table tr, .table td {
            display: block;
            width: 100%;
        }
        
        .table tr {
            position: relative;
            padding: 1.5rem 0.75rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .table td {
            padding: 0.5rem 0;
            text-align: right;
            padding-left: 50%;
            position: relative;
        }
        
        .table td::before {
            content: attr(data-label);
            position: absolute;
            left: 1rem;
            width: 45%;
            padding-right: 1rem;
            text-align: left;
            font-weight: 600;
            color: #6b7280;
        }
        
        .table td:first-child {
            padding-top: 0;
        }
        
        .table td:last-child {
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .btn-group {
            justify-content: flex-end;
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Activer les tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endsection