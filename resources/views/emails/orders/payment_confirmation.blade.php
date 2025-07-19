@component('mail::message')
# Confirmation de paiement

Bonjour {{ $order->user->prenom ?? $order->user->name }},

Nous confirmons la réception de votre paiement pour la commande n°{{ $order->id }}.

**Montant payé :** {{ $order->total }} €
**Mode de paiement :** {{ $order->payment_method == 'online' ? 'En ligne' : 'Espèces à la livraison' }}

@component('mail::panel')
Votre commande est en cours de traitement et vous serez informé(e) de son expédition.
@endcomponent

Merci pour votre confiance !

@endcomponent
