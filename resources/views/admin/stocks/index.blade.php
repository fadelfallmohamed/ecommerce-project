@extends('admin.layouts.app')

@section('title', 'Gestion des Stocks')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Gestion des Stocks</h4>
                <div class="page-title-right">
                    <a href="{{ route('admin.stocks.create') }}" class="btn btn-primary">
                        <i class="ri-add-line align-middle me-1"></i> Ajouter un stock
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form action="{{ route('admin.stocks.index') }}" method="GET" class="d-flex">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Rechercher un produit..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="ri-search-line"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" onchange="window.location.href=this.value">
                                <option value="{{ route('admin.stocks.index') }}" {{ !request('status') ? 'selected' : '' }}>Tous les statuts</option>
                                <option value="{{ route('admin.stocks.index', ['status' => 'in_stock']) }}" {{ request('status') == 'in_stock' ? 'selected' : '' }}>En stock</option>
                                <option value="{{ route('admin.stocks.index', ['status' => 'low_stock']) }}" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Stock bas</option>
                                <option value="{{ route('admin.stocks.index', ['status' => 'out_of_stock']) }}" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Rupture</option>
                            </select>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produit</th>
                                    <th>Référence</th>
                                    <th>Quantité</th>
                                    <th>Prix d'achat</th>
                                    <th>Prix de vente</th>
                                    <th>Statut</th>
                                    <th>Dernière mise à jour</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stocks as $stock)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($stock->product->image)
                                                    <img src="{{ asset('storage/' . $stock->product->image) }}" alt="" class="avatar-sm rounded me-2">
                                                @else
                                                    <div class="avatar-sm bg-soft-primary rounded me-2">
                                                        <span class="avatar-title">{{ substr($stock->product->name, 0, 2) }}</span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h5 class="font-size-14 mb-0">{{ $stock->product->name }}</h5>
                                                    <small class="text-muted">{{ $stock->location ?? 'Non spécifié' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                @if($stock->sku)
                                                    <span class="badge bg-soft-info">{{ $stock->sku }}</span>
                                                @endif
                                                @if($stock->barcode)
                                                    <span class="badge bg-soft-secondary">{{ $stock->barcode }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">{{ $stock->quantity }}</span>
                                                @if($stock->isOutOfStock())
                                                    <i class="ri-close-circle-fill text-danger"></i>
                                                @elseif($stock->isLow())
                                                    <i class="ri-alert-fill text-warning"></i>
                                                @else
                                                    <i class="ri-checkbox-circle-fill text-success"></i>
                                                @endif
                                            </div>
                                            <div class="progress mt-1" style="height: 4px;">
                                                @php
                                                    $percentage = min(100, ($stock->quantity / ($stock->alert_quantity * 2)) * 100);
                                                    $bgClass = $stock->isOutOfStock() ? 'bg-danger' : ($stock->isLow() ? 'bg-warning' : 'bg-success');
                                                @endphp
                                                <div class="progress-bar {{ $bgClass }}" role="progressbar" style="width: {{ $percentage }}%" 
                                                     aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-muted">Seuil d'alerte: {{ $stock->alert_quantity }}</small>
                                        </td>
                                        <td>{{ $stock->purchase_price ? number_format($stock->purchase_price, 2, ',', ' ') . ' €' : '-' }}</td>
                                        <td>{{ number_format($stock->selling_price, 2, ',', ' ') }} €</td>
                                        <td>
                                            @if($stock->isOutOfStock())
                                                <span class="badge bg-danger">Rupture</span>
                                            @elseif($stock->isLow())
                                                <span class="badge bg-warning">Stock bas</span>
                                            @else
                                                <span class="badge bg-success">En stock</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $stock->updated_at->format('d/m/Y H:i') }}</div>
                                            <small class="text-muted">
                                                {{ $stock->updatedBy ? 'Par ' . $stock->updatedBy->name : '' }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.stocks.show', $stock) }}" class="btn btn-sm btn-soft-info" 
                                                   data-bs-toggle="tooltip" title="Voir les détails">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="{{ route('admin.stocks.edit', $stock) }}" class="btn btn-sm btn-soft-primary" 
                                                   data-bs-toggle="tooltip" title="Modifier">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                <a href="{{ route('admin.stocks.adjust', $stock) }}" class="btn btn-sm btn-soft-warning" 
                                                   data-bs-toggle="tooltip" title="Ajuster le stock">
                                                    <i class="ri-add-circle-line"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ri-information-line me-1"></i>
                                                Aucun stock trouvé
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $stocks->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Activer les tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
