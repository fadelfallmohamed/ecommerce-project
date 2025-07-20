@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Mes Notifications') }}</span>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <form method="POST" action="{{ route('notifications.markAllRead') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                {{ __('Tout marquer comme lu') }}
                            </button>
                        </form>
                    @endif
                </div>

                <div class="card-body">
                    @if($notifications->count() > 0)
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}" class="w-100">
                                    @csrf
                                    <button type="submit" class="list-group-item list-group-item-action w-100 text-start {{ $notification->is_read ? '' : 'list-group-item-primary' }}" style="border: none; background: none;">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $notification->message }}</h6>
                                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                        @if(!$notification->is_read)
                                            <small class="text-muted">(Nouveau)</small>
                                        @endif
                                    </button>
                                </form>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            {{ __('Aucune notification pour le moment.') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
