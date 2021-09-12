<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TransactionsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * A feature test user transactions.
     *
     * @return void
     */
    public function test_user_transactions()
    {
        $startBalance = 100;
        $systemIn = 150.05;
        $out = 50.05;

        /*** @var User $user */
        $user = User::factory()->create();
        $user->balance = $startBalance;
        $user->save();
        Sanctum::actingAs($user);

        /*** @var User $recipient */
        $recipient = User::factory()->create();

        // System up balance
        $this->artisan('balance:up', [
            'username' => $user->name,
            'amount' => $systemIn
        ])->assertExitCode(0);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'balance' => $startBalance + $systemIn
        ]);

        sleep(1);

        // Out amount from balance to recipient
        $this->postJson('/api/finance/transfer', [
            'username' => $recipient->name,
            'sum' => $out
        ])->assertCreated();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'balance' => $startBalance + $systemIn - $out
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $recipient->id,
            'balance' => $out
        ]);

        // Check transactions
        $this->getJson('/api/finance/transfer')
            ->assertJson(function (AssertableJson $json) use ($systemIn, $out, $recipient, $user) {
                $json->has('data.0', function (AssertableJson $json) use ($out, $recipient, $user) {
                    $json->where('sum', $out)
                        ->where('from.name', $user->name)
                        ->where('to.name', $recipient->name)
                        ->etc();
                })->has('data.1', function (AssertableJson $json) use ($systemIn, $user) {
                    $json->where('sum', $systemIn)
                        ->where('from.name', 'system')
                        ->where('to.name', $user->name)
                        ->etc();
                })->etc();
            });
    }

    /**
     * A feature test invalid up balance command data.
     *
     * @return void
     */
    public function test_invalid_command_data()
    {
        /*** @var User $user */
        $user = User::factory()->create();

        $this->artisan('balance:up', [
            'username' => $user->name,
            'amount' => 0
        ])->assertExitCode(1);
        $this->artisan('balance:up', [
            'username' => $this->faker->name,
            'amount' => 10
        ])->assertExitCode(1);
    }

    /**
     * A feature test invalid make transaction data.
     *
     * @return void
     */
    public function test_transaction_invalid_data()
    {
        /*** @var User $user */
        $user = User::factory()->create();
        $user->balance = 100;
        $user->save();
        Sanctum::actingAs($user);

        $this->postJson('/api/finance/transfer', [
            'username' => $this->faker->name,
            'sum' => 0
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'sum'
            ]);
    }
}
