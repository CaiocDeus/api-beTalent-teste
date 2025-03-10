<?php

namespace App\Interfaces;

interface GatewayInterface
{
    public function createTransaction(array $transactionData): string;

    public function refundTransaction(string $transaction_id);

    public function listTransactions();
}
