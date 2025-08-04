<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StockController extends Controller
{
    /**
     * Affiche la liste des stocks avec filtrage et pagination.
     */
    public function index(Request $request)
    {
        $query = Stock::with(['product', 'updatedBy'])
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = '%' . $request->search . '%';
                $q->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', $search)
                      ->orWhere('sku', 'like', $search)
                      ->orWhere('barcode', 'like', $search);
                });
            });

        $stocks = $query->latest()->paginate(15);
        
        return view('admin.stocks.index', compact('stocks'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau stock.
     */
    public function create()
    {
        $products = Product::whereDoesntHave('stock')->pluck('name', 'id');
        return view('admin.stocks.create', compact('products'));
    }

    /**
     * Enregistre un nouveau stock en base de données.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id|unique:stocks,product_id',
            'quantity' => 'required|integer|min:0',
            'alert_quantity' => 'required|integer|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|max:100|unique:stocks,sku',
            'barcode' => 'nullable|string|max:100|unique:stocks,barcode',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Générer automatiquement un SKU s'il n'est pas fourni
        if (empty($validated['sku'])) {
            $product = Product::find($validated['product_id']);
            $validated['sku'] = strtoupper(Str::substr($product->name, 0, 3)) . '-' . $product->id;
        }

        $stock = Stock::create(array_merge($validated, [
            'last_updated_by' => auth()->id(),
            'last_restocked_at' => now(),
        ]));

        // Mise à jour du statut en fonction de la quantité
        $stock->updateStatus();
        $stock->save();

        return redirect()->route('admin.stocks.index')
            ->with('success', 'Le stock a été créé avec succès.');
    }

    /**
     * Affiche les détails d'un stock.
     */
    public function show(Stock $stock)
    {
        $stock->load(['product', 'updatedBy']);
        $stockHistory = []; // À implémenter avec un système d'historique si nécessaire
        
        return view('admin.stocks.show', compact('stock', 'stockHistory'));
    }

    /**
     * Affiche le formulaire de modification d'un stock.
     */
    public function edit(Stock $stock)
    {
        $stock->load('product');
        return view('admin.stocks.edit', compact('stock'));
    }

    /**
     * Met à jour un stock existant.
     */
    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
            'alert_quantity' => 'required|integer|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'sku' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('stocks', 'sku')->ignore($stock->id)
            ],
            'barcode' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('stocks', 'barcode')->ignore($stock->id)
            ],
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'adjustment_notes' => 'required_if:quantity,' . ($stock->quantity + 1) . '|string|nullable',
        ]);

        $quantityChanged = $validated['quantity'] != $stock->quantity;
        
        DB::transaction(function () use ($stock, $validated, $quantityChanged) {
            $stock->update($validated);
            
            if ($quantityChanged) {
                $stock->update([
                    'last_updated_by' => auth()->id(),
                    'last_restocked_at' => now(),
                ]);
                
                // Enregistrer l'historique des ajustements
                // À implémenter avec un système d'historique si nécessaire
            }
            
            $stock->updateStatus();
            $stock->save();
        });

        return redirect()->route('admin.stocks.index')
            ->with('success', 'Le stock a été mis à jour avec succès.');
    }

    /**
     * Supprime un stock.
     */
    public function destroy(Stock $stock)
    {
        // Vérifier s'il y a des mouvements de stock avant de supprimer
        // À implémenter avec un système d'historique si nécessaire
        
        $stock->delete();
        
        return redirect()->route('admin.stocks.index')
            ->with('success', 'Le stock a été supprimé avec succès.');
    }
    
    /**
     * Affiche le tableau de bord des stocks.
     */
    public function dashboard()
    {
        $stockStats = [
            'total_products' => Stock::count(),
            'in_stock' => Stock::inStock()->count(),
            'low_stock' => Stock::lowStock()->count(),
            'out_of_stock' => Stock::outOfStock()->count(),
        ];
        
        $lowStockItems = Stock::with('product')
            ->where('quantity', '<=', DB::raw('alert_quantity'))
            ->orderBy('quantity', 'asc')
            ->take(10)
            ->get();
            
        $recentlyUpdated = Stock::with(['product', 'updatedBy'])
            ->latest('updated_at')
            ->take(5)
            ->get();
        
        return view('admin.stocks.dashboard', compact('stockStats', 'lowStockItems', 'recentlyUpdated'));
    }
    
    /**
     * Affiche le formulaire d'ajustement de stock.
     */
    public function showAdjustForm(Product $product)
    {
        $stock = $product->stock;
        if (!$stock) {
            // Créer un enregistrement de stock s'il n'existe pas
            $stock = Stock::create([
                'product_id' => $product->id,
                'quantity' => 0,
                'alert_quantity' => 5,
                'status' => 'in_stock',
                'last_updated_by' => auth()->id()
            ]);
        }
        
        return view('admin.stock.edit', [
            'product' => $product->load('stock')
        ]);
    }
    
    /**
     * Traite la mise à jour du stock.
     */
    public function adjust(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
            'alert_quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:255',
        ]);
        
        $stock = $product->stock;
        
        if (!$stock) {
            $stock = new Stock([
                'product_id' => $product->id,
                'last_updated_by' => auth()->id(),
            ]);
        }
        
        // Enregistrer l'ancienne quantité pour le journal
        $oldQuantity = $stock->quantity ?? 0;
        $newQuantity = $validated['quantity'];
        
        // Mettre à jour le stock
        $stock->fill([
            'quantity' => $newQuantity,
            'alert_quantity' => $validated['alert_quantity'],
            'last_updated_by' => auth()->id(),
            'status' => $this->determineStockStatus($newQuantity, $validated['alert_quantity']),
        ]);
        
        $stock->save();
        
        // Enregistrer l'historique des modifications
        // À implémenter avec un système d'historique si nécessaire
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Le stock a été mis à jour avec succès.');
    }
    
    /**
     * Détermine le statut du stock en fonction de la quantité et du seuil d'alerte.
     */
    protected function determineStockStatus($quantity, $alertQuantity)
    {
        if ($quantity <= 0) {
            return 'out_of_stock';
        }
        
        if ($quantity <= $alertQuantity) {
            return 'low_stock';
        }
        
        return 'in_stock';
    }
}
