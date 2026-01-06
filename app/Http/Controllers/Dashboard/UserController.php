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
        $this->service->store($request->validated());

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
        $this->service->update($request->validated(), $user);

        return to_route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->service->destroy($user);

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
