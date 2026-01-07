<?php

namespace App\Services\Dashboard;

use App\Foundations\Service;

class ProfileService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        $genders = ['male', 'female'];
        $user = auth('web')->user()->load('details');

        return compact('user', 'genders');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $request, string $type): bool
    {
        $user = auth('web')->user();

        return match ($type) {
            'basic' => $this->updateBasicInfo($user, $request),
            'other' => $this->updateOtherInfo($user, $request),
            'credential' => $user->update(['password' => $request['password']]),
            default => false,
        };
    }

    /**
     * Update basic profile information.
     */
    private function updateBasicInfo($user, array $request): bool
    {
        $user->details()->updateOrCreate(
            ['user_id' => $user->id],
            ['address' => $request['address']]
        );

        return $user->update([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
        ]);
    }

    /**
     * Update other profile information.
     */
    private function updateOtherInfo($user, array $request): bool
    {
        return (bool) $user->details()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'gender' => $request['gender'] ?? null,
                'date_of_birth' => $request['date_of_birth'] ?? null,
                'occupation' => $request['occupation'] ?? null,
                'domicile' => $request['domicile'] ?? null,
            ]
        );
    }
}
