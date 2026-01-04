<?php

namespace App\Http\Controllers\Dashboard;

use App\Foundations\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $blocks = config('dummy.blocks');
        return view('pages.dashboard.home', compact('blocks'));
    }
}
