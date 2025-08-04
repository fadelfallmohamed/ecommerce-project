@extends('layouts.admin')

@section('title', 'Ajouter des photos au produit')

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
                    <li class="breadcrumb-item active" aria-current="page">Ajouter des photos</li>
                </ol>
            </nav>
            
            <h1 class="h3">
                <i class="fas fa-upload"></i> Ajouter des photos pour : {{ $product->name }}
            </h1>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.products.photos.store', $product) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    Vous pouvez sélectionner plusieurs photos à la fois. Formats acceptés : JPG, JPEG, PNG, WEBP. Taille maximale : 5 Mo par fichier.
                </div>

                <div class="mb-4">
                    <label for="images" class="form-label">Sélectionner les photos</label>
                    <input class="form-control @error('images.*') is-invalid @enderror @error('images') is-invalid @enderror" 
                           type="file" 
                           id="images" 
                           name="images[]" 
                           multiple 
                           accept="image/jpeg,image/png,image/jpg,image/webp"
                           required>
                    
                    @error('images')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    
                    @error('images.*')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_primary" id="is_primary" value="1">
                        <label class="form-check-label" for="is_primary">
                            Définir comme photo principale
                        </label>
                        <div class="form-text">
                            Si coché, la première photo sera définie comme photo principale du produit.
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('admin.products.photos.index', $product) }}" class="btn btn-secondary me-md-2">
                        <i class="fas fa-arrow-left"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-upload"></i> Téléverser les photos
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-images"></i> Aperçu des photos sélectionnées</h5>
            </div>
            <div class="card-body">
                <div class="row" id="preview-container">
                    <div class="col-12 text-muted" id="no-preview">
                        Aucune image sélectionnée. Les aperçus apparaîtront ici.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .image-preview {
        position: relative;
        margin-bottom: 15px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 5px;
        background: #f8f9fa;
    }
    .image-preview img {
        max-width: 100%;
        height: 120px;
        object-fit: contain;
        display: block;
        margin: 0 auto;
    }
    .image-preview .remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        background: rgba(0,0,0,0.7);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        cursor: pointer;
        opacity: 0.8;
        transition: opacity 0.2s;
    }
    .image-preview .remove-btn:hover {
        opacity: 1;
    }
    .image-preview .file-name {
        font-size: 12px;
        text-align: center;
        margin-top: 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('images');
        const previewContainer = document.getElementById('preview-container');
        const noPreview = document.getElementById('no-preview');
        const form = document.getElementById('uploadForm');
        const submitBtn = document.getElementById('submitBtn');
        
        // Afficher les aperçus des images sélectionnées
        fileInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            
            if (files.length === 0) {
                noPreview.style.display = 'block';
                previewContainer.innerHTML = '';
                previewContainer.appendChild(noPreview);
                return;
            }
            
            noPreview.style.display = 'none';
            previewContainer.innerHTML = '';
            
            files.forEach((file, index) => {
                if (!file.type.match('image.*')) return;
                
                const reader = new FileReader();
                const col = document.createElement('div');
                col.className = 'col-md-3 col-6 mb-3';
                
                const preview = document.createElement('div');
                preview.className = 'image-preview';
                
                const img = document.createElement('img');
                img.alt = 'Aperçu ' + (index + 1);
                
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'remove-btn';
                removeBtn.innerHTML = '×';
                removeBtn.onclick = function() {
                    removeFileFromInput(file);
                    col.remove();
                    if (previewContainer.children.length === 0) {
                        noPreview.style.display = 'block';
                        previewContainer.appendChild(noPreview);
                    }
                };
                
                const fileName = document.createElement('div');
                fileName.className = 'file-name';
                fileName.textContent = file.name;
                
                preview.appendChild(img);
                preview.appendChild(removeBtn);
                preview.appendChild(fileName);
                col.appendChild(preview);
                previewContainer.appendChild(col);
                
                reader.onload = function(e) {
                    img.src = e.target.result;
                };
                
                reader.readAsDataURL(file);
            });
        });
        
        // Supprimer un fichier de l'input file
        function removeFileFromInput(fileToRemove) {
            const dt = new DataTransfer();
            const input = document.getElementById('images');
            const { files } = input;
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file !== fileToRemove) {
                    dt.items.add(file);
                }
            }
            
            input.files = dt.files;
        }
        
        // Désactiver le bouton de soumission pendant l'envoi
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Téléversement en cours...';
        });
    });
</script>
@endpush
@endsection
