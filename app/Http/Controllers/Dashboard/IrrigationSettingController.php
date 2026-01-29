<?php

namespace App\Http\Controllers\Dashboard;

use App\Foundations\Controller;
use App\Http\Requests\Dashboard\IrrigationSetting\UpdateRequest;
use App\Models\IrrigationSetting;
use App\Services\Dashboard\IrrigationSettingService;
use App\Traits\Authorizable;

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
    public function update(UpdateRequest $request, string $pengaturan)
    {
        if (!in_array($pengaturan, ['control', 'safety'])) {
            session()->flash('error', 'Invalid setting type.');
            return back()->withInput();
        }

        $result = $this->service->update($request->validated(), $pengaturan);

        if (!$result) {
            session()->flash('error', 'Failed to update irrigation settings.');
            return back()->withInput();
        }

        session()->flash('success', 'Irrigation settings updated successfully.');

        return to_route('user.pengaturan');
    }
}
