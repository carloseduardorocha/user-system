<?php

namespace App\Exceptions\Controllers;

use Exception;

use App\Helpers\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class InvalidUUIDException extends Exception
{
    /** Convert exception into HTTP response */
    public function render(): JsonResponse
    {
        return ApiResponse::jsonException(Response::HTTP_BAD_GATEWAY, 'Invalid UUID.', class_basename($this));
    }
}
