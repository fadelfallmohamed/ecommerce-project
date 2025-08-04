@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Gestion des produits</h1>
        <div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary me-2">
                <i class="fas fa-plus me-2"></i>Ajouter un produit
            </a>
            <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#stockManagementModal">
                <i class="fas fa-boxes me-2"></i>Gérer les stocks
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->main_image)
                                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="fas fa-box-open text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="align-middle">
                                <div class="fw-bold">{{ $product->name }}</div>
                                <div class="small text-muted">
                                    @if($product->categories->isNotEmpty())
                                        @foreach($product->categories as $category)
                                            <span class="badge bg-secondary me-1">{{ $category->name }}</span>
                                        @endforeach
                                        <br>
                                    @endif
                                    {{ Str::limit($product->description, 50) }}
                                </div>
                            </td>
                            <td class="align-middle">{{ format_price(convert_euro_to_fcfa($product->price)) }}</td>
                            <td class="align-middle">
                                @php
                                    $badgeClass = 'bg-success';
                                    $badgeText = 'En stock';
                                    $stockQuantity = $product->stock_quantity ?? 0;
                                    
                                    if ($product->stock_status === \App\Models\Stock::STATUS_OUT_OF_STOCK) {
                                        $badgeClass = 'bg-danger';
                                        $badgeText = 'Rupture';
                                    } elseif ($product->stock_status === \App\Models\Stock::STATUS_LOW_STOCK) {
                                        $badgeClass = 'bg-warning';
                                        $badgeText = 'Stock faible';
                                    }
                                @endphp
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge {{ $badgeClass }}">
                                    {{ $badgeText }} ({{ $product->stock_quantity }})
                                    </span>
                                    <span class="text-muted small">
                                        ({{ $stockQuantity }} {{ Str::plural('article', $stockQuantity) }})
                                    </span>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                        Modif
                                    </a>
                                    <a href="{{ route('admin.products.stock.edit', $product) }}" class="btn btn-sm btn-outline-warning" title="Gérer le stock">
                                        Stock
                                    </a>
                                    <a href="{{ route('catalogue.fiche', $product) }}" class="btn btn-sm btn-outline-info" title="Voir la fiche produit" target="_blank">
                                        Voir
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                            Suppr
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                Aucun produit trouvé. 
                                <a href="{{ route('admin.products.create') }}">Ajoutez votre premier produit</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
    <!-- Modal de gestion des stocks -->
    <div class="modal fade" id="stockManagementModal" tabindex="-1" aria-labelledby="stockManagementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="stockManagementModalLabel">
                        <i class="fas fa-boxes me-2"></i>Gestion des stocks
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-center">Stock actuel</th>
                                    <th class="text-center">Statut</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->main_image)
                                                <img src="{{ asset('storage/' . $product->main_image) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="img-thumbnail me-2" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $product->name }}</div>
                                                <small class="text-muted">Ref: {{ $product->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge bg-primary fs-6">
                                            {{ $product->stock_quantity ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">
                                        @php
                                            $badgeClass = 'bg-success';
                                            $badgeText = 'En stock';
                                            
                                            if ($product->stock_status === \App\Models\Stock::STATUS_OUT_OF_STOCK) {
                                                $badgeClass = 'bg-danger';
                                                $badgeText = 'Rupture';
                                            } elseif ($product->stock_status === \App\Models\Stock::STATUS_LOW_STOCK) {
                                                $badgeClass = 'bg-warning text-dark';
                                                $badgeText = 'Stock faible';
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $badgeText }}
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="{{ route('admin.products.stock.edit', $product) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Gérer le stock">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Fermer
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-primary">
                        <i class="fas fa-sync-alt me-1"></i> Actualiser
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-top: none;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .img-thumbnail {
        padding: 0.125rem;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }
</style>
@endpush
