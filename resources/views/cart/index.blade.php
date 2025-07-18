@extends('layouts.app')

@section('content')
<style>
    .cart-container {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 18px 0 rgba(31, 38, 135, 0.10);
        padding: 2.5rem 2rem;
        margin-top: 2rem;
        margin-bottom: 2rem;
    }
    .cart-table th, .cart-table td {
        vertical-align: middle;
    }
    .cart-table img {
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(30,64,175,0.08);
    }
    .btn-modern {
        border-radius: 30px;
        font-weight: 600;
        letter-spacing: 0.5px;
        padding: 0.5em 1.2em;
        transition: background 0.18s, color 0.18s;
    }
    .btn-modern.btn-outline-primary:hover, .btn-modern.btn-danger:hover {
        background: #2563eb;
        color: #fff;
    }
    .cart-total {
        font-size: 1.4rem;
        font-weight: bold;
        color: #2563eb;
    }
</style>
<div class="container cart-container">
    <h1 class="mb-4 fw-bold">Mon panier</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(count($cart) > 0)
        <div class="table-responsive">
            <table class="table cart-table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Sous-total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $id => $item)
                        <tr>
                            <td>
                                @if($item['image'])
                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" width="60">
                                @else
                                    <img src="https://via.placeholder.com/60x40?text=Image" alt="Image par défaut">
                                @endif
                            </td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['price'] }} €</td>
                            <td>
                                <form action="{{ route('cart.update', $id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" style="width:60px;">
                                    <button type="submit" class="btn btn-sm btn-outline-primary btn-modern">OK</button>
                                </form>
                            </td>
                            <td>{{ $item['price'] * $item['quantity'] }} €</td>
                            <td>
                                <form action="{{ route('cart.remove', $id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger btn-modern">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-end cart-total mt-3">
            Total : {{ $total }} €
        </div>
    @else
        <p>Votre panier est vide.</p>
    @endif
    <a href="{{ route('catalogue.index') }}" class="btn btn-secondary btn-modern mt-3">Continuer mes achats</a>
</div>
@endsection 