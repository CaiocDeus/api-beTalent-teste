<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\Client\ClientStoreRequest;
use App\Http\Requests\Client\ClientUpdateRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return JsonResource::collection(Client::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientStoreRequest $request)
    {
        $client = Client::create($request->all());

        return response()->json([
            "id" => $client->id,
            "message" => "Cliente criado"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new JsonResource(Client::findOrFail($id));
    }

    public function clientWithTransactions(string $id)
    {
        $client = Client::with('transactions')->findOrFail($id);

        return new JsonResource($client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientUpdateRequest $request, string $id)
    {
        $client = Client::findOrFail($id);

        $client->fill($request->all());
        $client->save();

        return response()->json([
            "message" => 'Cliente atualizado'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = Client::findOrFail($id);

        $client->delete();

        return response()->json([
            "message" => "Cliente deletado"
        ]);
    }
}
