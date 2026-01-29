<?php

namespace App\Services\Dashboard;

use App\Foundations\Service;
use App\Models\Block;
use App\Models\IrrigationHistory;
use App\Models\Sprayer;
use Illuminate\Support\Facades\DB;

class HomeService extends Service
{
    /**
     * Handle the incoming request.
     */
    public function invoke(): array
    {
        $user = auth('web')->user();
        $userRole = $user->roles->first()->name;

        return match ($userRole) {
            config('rbac.role.default') => $this->getUserData(),
            config('rbac.role.highest') => $this->getSuperadminData(),
            default => $this->getAdminData(),
        };
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $request, string $block): bool
    {
        try {
            if (empty($request['sprayer']) || !is_array($request['sprayer'])) {
                return false;
            }

            $user = auth('web')->user();
            $userId = $user->id;
            $regionId = $user->region_id;

            DB::beginTransaction();

            foreach ($request['sprayer'] as $sprayerData) {
                $name = $sprayerData['name'] ?? null;
                if (!$name) {
                    continue;
                }

                $blockModel = Block::firstOrCreate(
                    ['user_id' => $userId, 'name' => $block],
                    ['region_id' => $regionId, 'code' => strtoupper(substr($block, 0, 3))]
                );

                $sprayerModel = Sprayer::firstOrCreate(
                    ['block_id' => $blockModel->id, 'name' => $name]
                );

                IrrigationHistory::create([
                    'sprayer_id'    => $sprayerModel->id,
                    'moisture'      => $sprayerData['moisture'] ?? null,
                    'flow_rate'     => $sprayerData['flow_rate'] ?? null,
                    'type'          => $sprayerData['irrigation_type'] ?? null,
                    'water_volume'  => $sprayerData['water_volume'] ?? null,
                    'irrigation_at' => $sprayerData['irrigation_at'] ?? null,
                    'stopped_at'    => $sprayerData['stopped_at'] ?? null,
                ]);
            }

            DB::commit();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Get data for regular user.
     */
    private function getUserData(): array
    {
        $firebase = $this->getFirebaseConfig();

        return compact('firebase');
    }

    /**
     * Get data for superadmin.
     */
    private function getSuperadminData(): array
    {
        $firebase = $this->getFirebaseConfig();

        return compact('firebase');
    }

    /**
     * Get data for admin.
     */
    private function getAdminData(): array
    {
        $firebase = $this->getFirebaseConfig();

        return compact('firebase');
    }

    /**
     * Get Firebase configuration.
     */
    private function getFirebaseConfig(): array
    {
        $firebaseConfig = config('firebase');

        return [
            'appId' => $firebaseConfig['app_id'],
            'apiKey' => $firebaseConfig['api_key'],
            'projectId' => $firebaseConfig['project_id'],
            'authDomain' => $firebaseConfig['auth_domain'],
            'databaseURL' => $firebaseConfig['database_url'],
            'storageBucket' => $firebaseConfig['storage_bucket'],
            'messagingSenderId' => $firebaseConfig['messaging_sender_id'],
        ];
    }
}
