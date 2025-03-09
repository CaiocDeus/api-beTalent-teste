<?php

namespace App\Interfaces;

interface GatewayInterface
{
    public function createTransaction(array $transactionData);

    public function refundTransaction(int $transaction_id);

    public function listTransactions();
}
