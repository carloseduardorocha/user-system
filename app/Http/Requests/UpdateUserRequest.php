<?php

namespace App\Http\Requests;

use App\Traits\IsApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'max:255'],
            'name'  => ['required', 'string', 'max:100'],
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
            'email.required' => 'The email field is required.',
            'email.email'    => 'The value entered is not a valid email.',
            'email.max'      => 'The email must not exceed 255 characters.',
            'name.required'  => 'The name field is required.',
            'name.max'       => 'The name must not exceed 100 characters.',
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
            'email' => (isset($this->email) && is_string($this->email)) ? (string) trim(strip_tags($this->email)) : null,
            'name'  => (isset($this->name) && is_string($this->name)) ? (string) trim(strip_tags($this->name)) : null,
        ];

        $this->removeNullFields($data);
        $this->merge($data);
    }
}
