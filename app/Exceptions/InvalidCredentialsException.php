<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class InvalidCredentialsException extends Exception
{
    use ApiResponse;


    public function render()
    {
        return $this->error($this->message,
            401
        );
    }
}
