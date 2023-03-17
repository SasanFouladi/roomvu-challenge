<?php

namespace App\Services\Wallet;

use App\Exceptions\InsufficientFunds;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class WalletService implements WalletContract
{
    public function getBalance(int $userId): float
    {
        /** @var Wallet $wallet */
        $wallet = Wallet::query()->where('user_id', $userId)->first();

        return $wallet ? $wallet->balance : 0;
    }

    /**
     * @throws InsufficientFunds
     */
    public function addMoney(int $userId, float $amount): string
    {
        DB::beginTransaction();
        try {
            /** @var Wallet $wallet */
            $wallet = Wallet::query()->firstOrCreate(['user_id' => $userId]);
            if ($wallet->canTransaction($amount)) {
                throw new InsufficientFunds();
            }
            /** @var Transaction $transaction */
            $transaction = $wallet->transactions()->create([
                'type' => $this->getTransactionType($amount),
                'amount' => $amount,
            ]);

            $wallet->balance += $amount;
            $wallet->save();
            DB::commit();

            return $transaction->uuid;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function getTransactionType($amount): string
    {
        return $amount < 0 ? Transaction::TYPES['WITHDRAW'] : Transaction::TYPES['DEPOSIT'];
    }
}
