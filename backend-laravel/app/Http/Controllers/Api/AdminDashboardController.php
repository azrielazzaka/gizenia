<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FoodMenu;
use App\Models\User;
use App\Models\Menu;
use App\Models\Distribution;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil Angka Metrik Utama
        $totalMenus = FoodMenu::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalDistributions = Distribution::count();

        // 2. Ambil 5 Pengguna Terakhir (Penerima Terbaru)
        $latestUsers = User::where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['name', 'created_at', 'class_room']);

        // Format tanggal untuk tabel frontend
        $latestUsers->transform(function ($user) {
            $user->formatted_date = Carbon::parse($user->created_at)->translatedFormat('d M Y');
            $user->initials = strtoupper(substr($user->name, 0, 2)); // Inisial nama (Misal: BU)
            return $user;
        });

        // 3. Bangun Data Grafik Batang (6 Bulan Terakhir)
        $barChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $barChart[$month->format('Y-m')] = [
                'label' => $month->translatedFormat('M'), // Jan, Feb, dst
                'count' => 0
            ];
        }

        // Hitung pengguna yang mendaftar tiap bulan (Aman untuk MongoDB)
        $allUsers = User::where('role', 'user')->get(['created_at']);
        foreach ($allUsers as $u) {
            $dateKey = Carbon::parse($u->created_at)->format('Y-m');
            if (isset($barChart[$dateKey])) {
                $barChart[$dateKey]['count']++;
            }
        }

        return response()->json([
            'metrics' => [
                'menus' => $totalMenus,
                'users' => $totalUsers,
                'distributions' => $totalDistributions,
            ],
            'latest_users' => $latestUsers,
            'bar_chart' => array_values($barChart)
        ]);
    }
}