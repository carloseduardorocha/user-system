<?php

namespace App\Http\Requests;

use App\Traits\IsApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'name'     => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:6', 'max:20', 'regex:/[A-Z]/', 'regex:/[a-z]/', 'regex:/[^a-zA-Z0-9]/'],
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
            'email.required'    => 'The email field is required.',
            'email.email'       => 'The value entered is not a valid email.',
            'email.unique'      => 'The email has already been taken.',
            'name.required'     => 'The name field is required.',
            'name.max'          => 'The name must not exceed 100 characters.',
            'password.required' => 'The password field is required.',
            'password.min'      => 'The password must be at least 6 characters long.',
            'password.max'      => 'The password must not exceed 20 characters.',
            'password.regex'    => 'The password must contain at least one uppercase letter, one lowercase letter, and one special character.',
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
            'email'    => (isset($this->email) && is_string($this->email)) ? (string) trim(strip_tags($this->email)) : null,
            'name'     => (isset($this->name) && is_string($this->name)) ? (string) trim(strip_tags($this->name)) : null,
            'password' => (isset($this->password) && is_string($this->password)) ? (string) trim(strip_tags($this->password)) : null,
        ];

        $this->removeNullFields($data);
        $this->merge($data);
    }
}
