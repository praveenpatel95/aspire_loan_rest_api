<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponse
{
    /**
     * Build a success response
     * @param $data
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data, $status = Response::HTTP_OK)
    {
        return response()->json(['success' => true, 'data' => $data], $status);
    }

    /**
     * Build error response
     * @param $message
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($message, $status)
    {
        return response()->json(['success' => false, 'message' => $message], $status);
    }
}
