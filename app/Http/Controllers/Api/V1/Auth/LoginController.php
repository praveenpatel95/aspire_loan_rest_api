<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\Auth\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    use ApiResponse;
    protected AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Login user
     * @param LoginRequest $request
     * @return JsonResponse
     */

    public function login(LoginRequest $request) : JsonResponse
    {
        return $this->success($this->authService->login($request->all()));
    }
}
