<?php

namespace App\Services\AuthService;

use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Hash;

abstract class AuthService
{
    /**
     * Login the user.
     *
     * @param string $username
     * @param string $password
     *
     * @return array|bool
     */
    abstract public function login(string $username, string $password);

    /**
     * Logout the user.
     *
     * @param User $user
     *
     * @return void
     */
    abstract public function logout(User $user);

    /**
     * Find user.
     *
     * @param string $username
     *
     * @return User|null
     */
    final protected function findUser(string $username): ?User
    {
        return User::query()->whereUsername($username)->first();
    }

    /**
     * Check password.
     *
     * @param User   $user
     * @param string $password
     *
     * @return bool
     */
    protected function checkPassword(User $user, string $password): bool
    {
        return Hash::check($password, $user->password);
    }

    /**
     * @param string|null $guard
     *
     * @return Guard|StatefulGuard
     */
    final protected function getGuard(?string $guard = null)
    {
        return auth($guard);
    }
}
