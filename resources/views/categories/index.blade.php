@extends('layouts.app')
@section('content')
    <h1>Catégories</h1>
    <a href="{{ route('categories.create') }}">Ajouter une catégorie</a>
    <ul>
        @foreach($categories as $category)
            <li>
                <a href="{{ route('categories.show', $category) }}">{{ $category->name }}</a>
                <a href="{{ route('categories.edit', $category) }}">Modifier</a>
                <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Supprimer</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection 