<?php

namespace App\Http\Requests\Dashboard\IrrigationSetting;

use Illuminate\Container\Attributes\RouteParameter;
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
    public function rules(#[RouteParameter('pengaturan')] string $pengaturan): array
    {
        return match ($pengaturan) {
            'control' => [
                'moisture_min' => ['required', 'numeric', 'min:0', 'max:100'],
                'moisture_max' => ['required', 'numeric', 'min:0', 'max:100', 'gte:moisture_min'],
                'moisture_dry' => ['required', 'numeric', 'min:0', 'max:100'],
                'moisture_normal' => ['required', 'numeric', 'min:0', 'max:100', 'gte:moisture_dry'],
                'moisture_wet' => ['required', 'numeric', 'min:0', 'max:100', 'gte:moisture_normal'],
            ],
            'safety' => [
                'safety_timeout_min' => ['required', 'numeric', 'min:1', 'max:9'],
                'safety_timeout_max' => ['required', 'numeric', 'min:2', 'max:10', 'gte:safety_timeout_min'],
            ],
            default => [],
        };
    }
}
