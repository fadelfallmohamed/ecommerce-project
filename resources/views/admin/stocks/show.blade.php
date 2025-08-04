@extends('admin.layouts.app')

@section('title', 'Détails du Stock - ' . $stock->product->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Détails du Stock</h4>
                <div class="page-title-right">
                    <a href="{{ route('admin.stocks.index') }}" class="btn btn-soft-secondary">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        @if($stock->product->image)
                            <img src="{{ asset('storage/' . $stock->product->image) }}" alt="" class="img-thumbnail" style="max-height: 200px;">
                        @else
                            <div class="avatar-xxl bg-soft-primary rounded-circle text-center d-flex align-items-center justify-content-center" style="width: 200px; height: 200px; margin: 0 auto;">
                                <span class="display-4 text-primary">{{ substr($stock->product->name, 0, 2) }}</span>
                            </div>
                        @endif
                        <h4 class="mt-3">{{ $stock->product->name }}</h4>
                        
                        <div class="mt-3">
                            @if($stock->isOutOfStock())
                                <span class="badge bg-danger">Rupture de stock</span>
                            @elseif($stock->isLow())
                                <span class="badge bg-warning">Stock bas</span>
                            @else
                                <span class="badge bg-success">En stock</span>
                            @endif
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="mb-3">
                        <h5 class="font-size-14">Informations de base</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="row" style="width: 50%;">Référence :</th>
                                        <td>{{ $stock->sku ?? 'Non défini' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Code-barres :</th>
                                        <td>{{ $stock->barcode ?? 'Non défini' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Emplacement :</th>
                                        <td>{{ $stock->location ?? 'Non spécifié' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Dernière mise à jour :</th>
                                        <td>{{ $stock->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Dernier approvisionnement :</th>
                                        <td>{{ $stock->last_restocked_at ? $stock->last_restocked_at->format('d/m/Y H:i') : 'Jamais' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Dernière modification par :</th>
                                        <td>{{ $stock->updatedBy ? $stock->updatedBy->name : 'Système' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('admin.stocks.edit', $stock) }}" class="btn btn-primary w-100">
                            <i class="ri-edit-line align-middle me-1"></i> Modifier
                        </a>
                        <a href="{{ route('admin.stocks.adjust', $stock) }}" class="btn btn-warning w-100">
                            <i class="ri-add-circle-line align-middle me-1"></i> Ajuster
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-custom mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#stock-details" role="tab">
                                Détails du stock
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#stock-movements" role="tab">
                                Mouvements
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#stock-notes" role="tab">
                                Notes
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="stock-details" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h5 class="card-title text-muted mb-3">Niveaux de stock</h5>
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Stock actuel</span>
                                                    <span class="fw-bold">{{ $stock->quantity }} unités</span>
                                                </div>
                                                <div class="progress" style="height: 8px;">
                                                    @php
                                                        $max = max($stock->quantity, $stock->alert_quantity * 2, 10);
                                                        $quantityPercent = ($stock->quantity / $max) * 100;
                                                        $alertPercent = ($stock->alert_quantity / $max) * 100;
                                                    @endphp
                                                    <div class="progress-bar bg-success" role="progressbar" 
                                                         style="width: {{ $quantityPercent }}%" 
                                                         aria-valuenow="{{ $quantityPercent }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <div class="mt-1">
                                                    <small class="text-muted">Seuil d'alerte: {{ $stock->alert_quantity }} unités</small>
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <h6 class="text-muted">Valeur du stock</h6>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Prix d'achat unitaire :</span>
                                                    <span class="fw-bold">{{ $stock->purchase_price ? number_format($stock->purchase_price, 2, ',', ' ') . ' €' : 'Non défini' }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Prix de vente unitaire :</span>
                                                    <span class="fw-bold">{{ number_format($stock->selling_price, 2, ',', ' ') }} €</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Marge brute :</span>
                                                    <span class="fw-bold">
                                                        @if($stock->purchase_price && $stock->purchase_price > 0)
                                                            @php
                                                                $margin = (($stock->selling_price - $stock->purchase_price) / $stock->purchase_price) * 100;
                                                                echo number_format($margin, 2, ',', ' ') . '%';
                                                            @endphp
                                                        @else
                                                            -
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between fw-bold mt-3 pt-2 border-top">
                                                    <span>Valeur totale :</span>
                                                    <span>
                                                        @if($stock->purchase_price)
                                                            {{ number_format($stock->quantity * $stock->purchase_price, 2, ',', ' ') }} €
                                                        @else
                                                            -
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h5 class="card-title text-muted mb-3">Statistiques rapides</h5>
                                            
                                            <div class="mb-4">
                                                <h6 class="text-muted">Statut actuel</h6>
                                                @if($stock->isOutOfStock())
                                                    <div class="alert alert-danger mb-0">
                                                        <i class="ri-error-warning-line align-middle me-1"></i>
                                                        Ce produit est en rupture de stock.
                                                    </div>
                                                @elseif($stock->isLow())
                                                    <div class="alert alert-warning mb-0">
                                                        <i class="ri-alert-line align-middle me-1"></i>
                                                        Le stock est en dessous du seuil d'alerte.
                                                    </div>
                                                @else
                                                    <div class="alert alert-success mb-0">
                                                        <i class="ri-checkbox-circle-line align-middle me-1"></i>
                                                        Le stock est à un niveau satisfaisant.
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="mt-4">
                                                <h6 class="text-muted">Actions rapides</h6>
                                                <div class="d-grid gap-2">
                                                    <a href="{{ route('admin.stocks.adjust', $stock) }}" class="btn btn-outline-primary">
                                                        <i class="ri-add-circle-line align-middle me-1"></i> Ajuster le stock
                                                    </a>
                                                    <a href="#" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#reorderModal">
                                                        <i class="ri-shopping-cart-line align-middle me-1"></i> Commander des fournitures
                                                    </a>
                                                    <a href="{{ route('admin.products.edit', $stock->product) }}" class="btn btn-outline-secondary">
                                                        <i class="ri-pencil-line align-middle me-1"></i> Modifier le produit
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="stock-movements" role="tabpanel">
                            @if($stockHistory && count($stockHistory) > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-centered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Quantité</th>
                                                <th>Solde</th>
                                                <th>Utilisateur</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($stockHistory as $movement)
                                                <tr>
                                                    <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        @if($movement->type === 'in')
                                                            <span class="badge bg-success">Entrée</span>
                                                        @else
                                                            <span class="badge bg-danger">Sortie</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $movement->quantity }}</td>
                                                    <td>{{ $movement->balance_after }}</td>
                                                    <td>{{ $movement->user->name ?? 'Système' }}</td>
                                                    <td>{{ $movement->notes ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="ri-information-line me-1"></i>
                                        Aucun mouvement de stock enregistré.
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane" id="stock-notes" role="tabpanel">
                            @if($stock->notes)
                                <div class="p-3 border rounded bg-light">
                                    {!! nl2br(e($stock->notes)) !!}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="ri-information-line me-1"></i>
                                        Aucune note n'a été enregistrée pour ce stock.
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reorder Modal -->
<div class="modal fade" id="reorderModal" tabindex="-1" aria-labelledby="reorderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reorderModalLabel">Commander des fournitures</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="#" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantité à commander</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="{{ max(10, $stock->alert_quantity - $stock->quantity) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="supplier" class="form-label">Fournisseur</label>
                        <select class="form-select" id="supplier" name="supplier_id" required>
                            <option value="">Sélectionner un fournisseur</option>
                            <!-- Boucle sur les fournisseurs -->
                            <option value="1">Fournisseur principal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="expected_date" class="form-label">Date de livraison prévue</label>
                        <input type="date" class="form-control" id="expected_date" name="expected_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer la commande</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialiser la date d'expédition à 7 jours à partir d'aujourd'hui
        var today = new Date();
        var nextWeek = new Date(today);
        nextWeek.setDate(today.getDate() + 7);
        
        var formattedDate = nextWeek.toISOString().split('T')[0];
        document.getElementById('expected_date').min = today.toISOString().split('T')[0];
        document.getElementById('expected_date').value = formattedDate;
    });
</script>
@endpush
