<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'wallet_id' => function () {
                return Wallet::factory()->create();
            },
            'type' => $this->faker->randomElement(Transaction::TYPES),
            'amount' => rand(1, 1000),
        ];
    }
}
