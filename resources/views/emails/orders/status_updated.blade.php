@component('mail::message')
# Mise à jour de votre commande

Bonjour {{ $order->user->prenom ?? $order->user->name }},

Le statut de votre commande n°{{ $order->id }} a été mis à jour.

**Nouveau statut :** {{ ucfirst($order->status) }}

@component('mail::panel')
**Total :** {{ format_price(convert_euro_to_fcfa($order->total)) }}
**Paiement :** {{ $order->payment_method == 'online' ? 'En ligne' : 'Espèces à la livraison' }}
@endcomponent

## Articles commandés
@component('mail::table')
| Produit | Prix | Quantité | Sous-total |
|---------|------|----------|------------|
@foreach($order->items as $item)
| {{ $item->product->name ?? 'Produit supprimé' }} | {{ format_price(convert_euro_to_fcfa($item->price)) }} | {{ $item->quantity }} | {{ format_price(convert_euro_to_fcfa($item->price * $item->quantity)) }} |
@endforeach
@endcomponent

Merci pour votre confiance !

@endcomponent
