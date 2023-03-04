<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ForbiddenException extends Exception
{
    use ApiResponse;
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function render(Request $request) : JsonResponse
    {
        return $this->error("You dont have access.",403);
    }
}
