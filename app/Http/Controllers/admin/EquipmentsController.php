<?php

namespace App\Http\Controllers\Admin; // Perhatikan huruf 'A' kapital

use App\Http\Controllers\Controller;
use App\Models\EquipmentCategories;
use App\Models\Equipments;
use Illuminate\Http\Request; // Perbaiki import

class EquipmentsController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');

        $query = Equipments::query();

        // card
        $barangDipinjam = Equipments::where('available_status', 'dipinjam')->count();
        $barangTersedia = Equipments::where('available_status', 'tersedia')->count(); 
        $barangRusak = Equipments::where('condition_status', 'rusak')->count();
        $totalBarang = Equipments::count(); // Perbaiki typo

        // kategori
        $category = EquipmentCategories::all();

        // Fungsi Pencarian
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        }

        // Pagination
        $equipment = $query->latest()->paginate($perPage);
        
        return view('admin.equipment-manajemen.index', [
            'equipment' => $equipment,
            'barangDipinjam' => $barangDipinjam,
            'barangTersedia' => $barangTersedia, // Perbaiki typo
            'totalBarang' => $totalBarang, // Perbaiki typo
            'barangRusak' => $barangRusak,
            'category' => $category,
        ]);
    }

    public function insert(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:255|unique:equipments',
            'category_id' => 'required|integer|exists:equipment_categories,id',
            'description' => 'nullable|string',
            'hourly_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'condition_status' => 'required|string|max:100',
            'available_status' => 'required|string|max:100',
            'image' => 'nullable|string|max:255', // Ubah jadi nullable
        ]);

        Equipments::create($validator); // Tidak perlu compact

        return redirect()
            ->route('admin.equipments')
            ->with('success', 'Equipment created successfully.');
    }

    public function update(Request $request, $id)
    {
        $equipment = Equipments::find($id);

        if (!$equipment) {
            return redirect()
                ->route('admin.equipments')
                ->with('error', 'Equipment not found.');
        }

        $validator = $request->validate([
            'name' => 'required|string|max:255|unique:equipments,name,' . $id,
            'category_id' => 'required|integer|exists:equipment_categories,id',
            'description' => 'nullable|string',
            'hourly_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'condition_status' => 'required|string|max:100',
            'available_status' => 'required|string|max:100',
            'image' => 'nullable|string|max:255', 
        ]);

        $equipment->update($validator);

        return redirect()->route('admin.equipments')
            ->with('success');
    }

    public function destroy($id)
    {
        $equipment = Equipments::find($id);

        if (!$equipment) {
            return redirect()
                ->route('admin.equipments')
                ->with('error', 'Equipment not found.');
        }

        $equipment->delete();

        return redirect()
            ->route('admin.equipments')
            ->with('success', 'Equipment deleted successfully.');
    }
}