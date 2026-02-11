<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatistikTestingController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // Only accessible in debug mode
        if (!config('app.debug')) {
            abort(404);
        }

        $currentPeriod = $request->query('period', 'today');

        // Validate period
        if (!in_array($currentPeriod, ['today', '7days', '30days'])) {
            $currentPeriod = 'today';
        }

        return view('pages.dashboard.statistik-testing', compact('currentPeriod'));
    }
}
