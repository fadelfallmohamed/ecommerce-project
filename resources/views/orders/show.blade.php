@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
    <h1 class="mb-4 fw-bold">Détail de la commande #{{ $order->id }}</h1>
    <div class="mb-3">
        <strong>Date :</strong> {{ $order->created_at->format('d/m/Y H:i') }}<br>
        <strong>Statut :</strong> {{ ucfirst($order->status) }}<br>
        <strong>Paiement :</strong> {{ $order->payment_method == 'online' ? 'En ligne' : 'Espèces à la livraison' }}<br>
        <strong>Total :</strong> {{ $order->total }} €<br>
        <strong>Adresse de livraison :</strong> {{ $order->address->address_line1 ?? '' }}<br>
        <strong>Téléphone :</strong> {{ $order->address->phone ?? '' }}
    </div>
    <h4>Articles commandés</h4>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Produit</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Sous-total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'Produit supprimé' }}</td>
                        <td>{{ $item->price }} €</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->price * $item->quantity }} €</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        @if($order->invoice)
            <a href="{{ asset('storage/' . $order->invoice->pdf_path) }}" target="_blank" class="btn btn-outline-primary btn-modern">Télécharger la facture PDF</a>
        @endif
        <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-modern ms-2">Retour à mes commandes</a>
    </div>
</div>
@endsection 