<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'main_image' => 'nullable|image|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        // Traitement de l'image principale
        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('products', 'public');
            $validated['main_image'] = $path;
        }

        // Créer le produit
        $product = Product::create($validated);
        
        // Créer l'enregistrement de stock
        $product->stock()->create([
            'quantity' => $validated['quantity'],
            'alert_quantity' => 5, // Valeur par défaut
            'status' => $validated['quantity'] > 0 ? 'in_stock' : 'out_of_stock',
            'last_updated_by' => auth()->id(),
            'last_restocked_at' => now(),
            'selling_price' => $validated['price'],
        ]);
        
        // Synchroniser les catégories
        if (isset($validated['categories'])) {
            $product->categories()->sync($validated['categories']);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit ajouté avec succès.');
    }

    /**
     * Afficher les détails d'un produit avec ses photos.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        // Charger les photos du produit triées par ordre
        $product->load(['photos' => function($query) {
            $query->orderBy('order');
        }]);
        
        // Si le produit n'a pas de photo principale mais a des photos, on prend la première
        if (!$product->main_image && $product->photos->isNotEmpty()) {
            $product->main_image = $product->photos->first()->path;
        }
        
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = \App\Models\Category::all();
        $selectedCategories = $product->categories->pluck('id')->toArray();
        return view('products.edit', compact('product', 'categories', 'selectedCategories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:0',
            'main_image' => 'nullable|image|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        if ($request->hasFile('main_image')) {
            // Supprimer l'ancienne image si elle existe
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }
            $path = $request->file('main_image')->store('products', 'public');
            $validated['main_image'] = $path;
        }

        // Mettre à jour le produit
        $product->update($validated);
        
        // Mettre à jour la quantité en stock via la relation stock
        if ($product->stock) {
            $product->stock->update([
                'quantity' => $validated['quantity'],
                'last_updated_by' => auth()->id(),
                'last_restocked_at' => now(),
            ]);
        } else {
            // Créer un enregistrement de stock s'il n'existe pas
            $product->stock()->create([
                'quantity' => $validated['quantity'],
                'alert_quantity' => 5, // Valeur par défaut
                'status' => $validated['quantity'] > 0 ? 'in_stock' : 'out_of_stock',
                'last_updated_by' => auth()->id(),
                'last_restocked_at' => now(),
                'selling_price' => $validated['price'],
            ]);
        }
        
        // Synchroniser les catégories
        $product->categories()->sync($validated['categories'] ?? []);

        return redirect()->route('admin.products.index')->with('success', 'Produit modifié avec succès.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produit supprimé avec succès.');
    }

    public function catalogue(Request $request)
    {
        $query = Product::query();

        // Filtrage par catégorie
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Recherche par mots-clés
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%") ;
            });
        }

        $products = $query->paginate(9);
        $categories = \App\Models\Category::all();
        return view('catalogue.index', compact('products', 'categories'));
    }

    public function fiche(Product $product)
    {
        // Charger les photos du produit avec la relation 'photos' définie dans le modèle
        $product->load('photos');
        return view('catalogue.fiche', [
            'product' => $product,
            'images' => $product->photos // Utilisation de la relation 'photos' au lieu de 'images'
        ]);
    }
} 