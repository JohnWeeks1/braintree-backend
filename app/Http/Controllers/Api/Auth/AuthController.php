<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\JsonResponse;
use App\Services\User\UserService;
use App\Http\Controllers\Controller;
use App\Http\Responses\SuccessResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    /**
     * User service instance.
     *
     * @var UserService
     */
    protected $userService;

    /**
     * AuthController constructor.
     *
     * @param UserService $userService
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Login user.
     *
     * @param LoginRequest $request
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // Get email and password and see if valid
        if(!auth()->attempt($request->only(['email', 'password']))) {
            return response()->json(['message' => 'Unauthorized'], 500);
        }

        return response()->json(['message' => 'Success'], 200);
    }

    /**
     * Register new user.
     *
     * @param RegisterRequest $request
     *
     * @return SuccessResponse
     */
    public function register(RegisterRequest $request): SuccessResponse
    {
        $this->userService->createUser($request);

        return new SuccessResponse('User created!');
    }

    /**
     * Logout user.
     */
    public function logout(): void
    {
        auth()->logout();
    }
}
