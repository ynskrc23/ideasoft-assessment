<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Tüm ürünleri listeleme
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    // Yeni ürün ekleme
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|integer',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $product = Product::create($data);
        return response()->json($product, 201);
    }

    // Belirli bir ürünü görüntüleme
    public function show(Product $product)
    {
        return response()->json($product);
    }

    // Ürün bilgilerini güncelleme
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'category' => 'sometimes|integer',
            'price' => 'sometimes|numeric',
            'stock' => 'sometimes|integer',
        ]);

        $product->update($data);
        return response()->json($product);
    }

    // Ürün silme
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->noContent();
    }
}