<?php

namespace App\Http\Controllers\Dashboard;

use App\Foundations\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // TODO: Replace with real data from database
        // Load dummy notifications data
        $notifications = include database_path('datadummy/notifications.php');
        
        return view('pages.dashboard.notifications', [
            'notifications' => $notifications,
        ]);
    }
}
