<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Show user login page
     */
    public function userLogin()
    {
        return view('auth.user.login');
    }

    /**
     * Show admin login page
     */
    public function adminLogin()
    {
        return view('auth.admin.login');
    }

    /**
     * Show superadmin login page
     */
    public function superAdminLogin()
    {
        return view('auth.superadmin.login');
    }

    /**
     * Handle user login (dummy authentication)
     */
    public function handleUserLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Dummy credentials untuk user
        if ($request->username === 'user' && $request->password === 'password123') {
            $request->session()->put('dummy_auth', [
                'user_id' => 1,
                'role' => 'user',
                'name' => 'User Test',
                'email' => 'user@test.com',
                'username' => 'user'
            ]);

            return redirect()->route('user.dashboard')->with('success', 'Berhasil login sebagai User!');
        }

        return back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
    }

    /**
     * Handle admin login (dummy authentication)
     */
    public function handleAdminLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Dummy credentials untuk admin
        if ($request->username === 'admin' && $request->password === 'admin123') {
            $request->session()->put('dummy_auth', [
                'user_id' => 2,
                'role' => 'admin',
                'name' => 'Admin Test',
                'email' => 'admin@test.com',
                'username' => 'admin'
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'Berhasil login sebagai Admin!');
        }

        return back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
    }

    /**
     * Handle superadmin login (dummy authentication)
     */
    public function handleSuperAdminLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Dummy credentials untuk superadmin
        if ($request->username === 'superadmin' && $request->password === 'super123') {
            $request->session()->put('dummy_auth', [
                'user_id' => 3,
                'role' => 'superadmin',
                'name' => 'Super Admin Test',
                'email' => 'superadmin@test.com',
                'username' => 'superadmin'
            ]);

            return redirect()->route('superadmin.dashboard')->with('success', 'Berhasil login sebagai Super Admin!');
        }

        return back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        $request->session()->forget('dummy_auth');
        return redirect()->route('login')->with('success', 'Berhasil logout!');
    }

    /**
     * Helper function to check if user is authenticated
     */
    public static function isDummyAuthenticated()
    {
        return session()->has('dummy_auth');
    }

    /**
     * Helper function to get authenticated user data
     */
    public static function getDummyUser()
    {
        return session('dummy_auth');
    }

    /**
     * Helper function to check user role
     */
    public static function hasRole($role)
    {
        $user = self::getDummyUser();
        return $user && $user['role'] === $role;
    }
}
