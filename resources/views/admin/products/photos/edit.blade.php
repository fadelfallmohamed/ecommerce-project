@extends('layouts.admin')

@section('title', 'Réorganiser les photos du produit')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Tableau de bord</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.products.index') }}">Produits</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.products.photos.index', $product) }}">Photos de {{ $product->name }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Réorganiser les photos</li>
                </ol>
            </nav>
            
            <h1 class="h3">
                <i class="fas fa-sort"></i> Réorganiser les photos de : {{ $product->name }}
            </h1>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                Glissez-déposez les photos pour les réorganiser. La première photo sera considérée comme la photo principale.
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.products.photos.updateOrder', $product) }}" method="POST" id="sortableForm">
                @csrf
                @method('PUT')
                
                <div class="row" id="sortable">
                    @foreach($photos as $index => $photo)
                        <div class="col-md-3 col-6 mb-4 photo-item" data-id="{{ $photo->id }}">
                            <div class="card h-100">
                                <img src="{{ $photo->url }}" class="card-img-top" alt="Photo du produit" style="height: 150px; object-fit: cover;">
                                <div class="card-body p-2 text-center">
                                    @if($photo->is_primary)
                                        <span class="badge bg-success mb-2">Photo principale</span>
                                    @endif
                                    <p class="card-text small text-muted mb-1">
                                        {{ $photo->original_name }}
                                    </p>
                                    <p class="card-text small text-muted">
                                        Position: <span class="position-badge badge bg-primary">{{ $index + 1 }}</span>
                                    </p>
                                    <input type="hidden" name="photos[{{ $photo->id }}][order]" value="{{ $index + 1 }}" class="photo-order">
                                    <input type="hidden" name="photos[{{ $photo->id }}][is_primary]" value="{{ $photo->is_primary ? '1' : '0' }}" class="is-primary">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="{{ route('admin.products.photos.index', $product) }}" class="btn btn-secondary me-md-2">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary" id="saveBtn">
                        <i class="fas fa-save"></i> Enregistrer l'ordre
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .photo-item {
        cursor: move; /* fallback if grab cursor is unsupported */
        cursor: grab;
        cursor: -moz-grab;
        cursor: -webkit-grab;
    }
    
    .photo-item:active {
        cursor: grabbing;
        cursor: -moz-grabbing;
        cursor: -webkit-grabbing;
    }
    
    .photo-item.sortable-ghost {
        opacity: 0.5;
        background: #cce5ff;
        border-radius: 5px;
    }
    
    .photo-item.sortable-chosen {
        background-color: #e9ecef;
        border-radius: 5px;
    }
    
    .position-badge {
        min-width: 30px;
        display: inline-block;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sortable = document.getElementById('sortable');
        const saveBtn = document.getElementById('saveBtn');
        
        // Initialiser le glisser-déposer
        const sortableInstance = new Sortable(sortable, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function() {
                // Mettre à jour les numéros de position
                updatePositions();
            }
        });
        
        // Mettre à jour les positions et définir la première photo comme principale
        function updatePositions() {
            const items = sortable.querySelectorAll('.photo-item');
            let hasPrimary = false;
            
            items.forEach((item, index) => {
                const position = index + 1;
                const photoId = item.dataset.id;
                const orderInput = item.querySelector('.photo-order');
                const isPrimaryInput = item.querySelector('.is-primary');
                const positionBadge = item.querySelector('.position-badge');
                
                // Mettre à jour la position
                orderInput.value = position;
                positionBadge.textContent = position;
                
                // Définir la première photo comme principale
                if (position === 1 && !hasPrimary) {
                    isPrimaryInput.value = '1';
                    const primaryBadge = item.querySelector('.badge.bg-success');
                    if (!primaryBadge) {
                        const badge = document.createElement('span');
                        badge.className = 'badge bg-success mb-2';
                        badge.textContent = 'Photo principale';
                        item.querySelector('.card-body').insertBefore(badge, item.querySelector('.card-body').firstChild);
                    }
                    hasPrimary = true;
                } else if (position > 1) {
                    isPrimaryInput.value = '0';
                    const primaryBadge = item.querySelector('.badge.bg-success');
                    if (primaryBadge) {
                        primaryBadge.remove();
                    }
                }
            });
            
            // Si aucune photo n'est marquée comme principale (au cas où), on force la première
            if (!hasPrimary && items.length > 0) {
                const firstItem = items[0];
                const firstIsPrimaryInput = firstItem.querySelector('.is-primary');
                firstIsPrimaryInput.value = '1';
                
                const badge = document.createElement('span');
                badge.className = 'badge bg-success mb-2';
                badge.textContent = 'Photo principale';
                firstItem.querySelector('.card-body').insertBefore(badge, firstItem.querySelector('.card-body').firstChild);
            }
        }
        
        // Désactiver le bouton pendant l'envoi du formulaire
        document.getElementById('sortableForm').addEventListener('submit', function() {
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enregistrement...';
        });
        
        // Initialiser les positions au chargement
        updatePositions();
    });
</script>
@endpush
@endsection
