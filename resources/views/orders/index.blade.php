@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
    <h1 class="mb-4 fw-bold">Mes commandes</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Paiement</th>
                        <th>Adresse</th>
                        <th>Facture</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $order->total }} €</td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td>{{ $order->payment_method == 'online' ? 'En ligne' : 'Espèces' }}</td>
                            <td>{{ $order->address->address_line1 ?? '' }}<br>{{ $order->address->phone ?? '' }}</td>
                            <td>
                                @if($order->invoice)
                                    <a href="{{ asset('storage/' . $order->invoice->pdf_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">Télécharger</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">Voir</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>Vous n'avez pas encore passé de commande.</p>
    @endif
</div>
@endsection 