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
            config('rbac.role.highest') => function () {
                return [];
            },
            default => [],
        };
    }

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

        return compact('blocks', 'coordinates');
    }

    private function getSuperadminData(): array
    {
        // Implement data retrieval logic for highest role users here
        return [];
    }
}
