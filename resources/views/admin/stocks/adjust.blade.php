@extends('admin.layouts.app')

@section('title', 'Ajuster le stock - ' . $stock->product->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Ajuster le stock</h4>
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

                    <form action="{{ route('admin.stocks.adjust', $stock) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label">Type d'ajustement</label>
                            <div class="d-flex gap-3">
                                <div class="form-check card-radio">
                                    <input class="form-check-input" type="radio" name="adjustment_type" id="addStock" value="add" checked>
                                    <label class="form-check-label card-radio-label p-3 rounded" for="addStock">
                                        <span class="d-block mb-1">
                                            <i class="ri-add-circle-line text-success fs-1"></i>
                                        </span>
                                        <span class="d-block fw-semibold">Ajouter du stock</span>
                                        <small class="text-muted d-block">Augmente la quantité en stock</small>
                                    </label>
                                </div>

                                <div class="form-check card-radio">
                                    <input class="form-check-input" type="radio" name="adjustment_type" id="removeStock" value="remove">
                                    <label class="form-check-label card-radio-label p-3 rounded" for="removeStock">
                                        <span class="d-block mb-1">
                                            <i class="ri-indeterminate-circle-line text-danger fs-1"></i>
                                        </span>
                                        <span class="d-block fw-semibold">Retirer du stock</span>
                                        <small class="text-muted d-block">Réduit la quantité en stock</small>
                                    </label>
                                </div>

                                <div class="form-check card-radio">
                                    <input class="form-check-input" type="radio" name="adjustment_type" id="setStock" value="set">
                                    <label class="form-check-label card-radio-label p-3 rounded" for="setStock">
                                        <span class="d-block mb-1">
                                            <i class="ri-edit-circle-line text-primary fs-1"></i>
                                        </span>
                                        <span class="d-block fw-semibold">Définir la quantité</span>
                                        <small class="text-muted d-block">Définit la quantité exacte</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantité <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1" required>
                                    <small class="text-muted">Quantité à ajouter/supprimer/définir</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="adjustment_date" class="form-label">Date de l'ajustement <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control" id="adjustment_date" name="adjustment_date" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" required placeholder="Raison de l'ajustement, référence de bon, etc."></textarea>
                            <small class="text-muted">Décrivez la raison de cet ajustement de stock</small>
                        </div>

                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="ri-information-line me-2"></i>
                                <div>
                                    <h6 class="mb-1">Résumé de l'ajustement</h6>
                                    <p class="mb-0" id="adjustmentSummary">
                                        <span class="fw-semibold">Nouvelle quantité :</span> 
                                        <span id="newQuantity">{{ $stock->quantity + 1 }}</span> unités
                                        <span id="quantityChange" class="text-success">(+1)</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.stocks.show', $stock) }}" class="btn btn-light">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line align-middle me-1"></i> Enregistrer l'ajustement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-radio {
        padding: 0;
        margin: 0;
    }
    .card-radio .form-check-input {
        display: none;
    }
    .card-radio .form-check-input:checked + .card-radio-label {
        border-color: #405189;
        background-color: rgba(64, 81, 137, 0.05);
    }
    .card-radio-label {
        display: block;
        border: 2px solid #e9ecef;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }
    .card-radio-label:hover {
        border-color: #405189;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('quantity');
        const adjustmentTypeRadios = document.querySelectorAll('input[name="adjustment_type"]');
        const newQuantitySpan = document.getElementById('newQuantity');
        const quantityChangeSpan = document.getElementById('quantityChange');
        
        // Définir la date et l'heure actuelles par défaut
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        
        document.getElementById('adjustment_date').value = `${year}-${month}-${day}T${hours}:${minutes}`;
        
        // Fonction pour mettre à jour le résumé
        function updateSummary() {
            const currentQuantity = {{ $stock->quantity }};
            const adjustmentQuantity = parseInt(quantityInput.value) || 0;
            const adjustmentType = document.querySelector('input[name="adjustment_type"]:checked').value;
            
            let newQuantity = currentQuantity;
            let changeText = '';
            let changeClass = '';
            
            switch(adjustmentType) {
                case 'add':
                    newQuantity = currentQuantity + adjustmentQuantity;
                    changeText = `(+${adjustmentQuantity})`;
                    changeClass = 'text-success';
                    break;
                    
                case 'remove':
                    newQuantity = Math.max(0, currentQuantity - adjustmentQuantity);
                    changeText = `(-${adjustmentQuantity})`;
                    changeClass = 'text-danger';
                    break;
                    
                case 'set':
                    newQuantity = Math.max(0, adjustmentQuantity);
                    const change = newQuantity - currentQuantity;
                    if (change > 0) {
                        changeText = `(+${change})`;
                        changeClass = 'text-success';
                    } else if (change < 0) {
                        changeText = `(${change})`;
                        changeClass = 'text-danger';
                    } else {
                        changeText = '(inchangé)';
                        changeClass = 'text-muted';
                    }
                    break;
            }
            
            newQuantitySpan.textContent = newQuantity;
            quantityChangeSpan.textContent = changeText;
            quantityChangeSpan.className = changeClass;
            
            // Mettre à jour les classes d'alerte en fonction du nouveau stock
            const summaryAlert = document.querySelector('.alert-info');
            if (newQuantity === 0) {
                summaryAlert.classList.remove('alert-warning', 'alert-success');
                summaryAlert.classList.add('alert-danger');
            } else if (newQuantity <= {{ $stock->alert_quantity }}) {
                summaryAlert.classList.remove('alert-danger', 'alert-success');
                summaryAlert.classList.add('alert-warning');
            } else {
                summaryAlert.classList.remove('alert-danger', 'alert-warning');
                summaryAlert.classList.add('alert-success');
            }
        }
        
        // Écouter les changements
        quantityInput.addEventListener('input', updateSummary);
        adjustmentTypeRadios.forEach(radio => {
            radio.addEventListener('change', updateSummary);
        });
        
        // Initialiser le résumé
        updateSummary();
    });
</script>
@endpush
