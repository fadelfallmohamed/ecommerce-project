@extends('layouts.app')

@section('content')
<div class="container" style="max-width:600px;">
    <h2 class="mb-4">Ajouter un produit</h2>
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nom du produit</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Prix (€)</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" min="0" required>
        </div>
        <div class="mb-3">
            <label for="main_image" class="form-label">Image principale</label>
            <input type="file" class="form-control" id="main_image" name="main_image">
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
        <a href="{{ route('catalogue.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection 