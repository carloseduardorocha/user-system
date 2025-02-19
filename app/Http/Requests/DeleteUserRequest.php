<?php

namespace App\Http\Requests;

use App\Traits\IsApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class DeleteUserRequest extends FormRequest
{
    use IsApiRequest;

    /** Determine if the user is authorized to make this request. */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'uuid' => ['required', 'string', 'uuid'],
        ];
    }

    /**
     * Get the custom error messages for the validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'uuid.required' => 'The uuid field is required.',
            'uuid.uuid'     => 'The uuid must be a valid UUID.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $data = [
            'uuid' => (isset($this->uuid) && is_string($this->uuid)) ? (string) trim(strip_tags($this->uuid)) : null,
        ];

        $this->removeNullFields($data);
        $this->merge($data);
    }
}
