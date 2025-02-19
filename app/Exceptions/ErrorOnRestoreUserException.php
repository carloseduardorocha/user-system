<?php

namespace App\Exceptions;

use Exception;

use App\Helpers\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class ErrorOnRestoreUserException extends Exception
{
    /** Convert exception into HTTP response */
    public function render(): JsonResponse
    {
        return ApiResponse::jsonException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Error on restore user.', class_basename($this));
    }
}
