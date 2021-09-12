<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * A feature test register success.
     *
     * @return void
     */
    public function test_register_success()
    {
        $name = $this->faker->name;
        $email = $this->faker->email;

        $this->postJson('/api/user/register', [
            'name' => $name,
            'email' => $email,
            'password' => 'password',
        ])->assertCreated()->assertJsonStructure([
            'message',
        ]);

        $this->assertDatabaseHas('users', compact('name', 'email'));
    }

    /**
     * A feature test register invalid data.
     *
     * @return void
     */
    public function test_register_invalid_data()
    {
        $this->postJson('/api/user/register', [
            'name' => null,
            'email' => 'fake',
            'password' => null,
        ])->assertStatus(422)->assertJsonValidationErrors([
            'name',
            'email',
            'password',
        ]);
    }

    /**
     * A feature test register unique user.
     *
     * @return void
     */
    public function test_register_unique_user()
    {
        /*** @var User $user */
        $user = User::factory()->create();

        $this->postJson('/api/user/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
        ])->assertStatus(422)->assertJsonValidationErrors([
            'name',
            'email',
        ]);
    }
}
