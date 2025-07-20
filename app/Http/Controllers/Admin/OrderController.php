<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'address', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'address', 'items.product', 'invoice']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:en_attente,en_cours_de_livraison,livrée,annulée',
            'notes' => 'nullable|string|max:1000'
        ]);

        $order->update([
            'status' => $request->status,
            'status_notes' => $request->notes
        ]);

        return back()->with('success', 'Statut de la commande mis à jour avec succès.');
    }
}
