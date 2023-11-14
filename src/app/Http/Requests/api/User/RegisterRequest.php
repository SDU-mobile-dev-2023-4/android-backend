<?php

namespace App\Http\Requests\api\User;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public endpoint
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:1|max:255',
            'email' => 'required|email:rfc,dns|unique:users,email|max:255',
            'password' => 'required|min:6|max:255',
            'device_name' => 'required|min:2|max:255',
        ];
    }
}
