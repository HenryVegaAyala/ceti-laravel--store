<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait JsonResponseTrait
{
    public function successResponse(mixed $data = [], int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $data,
            'code' => $code
        ];

        return response()->json($response);
    }

    public function errorResponse(mixed $message = [], int $code = 500): JsonResponse
    {
        $response = [
            'success' => false,
            'error' => $message,
            'code' => $code,
            'data' => []
        ];

        return response()->json($response, 500);
    }
}
