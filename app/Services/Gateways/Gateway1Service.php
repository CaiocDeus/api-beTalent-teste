<?php

namespace App\Services\Gateways;

use App\Interfaces\GatewayInterface;
use Illuminate\Support\Facades\Http;

class Gateway1Service implements GatewayInterface
{
    private $url = 'http://host.docker.internal:3001';
    private $credentialsLogin = ["email" => "dev@betalent.tech", "token" => "FEC9BB078BF338F464F96B48089EB498"];
    private $headers = [];

    public function __construct()
    {
        $this->url = env('URL_GATEWAY_1', 'http://host.docker.internal:3001');
        $token = $this->login();

        $this->headers = ['Authorization' => "Bearer {$token}"];
    }

    public function createTransaction(array $transactionData): string
    {
        $response = Http::withHeaders($this->headers)->post("{$this->url}/transactions", $transactionData);

        if ($response->failed()) {
            return false;
        }

        return $response->json()['id'];
    }

    public function refundTransaction(string $transaction_id)
    {
        $response = Http::withHeaders($this->headers)->post("{$this->url}/{$transaction_id}/charge_back");

        if ($response->failed()) {
            return false;
        }

        return $response->json();
    }

    public function listTransactions()
    {
        $response = Http::withHeaders($this->headers)->get("{$this->url}/transactions");

        if ($response->failed()) {
            throw new \Exception('Erro ao listar transações no Gateway 1.');
        }

        return $response->json();
    }

    private function login(): string
    {
        $response = Http::withHeaders($this->headers)->post("{$this->url}/login", $this->credentialsLogin);

        if ($response->failed()) {
            throw new \Exception('Erro ao efetuar login no Gateway 1.');
        }

        return $response->json()['token'];
    }
}
