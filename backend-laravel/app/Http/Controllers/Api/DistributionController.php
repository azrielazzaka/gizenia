<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Distribution;
use App\Models\FoodMenu;
use Illuminate\Http\Request;

class DistributionController extends Controller
{
    public function index(Request $request)
    {
        $query = Distribution::query();

        // FILTER BY DATE
        if ($request->has('date')) {
            $query->where('distribution_date', $request->date);
        }

        return $query->paginate(5);
    }

    public function store(Request $request)
    {
        $request->validate([
            'foods' => 'required|array',
            'target_classes' => 'required|array'
        ]);

        $foods = [];

        foreach ($request->foods as $food) {

            // Cari menu berdasarkan ID
            $menu = FoodMenu::find($food['menu_id']);

            $foods[] = [
                'menu_id' => $food['menu_id'],
                'name' => $menu ? $menu->name : 'Menu Tidak Diketahui',
                'weight' => $food['weight']
            ];
        }

        $dist = Distribution::create([
            'distribution_date' => now()->toDateString(),
            'foods' => $foods,
            'target_classes' => $request->target_classes,
            'responses' => []
        ]);

        return response()->json([
            'message' => 'Makanan berhasil didistribusikan!',
            'data' => $dist
        ]);
    }

    public function update(Request $request, $id)
    {
        $dist = Distribution::find($id);

        if (!$dist) {
            return response()->json([
                'error' => 'Data tidak ditemukan'
            ], 404);
        }

        $foods = [];

        foreach ($request->foods as $food) {

            $menu = FoodMenu::find($food['menu_id']);

            $foods[] = [
                'menu_id' => $food['menu_id'],
                'name' => $menu ? $menu->name : 'Menu Tidak Diketahui',
                'weight' => $food['weight']
            ];
        }

        $dist->update([
            'foods' => $foods,
            'target_classes' => $request->target_classes,
        ]);

        return response()->json([
            'message' => 'Data distribusi berhasil diperbarui!',
            'data' => $dist
        ]);
    }

    public function destroy($id)
    {
        $dist = Distribution::find($id);

        if (!$dist) {
            return response()->json([
                'error' => 'Data tidak ditemukan'
            ], 404);
        }

        $dist->delete();

        return response()->json([
            'message' => 'Data distribusi berhasil dihapus!'
        ]);
    }
}