@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Commandes</h1>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
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
                            <th class="border-0">N°</th>
                            <th class="border-0">Date</th>
                            <th class="border-0">Client</th>
                            <th class="border-0 text-end">Total</th>
                            <th class="border-0 text-center">Statut</th>
                            <th class="border-0 text-end"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>{{ $order->user->name ?? 'Client inconnu' }}</td>
                                <td class="text-end fw-medium">{{ number_format($order->total, 2, ',', ' ') }} €</td>
                                <td class="text-center">
                                    @php
                                        $statusClasses = [
                                            'en_attente' => 'bg-warning text-dark',
                                            'en_cours_de_livraison' => 'bg-info',
                                            'livrée' => 'bg-success',
                                            'annulée' => 'bg-danger',
                                            'default' => 'bg-secondary'
                                        ];
                                        $statusText = [
                                            'en_attente' => 'En attente',
                                            'en_cours_de_livraison' => 'En cours',
                                            'livrée' => 'Livrée',
                                            'annulée' => 'Annulée'
                                        ];
                                        $statusClass = $statusClasses[$order->status] ?? $statusClasses['default'];
                                        $statusDisplay = $statusText[$order->status] ?? ucfirst(str_replace('_', ' ', $order->status));
                                    @endphp
                                    <span class="badge rounded-pill {{ $statusClass }}">
                                        {{ $statusDisplay }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.orders.show', $order) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center p-5">
                <div class="mb-3">
                    <i class="fas fa-inbox fa-3x text-muted"></i>
                </div>
                <p class="text-muted mb-0">Aucune commande trouvée</p>
            </div>
        @endif
    </div>
</div>

<style>
    .table th {
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
    }
    .table td {
        vertical-align: middle;
    }
    .badge {
        font-weight: 500;
        padding: 0.4em 0.8em;
    }
</style>
@endsection
