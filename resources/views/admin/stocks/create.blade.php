@extends('admin.layouts.app')

@section('title', 'Ajouter un stock')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Ajouter un stock</h4>
                <div>
                    <a href="{{ route('admin.stocks.index') }}" class="btn btn-soft-secondary">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.stocks.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Produit <span class="text-danger">*</span></label>
                            <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                                <option value="">Sélectionner un produit</option>
                                @foreach($products as $id => $name)
                                    <option value="{{ $id }}" {{ old('product_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantité initiale <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                           id="quantity" name="quantity" min="0" value="{{ old('quantity', 0) }}" required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="alert_quantity" class="form-label">Seuil d'alerte <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('alert_quantity') is-invalid @enderror" 
                                           id="alert_quantity" name="alert_quantity" min="1" value="{{ old('alert_quantity', 10) }}" required>
                                    <small class="text-muted">Le système vous alertera lorsque le stock sera en dessous de ce seuil</small>
                                    @error('alert_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="purchase_price" class="form-label">Prix d'achat unitaire (HT)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" 
                                               class="form-control @error('purchase_price') is-invalid @enderror" 
                                               id="purchase_price" name="purchase_price" 
                                               value="{{ old('purchase_price') }}">
                                        <span class="input-group-text">€</span>
                                        @error('purchase_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="selling_price" class="form-label">Prix de vente unitaire (HT) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" 
                                               class="form-control @error('selling_price') is-invalid @enderror" 
                                               id="selling_price" name="selling_price" 
                                               value="{{ old('selling_price') }}" required>
                                        <span class="input-group-text">€</span>
                                        @error('selling_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sku" class="form-label">Référence (SKU)</label>
                                    <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                           id="sku" name="sku" value="{{ old('sku') }}">
                                    <small class="text-muted">Laissé vide pour générer automatiquement</small>
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="barcode" class="form-label">Code-barres (EAN/ISBN/UPC)</label>
                                    <input type="text" class="form-control @error('barcode') is-invalid @enderror" 
                                           id="barcode" name="barcode" value="{{ old('barcode') }}">
                                    @error('barcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Emplacement dans le stock</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                   id="location" name="location" value="{{ old('location') }}" 
                                   placeholder="Ex: Rayon A, Étagère 2">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes internes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-light">Réinitialiser</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line align-middle me-1"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Générer automatiquement le SKU basé sur le nom du produit
        const productSelect = document.getElementById('product_id');
        const skuInput = document.getElementById('sku');
        
        if (productSelect && skuInput) {
            productSelect.addEventListener('change', function() {
                if (!skuInput.value) {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption.value) {
                        const productName = selectedOption.text;
                        const skuBase = productName.substring(0, 3).toUpperCase();
                        const randomNum = Math.floor(100 + Math.random() * 900); // Génère un nombre aléatoire à 3 chiffres
                        skuInput.value = `${skuBase}-${randomNum}`;
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
