<?php

namespace App\Http\Controllers\Dashboard;

use App\Foundations\Controller;
use App\Models\IrrigationSetting;
use App\Services\Dashboard\IrrigationSettingService;
use App\Traits\Authorizable;
use Illuminate\Http\Request;

class IrrigationSettingController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private IrrigationSettingService $service,
        private $policy = IrrigationSetting::class,
        private $abilities = [
            'index' => 'viewAny',
            'update' => 'update',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.dashboard.pengaturan', $this->service->index());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        return false;
    }
}
