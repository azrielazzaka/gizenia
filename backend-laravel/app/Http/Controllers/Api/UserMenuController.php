<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FoodMenu;
use App\Models\Menu;
use Illuminate\Http\Request;

class UserMenuController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. Ambil data fisik User (berikan nilai default jika belum mengisi)
        $weight = $user->weight ?? 40; // kg
        $height = $user->height ?? 140; // cm
        $age = $user->age ?? 12; // tahun

        // 2. Kalkulasi TDEE (Kebutuhan Kalori Harian)
        $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) + 5;
        $tdee = $bmr * 1.375;
        $targetMealCalories = $tdee * 0.35;

        // 3. AI Rekomendasi (Harus mencari dari SEMUA menu di database)
        $allMenusForAI = FoodMenu::all();
        $recommendations = $allMenusForAI->sortBy(function($menu) use ($targetMealCalories) {
            return abs((float)$menu->calories - $targetMealCalories);
        })->take(3)->values();

        // 4. Katalog Semua Menu (Hanya diambil 12 per halaman untuk Paginasi)
        $paginatedMenus = FoodMenu::paginate(12);

        return response()->json([
            'user_stats' => [
                'age' => $age,
                'weight' => $weight,
                'height' => $height,
                'tdee' => round($tdee),
                'target_meal' => round($targetMealCalories)
            ],
            'recommendations' => $recommendations,
            'all_menus' => $paginatedMenus // Mengirim objek paginasi
        ]);
    }
}