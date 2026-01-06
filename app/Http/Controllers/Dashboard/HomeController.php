<?php

namespace App\Http\Controllers\Dashboard;

use App\Foundations\Controller;
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
        ]
    ) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('pages.dashboard.home', $this->service->invoke());
    }
}
