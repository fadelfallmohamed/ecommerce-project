@php
    $invoice = $invoice ?? $order->invoice;
    $isAdmin = auth()->user()->is_admin ?? false;
    $isOwner = !$isAdmin && isset($order) && $order->user_id === auth()->id();
    
    $statusClasses = [
        'pending' => 'bg-yellow-100 text-yellow-800',
        'signed' => 'bg-green-100 text-green-800',
        'cancelled' => 'bg-red-100 text-red-800',
    ][$invoice->status] ?? 'bg-gray-100 text-gray-800';
    
    $statusLabels = [
        'pending' => 'En attente de signature',
        'signed' => 'Signée',
        'cancelled' => 'Annulée',
    ][$invoice->status] ?? 'Inconnu';
@endphp

<div class="bg-white p-4 rounded-lg shadow mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div class="mb-4 md:mb-0">
            <h3 class="text-lg font-medium text-gray-900">État de la facture</h3>
            <div class="mt-1">
                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses }}">
                    {{ $statusLabels }}
                </span>
                @if($invoice->signed_at)
                    <p class="mt-1 text-sm text-gray-500">
                        Signée le {{ $invoice->signed_at->format('d/m/Y à H:i') }}
                        @if($invoice->signedBy)
                            par {{ $invoice->signedBy->name }}
                        @endif
                    </p>
                @endif
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3">
            @if($isAdmin)
                @if($invoice->status !== 'signed')
                    <form action="{{ route('invoices.sign', $invoice) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            Signer la facture
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('invoices.download', $invoice) }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Télécharger
                </a>
            @elseif($isOwner)
                @if($invoice->status === 'signed')
                    <a href="{{ route('invoices.download', $invoice) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Télécharger ma facture
                    </a>
                @else
                    <span class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-600 uppercase tracking-widest cursor-not-allowed">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        En attente de signature
                    </span>
                @endif
                
                <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour à la commande
                </a>
            @endif
        </div>
    </div>
</div>
