<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return View
     */
    public function dashboard(): View
    {
        return view('admin.dashboard');
    }

    /**
     * Show the form for creating a new user.
     *
     * @return View
     */
    public function createUser(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeUser(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'nama_pengguna' => 'required|string|max:255',
            'nomor_whatsapp' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'jenis_pengguna' => 'required|in:Individu,Institusi/Lembaga',
            'nama_panggilan' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'nullable|date',
            'hp_lain' => 'nullable|string|max:20',
            'alamat_lengkap' => 'nullable|string'
        ]);

        // Here you would typically save to database
        // For now, we'll just redirect back with success message
        
        return redirect()->route('admin.dashboard')
                        ->with('success', 'Pengguna berhasil ditambahkan: ' . $validated['nama_pengguna']);
    }
}