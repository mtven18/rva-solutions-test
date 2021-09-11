<?php

namespace App\Services\AuthService;

use App\Models\User;
use Illuminate\Support\Str;

class SanctumTokenAuth extends AuthService
{
    /**
     * @inheritDoc
     */
    public function login(string $username, string $password)
    {
        $user = $this->findUser($username);
        if (! $user || ! $this->checkPassword($user, $password)) {
            return false;
        }

        return [
            'token' => $user->createToken(Str::uuid())->plainTextToken
        ];
    }

    /**
     * @inheritDoc
     */
    public function logout(User $user)
    {
        $user->currentAccessToken()->delete();
    }

    /**
     * @inheritDoc
     */
    public function user()
    {
        return $this->getGuard()->user();
    }
}
