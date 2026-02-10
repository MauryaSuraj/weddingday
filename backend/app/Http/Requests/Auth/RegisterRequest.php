<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * RegisterRequest
 * 
 * Form request for user registration validation.
 * 
 * Security:
 * - Server-side validation
 * - Strong password requirements
 * - Email uniqueness check
 */
class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;  // Public endpoint
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'unique:users',
                'max:255',
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(12)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'password.confirmed' => 'Passwords do not match.',
            'password.min' => 'Password must be at least 12 characters.',
        ];
    }
}
