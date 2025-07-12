@extends('layouts.app')
@section('content')
    <h1>Ajouter une catégorie</h1>
    <form method="POST" action="{{ route('categories.store') }}">
        @csrf
        <label>Nom :</label>
        <input type="text" name="name" required><br>
        <label>Description :</label>
        <textarea name="description"></textarea><br>
        <button type="submit">Créer</button>
    </form>
@endsection 