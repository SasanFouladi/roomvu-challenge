<?php

namespace Tests\Feature;

use App\Models\Wallet;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class AddMoneyTest extends TestCase
{
    /**
     * User id is required in add-money api
     *
     * @test
     */
    public function user_id_field_is_required(): void
    {
        // Arrange

        // Act
        $response = $this->postJson(route('api.add_money'), [
            'amount' => rand(1, 1000),
        ]);

        // Assert
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure()
            ->assertJsonValidationErrorFor('user_id')
            ->assertJsonValidationErrors([
                'user_id' => [
                    'The user id field is required.',
                ],
            ]);
    }

    /**
     * User id must be an integer in add-money api
     *
     * @test
     */
    public function user_id_field_must_be_an_integer(): void
    {
        // Arrange

        // Act
        $response = $this->postJson(route('api.add_money'), [
            'user_id' => Str::random(5),
            'amount' => rand(1, 1000),
        ]);

        // Assert
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure()
            ->assertJsonValidationErrorFor('user_id')
            ->assertJsonValidationErrors([
                'user_id' => [
                    'The user id must be an integer.',
                ],
            ]);
    }

    /**
     * amount is required in add-money api
     *
     * @test
     */
    public function amount_field_is_required(): void
    {
        // Arrange

        // Act
        $response = $this->postJson(route('api.add_money'), [
            'user_id' => rand(1, 1000),
        ]);

        // Assert
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure()
            ->assertJsonValidationErrorFor('amount')
            ->assertJsonValidationErrors([
                'amount' => [
                    'The amount field is required.',
                ],
            ]);
    }

    /**
     * amount must be numeric in add-money api
     *
     * @test
     */
    public function amount_field_must_be_numeric(): void
    {
        // Arrange

        // Act
        $response = $this->postJson(route('api.add_money', [
            'user_id' => rand(1, 1000),
            'amount' => Str::random(),
        ]),
        );

        // Assert
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure()
            ->assertJsonValidationErrorFor('amount')
            ->assertJsonValidationErrors([
                'amount' => [
                    'The amount must be a number.',
                ],
            ]);
    }

    /**
     * Deposit user wallet in add-money api
     *
     * @test
     */
    public function user_wallet_deposit(): void
    {
        // Arrange
        /** @var Wallet $wallet */
        $wallet = Wallet::factory()->create();
        $amount = rand(100, 200);

        // Act
        $response = $this->postJson(route('api.add_money'), [
            'user_id' => $wallet->user_id,
            'amount' => $amount,
        ]);
        $balanceResponse = $this->getJson(route('api.get_balance', [
            'user_id' => $wallet->user_id,
        ]));

        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => true,
                'message' => 'Change balance successfully',
            ])->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'reference_id',
                ],
            ]);

        $this->assertEquals($wallet->balance + $amount, $balanceResponse->json('data.balance'));
    }

    /**
     * Withdraw user wallet in add-money api
     *
     * @test
     */
    public function user_wallet_withdraw(): void
    {
        // Arrange
        /** @var Wallet $wallet */
        $wallet = Wallet::factory()->create([
            'balance' => rand(500, 1000),
        ]);
        $amount = -1 * rand(50, 400);

        // Act
        $response = $this->postJson(route('api.add_money'), [
            'user_id' => $wallet->user_id,
            'amount' => $amount,
        ]);
        $balanceResponse = $this->getJson(route('api.get_balance', [
            'user_id' => $wallet->user_id,
        ]));

        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => true,
                'message' => 'Change balance successfully',
            ])->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'reference_id',
                ],
            ]);

        $this->assertEquals($wallet->balance + $amount, $balanceResponse->json('data.balance'));
    }
}
