@extends('layouts.admin')

@section('title', 'Détails du message')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Détails du message</h1>
        <div>
            @if($contact->unread())
                <form action="{{ route('admin.contacts.mark-as-unread', $contact) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="fas fa-envelope"></i> Marquer comme non lu
                    </button>
                </form>
            @else
                <form action="{{ route('admin.contacts.update', $contact) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="mark_as_unread">
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-envelope-open-text"></i> Marquer comme non lu
                    </button>
                </form>
            @endif
            
            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline" 
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            </form>
            
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ $contact->subject }}</h6>
                    <div class="text-muted small">
                        {{ $contact->created_at->format('d/m/Y à H:i') }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-circle fa-3x text-gray-300"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-1">{{ $contact->name }}</h5>
                                <div class="text-muted">{{ $contact->email }}</div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h6>Message :</h6>
                            <div class="p-3 bg-light rounded">
                                {!! nl2br(e($contact->message)) !!}
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top">
                        <a href="mailto:{{ $contact->email }}?subject=RE: {{ $contact->subject }}" 
                           class="btn btn-primary">
                            <i class="fas fa-reply"></i> Répondre
                        </a>
                        
                        @if($contact->unread())
                            <form action="{{ route('admin.contacts.update', $contact) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="action" value="mark_as_read">
                                <button type="submit" class="btn btn-outline-success">
                                    <i class="fas fa-check"></i> Marquer comme lu
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted small mb-1">Statut</h6>
                        @if($contact->unread())
                            <span class="badge bg-warning">Non lu</span>
                        @else
                            <span class="badge bg-success">Lu le {{ $contact->read_at->format('d/m/Y à H:i') }}</span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted small mb-1">Date d'envoi</h6>
                        <div>{{ $contact->created_at->format('d/m/Y à H:i') }}</div>
                        <small class="text-muted">({{ $contact->created_at->diffForHumans() }})</small>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted small mb-1">Adresse IP</h6>
                        <div>Non disponible</div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top">
                        <a href="mailto:{{ $contact->email }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-envelope"></i> Envoyer un email
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
