<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        return view('orders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'address_id' => 'required|exists:addresses,id',
            'total' => 'required|numeric',
            'status' => 'required|string',
            'payment_method' => 'required|string',
            'is_paid' => 'required|boolean',
        ]);
        Order::create($validated);
        return redirect()->route('orders.index');
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
        return redirect()->route('orders.index');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index');
    }
} 