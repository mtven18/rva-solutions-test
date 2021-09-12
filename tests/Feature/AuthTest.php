<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * A feature test login success.
     *
     * @return void
     */
    public function test_login_success()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->postJson('/api/user/login', [
            'username' => $user->email,
            'password' => 'password',
        ])->assertOk()->assertJsonStructure([
            'token',
        ]);

        $this->getJson('/api/user/info', [
            'Authorization' => "Bearer {$response['token']}"
        ])->assertJson([
            'name' => $user->name,
            'email' => $user->security_email,
        ]);
    }

    /**
     * A feature test login invalid data.
     *
     * @return void
     */
    public function test_login_invalid_data()
    {
        $this->postJson('/api/user/login', [
            'username' => null,
            'password' => null,
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'username',
                'password',
            ]);
    }

    /**
     * A feature test failed login.
     *
     * @return void
     */
    public function test_login_failed()
    {
        /*** @var User $user */
        $user = User::factory()->create();

        $this->postJson('/api/user/login', [
            'username' => $this->faker->freeEmail,
            'password' => 'password',
        ])->assertUnauthorized();

        $this->postJson('/api/user/login', [
            'username' => $user->email,
            'password' => $this->faker->text(5),
        ])->assertUnauthorized();
    }

    /**
     * A feature test logout.
     *
     * @return void
     */
    public function test_logout()
    {
        /*** @var User $user */
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/user/logout')->assertNoContent();
    }
}
