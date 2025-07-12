<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function index()
    {
        $images = ProductImage::all();
        return view('product_images.index', compact('images'));
    }

    public function create()
    {
        return view('product_images.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'image_path' => 'required|string',
        ]);
        ProductImage::create($validated);
        return redirect()->route('product_images.index');
    }

    public function show(ProductImage $productImage)
    {
        return view('product_images.show', compact('productImage'));
    }

    public function edit(ProductImage $productImage)
    {
        return view('product_images.edit', compact('productImage'));
    }

    public function update(Request $request, ProductImage $productImage)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'image_path' => 'required|string',
        ]);
        $productImage->update($validated);
        return redirect()->route('product_images.index');
    }

    public function destroy(ProductImage $productImage)
    {
        $productImage->delete();
        return redirect()->route('product_images.index');
    }
} 