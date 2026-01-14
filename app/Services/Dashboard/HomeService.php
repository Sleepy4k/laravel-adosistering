<?php

namespace App\Services\Dashboard;

use App\Foundations\Service;
use App\Models\Block;

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
            config('rbac.role.default') => $this->getUserData($user->id),
            config('rbac.role.highest') => $this->getSuperadminData(),
            default => $this->getAdminData(),
        };
    }

    /**
     * Get data for regular user.
     */
    private function getUserData($userId): array
    {
        $coordinates = [];
        $data = Block::query()
            ->with('coordinate', 'sprayers', 'sprayers.sensor')
            ->where('user_id', $userId)
            ->get();

        foreach ($data as $block) {
            if ($block->coordinate) {
                $coordinates[] = [
                    'id' => $block->id,
                    'name' => $block->name,
                    'opacity' => $block->coordinate->opacity,
                    'marker' => $block->coordinate->marker,
                    'color' => $block->coordinate->color,
                    'points' => $block->coordinate->points,
                ];
            }
        }

        $blocks = $data->map(function ($block) {
            return [
                'blockId' => $block->id,
                'blockName' => $block->name,
                'avgHumidity' => $block->sprayers->flatMap(function ($sprayer) {
                    return $sprayer->sensor ? [$sprayer->sensor->humidity] : [];
                })->avg(),
                'avgFlowRate' => $block->sprayers->flatMap(function ($sprayer) {
                    return $sprayer->sensor ? [$sprayer->sensor->flow_rate] : [];
                })->avg(),
                'totalVolume' => $block->sprayers->flatMap(function ($sprayer) {
                    return $sprayer->sensor ? [$sprayer->sensor->volume] : [];
                })->sum(),
                'sprayers' => $block->sprayers->map(function ($sprayer) use ($block) {
                    return [
                        'id' => $sprayer->id,
                        'name' => $sprayer->name,
                        'location' => $block->location,
                        'sensorStatus' => $sprayer->sensor ? $sprayer->sensor->status : 'offline',
                        'humidity' => $sprayer->sensor ? $sprayer->sensor->humidity : 0,
                        'flowRate' => $sprayer->sensor ? $sprayer->sensor->flow_rate : 0,
                        'volume' => $sprayer->sensor ? $sprayer->sensor->volume : 0,
                        'pumpStatus' => $sprayer->is_pump_on ? 'Aktif' : 'Mati',
                        'lastUpdate' => $sprayer->sensor ? $sprayer->sensor->updated_at->toDateTimeString() : null,
                        'isPumpOn' => $sprayer->is_pump_on,
                        'isAutoIrrigation' => $sprayer->is_auto_irrigation,
                    ];
                })->toArray(),
            ];
        })->toArray();

        $firebase = $this->getFirebaseConfig();

        return compact('blocks', 'coordinates', 'firebase');
    }

    /**
     * Get data for superadmin.
     */
    private function getSuperadminData(): array
    {
        return $this->getFirebaseConfig();
    }

    /**
     * Get data for admin.
     */
    private function getAdminData(): array
    {
        return $this->getFirebaseConfig();
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
