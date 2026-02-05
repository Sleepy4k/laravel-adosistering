<?php

namespace App\Services\Dashboard;

use App\Foundations\Service;
use App\Models\UserDetail;

class ProfileService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        $genders = [
            'male' => 'Laki-laki',
            'female' => 'Perempuan',
        ];
        
        $user = auth('web')->user()->load(['details', 'type']);

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
            'credential' => $this->updateCredential($user, $request),
            default => false,
        };
    }

    /**
     * Update basic profile information.
     */
    private function updateBasicInfo($user, array $request): bool
    {
        // Update user details - only fields that are used in the form
        $detailsData = [
            'gender' => $request['gender'] ?? null,
            'address' => $request['address'] ?? null,
        ];

        $user->details()->updateOrCreate(
            ['user_id' => $user->id],
            $detailsData
        );

        // Update user
        return $user->update([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
        ]);
    }

    /**
     * Update other profile information (domicile).
     * Note: negara, provinsi, kode_pos are NOT saved to database yet.
     * They are only used in the form for future features.
     * Field 'notes' is reserved for future use.
     */
    private function updateOtherInfo($user, array $request): bool
    {
        // Only update domicile field (kota/kabupaten)
        // Other fields (negara, provinsi, kode_pos) are not stored yet
        return (bool) $user->details()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'domicile' => $request['domicile'] ?? null,
            ]
        );
    }

    /**
     * Update user credential (password).
     * Validates current password before updating.
     */
    private function updateCredential($user, array $request): bool
    {
        // Validate current password if provided
        if (isset($request['current_password'])) {
            if (!\Illuminate\Support\Facades\Hash::check($request['current_password'], $user->password)) {
                return false; // Current password doesn't match
            }
        }
        
        return $user->update(['password' => $request['password']]);
    }

    /**
     * Update all profile information at once (combined update).
     * Note: negara, provinsi, kode_pos are NOT saved yet (for future features).
     */
    public function updateAll(array $data): bool
    {
        $user = auth('web')->user();

        // Update users table
        $user->update([
            'name' => $data['nama_lengkap'],
            'email' => $data['email'],
            'phone' => $data['nomor_whatsapp'],
        ]);

        // Map gender from display value to database value
        $gender = $this->mapGenderToDb($data['gender'] ?? null);

        // Update user_details table
        // Only store fields that are actually used
        $user->details()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'gender' => $gender,
                'address' => $data['alamat'] ?? null,
                'domicile' => $data['kota'] ?? null,
            ]
        );

        return true;
    }

    /**
     * Map display gender value to database value.
     */
    private function mapGenderToDb(?string $gender): ?string
    {
        return match($gender) {
            'Laki-laki' => 'male',
            'Perempuan' => 'female',
            default => null,
        };
    }
}
