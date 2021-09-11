<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\User\AuthUserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    private $service;

    /**
     * UserController constructor.
     *
     * @param UserService $service
     *
     * @return void
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Register new user.
     *
     * @param RegisterRequest $request
     *
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $this->service->register($request->validated());
        return response()->json([
            'message' => __('app.register_success')
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * User login.
     *
     * @param LoginRequest $request
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->service->getAuth()
            ->login($request->username, $request->password);

        if ($result === false) {
            return response()->json([
                'message' => __('app.unauthenticated')
            ]);
        }

        return response()->json($result);
    }

    /**
     * Authenticated user.
     *
     * @return AuthUserResource
     */
    public function me(): AuthUserResource
    {
        return new AuthUserResource(
            $this->service->getAuth()->user()
        );
    }
}
