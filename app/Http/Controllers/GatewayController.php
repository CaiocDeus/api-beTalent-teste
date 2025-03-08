<?php

namespace App\Http\Controllers;

use App\Models\Gateway;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GatewayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return JsonResource::collection(Gateway::all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new JsonResource(Gateway::findOrFail($id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function changeStatus(string $id)
    {
        $gateway = Gateway::findOrFail($id);

        $gateway->status = !$gateway->status;
        $gateway->save();

        $texto = $gateway->status ? 'ativado' : 'desativado';

        return response()->json([
            "message" => `Gateway $texto`
        ]);
    }

    // TODO Verificar
    public function changePriority(Request $request, string $id)
    {
        $gateway = Gateway::findOrFail($id);

        $priority = $request->priority;

        $gateways = Gateway::where('priority', $priority);

        foreach ($gateways as $key => &$gateway) {
            $gateway->priority = $key + 1 + $priority;
        }

        $gateway->priority = $priority;
        $gateway->save();

        $gateways->saveMany();

        return response()->json([
            "message" => 'Prioridade do gateway alterada'
        ]);
    }
}
