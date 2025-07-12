<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('invoices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'pdf_path' => 'required|string',
            'invoice_date' => 'required|date',
        ]);
        Invoice::create($validated);
        return redirect()->route('invoices.index');
    }

    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'pdf_path' => 'required|string',
            'invoice_date' => 'required|date',
        ]);
        $invoice->update($validated);
        return redirect()->route('invoices.index');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index');
    }
} 