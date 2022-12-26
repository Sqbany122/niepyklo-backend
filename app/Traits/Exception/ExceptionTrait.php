<?php

namespace App\Traits\Exception;

use Illuminate\Http\JsonResponse;

trait ExceptionTrait
{
    public function validationException($message): JsonResponse
    {
        return response()->json([
            'status_code' => 422,
            'message' => $message,
        ], 422);
    }

    public function serverErrorException(): JsonResponse
    {
        return response()->json([
            'status_code' => 500,
            'message' => __('messages.exceptions.something_went_wrong')
        ], 500);
    }
}
