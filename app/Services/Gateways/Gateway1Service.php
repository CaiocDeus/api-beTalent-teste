<?php

namespace App\Services\Gateways;

use App\Interfaces\GatewayInterface;
use Illuminate\Support\Facades\Http;

class Gateway1Service implements GatewayInterface
{
    // TODO Transformar em variavel de ambiente
    private $url = 'http://localhost:3001/transactions';
    private $logindUrl = 'http://localhost:3001/login';
    private $credentialsLogin = ["email" => "dev@betalent.tech", "token" => "FEC9BB078BF338F464F96B48089EB498"];
    private $headers = [];

    public function __construct()
    {
        $token = $this->login();

        $this->headers = ['Authorization' => `Bearer $token`];
    }

    public function createTransaction(array $transactionData): int
    {
        $response = Http::withHeaders($this->headers)->post($this->url, $transactionData);

        if ($response->failed()) {
            throw new \Exception('Erro ao criar transação no Gateway 1.');
        }

        return $response->json()['id'];
    }

    public function refundTransaction(int $transaction_id)
    {
        $response = Http::withHeaders($this->headers)->post(`$this->url/$transaction_id/charge_back`);

        if ($response->failed()) {
            throw new \Exception('Erro ao processar reembolso no Gateway 1.');
        }

        return $response->json();
    }

    public function listTransactions()
    {
        $response = Http::withHeaders($this->headers)->get($this->url);

        if ($response->failed()) {
            throw new \Exception('Erro ao listar transações no Gateway 1.');
        }

        return $response->json();
    }

    private function login(): string
    {
        $response = Http::withHeaders($this->headers)->post($this->logindUrl, $this->credentialsLogin);

        if ($response->failed()) {
            throw new \Exception('Erro ao efetuar login no Gateway 1.');
        }

        return $response->json()['token'];
    }
}
