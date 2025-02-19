<?php

namespace App\Helpers;

use App\Enums\Error;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponse
{
    /**
     * Returns JSON formated response.
     *
     * @param bool $success
     * @param mixed $data
     * @param string $message
     * @param string $error_slug
     * @return array{success: bool, data: mixed, message: string, error_slug?: string}
     */
    public static function format(bool $success, mixed $data, string $message = '', string $error_slug = ''): array
    {
        $response = [
            'data'    => $data,
            'success' => $success,
            'message' => empty($message) ? '' : addslashes($message),
        ];

        if (!$success)
        {
            $response['error_slug'] = empty($error_slug) ? '' : addslashes($error_slug);
        }

        return $response;
    }

    /**
     * Returns JSON formated response for Success.
     *
     * @param JsonResource $resource
     * @param int $status_code
     * @param string $message
     * @return JsonResponse
     */
    public static function jsonSuccess(JsonResource $resource, int $status_code, string $message): JsonResponse
    {
        return $resource->additional(self::format(true, [...$resource->additional], $message))
            ->response()
            ->setStatusCode($status_code);
    }

    /**
     * Returns JSON formated response for Error.
     *
     * @param JsonResource $resource
     * @param int $status_code
     * @param string $message
     * @return JsonResponse
     */
    public static function jsonError(JsonResource $resource, int $status_code, string $message): JsonResponse
    {
        return $resource->additional(self::format(false, [...$resource->additional], $message))
            ->response()
            ->setStatusCode($status_code);
    }

    /**
     * Returns JSON formated response for Exception.
     *
     * @param int $status_code
     * @param string $message
     * @param string $error_slug
     * @return JsonResponse
     */
    public static function jsonException(int $status_code, string $message, string $error_slug): JsonResponse
    {
        return response()
            ->json(self::format(false, [], $message, $error_slug), $status_code);
    }

    /**
     * Returns JSON formated response for FormRequest when API call has errors.
     *
     * @param Validator $validator
     * @return JsonResponse
     */
    public static function jsonRequestValidationError(Validator $validator): JsonResponse
    {
        throw new HttpResponseException(response()
            ->json(self::format(false, $validator->errors(), 'Request validation errors.', Error::REQUEST_VALIDATION_ERROR->value), Response::HTTP_UNPROCESSABLE_ENTITY));
    }

    /**
     * Returns JSON formated response for FormRequest when API call is forbidden.
     *
     * @return JsonResponse
     */
    public static function jsonRequestForbidden(): JsonResponse
    {
        throw new HttpResponseException(self::jsonException(Response::HTTP_FORBIDDEN, 'This action is forbidden.', Error::REQUEST_ACTION_FORBIDDEN->value));
    }
}
