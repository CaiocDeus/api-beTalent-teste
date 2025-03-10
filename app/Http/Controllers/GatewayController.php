<?php

namespace App\Http\Controllers;

use App\Models\Gateway;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class GatewayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return JsonResource::collection(Gateway::orderBy('priority', 'asc')->get());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function changeStatus(string $id)
    {
        $gateway = Gateway::findOrFail($id);

        $gateway->is_active = !$gateway->is_active;
        $gateway->save();

        $texto = $gateway->is_active ? 'ativado' : 'desativado';

        return response()->json([
            "message" => "Gateway {$texto}"
        ]);
    }

    // TODO Verificar
    public function changePriority(Request $request, string $id)
    {
        $gateway = Gateway::findOrFail($id);

        $priority = $request->priority;

        $gateways = Gateway::where([
            ['priority', $priority],
            ['is_active', true]
        ])->get();

        $gateway->priority = $priority;
        $gateway->save();

        DB::transaction(function () use ($gateways, $priority) {
            $count = 1;
            $gateways->each(function ($gateway) use ($count, $priority) {
                $gateway->priority = $count + $priority;
                $gateway->save();
                $count++;
            });
        });

        return response()->json([
            "message" => 'Prioridade do gateway alterada'
        ]);
    }
}
