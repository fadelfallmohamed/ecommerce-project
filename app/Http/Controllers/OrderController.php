<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdated;

class OrderController extends Controller
{
    /**
     * Affiche la liste des commandes de l'utilisateur connecté
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupérer uniquement les commandes de l'utilisateur connecté avec pagination
        $orders = Auth::user()
            ->orders()
            ->with(['invoice']) // Charger uniquement la relation invoice pour l'icône PDF
            ->orderBy('created_at', 'desc')
            ->paginate(10) // 10 commandes par page
            ->withQueryString(); // Conserver les paramètres de requête lors de la pagination
            
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        return view('orders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'phone' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $user = Auth::user();
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Création de l'adresse de livraison
        $address = Address::create([
            'user_id' => $user->id,
            'address_line1' => $request->address,
            'address_line2' => null,
            'city' => '',
            'postal_code' => '',
            'country' => '',
            'phone' => $request->phone,
        ]);

        // Création de la commande
        $order = Order::create([
            'user_id' => $user->id,
            'address_id' => $address->id,
            'total' => $total,
            'status' => 'en_attente',
            'payment_method' => $request->payment_method === 'online' ? 'en_ligne' : 'a_la_livraison',
            'is_paid' => $request->payment_method === 'online',
        ]);

        // Création des OrderItems
        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Création d'une facture pour la commande
        if ($order->status === 'en_attente') {
            // Créer un chemin par défaut pour la facture
            $filename = 'invoices/facture-' . $order->id . '-' . now()->format('Y-m-d') . '.pdf';
            
            // Créer la facture avec un chemin par défaut
            $invoice = $order->invoice()->create([
                'invoice_date' => now(),
                'status' => 'pending',
                'pdf_path' => $filename, // Chemin par défaut, le fichier sera généré plus tard
            ]);
            
            // Créer le répertoire s'il n'existe pas
            $directory = storage_path('app/public/invoices');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Notifier l'administrateur qu'une nouvelle facture nécessite une signature
            $admin = \App\Models\User::where('is_admin', true)->first();
            if ($admin) {
                $admin->notifications()->create([
                    'order_id' => $order->id,
                    'type' => 'new_invoice',
                    'message' => 'Une nouvelle facture #' . $invoice->id . ' nécessite une signature pour la commande #' . $order->id,
                    'is_read' => false,
                ]);
            }
        }

        // Envoi de l'email de confirmation (désactivé temporairement)
        // Mail::to($user->email)->send(new OrderConfirmation($order->fresh(['user','address','items.product'])));

        // Vider le panier
        session()->forget('cart');

        return redirect()->route('orders.index')
            ->with('success', 'Commande passée avec succès ! Votre facture sera disponible une fois la commande validée par notre équipe.');
    }

    public function show(Order $order)
    {
        // Vérifier que l'utilisateur est autorisé à voir cette commande
        if (auth()->id() !== $order->user_id && !auth()->user()->is_admin) {
            abort(403, 'Accès non autorisé à cette commande.');
        }

        // Charger les relations nécessaires
        $order->load(['items.product', 'address', 'invoice']);
        
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'address_id' => 'required|exists:addresses,id',
            'total' => 'required|numeric',
            'status' => 'required|string',
            'payment_method' => 'required|string',
            'is_paid' => 'required|boolean',
        ]);
        $order->update($validated);

        // Envoi de l'email de notification de statut
        Mail::to($order->user->email)->send(new OrderStatusUpdated($order->fresh(['user','address','items.product'])));

        return redirect()->route('orders.index');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index');
    }

    /**
     * Met à jour le statut d'une commande (pour les administrateurs)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Vérifier que l'utilisateur est administrateur
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403, 'Action non autorisée.');
        }

        $request->validate([
            'status' => 'required|in:en_attente,en_cours_de_livraison,livrée,annulée'
        ]);

        // Sauvegarder l'ancien statut pour la notification
        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Mettre à jour le statut de la commande
        $order->update(['status' => $newStatus]);

        // Si la commande est marquée comme livrée, marquer également la facture comme signée si elle existe
        if ($newStatus === 'livrée' && $order->invoice) {
            $order->invoice->update([
                'status' => 'signed',
                'signed_by' => auth()->id(),
                'signed_at' => now()
            ]);
        }

        // Envoyer une notification à l'utilisateur si le statut a changé
        if ($oldStatus !== $newStatus) {
            $message = match($newStatus) {
                'en_attente' => "Votre commande #{$order->id} est en attente de traitement.",
                'en_cours_de_livraison' => "Votre commande #{$order->id} est en cours de livraison.",
                'livrée' => "Votre commande #{$order->id} a été livrée. La facture est disponible dans votre espace client.",
                'annulée' => "Votre commande #{$order->id} a été annulée. Contactez-nous pour plus d'informations.",
                default => "Le statut de votre commande #{$order->id} a été mis à jour.",
            };

            $order->user->notifications()->create([
                'order_id' => $order->id,
                'type' => 'order_status_updated',
                'message' => $message,
                'read_at' => null,
            ]);

            // Envoyer un email de notification (désactivé temporairement pendant le développement)
            // Mail::to($order->user->email)->send(new OrderStatusUpdated($order));
        }

        return redirect()->back()
            ->with('success', "Le statut de la commande a été mis à jour avec succès.");
    }
} 