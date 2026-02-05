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
        $dbSettings = IrrigationSetting::query()
            ->select('user_id', 'moisture_min', 'moisture_max', 'moisture_dry', 'moisture_normal', 'moisture_wet', 'safety_timeout_min', 'safety_timeout_max')
            ->where('user_id', auth('web')->id())
            ->first();

        // If no settings exist, create default settings
        if (!$dbSettings) {
            $dbSettings = IrrigationSetting::create([
                'user_id' => auth('web')->id(),
                'moisture_min' => 40,
                'moisture_max' => 80,
                'moisture_dry' => 20,
                'moisture_normal' => 50,
                'moisture_wet' => 80,
                'safety_timeout_min' => 1,
                'safety_timeout_max' => 3,
            ]);
        }

        $settings = [
            'kontrol_irigasi' => [
                'kelembaban_tanah' => [
                    'min' => $dbSettings->moisture_min ?? 40,
                    'max' => $dbSettings->moisture_max ?? 80,
                ],
                'kondisi_lahan' => [
                    'kering' => $dbSettings->moisture_dry ?? 20,
                    'lembab' => $dbSettings->moisture_normal ?? 50,
                    'basah' => $dbSettings->moisture_wet ?? 80,
                ],
            ],
            'safety_timeout' => [
                'pengaman_irigasi' => [
                    'min' => $dbSettings->safety_timeout_min ?? 1,
                    'max' => $dbSettings->safety_timeout_max ?? 3,
                ],
            ],
        ];

        return compact('settings');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $request, string $pengaturan): bool
    {
        $settings = IrrigationSetting::query()
            ->where('user_id', auth('web')->id())
            ->first();

        if (!$settings) {
            return false;
        }

        if ($pengaturan === 'control') {
            $settings->moisture_min = $request['moisture_min'];
            $settings->moisture_max = $request['moisture_max'];
            $settings->moisture_dry = $request['moisture_dry'];
            $settings->moisture_normal = $request['moisture_normal'];
            $settings->moisture_wet = $request['moisture_wet'];
        } elseif ($pengaturan === 'safety') {
            $settings->safety_timeout_min = $request['safety_timeout_min'];
            $settings->safety_timeout_max = $request['safety_timeout_max'];
        } else {
            return false;
        }

        return $settings->save();
    }
}
