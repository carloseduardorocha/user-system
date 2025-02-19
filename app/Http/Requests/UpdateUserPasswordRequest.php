<?php

namespace App\Http\Requests;

use App\Traits\IsApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPasswordRequest extends FormRequest
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
            'current_password' => ['required', 'string'],
            'new_password'     => ['required', 'string', 'min:6', 'max:20', 'regex:/[A-Z]/', 'regex:/[a-z]/', 'regex:/[^a-zA-Z0-9]/'],
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
            'current_password.required' => 'The current password field is required.',
            'new_password.required'     => 'The new password field is required.',
            'new_password.min'          => 'The new password must be at least 6 characters long.',
            'new_password.max'          => 'The new password must not exceed 20 characters.',
            'new_password.regex'        => 'The new password must contain at least one uppercase letter, one lowercase letter, and one special character.',
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
            'current_password' => (isset($this->current_password) && is_string($this->current_password)) ? (string) trim(strip_tags($this->current_password)) : null,
            'new_password'     => (isset($this->new_password) && is_string($this->new_password)) ? (string) trim(strip_tags($this->new_password)) : null,
        ];

        $this->removeNullFields($data);
        $this->merge($data);
    }
}
