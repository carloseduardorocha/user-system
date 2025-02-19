<?php

namespace App\Traits;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;

trait IsApiRequest
{
    /**
     * Handle a failed validation attempt.
     *
     * @param  Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function failedValidation(Validator $validator): void
    {
        ApiResponse::jsonRequestValidationError($validator);
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization(): void
    {
        ApiResponse::jsonRequestForbidden();
    }

    /**
     * Remove null fields in data array
     *
     * @param array<mixed> $data
     * @return array<mixed>
     */
    protected function removeNullFields(array &$data): array
    {
        $data = array_filter($data, function($value) {
            return $value !== null;
        });

        return $data;
    }
}
