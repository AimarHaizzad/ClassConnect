<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'mobile_phone' => ['required', 'string', 'max:20'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'password' => ['required', 'string', Password::defaults()],
            'user_id' => ['required', 'string', 'max:255', 'unique:users,user_id'],
            'user_type' => ['nullable', 'in:student,lecturer'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('user_type') || empty($this->input('user_type'))) {
            $this->merge(['user_type' => 'student']);
        }
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name.',
            'username.required' => 'Please enter a username.',
            'username.unique' => 'This username is already taken.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'mobile_phone.required' => 'Please enter your mobile phone number.',
            'date_of_birth.required' => 'Please enter your date of birth.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
            'password.required' => 'Please enter a password.',
            'user_id.required' => 'Please enter your user ID.',
            'user_id.unique' => 'This user ID is already registered.',
            'user_type.in' => 'Please select either student or lecturer.',
        ];
    }
}
