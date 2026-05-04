<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function showPage(Request $request)
    {
        $jumlahAdmin = User::where('role_id', 1)->count();
        $jumlahPetugas = User::where('role_id', 2)->count();
        $jumlahUser = User::where('role_id', 3)->count();

        $search = $request->input('search');
        $filterUser = $request->input('role'); // samakan dengan blade

        $query = User::query();

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter Role
        if ($filterUser) {
            $query->where('role_id', $filterUser);
        }

        $dataUser = $query->paginate(5)->withQueryString();

        $roleUser = Roles::all();

        return view('admin.user-manajemen.index', compact(
            'jumlahAdmin',
            'jumlahPetugas',
            'jumlahUser',
            'dataUser',
            'roleUser',
            'filterUser',
            'search'
        ));
    }

    //show user list
    public function index()
    {
        $user = User::all();

        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully',
            'data' => $user,
        ], 200);
    }

    // Get the spesific user
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully',
            'data' => $user,
        ], 200);
    }

    // Insert user
    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'role_id' => 'nullable|integer|exists:roles,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
        ]);

        if (!$user) {
            return redirect()
                ->route('admin.users')
                ->with('error', 'Failed to create user.');
        } else {
            return redirect()
                ->route('admin.users')
                ->with('success', 'User created successfully.');
        }
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'role_id' => 'nullable|integer|exists:roles,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => $request->role_id,
        ];

        // Jika password diisi, update password
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if (!$user) {
            return redirect()
                ->route('admin.users')
                ->with('error', 'Failed to update user.');
        } else {
            return redirect()
                ->route('admin.users')
                ->with('success', 'User updated successfully.');
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $user->delete();

        return redirect()
            ->route('admin.users')
            ->with('success', 'User updated successfully.');
    }
}
