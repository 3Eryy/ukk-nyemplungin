<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Login page
    public function index()
    {
        return view('auth.login.index');
    }


    public function login(Request $request) 
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $cek = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        if (!$cek) {
            return redirect()->back()->withErrors('error', 'Invalid login details');
        }
        
        $user = Auth::user();
        Auth::login($user);

        $redirectPage = '/';

        // Send role and redirect page
        switch($user->role->name) {
            case 'admin':
                $redirectPage = '/admin/dashboard';
                break;
            case 'petugas':
                $redirectPage = '/petugas/dashboard';
                break;
            case 'peminjam':
                $redirectPage = '/user/dashboard';
                break;
            default:
                $redirectPage = '/';
        }

        return redirect($redirectPage)->with('success', 'User logged in successfully');
    }
}
