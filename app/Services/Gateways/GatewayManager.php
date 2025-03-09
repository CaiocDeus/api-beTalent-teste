<?php

namespace App\Services\Gateways;

use App\Models\Client;
use App\Models\Gateway;

class GatewayManager
{
    private array $gatewayClassMap = [
        'Gateway 1' => Gateway1Service::class,
        'Gateway 2' => Gateway2Service::class,
    ];

    private array $gateways = [];

    public function __construct()
    {
        $this->loadActiveGateways();
    }

    /**
     * Carrega os gateways ativos do banco e instancia os serviços corretamente.
     */
    private function loadActiveGateways()
    {
        $gateways = Gateway::where('is_active', true)
            ->orderBy('priority', 'asc')
            ->get();

        foreach ($gateways as $gatewayConfig) {
            if (isset($this->gatewayClassMap[$gatewayConfig->name])) {
                $this->gateways[$gatewayConfig->id] = app($this->gatewayClassMap[$gatewayConfig->name]);
            }
        }
    }

    /**
     * Processa um pagamento, tentando os gateways ativos na ordem de prioridade.
     */
    public function processPayment(array $data)
    {
        $client = Client::firstOrFail($data['client_id']);

        unset($data['client_id']);
        $data['name'] = $client->name;
        $data['email'] = $client->email;

        foreach ($this->gateways as $gateway_id => $gateway) {
            if ($response = $gateway->createTransaction($data)) {
                return response()->json([
                    'message' => 'Pagamento realizado com sucesso',
                    'gateway_id' => $gateway_id,
                    $response
                ]);
            }
        }

        return response()->json(['error' => 'Falha em todos os gateways'], 400);
    }

    /**
     * Processa um reembolso, tentando os gateways na ordem de prioridade.
     */
    public function processRefund(int $transaction_id, int $gateway_id)
    {
        $gateway = $this->gateways[$gateway_id];

        if ($response = $gateway->refundTransaction($transaction_id)) {
            return response()->json([
                'message' => 'Reembolso realizado com sucesso',
                $response
            ]);
        }

        return response()->json(['error' => 'Falha ao processar o reembolso'], 400);
    }

    /**
     * Lista transações de um determinado gateway.
     */
    public function listTransactions()
    {
        $transactions = [];

        foreach ($this->gateways as $gateway_id => $gateway) {
            $transactions[] = [
                'gateway_id' => $gateway_id,
                'transactions' => $gateway->listTransactions()
            ];
        }

        return response()->json($transactions);
    }
}
