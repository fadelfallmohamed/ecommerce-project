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
    public function index()
    {
        $orders = Auth::user()->orders()->with(['address', 'invoice'])->orderBy('created_at', 'desc')->get();
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
            'status' => 'en attente',
            'payment_method' => $request->payment_method,
            'is_paid' => $request->payment_method === 'online' ? false : false,
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

        // Envoi de l'email de confirmation
        Mail::to($user->email)->send(new OrderConfirmation($order->fresh(['user','address','items.product'])));

        // Vider le panier
        session()->forget('cart');

        return redirect()->route('orders.index')->with('success', 'Commande passée avec succès !');
    }

    public function show(Order $order)
    {
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
} 