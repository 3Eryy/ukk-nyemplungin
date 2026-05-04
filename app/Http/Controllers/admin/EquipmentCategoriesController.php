<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentCategories;
use Illuminate\Http\Request;

class EquipmentCategoriesController extends Controller
{
    // Show data
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search', '');

            $query = EquipmentCategories::query();

            if ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            }

            $categories = $query->paginate($perPage);

            return view('admin.equipment-categories.index', compact('categories'));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Insert data
    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:equipment_categories',
            'description' => 'nullable|string',
        ]);

        EquipmentCategories::create($request->all());

        return redirect()->route('admin.equipment-categories')
            ->with('success');
    }

    // Get specific category (untuk API atau modal)
    public function show($id)
    {
        try {
            $category = EquipmentCategories::find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Equipment category not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $category
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred'
            ], 500);
        }
    }

    // Update data
    public function update(Request $request, $id)
    {
        $category = EquipmentCategories::find($id);

        if (!$category) {
            return redirect()->route('admin.equipment-categories')
                ->with('error');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:equipment_categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $category->update($request->all());

        return redirect()->route('admin.equipment-categories')
            ->with('success');
    }

    // Delete data
    public function destroy($id)
    {
        $category = EquipmentCategories::find($id);

        if (!$category) {
            return redirect()->route('admin.equipment-categories')
                ->with('error', 'Equipment category not found');
        }

        $category->delete();

        return redirect()->route('admin.equipment-categories')
            ->with('success');
    }
}