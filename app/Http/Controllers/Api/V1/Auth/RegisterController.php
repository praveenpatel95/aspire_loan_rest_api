<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\Auth\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    use ApiResponse;

    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /** Create account as a customer
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function createCustomer(UserRequest $request) : JsonResponse
    {
        return $this->success($this->authService->create($request->all(), 'CUSTOMER'));
    }

    /**
     * Create account as a admin
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function createAdmin(UserRequest $request) : JsonResponse
    {
        return $this->success($this->authService->create($request->all(), 'ADMIN'));
    }
}
