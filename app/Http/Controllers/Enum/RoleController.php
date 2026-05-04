<?php

namespace App\Http\Controllers\Enum;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //Insert role
    public function insert(Request $request)
    {
        //Validator
        $validator = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $roles = Roles::create($validator);

        return response()->json([
            'success' => true,
            'message' => 'Role inserted successfully',
            'data' => $roles,
        ], 201);
    }
}
