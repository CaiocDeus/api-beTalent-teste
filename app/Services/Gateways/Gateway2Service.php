<?php

namespace App\Services\Gateways;

use App\Interfaces\GatewayInterface;
use Illuminate\Support\Facades\Http;

class Gateway2Service implements GatewayInterface
{
    // TODO Transformar em variavel de ambiente
    private $url = 'http://localhost:3002/transacoes';
    private $headers = [
        'Gateway-Auth-Token' => 'tk_f2198cc671b5289fa856',
        'Gateway-Auth-Secret' => '3d15e8ed6131446ea7e3456728b1211f',
    ];

    public function createTransaction(array $transactionData): int
    {
        $response = Http::withHeaders($this->headers)->post($this->url, $this->ajustTransactionData($transactionData));

        if ($response->failed()) {
            throw new \Exception('Erro ao criar transação no Gateway 2.');
        }

        return $response->json()['id'];
    }

    public function refundTransaction(int $transaction_id)
    {
        $response = Http::withHeaders($this->headers)->post(`$this->url/reembolso`, ["id" => $transaction_id]);

        if ($response->failed()) {
            throw new \Exception('Erro ao processar reembolso no Gateway 2.');
        }

        return $response->json();
    }

    public function listTransactions()
    {
        $response = Http::withHeaders($this->headers)->get($this->url);

        if ($response->failed()) {
            throw new \Exception('Erro ao listar transações no Gateway 2.');
        }

        return $response->json();
    }

    private function ajustTransactionData(array $transactionData) {
        return [
            'nome' => $transactionData['name'],
            'email' => $transactionData['email'],
            'valor' => $transactionData['amount'],
            'numeroCartao' => $transactionData['cardNumber'],
            'cvv' => $transactionData['cvv'],
        ];
    }
}
