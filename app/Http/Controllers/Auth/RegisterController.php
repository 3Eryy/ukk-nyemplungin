<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Roles;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register.index');
    }
    
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Cari role 'peminjam'
        $role = Roles::where('name', 'peminjam')->first();
        
        // Jika role tidak ditemukan, buat role baru atau handle error
        if (!$role) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Role peminjam tidak ditemukan. Silakan hubungi administrator.']);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role_id' => $role->id,
                'password' => Hash::make($request->password),
            ]);

            // Optional: Login otomatis setelah register
            // auth()->login($user);

            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silakan login.');
                
        } catch (\Throwable $th) {
            // Log error untuk debugging
            \Log::error('Registration error: ' . $th->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Registrasi gagal: ' . $th->getMessage()]);
        }
    }
}