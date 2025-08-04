@extends('layouts.admin')

@section('title', 'Gestion des photos du produit')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">
            <i class="fas fa-images"></i> Photos du produit : {{ $product->name }}
        </h1>
        <div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour aux produits
            </a>
            <a href="{{ route('admin.products.photos.create', $product) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter des photos
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($photos->isEmpty())
        <div class="alert alert-info">
            Aucune photo n'a été ajoutée à ce produit pour le moment.
        </div>
    @else
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    @foreach($photos as $photo)
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="card h-100">
                                <img src="{{ $photo->url }}" class="card-img-top" alt="Photo du produit" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        @if($photo->is_primary)
                                            <span class="badge bg-success">Photo principale</span>
                                        @else
                                            <span class="badge bg-secondary">Photo secondaire</span>
                                        @endif
                                    </h5>
                                    <p class="card-text small text-muted">
                                        {{ $photo->original_name }}<br>
                                        {{ number_format($photo->size / 1024, 2) }} KB
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="btn-group w-100">
                                        @if(!$photo->is_primary)
                                            <form action="{{ route('admin.products.photos.setPrimary', [$product, $photo]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-primary" title="Définir comme photo principale">
                                                    <i class="fas fa-star"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#photoModal{{ $photo->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        @if($photos->count() > 1)
                                            <form action="{{ route('admin.products.photos.destroy', [$product, $photo]) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette photo ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" {{ $photo->is_primary ? 'disabled' : '' }} title="{{ $photo->is_primary ? 'Impossible de supprimer la photo principale' : 'Supprimer' }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="photoModal{{ $photo->id }}" tabindex="-1" aria-labelledby="photoModalLabel{{ $photo->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="photoModalLabel{{ $photo->id }}">Détails de la photo</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="{{ $photo->url }}" class="img-fluid" alt="Photo du produit">
                                        <div class="mt-3">
                                            <p><strong>Nom original :</strong> {{ $photo->original_name }}</p>
                                            <p><strong>Taille :</strong> {{ number_format($photo->size / 1024, 2) }} KB</p>
                                            <p><strong>Type :</strong> {{ $photo->mime_type }}</p>
                                            <p><strong>Date d'ajout :</strong> {{ $photo->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                        <a href="{{ $photo->url }}" class="btn btn-primary" download>
                                            <i class="fas fa-download"></i> Télécharger
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
