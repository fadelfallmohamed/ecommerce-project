@component('mail::message')
# Confirmation de votre commande

Bonjour {{ $order->user->prenom ?? $order->user->name }},

Merci pour votre commande n°{{ $order->id }} passée le {{ $order->created_at->format('d/m/Y H:i') }}.

**Total :** {{ $order->total }} €
**Statut :** {{ ucfirst($order->status) }}
**Paiement :** {{ $order->payment_method == 'online' ? 'En ligne' : 'Espèces à la livraison' }}

## Adresse de livraison
{{ $order->address->address_line1 ?? '' }}<br>
Téléphone : {{ $order->address->phone ?? '' }}

## Articles commandés
@component('mail::table')
| Produit | Prix | Quantité | Sous-total |
|---------|------|----------|------------|
@foreach($order->items as $item)
| {{ $item->product->name ?? 'Produit supprimé' }} | {{ $item->price }} € | {{ $item->quantity }} | {{ $item->price * $item->quantity }} € |
@endforeach
@endcomponent

Nous vous tiendrons informé(e) de l'évolution de votre commande.

Merci pour votre confiance !

@endcomponent
