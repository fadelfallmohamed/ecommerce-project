@extends('admin.layouts.app')

@section('title', 'Modifier le stock - ' . $stock->product->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Modifier le stock</h4>
                <div>
                    <a href="{{ route('admin.stocks.show', $stock) }}" class="btn btn-soft-secondary me-2">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        @if($stock->product->image)
                            <img src="{{ asset('storage/' . $stock->product->image) }}" alt="" class="avatar-md rounded me-3">
                        @else
                            <div class="avatar-md bg-soft-primary rounded-circle text-center d-flex align-items-center justify-content-center me-3">
                                <span class="display-6 text-primary">{{ substr($stock->product->name, 0, 2) }}</span>
                            </div>
                        @endif
                        <div>
                            <h4 class="mb-0">{{ $stock->product->name }}</h4>
                            <p class="text-muted mb-0">
                                Stock actuel: 
                                <span class="fw-bold {{ $stock->isOutOfStock() ? 'text-danger' : ($stock->isLow() ? 'text-warning' : 'text-success') }}">
                                    {{ $stock->quantity }} unités
                                </span>
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('admin.stocks.update', $stock) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantité en stock <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                           id="quantity" name="quantity" min="0" value="{{ old('quantity', $stock->quantity) }}" required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="alert_quantity" class="form-label">Seuil d'alerte <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('alert_quantity') is-invalid @enderror" 
                                           id="alert_quantity" name="alert_quantity" min="1" value="{{ old('alert_quantity', $stock->alert_quantity) }}" required>
                                    <small class="text-muted">Alarme lorsque le stock est en dessous de cette valeur</small>
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
                                               value="{{ old('purchase_price', $stock->purchase_price) }}">
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
                                               value="{{ old('selling_price', $stock->selling_price) }}" required>
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
                                           id="sku" name="sku" value="{{ old('sku', $stock->sku) }}">
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="barcode" class="form-label">Code-barres (EAN/ISBN/UPC)</label>
                                    <input type="text" class="form-control @error('barcode') is-invalid @enderror" 
                                           id="barcode" name="barcode" value="{{ old('barcode', $stock->barcode) }}">
                                    @error('barcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Emplacement dans le stock</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                   id="location" name="location" value="{{ old('location', $stock->location) }}" 
                                   placeholder="Ex: Rayon A, Étagère 2">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes internes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes', $stock->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Ajustement de stock</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="log_adjustment" name="log_adjustment" value="1" checked>
                                <label class="form-check-label" for="log_adjustment">
                                    Enregistrer une entrée dans l'historique des ajustements
                                </label>
                            </div>
                            <div id="adjustmentNotesContainer" class="mt-2">
                                <label for="adjustment_notes" class="form-label">Raison de l'ajustement</label>
                                <textarea class="form-control" id="adjustment_notes" name="adjustment_notes" rows="2" 
                                          placeholder="Expliquez pourquoi vous modifiez ce stock"></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <div>
                                <span class="text-muted">Dernière mise à jour: {{ $stock->updated_at->format('d/m/Y H:i') }}</span><br>
                                <small class="text-muted">Par: {{ $stock->updatedBy ? $stock->updatedBy->name : 'Système' }}</small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.stocks.show', $stock) }}" class="btn btn-light">Annuler</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line align-middle me-1"></i> Enregistrer les modifications
                                </button>
                            </div>
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
        // Afficher/masquer les notes d'ajustement
        const logAdjustmentCheckbox = document.getElementById('log_adjustment');
        const adjustmentNotesContainer = document.getElementById('adjustmentNotesContainer');
        
        if (logAdjustmentCheckbox && adjustmentNotesContainer) {
            function toggleAdjustmentNotes() {
                adjustmentNotesContainer.style.display = logAdjustmentCheckbox.checked ? 'block' : 'none';
            }
            
            // Initialiser l'état
            toggleAdjustmentNotes();
            
            // Écouter les changements
            logAdjustmentCheckbox.addEventListener('change', toggleAdjustmentNotes);
        }
        
        // Calculer et afficher la marge en temps réel
        const purchasePriceInput = document.getElementById('purchase_price');
        const sellingPriceInput = document.getElementById('selling_price');
        
        function calculateMargin() {
            const purchasePrice = parseFloat(purchasePriceInput.value) || 0;
            const sellingPrice = parseFloat(sellingPriceInput.value) || 0;
            
            let marginElement = document.getElementById('marginValue');
            
            if (!marginElement) {
                marginElement = document.createElement('div');
                marginElement.id = 'marginValue';
                marginElement.className = 'form-text';
                sellingPriceInput.parentNode.appendChild(marginElement);
            }
            
            if (purchasePrice > 0 && sellingPrice > 0) {
                const margin = ((sellingPrice - purchasePrice) / purchasePrice) * 100;
                marginElement.textContent = `Marge brute: ${margin.toFixed(2)}%`;
                marginElement.className = margin >= 0 ? 'form-text text-success' : 'form-text text-danger';
            } else {
                marginElement.textContent = 'Renseignez les prix pour voir la marge';
                marginElement.className = 'form-text text-muted';
            }
        }
        
        if (purchasePriceInput && sellingPriceInput) {
            purchasePriceInput.addEventListener('input', calculateMargin);
            sellingPriceInput.addEventListener('input', calculateMargin);
            // Calcul initial
            calculateMargin();
        }
    });
</script>
@endpush
@endsection
