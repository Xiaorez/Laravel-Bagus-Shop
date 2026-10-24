<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Menampilkan semua produk.
     */
    public function index()
    {
        return ProductResource::collection(
            Product::with('category')->get()
        );
    }

    /**
     * Menambahkan produk baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,category_id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'img' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imgPath = null;

        if ($request->hasFile('img')) {
            $imgPath = $request->file('img')->store('products', 'public');
        }

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'img' => $imgPath,
        ]);

        return new ProductResource($product->load('category'));
    }

    /**
     * Menampilkan satu produk.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return new ProductResource($product->load('category'));
    }

    /**
     * Memperbarui produk.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,category_id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'img' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imgPath = $product->img;

        if ($request->hasFile('img')) {
            if ($product->img) {
                Storage::disk('public')->delete($product->img);
            }

        $imgPath = $request->file('img')->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'img' => $imgPath,
        ]);

        return new ProductResource($product->load('category'));
    }

    /**
     * Menghapus produk.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }
}
