<?php

namespace App\Http\Controllers\Dashboard;

use App\Foundations\Controller;
use Illuminate\Http\Request;

class StatistikController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // Get period from query parameter, default to 'today'
        $currentPeriod = $request->query('period', 'today');
        
        // Load dummy data from datadummy directory
        $allData = include database_path('datadummy/statistik.php');
        
        // Get data for current period
        $periodData = $allData[$currentPeriod] ?? $allData['today'];
        
        return view('pages.dashboard.statistik', [
            'currentPeriod' => $currentPeriod,
            'summary' => $periodData['summary'],
            'bloks' => $periodData['bloks'],
        ]);
    }
}
