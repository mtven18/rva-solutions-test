<?php

namespace App\Services;

use App\Models\User;
use App\Services\AuthService\AuthService;

class UserService
{
    public function register(array $data)
    {
        unset($data['balance']); // prohibit saving balance for a new user
        return User::query()->create($data);
    }

    /**
     * @return AuthService
     */
    private function getAuth(): AuthService
    {
        return app(AuthService::class);
    }
}
