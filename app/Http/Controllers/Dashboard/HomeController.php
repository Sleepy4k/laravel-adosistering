<?php

namespace App\Http\Controllers\Dashboard;

use App\Foundations\Controller;
use App\Services\Dashboard\HomeService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(HomeService $service, Request $request)
    {
        return view('pages.dashboard.home', $service->invoke());
    }
}
