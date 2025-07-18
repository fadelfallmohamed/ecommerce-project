@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<style>
    :root {
        --primary: #1e40af;
        --primary-light: #3b82f6;
        --primary-lighter: #60a5fa;
        --light-bg: #f8fafc;
        --card-bg: #ffffff;
        --text-dark: #1f2937;
        --text-light: #6b7280;
        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --transition: all 0.2s ease-in-out;
    }
    
    body {
        background: linear-gradient(135deg, #f0f7ff 0%, #e0f2fe 100%);
        color: var(--text-dark);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    .catalogue-header {
        text-align: center;
        padding: 3rem 1rem 1.5rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        margin-bottom: 2rem;
        border-radius: 0 0 20px 20px;
        box-shadow: var(--shadow-md);
    }
    
    .catalogue-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.75rem;
        letter-spacing: -0.5px;
    }
    
    .catalogue-header p {
        font-size: 1.15rem;
        opacity: 0.95;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }
    
    .catalogue-filter-box {
        background: var(--card-bg);
        border-radius: 16px;
        box-shadow: var(--shadow-sm);
        padding: 1.75rem;
        max-width: 1000px;
        margin: -2rem auto 2.5rem;
        position: relative;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .product-card {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        transition: var(--transition);
        background: var(--card-bg);
        box-shadow: var(--shadow-sm);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }
    
    .product-img-container {
        position: relative;
        overflow: hidden;
        padding-top: 75%; /* 4:3 Aspect Ratio */
        background: #f8fafc;
    }
    
    .product-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .product-card:hover .product-img {
        transform: scale(1.05);
    }
    
    .product-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 2;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.35rem 0.8rem;
        border-radius: 50px;
        box-shadow: var(--shadow-sm);
    }
    
    .product-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .product-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
    }
    
    .product-description {
        color: var(--text-light);
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        flex-grow: 1;
    }
    
    .product-price {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--primary);
        margin: 0.5rem 0 1rem;
    }
    
    .btn-primary {
        background: var(--primary);
        border: none;
        border-radius: 8px;
        padding: 0.65rem 1.5rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        transition: var(--transition);
    }
    
    .btn-primary:hover {
        background: var(--primary-light);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(30, 64, 175, 0.2);
    }
    
    .btn-outline-primary {
        border: 2px solid var(--primary);
        color: var(--primary);
        font-weight: 600;
        border-radius: 8px;
        padding: 0.5rem 1.25rem;
        transition: var(--transition);
    }
    
    .btn-outline-primary:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-1px);
    }
    
    .btn-group-vertical {
        width: 100%;
        gap: 0.5rem;
    }
    
    .btn-group-vertical .btn {
        margin: 0;
        border-radius: 6px !important;
    }
    
    .pagination {
        margin: 3rem 0 2rem;
        justify-content: center;
    }
    
    .page-link {
        color: var(--primary);
        border: 1px solid #e2e8f0;
        padding: 0.5rem 1rem;
        margin: 0 0.25rem;
        border-radius: 8px !important;
        font-weight: 500;
        transition: var(--transition);
    }
    
    .page-link:hover {
        background: var(--primary-light);
        color: white;
        border-color: var(--primary-light);
    }
    
    .page-item.active .page-link {
        background: var(--primary);
        border-color: var(--primary);
    }
    
    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.65rem 1rem;
        transition: var(--transition);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }
    
    .add-product-btn {
        background: white;
        color: var(--primary);
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        height: 100%;
        transition: var(--transition);
    }
    
    .add-product-btn:hover {
        border-color: var(--primary-light);
        background: #f8fafc;
        transform: translateY(-2px);
    }
    
    .add-product-btn i {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        color: var(--primary);
    }
    
    @media (max-width: 768px) {
        .catalogue-header {
            padding: 2rem 1rem;
        }
        
        .catalogue-header h1 {
            font-size: 2rem;
        }
        
        .catalogue-filter-box {
            padding: 1.25rem;
            margin: -1.5rem 1rem 2rem;
        }
        
        .product-card {
            margin-bottom: 1.5rem;
        }
    }
</style>
<div class="catalogue-header">
    <div class="container">
        <h1>Notre Collection</h1>
        <p>Découvrez notre sélection exclusive de produits soigneusement choisis pour vous.</p>
    </div>
</div>

<div class="container">
    <div class="catalogue-filter-box">
        <form method="GET" action="{{ route('catalogue.index') }}" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label for="search" class="form-label">Rechercher un produit</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" id="search" class="form-control" 
                           placeholder="Rechercher par nom ou description..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-5">
                <label for="category" class="form-label">Filtrer par catégorie</label>
                <select name="category" id="category" class="form-select">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-1"></i> Filtrer
                </button>
            </div>
        </form>
    </div>

    @if($products->isEmpty())
        <div class="text-center py-5">
            <div class="py-5">
                <i class="fas fa-box-open fa-4x mb-3" style="color: #cbd5e1;"></i>
                <h4 class="mb-3">Aucun produit trouvé</h4>
                <p class="text-muted mb-4">Aucun produit ne correspond à votre recherche.</p>
                <a href="{{ route('catalogue.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-undo me-1"></i> Réinitialiser les filtres
                </a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="product-card">
                        <div class="product-img-container">
                            <img src="{{ $product->main_image_url }}" class="product-img" alt="{{ $product->name }}">
                            <span class="product-badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->stock > 0 ? 'En stock' : 'Rupture' }}
                            </span>
                        </div>
                        <div class="product-body">
                            <h3 class="product-title">{{ $product->name }}</h3>
                            <p class="product-description">{{ Str::limit($product->description, 80) }}</p>
                            <div class="product-price">{{ number_format($product->price, 2, ',', ' ') }} €</div>
                            
                            <div class="btn-group-vertical">
                                <a href="{{ route('catalogue.fiche', $product) }}" class="btn btn-primary">
                                    <i class="fas fa-eye me-1"></i> Voir le produit
                                </a>
                                
                                @if(auth()->check() && auth()->user()->nom === 'admin')
                                    <div class="d-flex gap-2 mt-2">
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-primary flex-grow-1">
                                            <i class="fas fa-edit me-1"></i> Modifier
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" 
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $products->withQueryString()->links() }}
        </div>
    @endif

    @if(auth()->check() && auth()->user()->nom === 'admin')
        <div class="row mt-4">
            <div class="col-12 col-md-4 mx-auto">
                <a href="{{ url('/admin/produits/create') }}" class="add-product-btn text-decoration-none">
                    <i class="fas fa-plus-circle"></i>
                    <span>Ajouter un nouveau produit</span>
                </a>
            </div>
        </div>
    @endif
</div>
@endsection 