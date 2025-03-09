<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Http\Requests\Transaction\TransactionStoreRequest;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return JsonResource::collection(Transaction::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionStoreRequest $request)
    {
        // TODO Remove isso?
        $products_bd = Product::whereIn('id', array_column($request->products, 'id'))->get();
        $amount = 0;

        foreach ($request->products as $product) {
            $product_bd = $products_bd->where('id', $product['id'])->first();
            $amount += $product_bd->amount * $product['quantity'];
        }

        $transaction = new Transaction;
        $transaction->client_id = $request->client_id;
        $transaction->gateway_id = $request->gateway_id;
        $transaction->external_id = is_null($request->external_id) ? null : $request->external_id;
        $transaction->card_last_numbers = $request->card_last_numbers;
        $transaction->$amount = $amount;
        $transaction->status = 'completed';
        $transaction->save();

        return response()->json([
            "message" => "Transação efetuada"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = Transaction::with('products')->findOrFail($id);

        return new JsonResource($transaction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function refund(string $id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->status = 'refunded';
        $transaction->save();

        return response()->json([
            "message" => "Transação reembolsada"
        ]);
    }
}
