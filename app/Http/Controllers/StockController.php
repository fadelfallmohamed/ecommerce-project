<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    /**
     * Afficher le formulaire de gestion du stock
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    /**
     * Afficher le formulaire de gestion du stock
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        return view('admin.stock.edit', [
            'product' => $product->load('stock')
        ]);
    }

    /**
     * Mettre à jour le stock du produit
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
            'alert_quantity' => 'required|integer|min:0',
            'status' => 'required|in:in_stock,low_stock,out_of_stock',
            'reason' => 'nullable|string|max:255',
        ]);

        $stock = $product->stock;
        
        if (!$stock) {
            $stock = new Stock([
                'product_id' => $product->id,
                'last_updated_by' => Auth::id(),
            ]);
        }

        // Enregistrer l'ancienne quantité pour le journal
        $oldQuantity = $stock->quantity ?? 0;
        $newQuantity = $validated['quantity'];
        
        // Mettre à jour le stock
        $stock->fill([
            'quantity' => $newQuantity,
            'alert_quantity' => $validated['alert_quantity'],
            'status' => $validated['status'],
            'last_updated_by' => Auth::id(),
            'last_restocked_at' => now(),
        ]);

        $stock->save();

        // Journalisation de la modification
        if ($oldQuantity != $newQuantity) {
            // Ici, vous pourriez enregistrer l'historique des modifications de stock
            // Exemple : StockHistory::create([...]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Le stock a été mis à jour avec succès.');
    }
}
