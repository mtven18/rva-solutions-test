<?php

namespace App\Services;

use App\Models\User;
use App\Services\AuthService\AuthService;

class UserService
{
    /**
     * Register new user.
     *
     * @param array $data
     *
     * @return User
     */
    public function register(array $data): User
    {
        unset($data['balance']); // prohibit saving balance for a new user
        return User::query()->create($data);
    }

    /**
     * @return AuthService
     */
    public function getAuth(): AuthService
    {
        return app(AuthService::class);
    }
}
