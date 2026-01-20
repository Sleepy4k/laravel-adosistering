<?php

namespace App\Http\Controllers\Dashboard;

use App\Foundations\Controller;
use Illuminate\Http\Request;

class IrrigationHistoryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // Load dummy data for development
        $irrigationHistory = include database_path('datadummy/irrigation-history.php');

        return view('pages.dashboard.irrigation-history', [
            'irrigationHistory' => $irrigationHistory,
        ]);
    }
}
