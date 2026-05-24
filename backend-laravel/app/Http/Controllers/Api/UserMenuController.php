<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FoodMenu; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 

class UserMenuController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. Ambil input dari request (saat user klik submit di form) TANPA nilai default
        $weight = $request->input('weight'); 
        $height = $request->input('height'); 
        $age = $request->input('age'); 
        $gender = $request->input('gender'); 
        $activity = $request->input('activity');

        // Jika form belum diisi, coba periksa apakah user tersebut sudah mengisi profil di database
        if (!$weight && $user) {
            $weight = $user->weight;
            $height = $user->height;
            $age = $user->age;
            $gender = $user->gender;
            $activity = $user->activity;
        }

        // 2. Siapkan wadah kosong (Nilai Default jika AI belum dijalankan)
        $recommendations = [];
        $clusterInfo = "Belum teridentifikasi";
        $targetNutrisi = null;
        $userStats = null;
        
        // PESAN DEFAULT SEBELUM FORM DIISI
        $aiMessage = "Silakan isi data fisik Anda pada form di atas, lalu klik tombol 'Analisis Gizi' untuk mendapatkan rekomendasi yang presisi dari AI.";

        // 3. HANYA JALANKAN MESIN AI JIKA DATA FISIK SUDAH ADA (TIDAK KOSONG)
        if ($weight && $height && $age) {
            
            $userStats = [
                'age' => $age,
                'weight' => $weight,
                'height' => $height,
                'gender' => $gender ?? 'male',
                'activity' => $activity ?? 'moderate'
            ];

            try {
                // Hubungi Mesin K-Means Python
                $response = Http::timeout(5)->post('http://127.0.0.1:5000/api/predict/recommendation', [
                    'weight' => $weight,
                    'height' => $height,
                    'age' => $age,
                    'gender' => $gender ?? 'male',
                    'activity' => $activity ?? 'moderate'
                ]);

                if ($response->successful()) {
                    $aiData = $response->json();
                    
                    $clusterId = $aiData['cluster_id'];
                    $clusterInfo = $aiData['cluster_name'];
                    $targetNutrisi = $aiData['nutrisi_target'];
                    $aiMessage = "Rekomendasi ini berasal dari cluster makanan ($clusterInfo) yang paling mendekati kebutuhan gizi Anda";

                    // Ambil data dari MongoDB sesuai Cluster AI
                    $menusInCluster = FoodMenu::where('cluster_id', $clusterId)->get();

                    // Urutkan berdasarkan jarak Euclidean
                    $recommendations = $menusInCluster->map(function ($menu) use ($targetNutrisi) {
                        $distance = sqrt(
                            // Kalori dibagi 10 agar setara porsinya dengan nilai makro gizi
                            pow(($menu->calories - $targetNutrisi['calories']) / 10, 2) +
                            pow($menu->protein - $targetNutrisi['protein'], 2) + 
                            pow($menu->fat - $targetNutrisi['fat'], 2) +
                            pow($menu->carbohydrates - $targetNutrisi['carbohydrates'], 2) 
                        );
                        
                        // Normalisasi persentase kecocokan
                        $menu->match_score = round(max(0, 100 - ($distance * 2)), 1);
                        return $menu;
                    })->sortByDesc('match_score')->take(10)->values();
                }
            } catch (\Exception $e) {
                $aiMessage = "Gagal terhubung ke AI Service Python. Pastikan server Flask berjalan.";
            }
        }

        // 4. Katalog Semua Menu Reguler (Akan selalu tampil meski form belum diisi)
        $query = FoodMenu::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->sort == 'az') {
            $query->orderBy('name', 'asc');
        } elseif ($request->sort == 'za') {
            $query->orderBy('name', 'desc');
        } elseif ($request->sort == 'cal_low') {
            $query->orderBy('calories', 'asc');
        } elseif ($request->sort == 'cal_high') {
            $query->orderBy('calories', 'desc');
        }

        $paginatedMenus = $query->paginate(12)->appends($request->all());

        return response()->json([
            'user_stats' => $userStats,
            'ai_analysis' => [
                'cluster_name' => $clusterInfo,
                'message' => $aiMessage,
                'target_nutrisi' => $targetNutrisi
            ],
            'recommendations' => $recommendations, 
            'all_menus' => $paginatedMenus 
        ]);
    }
}