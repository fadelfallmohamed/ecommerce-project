@extends('layouts.admin')

@section('title', 'Messages de contact')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Messages de contact</h1>
        <div>
            <span class="badge bg-primary">
                {{ $unreadCount }} {{ Str::plural('message', $unreadCount) }} non lu(s)
            </span>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($messages->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-gray-300 mb-3"></i>
                    <p class="text-muted">Aucun message pour le moment.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Expéditeur</th>
                                <th>Sujet</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($messages as $message)
                                <tr class="{{ $message->unread() ? 'table-primary' : '' }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-user-circle fa-2x text-gray-300"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="fw-bold">{{ $message->name }}</div>
                                                <div class="text-muted small">{{ $message->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="fw-bold">{{ Str::limit($message->subject, 50) }}</div>
                                        <div class="text-muted small">{{ Str::limit($message->message, 70) }}</div>
                                    </td>
                                    <td class="align-middle">
                                        <div>{{ $message->created_at->format('d/m/Y') }}</div>
                                        <div class="text-muted small">{{ $message->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="align-middle">
                                        @if($message->unread())
                                            <span class="badge bg-warning">Non lu</span>
                                        @else
                                            <span class="badge bg-success">Lu</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.contacts.show', $message) }}" 
                                               class="btn btn-sm btn-primary" 
                                               title="Voir le message">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.contacts.destroy', $message) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script pour gérer les actions rapides
    document.addEventListener('DOMContentLoaded', function() {
        // Marquer un message comme lu/non lu
        document.querySelectorAll('.mark-as-read').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('data-url');
                
                fetch(url, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        _token: '{{ csrf_token() }}',
                        _method: 'PUT'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Erreur:', error));
            });
        });
    });
</script>
@endpush
