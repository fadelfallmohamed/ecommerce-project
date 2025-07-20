@extends('layouts.app')

@push('styles')
<style>
    :root {
        --primary-color: #4361ee;
        --primary-dark: #3a56d4;
        --secondary-color: #3f37c9;
        --success-color: #4bb543;
        --warning-color: #f9c74f;
        --danger-color: #ef476f;
        --light-color: #f8f9fa;
        --dark-color: #212529;
        --gradient-primary: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        --gradient-success: linear-gradient(135deg, #4bb543 0%, #2b8a3e 100%);
        --gradient-warning: linear-gradient(135deg, #f9c74f 0%, #f8961e 100%);
        --gradient-danger: linear-gradient(135deg, #ef476f 0%, #d00000 100%);
    }
    
    body {
        background-color: #f5f7ff;
    }
    
    .card-hover {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none;
        border-radius: 16px;
        overflow: hidden;
        background: white;
        box-shadow: 0 4px 20px rgba(67, 97, 238, 0.08);
        position: relative;
        z-index: 1;
    }
    
    .card-hover:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(67, 97, 238, 0.15);
    }
    
    .card-hover:hover:before {
        opacity: 1;
    }
    
    .status-badge {
        font-size: 0.85rem;
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .status-badge i {
        font-size: 1rem;
    }
    
    .status-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    }
    
    .btn-action {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border-radius: 10px;
        font-weight: 600;
        letter-spacing: 0.5px;
        padding: 0.6rem 1.5rem;
        position: relative;
        overflow: hidden;
        z-index: 1;
        border: none;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.2);
    }
    
    .btn-action:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: var(--primary-color);
        z-index: -2;
    }
    
    .btn-action:before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0%;
        height: 100%;
        background-color: var(--primary-dark);
        transition: all 0.3s;
        z-index: -1;
    }
    
    .btn-action:hover {
        transform: translateY(-3px);
        box-shadow: 0 7px 20px rgba(67, 97, 238, 0.3);
    }
    
    .btn-action:hover:before {
        width: 100%;
    }
    
    .btn-action i {
        transition: transform 0.3s ease;
        margin-right: 8px;
    }
    
    .btn-action:hover i {
        transform: translateX(4px);
    }
    
    .section-title {
        position: relative;
        padding-bottom: 0.75rem;
        margin-bottom: 1.5rem;
        font-weight: 700;
        color: var(--dark-color);
    }
    
    .section-title:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 4px;
        background: var(--gradient-primary);
        border-radius: 2px;
        transition: width 0.3s ease;
    }
    
    .card-hover:hover .section-title:after {
        width: 80px;
    }
    
    .product-card {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
        background: white;
        position: relative;
    }
    
    .product-card:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--gradient-primary);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.1);
        border-color: rgba(67, 97, 238, 0.2);
    }
    
    .product-card:hover:before {
        opacity: 1;
    }
    
    .summary-card {
        background: white;
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.1);
        position: relative;
        z-index: 1;
    }
    
    .summary-card:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
    }
    
    .divider {
        border: 0;
        height: 1px;
        background: linear-gradient(to right, rgba(0,0,0,0), rgba(67, 97, 238, 0.3), rgba(0,0,0,0));
        margin: 1.5rem 0;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <!-- En-tête amélioré avec fond dégradé -->
    <div class="bg-white rounded-3 p-4 mb-5 shadow-sm">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-primary">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none text-primary">Mes commandes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Commande #{{ $order->id }}</li>
            </ol>
        </nav>
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div>
                <h1 class="h3 mb-2 fw-bold text-dark">Commande #{{ $order->id }}</h1>
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <span class="text-muted">
                        <i class="far fa-calendar-alt me-1"></i>
                        {{ $order->created_at->format('d/m/Y à H:i') }}
                    </span>
                    @php
                        $statusClasses = [
                            'en_attente' => 'bg-warning text-dark',
                            'en_cours_de_livraison' => 'bg-info text-white',
                            'livrée' => 'bg-success text-white',
                            'annulée' => 'bg-danger text-white',
                            'default' => 'bg-secondary text-white'
                        ];
                        $statusIcons = [
                            'en_attente' => 'fa-clock',
                            'en_cours_de_livraison' => 'fa-truck-fast',
                            'livrée' => 'fa-circle-check',
                            'annulée' => 'fa-circle-xmark'
                        ];
                        $statusText = [
                            'en_attente' => 'En attente',
                            'en_cours_de_livraison' => 'En cours de livraison',
                            'livrée' => 'Livrée',
                            'annulée' => 'Annulée'
                        ];
                        $statusClass = $statusClasses[$order->status] ?? $statusClasses['default'];
                        $statusIcon = $statusIcons[$order->status] ?? 'fa-circle-info';
                        $statusDisplay = $statusText[$order->status] ?? ucfirst(str_replace('_', ' ', $order->status));
                    @endphp
                    <span class="status-badge {{ $statusClass }}">
                        <i class="fas {{ $statusIcon }} me-1"></i>
                        {{ $statusDisplay }}
                    </span>
                </div>
            </div>
            @if(auth()->user()->is_admin)
            <div class="mt-3 mt-md-0 d-flex flex-wrap gap-2">
                @if($order->status === 'en_attente' || $order->status === 'en_cours_de_livraison')
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $order->status === 'en_attente' ? 'en_cours_de_livraison' : 'livrée' }}">
                        <button type="submit" class="btn btn-primary btn-action">
                            <i class="fas {{ $order->status === 'en_attente' ? 'fa-truck-fast' : 'fa-circle-check' }} me-1"></i>
                            {{ $order->status === 'en_attente' ? 'Marquer comme expédiée' : 'Marquer comme livrée' }}
                        </button>
                    </form>
                @endif
                
                @if($order->status !== 'annulée')
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="ms-0 ms-md-2">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="annulée">
                        <button type="submit" class="btn btn-outline-danger btn-action" 
                                onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                            <i class="fas fa-times me-1"></i>Annuler la commande
                        </button>
                    </form>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Alertes avec animation -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Adresse de livraison -->
        <div class="col-lg-6">
            <div class="card card-hover h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                    <span class="icon-wrapper me-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(67, 97, 238, 0.1); border-radius: 10px;">
                        <i class="fas fa-truck-fast" style="color: var(--primary-color);"></i>
                    </span>
                    <span>Adresse de livraison</span>
                </h5>
                </div>
                <div class="card-body">
                    @if($order->address)
                        <div class="vstack gap-2">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-user mt-1 me-2 text-muted"></i>
                                <div>{{ $order->address->first_name }} {{ $order->address->last_name }}</div>
                            </div>
                            <div class="d-flex align-items-start">
                                <i class="fas fa-map-marker-alt mt-1 me-2 text-muted"></i>
                                <div>
                                    <div>{{ $order->address->address_line1 }}</div>
                                    @if($order->address->address_line2)
                                        <div>{{ $order->address->address_line2 }}</div>
                                    @endif
                                    <div>{{ $order->address->postal_code }} {{ $order->address->city }}</div>
                                    <div>{{ $order->address->country }}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-start mt-2">
                                <i class="fas fa-phone mt-1 me-2 text-muted"></i>
                                <div>{{ $order->address->phone }}</div>
                            </div>
                            @if($order->address->email)
                            <div class="d-flex align-items-start">
                                <i class="fas fa-envelope mt-1 me-2 text-muted"></i>
                                <div>{{ $order->address->email }}</div>
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="text-muted">
                            <i class="fas fa-exclamation-circle me-2"></i>Aucune adresse de livraison
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Paiement -->
        <div class="col-lg-6">
            <div class="card card-hover h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                    <span class="icon-wrapper me-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(67, 97, 238, 0.1); border-radius: 10px;">
                        <i class="fas fa-credit-card" style="color: var(--primary-color);"></i>
                    </span>
                    <span>Paiement</span>
                </h5>
                </div>
                <div class="card-body">
                    <div class="vstack gap-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Statut :</span>
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Payé
                                </span>
                            @else
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-clock me-1"></i>En attente
                                </span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Méthode :</span>
                            <span class="fw-medium">
                                <i class="fas {{ $order->payment_method === 'online' ? 'fa-credit-card' : 'fa-money-bill-wave' }} me-1"></i>
                                {{ $order->payment_method === 'online' ? 'Paiement en ligne' : 'Paiement à la livraison' }}
                            </span>
                        </div>
                        @if($order->payment_method === 'online' && $order->transaction_id)
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Référence :</span>
                            <span class="font-monospace">{{ $order->transaction_id }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Articles de la commande -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-hover">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                    <span class="icon-wrapper me-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(67, 97, 238, 0.1); border-radius: 10px;">
                        <i class="fas fa-box-open" style="color: var(--primary-color);"></i>
                    </span>
                    <span>Articles commandés</span>
                </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 ps-4">Article</th>
                                    <th class="border-0 text-end">Prix unitaire</th>
                                    <th class="border-0 text-center">Quantité</th>
                                    <th class="border-0 text-end pe-4">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                @if($item->product && $item->product->images->isNotEmpty())
                                                    <div class="flex-shrink-0 me-3" style="width: 60px; height: 60px;">
                                                        <img src="{{ asset('storage/' . $item->product->images->first()->path) }}" 
                                                             alt="{{ $item->product->name }}"
                                                             class="img-fluid rounded"
                                                             style="width: 100%; height: 100%; object-fit: cover;">
                                                    </div>
                                                @else
                                                    <div class="flex-shrink-0 me-3 bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-medium">
                                                        {{ $item->product_name ?? 'Produit supprimé' }}
                                                    </div>
                                                    @if($item->variant_name)
                                                        <div class="text-muted small">
                                                            {{ $item->variant_name }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($item->price, 2, ',', ' ') }} €
                                        </td>
                                        <td class="text-center">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="text-end pe-4 fw-medium">
                                            {{ number_format($item->price * $item->quantity, 2, ',', ' ') }} €
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Récapitulatif de la commande -->
    <div class="row mt-4">
        <div class="col-lg-6 offset-lg-6">
            <div class="summary-card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                    <span class="icon-wrapper me-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(67, 97, 238, 0.1); border-radius: 10px;">
                        <i class="fas fa-receipt" style="color: var(--primary-color);"></i>
                    </span>
                    <span>Récapitulatif</span>
                </h5>
                </div>
                <div class="card-body">
                    <div class="vstack gap-2">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Sous-total</span>
                            <span>{{ number_format($order->subtotal, 2, ',', ' ') }} €</span>
                        </div>
                        
                        @if($order->discount > 0)
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Réduction</span>
                                <span class="text-danger">-{{ number_format($order->discount, 2, ',', ' ') }} €</span>
                            </div>
                        @endif
                        
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Frais de livraison</span>
                            <span>{{ $order->shipping_cost > 0 ? number_format($order->shipping_cost, 2, ',', ' ') . ' €' : 'Gratuit' }}</span>
                        </div>
                        
                        <div class="pt-3 mt-2 border-top">
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>Total TTC</span>
                                <span>{{ number_format($order->total, 2, ',', ' ') }} €</span>
                            </div>
                        </div>
                    </div>

                    <!-- Facture -->
                    @if($order->invoice)
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
                                <div>
                                    <h6 class="mb-1">Facture</h6>
                                    @if($order->invoice->status === 'signed')
                                        <p class="text-muted small mb-2 mb-sm-0">
                                            <i class="fas fa-check-circle text-success me-1"></i>
                                            Facture signée le {{ $order->invoice->signed_at->format('d/m/Y à H:i') }}
                                        </p>
                                    @elseif($order->invoice->status === 'pending')
                                        <p class="text-muted small mb-2 mb-sm-0">
                                            <i class="fas fa-hourglass-half text-warning me-1"></i>
                                            En attente de signature par l'administrateur
                                        </p>
                                    @endif
                                </div>
                                @if($order->invoice->status === 'signed')
                                    <a href="{{ route('invoices.download', $order->invoice) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-file-pdf me-1"></i>Télécharger
                                    </a>
                                @elseif(auth()->user()->is_admin)
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        En attente de signature
                                    </span>
                                @endif
                            </div>
                            
                            @if(auth()->user()->is_admin && $order->invoice->status === 'pending')
                                <div class="mt-3 pt-2 border-top">
                                    <form action="{{ route('invoices.sign', $order->invoice) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-success btn-sm"
                                                onclick="return confirm('Êtes-vous sûr de vouloir signer cette facture ? Cette action est irréversible.')">
                                            <i class="fas fa-signature me-1"></i>Signer la facture
                                        </button>
                                        <small class="text-muted d-block mt-1">
                                            La signature déclenchera l'envoi d'une notification au client.
                                        </small>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @elseif(auth()->user()->is_admin && $order->status !== 'annulée')
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
                                <div>
                                    <h6 class="mb-1">Facture</h6>
                                    <p class="text-muted small mb-2 mb-sm-0">
                                        <i class="fas fa-info-circle text-primary me-1"></i>
                                        Aucune facture générée pour cette commande
                                    </p>
                                </div>
                                <a href="{{ route('invoices.generate', $order) }}" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-file-invoice me-1"></i>Générer la facture
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Actions améliorées -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mt-5 pt-4 border-top gap-3">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-action mb-3 mb-sm-0">
            <i class="fas fa-arrow-left me-1"></i>Retour à mes commandes
        </a>
        
        <div class="d-flex flex-wrap gap-2">
            @if($order->invoice)
                @if($order->invoice->status === 'signed')
                    <a href="{{ route('invoices.download', $order->invoice) }}" class="btn btn-primary btn-action">
                        <i class="fas fa-file-pdf me-1"></i>Télécharger la facture
                    </a>
                    
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-primary btn-action">
                            <i class="fas fa-cog me-1"></i>Gérer la commande
                        </a>
                    @endif
                @else
                    <button class="btn btn-outline-secondary btn-action" disabled>
                        @if($order->invoice->status === 'pending')
                            <i class="fas fa-hourglass-half me-1"></i>En attente de signature
                        @else
                            <i class="fas fa-file-import me-1"></i>Facture en préparation
                        @endif
                    </button>
                @endif
            @else
                @if(auth()->user()->is_admin && $order->status !== 'annulée')
                    <a href="{{ route('invoices.generate', $order) }}" class="btn btn-primary btn-action">
                        <i class="fas fa-file-invoice me-1"></i>Générer la facture
                    </a>
                @else
                    <button class="btn btn-outline-secondary btn-action" disabled>
                        <i class="fas fa-file-import me-1"></i>Facture en préparation
                    </button>
                @endif
            @endif
            
            @if(auth()->user()->is_admin && $order->invoice && $order->invoice->status === 'pending')
                <form action="{{ route('invoices.sign', $order->invoice) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-action" 
                            onclick="return confirm('Êtes-vous sûr de vouloir signer cette facture ? Cette action est irréversible.')">
                        <i class="fas fa-signature me-1"></i>Signer la facture
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection