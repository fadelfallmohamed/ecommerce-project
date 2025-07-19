@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
    <h1 class="mb-4 fw-bold">Passer ma commande</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('order.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="address" class="form-label">Adresse de livraison</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Téléphone</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="mb-3">
            <label for="payment_method" class="form-label">Mode de paiement</label>
            <select class="form-select" id="payment_method" name="payment_method" required>
                <option value="online">Paiement avant livraison (en ligne)</option>
                <option value="cod">Paiement après livraison (espèces à la réception)</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-modern">Valider la commande</button>
        <a href="{{ route('cart.index') }}" class="btn btn-secondary btn-modern ms-2">Retour au panier</a>
    </form>
</div>
@endsection 