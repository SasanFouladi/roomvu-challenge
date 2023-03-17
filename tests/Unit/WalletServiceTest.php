<?php

use App\Exceptions\InsufficientFunds;
use App\Models\Wallet;
use App\Services\Wallet\WalletService;
use Tests\TestCase;

class WalletServiceTest extends TestCase
{
    /**
     * Get balance for a user who does not wallet.
     *
     * @test
     */
    public function user_does_not_have_a_wallet(): void
    {
        $userId = rand(1, 1000);
        $walletService = new WalletService();
        $this->assertEquals(0, $walletService->getBalance($userId));
    }

    /**
     * User has wallet and get balance.
     *
     * @test
     */
    public function user_has_wallet_and_get_balance(): void
    {
        /** @var Wallet $wallet */
        $wallet = Wallet::factory()->create();
        $walletService = new WalletService();
        $this->assertEquals($wallet->balance, $walletService->getBalance($wallet->user_id));
    }

    /**
     * User can deposit money to wallet.
     *
     * @test
     *
     * @throws InsufficientFunds
     */
    public function user_deposit_money_to_wallet(): void
    {
        /** @var Wallet $wallet */
        $amount = rand(100, 1000);
        $wallet = Wallet::factory()->create(['balance' => 0]);
        $walletService = new WalletService();
        $walletService->addMoney($wallet->user_id, $amount);

        $this->assertEquals($amount, $walletService->getBalance($wallet->user_id));
    }

    /**
     * User can withdraw money from wallet.
     *
     * @test
     *
     * @throws InsufficientFunds
     */
    public function user_withdraw_money_from_wallet(): void
    {
        $amount = -1 * rand(100, 1000);
        /** @var Wallet $wallet */
        $wallet = Wallet::factory()->create(['balance' => 3000]);
        $walletService = new WalletService();
        $walletService->addMoney($wallet->user_id, $amount);

        $this->assertEquals($wallet->balance + $amount, $walletService->getBalance($wallet->user_id));
    }

    /**
     * User does not have enough money for withdraw.
     *
     * @test
     *
     * @throws InsufficientFunds
     */
    public function user_does_not_have_enough_money_for_withdraw(): void
    {
        $this->expectException(InsufficientFunds::class);

        $amount = -1 * rand(100, 1000);
        /** @var Wallet $wallet */
        $wallet = Wallet::factory()->create(['balance' => 50]);
        $walletService = new WalletService();
        $walletService->addMoney($wallet->user_id, $amount);
    }

    /**
     * User must have consistent balance and transactions.
     *
     * @test
     *
     * @throws InsufficientFunds
     */
    public function user_must_have_consistent_balance_and_transactions(): void
    {
        /** @var Wallet $wallet */
        $walletService = new WalletService();
        $wallet = Wallet::factory()->create(['balance' => 0]);

        foreach ($this->totalTestCases() as $testCase) {
            $walletService->addMoney($wallet->user_id, $testCase[0]);
            $this->assertEquals($walletService->getBalance($wallet->user_id), $testCase[1]);
        }
    }

    /**
     * Each item contains two values (amount, final balance)
     *
     * @return array[]
     */
    public function totalTestCases(): array
    {
        return[
            'deposit - 50' => [50, 50],
            'deposit - 40' => [40, 90],
            'deposit - 80' => [80, 170],
            'deposit - 100' => [100, 270],
            'withdraw - 130' => [-130, 140],
            'withdraw - 20' => [-20, 120],
            'withdraw - 60.4' => [-60.4, 59.6],
            'deposit - 4.4' => [4.8, 64.4],
        ];
    }
}
