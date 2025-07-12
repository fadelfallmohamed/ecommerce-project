@extends('layouts.app')
@section('content')
    <h1>{{ $category->name }}</h1>
    <p>{{ $category->description }}</p>
    <a href="{{ route('categories.edit', $category) }}">Modifier</a>
    <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline">
        @csrf
        @method('DELETE')
        <button type="submit">Supprimer</button>
    </form>
    <a href="{{ route('categories.index') }}">Retour à la liste</a>
@endsection 