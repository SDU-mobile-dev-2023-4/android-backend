<?php

namespace App\Http\Requests\api\Expense;

use Illuminate\Foundation\Http\FormRequest;

class GroupStoreExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payee_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
        ];
    }
}
