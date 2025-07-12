<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        return view('payments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|string',
            'amount' => 'required|numeric',
            'status' => 'required|string',
            'transaction_id' => 'nullable|string',
        ]);
        Payment::create($validated);
        return redirect()->route('payments.index');
    }

    public function show(Payment $payment)
    {
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        return view('payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|string',
            'amount' => 'required|numeric',
            'status' => 'required|string',
            'transaction_id' => 'nullable|string',
        ]);
        $payment->update($validated);
        return redirect()->route('payments.index');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index');
    }
} 