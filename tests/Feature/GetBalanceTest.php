<?php

namespace Tests\Feature;

use App\Models\Wallet;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class GetBalanceTest extends TestCase
{
    /**
     * User id is required in get-balance api
     *
     * @test
     */
    public function user_id_field_is_required(): void
    {
        // Arrange

        // Act
        $response = $this->getJson(route('api.get_balance'));

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
     * User id must be an integer in get-balance api
     *
     * @test
     */
    public function user_id_field_must_be_an_integer(): void
    {
        // Arrange

        // Act
        $response = $this->getJson(route('api.get_balance', [
            'user_id' => Str::random(5),
        ]),
        );

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
     * Get specific user wallet balance
     *
     * @test
     */
    public function get_user_balance_successfully(): void
    {
        // Arrange
        /** @var Wallet $wallet */
        $wallet = Wallet::factory()->create();

        // Act
        $response = $this->getJson(route('api.get_balance', [
            'user_id' => $wallet->user_id,
        ]));

        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'status' => true,
                'message' => 'User balance received',
            ])->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'balance',
                ],
            ]);

        $this->assertEquals($wallet->balance, $response->json('data.balance'));
    }
}
