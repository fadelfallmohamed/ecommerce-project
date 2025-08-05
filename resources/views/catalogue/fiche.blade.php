@extends('layouts.app')

@section('content')
<style>
    .fiche-container {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 18px 0 rgba(31, 38, 135, 0.10);
        padding: 2.5rem 2rem;
        margin-top: 2rem;
        margin-bottom: 2rem;
    }
    .fiche-title {
        font-size: 2.1rem;
        font-weight: bold;
        margin-bottom: 0.7em;
    }
    .fiche-price {
        font-size: 1.5rem;
        color: #2563eb;
        font-weight: 700;
        margin-bottom: 0.5em;
    }
    .fiche-stock {
        font-size: 1.1rem;
        margin-bottom: 1em;
    }
    .btn-modern {
        border-radius: 30px;
        font-weight: 600;
        letter-spacing: 0.5px;
        padding: 0.6em 1.5em;
        transition: background 0.18s, color 0.18s;
    }
    .btn-modern.btn-success:hover {
        background: #2563eb;
        color: #fff;
    }
    .carousel-inner img {
        max-height: 350px;
        object-fit: cover;
        border-radius: 12px;
    }
</style>
<div class="container fiche-container">
    <div class="row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0">
            @if($images->isNotEmpty())
                <div id="carouselImages" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($images as $key => $photo)
                            <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                <img src="{{ $photo->url }}" class="d-block w-100 img-fluid rounded" alt="{{ $product->name }} - Photo {{ $key + 1 }}">
                            </div>
                        @endforeach
                    </div>
                    @if($images->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                            <span class="visually-hidden">Précédent</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselImages" data-bs-slide="next">
                            <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                            <span class="visually-hidden">Suivant</span>
                        </button>
                    @endif
                </div>
            @else
                <div class="text-center py-5 bg-light rounded">
                    <i class="fas fa-image fa-4x text-muted mb-3"></i>
                    <p class="text-muted">Aucune photo disponible pour ce produit</p>
                </div>
            @endif
        </div>
        <div class="col-md-6">
            <div class="fiche-title">{{ $product->name }}</div>
            <div class="mb-3 text-muted">{{ $product->description }}</div>
            <div class="fiche-price">{{ format_price(convert_euro_to_fcfa($product->price)) }}</div>
            <div class="fiche-stock">
                @php
                    $badgeClass = 'bg-success';
                    $badgeText = 'En stock';
                    $maxQuantity = $product->stock_quantity;
                    
                    if ($product->stock_status === \App\Models\Stock::STATUS_OUT_OF_STOCK) {
                        $badgeClass = 'bg-danger';
                        $badgeText = 'Rupture';
                        $maxQuantity = 0;
                    } elseif ($product->stock_status === \App\Models\Stock::STATUS_LOW_STOCK) {
                        $badgeClass = 'bg-warning';
                        $badgeText = 'Stock faible';
                    }
                @endphp
                <span class="badge {{ $badgeClass }}">
                    {{ $badgeText }} ({{ $product->stock_quantity }} disponible{{ $product->stock_quantity > 1 ? 's' : '' }})
                </span>
            </div>
            <form method="POST" action="{{ route('cart.add', $product) }}" class="d-flex align-items-center gap-2 mb-3" {{ $product->stock_status === \App\Models\Stock::STATUS_OUT_OF_STOCK ? 'onsubmit="event.preventDefault();"' : '' }}>
                @csrf
                <input type="number" name="quantity" value="1" min="1" max="{{ $maxQuantity }}" class="form-control" style="width:90px;" {{ $product->stock_status === \App\Models\Stock::STATUS_OUT_OF_STOCK ? 'disabled' : '' }}>
                <button type="submit" class="btn btn-success btn-modern">Ajouter au panier</button>
            </form>
        </div>
    </div>
</div>
@endsection 