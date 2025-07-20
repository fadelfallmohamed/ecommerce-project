@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Détails de l'utilisateur</h5>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar avatar-xxl mb-3">
                            <span class="avatar-text bg-primary text-white rounded-circle">
                                {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
                            </span>
                        </div>
                        <h4>{{ $user->prenom }} {{ $user->nom }}</h4>
                        <span class="badge {{ $user->is_admin ? 'bg-primary' : 'bg-secondary' }}">
                            {{ $user->is_admin ? 'Administrateur' : 'Utilisateur' }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted text-uppercase small mb-3">Informations personnelles</h6>
                        <div class="mb-2">
                            <strong>Email :</strong> {{ $user->email }}
                        </div>
                        <div class="mb-2">
                            <strong>Inscrit le :</strong> {{ $user->created_at->format('d/m/Y à H:i') }}
                        </div>
                        <div>
                            <strong>Dernière mise à jour :</strong> {{ $user->updated_at->format('d/m/Y à H:i') }}
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                        
                        @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                    <i class="fas fa-trash me-1"></i> Supprimer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
