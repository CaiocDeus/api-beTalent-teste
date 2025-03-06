<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return JsonResource::collection(Product::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        $product = new Product;
        $product->name = $request->name;
        $product->amount = $request->amount;
        $product->save();

        return response()->json([
            "id" => $product->id,
            "message" => "Produto criado"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new JsonResource(Product::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, string $id)
    {
        $product = Product::findOrFail($id);

        $product->name = is_null($request->name) ? $product->name : $request->name;
        $product->amount = is_null($request->amount) ? $product->amount : $request->amount;
        $product->save();

        return response()->json([
            "message" => 'Produto atualizado'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return response()->json([
            "message" => "Produto deletado"
        ]);
    }
}
