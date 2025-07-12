@extends('layouts.app')
@section('content')
    <h1>Modifier la catégorie</h1>
    <form method="POST" action="{{ route('categories.update', $category) }}">
        @csrf
        @method('PUT')
        <label>Nom :</label>
        <input type="text" name="name" value="{{ $category->name }}" required><br>
        <label>Description :</label>
        <textarea name="description">{{ $category->description }}</textarea><br>
        <button type="submit">Mettre à jour</button>
    </form>
@endsection 