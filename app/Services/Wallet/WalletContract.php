<?php

namespace App\Services\Wallet;

interface WalletContract
{
    public function getBalance(int $userId): float;

    public function addMoney(int $userId, float $amount): string;
}
