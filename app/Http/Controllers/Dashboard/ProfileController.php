<?php

namespace App\Http\Controllers\Dashboard;

use App\Foundations\Controller;
use App\Http\Requests\Dashboard\Profile\UpdateRequest;
use App\Policies\Dashboard\ProfilePolicy;
use App\Services\Dashboard\ProfileService;
use App\Traits\Authorizable;

class ProfileController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private ProfileService $service,
        private $policy = ProfilePolicy::class,
        private $abilities = [
            'index' => 'viewAny',
            'update' => 'update',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.dashboard.profile', $this->service->index());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $profileType)
    {
        if (!in_array($profileType, ['basic', 'other', 'credential'])) {
            session()->flash('error', 'Invalid profile type.');
            return back()->withInput();
        }

        $result = $this->service->update($request->validated(), $profileType);

        if (!$result) {
            session()->flash('error', 'Failed to update profile.');
            return back()->withInput();
        }

        session()->flash('success', 'Profile updated successfully.');

        return to_route('users.index');
    }
}
