<?php

namespace App\Http\Controllers\Dashboard;

use App\Foundations\Controller;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // TODO: Replace with real data from database/settings
        // Load default settings
        $settings = [
            'kontrol_irigasi' => [
                'kelembaban_tanah' => [
                    'min' => 20,
                    'max' => 65,
                ],
                'kondisi_lahan' => [
                    'kering' => 20,
                    'lembab' => 50,
                    'basah' => 80,
                ],
            ],
            'safety_timeout' => [
                'pengaman_irigasi' => [
                    'min' => 1,
                    'max' => 3,
                ],
            ],
        ];
        
        return view('pages.dashboard.pengaturan', [
            'settings' => $settings,
        ]);
    }
}
