<?php

namespace App\Http\Controllers\Auth;

use App\Foundations\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        Auth::logout();

        $session = $request->session();
        $session->invalidate();
        $session->regenerateToken();

        session()->flash('status', 'You have been logged out.');

        return to_route('login');
    }
}
