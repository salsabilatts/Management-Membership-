<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $response = Http::post('http://192.168.18.245:8080/api/v1/auth/login', [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if ($response->successful() && isset($response['token'])) {
                // Simpan token ke session
                session(['token' => $response['token']]);

                // (Opsional) Bisa simpan juga email agar tahu siapa yg login
                session(['email' => $request->email]);

                return redirect()->route('admin.dashboard')->with('success', 'Berhasil login!');
            } else {
                return back()->with('error', 'Login gagal! Email atau password salah.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan koneksi ke server API: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login')->with('info', 'Berhasil logout.');
    }
}
