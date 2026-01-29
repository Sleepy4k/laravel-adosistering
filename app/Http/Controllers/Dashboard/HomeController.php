<?php

namespace App\Http\Controllers\Dashboard;

use App\Foundations\Controller;
use App\Http\Requests\Dashboard\Home\UpdateRequest;
use App\Policies\Dashboard\HomePolicy;
use App\Services\Dashboard\HomeService;
use App\Traits\Authorizable;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private HomeService $service,
        private $policy = HomePolicy::class,
        private $abilities = [
            '__invoke' => 'viewAny',
            'update' => 'viewAny',
        ]
    ) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('pages.dashboard.home', $this->service->invoke());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $block)
    {
        $result = $this->service->update($request->validated(), $block);

        if (!$result) {
            session()->flash('error', 'Failed to update home settings.');
            return back()->withInput();
        }

        session()->flash('success', 'Home settings updated successfully.');

        return to_route('home');
    }
}
