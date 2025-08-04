@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Gestion du stock</h4>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="text-muted">
                        Référence: {{ $product->id }}<br>
                        Prix: {{ format_price(convert_euro_to_fcfa($product->price)) }}
                    </p>
                    
                    <hr>
                    
                    <form method="POST" action="{{ route('admin.products.stock.update', $product) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantité en stock actuelle</label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" name="quantity" 
                                   value="{{ old('quantity', $product->stock->quantity ?? 0) }}" 
                                   min="0" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="alert_quantity" class="form-label">Seuil d'alerte</label>
                            <input type="number" class="form-control @error('alert_quantity') is-invalid @enderror" 
                                   id="alert_quantity" name="alert_quantity" 
                                   value="{{ old('alert_quantity', $product->stock->alert_quantity ?? 5) }}" 
                                   min="0" required>
                            <div class="form-text">Une alerte sera déclenchée lorsque le stock sera inférieur à cette valeur.</div>
                            @error('alert_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Statut du stock</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="in_stock" {{ old('status', $product->stock->status ?? '') == 'in_stock' ? 'selected' : '' }}>
                                    En stock
                                </option>
                                <option value="low_stock" {{ old('status', $product->stock->status ?? '') == 'low_stock' ? 'selected' : '' }}>
                                    Stock faible
                                </option>
                                <option value="out_of_stock" {{ old('status', $product->stock->status ?? '') == 'out_of_stock' ? 'selected' : '' }}>
                                    Rupture de stock
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="reason" class="form-label">Raison de la modification (optionnel)</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" 
                                      id="reason" name="reason" rows="2">{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Mettre à jour le stock
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
