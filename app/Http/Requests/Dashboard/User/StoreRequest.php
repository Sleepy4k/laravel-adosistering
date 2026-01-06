<?php

namespace App\Http\Requests\Dashboard\User;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('web')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Data Wajib
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'phone' => ['required', 'string', 'max:15', Rule::unique(User::class)],
            'user_type_id' => ['required', Rule::exists(UserType::class, 'id')],

            // Data Optional
            'familiar_name' => ['nullable', 'string', 'max:100'],
            'gender' => ['nullable', 'string', 'in:male,female'],
            'date_of_birth' => ['nullable', 'date'],
            'other_phone' => ['nullable', 'string', 'max:15'],
            'occupation' => ['nullable', 'string', 'max:100'],
            'domicile' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],

            // Kredensial
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
