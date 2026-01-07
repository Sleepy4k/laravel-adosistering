<?php

namespace App\Http\Requests\Dashboard\Profile;

use App\Models\User;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
    public function rules(#[RouteParameter('profileType')] string $type): array
    {
        $userId = auth('web')->id();

        return match ($type) {
            'basic' => [
                'name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'email', 'max:255', Rule::unique(User::class, 'email')->ignore($userId, 'id')],
                'phone' => ['required', 'string', 'max:150', Rule::unique(User::class, 'phone')->ignore($userId, 'id')],
                'address' => ['required', 'string', 'max:150'],
            ],
            'other' => [
                'gender' => ['nullable', 'in:male,female'],
                'date_of_birth' => ['nullable', 'date'],
                'occupation' => ['nullable', 'string', 'max:150'],
                'domicile' => ['nullable', 'string', 'max:150'],
            ],
            'credential' => [
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ],
            default => [],
        };
    }
}
