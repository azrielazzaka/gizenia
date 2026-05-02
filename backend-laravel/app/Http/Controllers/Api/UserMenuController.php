<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FoodMenu; // <-- Menggunakan model FoodMenu yang benar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // <-- WAJIB ADA: Untuk menelpon AI Python

class UserMenuController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. Ambil data fisik User (berikan nilai default jika belum mengisi)
        $weight = $user->weight ?? 40; // kg
        $height = $user->height ?? 140; // cm
        $age = $user->age ?? 12; // tahun

        // 2. Kalkulasi TDEE & Target Makro (Karbo, Pro, Lemak)
        $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) + 5;
        $tdee = $bmr * 1.375;
        $targetMealCalories = $tdee * 0.35; // 35% untuk 1x makan utama
        
        // Asumsi target makronutrisi seimbang untuk 1x makan
        $targetProtein = ($weight * 1.5) * 0.35; 
        $targetFat = ($targetMealCalories * 0.25) / 9;
        $targetCarbs = ($targetMealCalories * 0.50) / 4;

        // 3. MINTA REKOMENDASI KE MESIN AI (FLASK PYTHON) 🧠🤖
        $recommendations = [];
        try {
            // Laravel "mengetuk pintu" port 5000 milik Python
            $response = Http::timeout(5)->post('http://127.0.0.1:5000/api/predict/recommendation', [
                'target_calories' => $targetMealCalories,
                'target_protein' => $targetProtein,
                'target_fat' => $targetFat,
                'target_carbs' => $targetCarbs
            ]);

            // Jika Python menjawab (Status 200 OK)
            if ($response->successful()) {
                $aiData = $response->json();
                $recommendations = $aiData['rekomendasi']; // Ambil array rekomendasinya dari Python
            }
        } catch (\Exception $e) {
            // Jika server Python mati, fallback (kosongkan atau isi pesan error)
            $recommendations = [];
        }

        // 4. Katalog Semua Menu (Hanya diambil 12 per halaman untuk Paginasi)
       $query = FoodMenu::query();
       

// 🔎 SEARCH (nama menu)
if ($request->has('search') && $request->search != '') {
    $query->where('name', 'like', '%' . $request->search . '%');
}

// 🔤 SORT ABJAD
if ($request->sort == 'az') {
    $query->orderBy('name', 'asc');
} elseif ($request->sort == 'za') {
    $query->orderBy('name', 'desc');
}

// 🔥 SORT KALORI
if ($request->sort == 'cal_low') {
    $query->orderBy('calories', 'asc');
} elseif ($request->sort == 'cal_high') {
    $query->orderBy('calories', 'desc');
}

// PAGINASI
$paginatedMenus = $query->paginate(12)->appends($request->all());

        return response()->json([
            'user_stats' => [
                'age' => $age,
                'weight' => $weight,
                'height' => $height,
                'tdee' => round($tdee),
                'target_meal' => round($targetMealCalories)
            ],
            'recommendations' => $recommendations, // <-- Data ini sekarang murni dari AI Python!
            'all_menus' => $paginatedMenus // Mengirim objek paginasi
        ]);
    }
}