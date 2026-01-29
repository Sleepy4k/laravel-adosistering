<?php

namespace App\Services\Dashboard;

use App\Foundations\Service;
use App\Models\IrrigationSetting;

class IrrigationSettingService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        $settings = IrrigationSetting::query()
            ->select('user_id', 'moisture_min', 'moisture_max', 'moisture_dry', 'moisture_normal', 'moisture_wet', 'safety_timeout_min', 'safety_timeout_max')
            ->where('user_id', auth('web')->id())
            ->first();

        $settings = [
            'kontrol_irigasi' => [
                'kelembaban_tanah' => [
                    'min' => $settings->moisture_min,
                    'max' => $settings->moisture_max,
                ],
                'kondisi_lahan' => [
                    'kering' => $settings->moisture_dry,
                    'lembab' => $settings->moisture_normal,
                    'basah' => $settings->moisture_wet,
                ],
            ],
            'safety_timeout' => [
                'pengaman_irigasi' => [
                    'min' => $settings->safety_timeout_min,
                    'max' => $settings->safety_timeout_max,
                ],
            ],
        ];

        return compact('settings');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $request, int $id): bool
    {
        return false;
    }
}
