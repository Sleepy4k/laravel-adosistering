<?php

namespace App\Http\Controllers\Dashboard;

use App\Foundations\Controller;
use App\Http\Requests\Dashboard\User\StatusRequest;
use App\Http\Requests\Dashboard\User\StoreRequest;
use App\Http\Requests\Dashboard\User\UpdateRequest;
use App\Models\User;
use App\Services\Dashboard\UserService;
use App\Traits\Authorizable;

class UserController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private UserService $service,
        private $policy = User::class,
        private $abilities = [
            'index' => 'viewAny',
            'create' => 'store',
            'store' => 'store',
            'edit' => 'update',
            'update' => 'update',
            'destroy' => 'delete',
            'setActiveStatus' => 'update',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.dashboard.users.index', $this->service->index());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.dashboard.users.create', $this->service->create());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $result = $this->service->store($request->validated());

        if (!$result) {
            session()->flash('error', 'Failed to create new user.');
            return back()->withInput();
        }

        session()->flash('success', 'New user created successfully.');

        return to_route('users.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('pages.dashboard.users.edit', $this->service->edit($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, User $user)
    {
        $result = $this->service->update($request->validated(), $user);

        if (!$result) {
            session()->flash('error', 'Failed to update user.');
            return back()->withInput();
        }

        session()->flash('success', 'User updated successfully.');

        return to_route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $result = $this->service->destroy($user);

        if (!$result) {
            session()->flash('error', 'Failed to delete user.');
            return back();
        }

        session()->flash('success', 'User deleted successfully.');

        return to_route('users.index');
    }

    /**
     * Set active status for the specified resource.
     */
    public function setActiveStatus(StatusRequest $request, User $user)
    {
        $this->service->activeStatus($request->validated(), $user);

        return to_route('users.index');
    }
}
