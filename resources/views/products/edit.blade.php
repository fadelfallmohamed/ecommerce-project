@extends('layouts.app')

@section('content')
<div class="container" style="max-width:600px;">
    <h2 class="mb-4">Modifier le produit</h2>
    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nom du produit</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required>{{ $product->description }}</textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Prix (€)</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" value="{{ $product->price }}" required>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" min="0" value="{{ $product->stock }}" required>
        </div>
        <div class="mb-3">
            <label for="main_image" class="form-label">Image principale (laisser vide pour ne pas changer)</label>
            <input type="file" class="form-control" id="main_image" name="main_image">
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('catalogue.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection 