<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Services\Gateways\GatewayManager;
use App\Http\Requests\Transaction\TransactionStoreRequest;
use Illuminate\Http\Resources\Json\JsonResource;


class TransactionController extends Controller
{

    protected $gatewayManager;

    public function __construct(GatewayManager $gatewayManager)
    {
        $this->gatewayManager = $gatewayManager;
    }

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
        $transaction = new Transaction;
        $products_bd = Product::whereIn('id', array_column($request->products, 'id'))->get();
        $amount = 0;
        $transaction_products = [];

        foreach ($request->products as $product) {
            $product_bd = $products_bd->where('id', $product['id'])->first();
            $amount += $product_bd->amount * $product['quantity'];
            $transaction_products = array_merge($transaction_products, [$product['id'] =>  ['quantity' => $product['quantity']]]);
        }

        $response = $this->gatewayManager->processPayment(array_merge(
            $request->all(),
            ['amount' => round($amount, 0)]
        ));

        $transaction->client_id = $request->client_id;
        $transaction->gateway_id = $response['gateway_id'];
        $transaction->external_id = $response['transaction_id'];
        $transaction->card_last_numbers = substr($request->cardNumber, -4);
        $transaction->amount = $amount;
        $transaction->status = 'paid';
        $transaction->save();
        $transaction->products()->sync($transaction_products);

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

        $this->gatewayManager->processRefund($transaction->external_id, $transaction->gateway_id);

        $transaction->status = 'charged_back';
        $transaction->save();

        return response()->json([
            "message" => "Transação reembolsada"
        ]);
    }
}
