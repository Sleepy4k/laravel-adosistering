<?php

namespace App\Http\Requests\Dashboard\Home;

use Illuminate\Foundation\Http\FormRequest;

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
    public function rules(): array
    {
        return [
            'sprayer' => ['required', 'array'],
            'sprayer.*.name' => ['required', 'string', 'max:255'],
            'sprayer.*.moisture' => ['required', 'numeric', 'min:0', 'max:100'],
            'sprayer.*.flow_rate' => ['required', 'numeric', 'min:0'],
            'sprayer.*.irrigation_type' => ['required', 'in:manual,automatic'],
            'sprayer.*.water_volume' => ['required', 'numeric', 'min:0'],
            'sprayer.*.irrigation_at' => ['required', 'date_format:Y-m-d H:i:s'],
            'sprayer.*.stopped_at' => ['nullable', 'date_format:Y-m-d H:i:s', 'after_or_equal:sprayer.*.irrigation_at'],
        ];
    }
}
