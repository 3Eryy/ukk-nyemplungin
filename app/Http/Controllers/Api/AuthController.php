<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Roles;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //Register
    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'phone' => 'nullable|string|max:20',
    //         'role_id' => 'nullable|integer|exists:roles,id',
    //         'password' => 'required|string|min:8|confirmed',
    //     ]);

    //     $roles = Roles::where('name', 'peminjam')->first();

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'phone' => $request->phone,
    //         'role_id' => $roles->id,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     $token = $user->createToken('auth_token')->plainTextToken;

        
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'User registered successfully',
    //         'data' => [
    //             'user' => $user,
    //             'access_token' => $token,
    //             'token_type' => 'Bearer',
    //         ]
    //     ], 201);
    // }

    // Login
    // public function login(Request $request) 
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if (! $user || !Hash::check($request->password, $user->password)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid login details'
    //         ], 401);
    //     }

    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     // Send role and redirect page
    //     switch($user->role->name) {
    //         case 'admin':
    //             $redirectPage = '/admin/dashboard';
    //             break;
    //         case 'petugas':
    //             $redirectPage = '/petugas/dashboard';
    //             break;
    //         case 'peminjam':
    //             $redirectPage = '/peminjam/dashboard';
    //             break;
    //         default:
    //             $redirectPage = '/';
    //     }


    //     return response()->json([
    //         'success' => true,
    //         'message' => 'User logged in successfully',
    //         'data' => [
    //             'user' => $user,
    //             'role' => $user->role->name,
    //             'access_token' => $token,
    //             'token_type' => 'Bearer',
    //             'redirect_page' => $redirectPage,
    //         ]
    //     ]);
    // }
    

    // Logout
    

    // Get user profile
    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }
}
