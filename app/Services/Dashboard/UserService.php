<?php

namespace App\Services\Dashboard;

use App\Foundations\Service;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Log;

class UserService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        $users = User::query()
            ->select('id', 'name', 'email', 'phone', 'is_active')
            ->get();

        return compact('users');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): array
    {
        $types = UserType::query()
            ->select('id', 'name')
            ->get();

        return compact('types');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $request): bool
    {
        try {
            User::query()->create($request);
            return true;
        } catch (\Throwable $th) {
            Log::error('Failed to create user', [
                'request' => $request,
                'error' => $th->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): array
    {
        $types = UserType::query()
            ->select('id', 'name')
            ->get();

        return compact('user', 'types');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $request, User $user): bool
    {
        try {
            $user->update($request);
            return true;
        } catch (\Throwable $th) {
            Log::error('Failed to update user', [
                'user_id' => $user->id,
                'request' => $request,
                'error' => $th->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): bool
    {
        try {
            $user->delete();
            return true;
        } catch (\Throwable $th) {
            Log::error('Failed to delete user', [
                'user_id' => $user->id,
                'error' => $th->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Set active status for the specified resource.
     */
    public function activeStatus(array $request, User $user): bool
    {
        try {
            $user->is_active = $request['is_active'];
            $user->save();
            return true;
        } catch (\Throwable $th) {
            Log::error('Failed to set active status for user', [
                'user_id' => $user->id,
                'request' => $request,
                'error' => $th->getMessage(),
            ]);
            return false;
        }
    }
}
