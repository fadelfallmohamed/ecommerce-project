@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3">Commande #{{ $order->id }}</h1>
            <p class="text-muted mb-0">
                Passée le {{ $order->created_at->format('d/m/Y à H:i') }}
            </p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Détails de la commande</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-end">Prix unitaire</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->product->name ?? 'Produit supprimé' }}</strong><br>
                                            <small class="text-muted">
                                                Réf: {{ $item->product->reference ?? 'N/A' }}
                                            </small>
                                        </td>
                                        <td class="text-end">{{ number_format($item->price, 2, ',', ' ') }} €</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">
                                            {{ number_format($item->price * $item->quantity, 2, ',', ' ') }} €
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total HT:</td>
                                    <td class="text-end fw-bold">{{ number_format($order->total / 1.2, 2, ',', ' ') }} €</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end">TVA (20%):</td>
                                    <td class="text-end">{{ number_format($order->total * 0.2, 2, ',', ' ') }} €</td>
                                </tr>
                                <tr class="table-active">
                                    <td colspan="3" class="text-end fw-bold">Total TTC:</td>
                                    <td class="text-end fw-bold">{{ number_format($order->total, 2, ',', ' ') }} €</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informations client</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-3">{{ $order->user->name ?? 'Client inconnu' }}</h6>
                    <p class="mb-1">
                        <i class="fas fa-envelope me-2 text-muted"></i>
                        {{ $order->user->email ?? 'Email non disponible' }}
                    </p>
                    <p class="mb-1">
                        <i class="fas fa-phone me-2 text-muted"></i>
                        {{ $order->address->phone ?? 'Téléphone non disponible' }}
                    </p>
                    <hr>
                    <h6 class="mb-2">Adresse de livraison</h6>
                    <address class="mb-0">
                        {{ $order->address->address_line1 ?? 'Adresse non disponible' }}<br>
                        @if(!empty($order->address->address_line2))
                            {{ $order->address->address_line2 }}<br>
                        @endif
                        {{ $order->address->postal_code ?? '' }} {{ $order->address->city ?? '' }}<br>
                        {{ $order->address->country ?? '' }}
                    </address>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Mettre à jour le statut</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label">Statut de la commande</label>
                            <select name="status" id="status" class="form-select">
                                <option value="en_attente" {{ $order->status === 'en_attente' ? 'selected' : '' }}>En attente</option>
                                <option value="en_cours_de_livraison" {{ $order->status === 'en_cours_de_livraison' ? 'selected' : '' }}>En cours de livraison</option>
                                <option value="livrée" {{ $order->status === 'livrée' ? 'selected' : '' }}>Livrée</option>
                                <option value="annulée" {{ $order->status === 'annulée' ? 'selected' : '' }}>Annulée</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (optionnel)</label>
                            <textarea name="notes" id="notes" rows="3" class="form-control">{{ old('notes', $order->status_notes) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-1"></i> Mettre à jour
                        </button>
                    </form>

                    @if($order->invoice)
                        <hr>
                        <div class="mt-3">
                            <h6>Facture</h6>
                            @if($order->invoice->status === 'signed')
                                <a href="{{ route('invoices.download', $order->invoice) }}" class="btn btn-sm btn-outline-primary w-100">
                                    <i class="fas fa-file-pdf me-1"></i> Télécharger la facture
                                </a>
                            @else
                                <a href="{{ route('invoices.generate', $order) }}" class="btn btn-sm btn-outline-secondary w-100">
                                    <i class="fas fa-file-invoice me-1"></i> Générer la facture
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.05);
    }
    .table th {
        border-top: none;
        font-weight: 600;
        font-size: 0.85rem;
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
