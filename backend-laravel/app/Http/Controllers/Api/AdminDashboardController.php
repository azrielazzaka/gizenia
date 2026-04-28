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
    // 1. Metrik
    $totalMenus = FoodMenu::count();
    $totalUsers = User::where('role', 'user')->count();
    $totalDistributions = Distribution::count();

    // 2. Latest Users
    $latestUsers = User::where('role', 'user')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get(['name', 'created_at', 'class_room']);

    $latestUsers->transform(function ($user) {
        $user->formatted_date = Carbon::parse($user->created_at)->translatedFormat('d M Y');
        $user->initials = strtoupper(substr($user->name, 0, 2));
        return $user;
    });

    // 3. Bar Chart (6 bulan terakhir)
    $barChart = [];
    for ($i = 5; $i >= 0; $i--) {
        $month = Carbon::now()->subMonths($i);
        $barChart[$month->format('Y-m')] = [
            'label' => $month->translatedFormat('M'),
            'count' => 0
        ];
    }

    $allUsers = User::where('role', 'user')->get(['created_at']);
    foreach ($allUsers as $u) {
        $dateKey = Carbon::parse($u->created_at)->format('Y-m');
        if (isset($barChart[$dateKey])) {
            $barChart[$dateKey]['count']++;
        }
    }

    // 4. Trend Distribusi (Jan - Des tahun sekarang)
    $trend = [];
    $currentYear = Carbon::now()->year;

    for ($i = 1; $i <= 12; $i++) {
        $month = Carbon::create($currentYear, $i, 1);

        $trend[$month->format('Y-m')] = [
            'label' => $month->translatedFormat('M'),
            'total' => 0
        ];
    }

    $allDistributions = Distribution::get(['created_at']);

    foreach ($allDistributions as $d) {
        $date = Carbon::parse($d->created_at);

        if ($date->year == $currentYear) {
            $key = $date->format('Y-m');

            if (isset($trend[$key])) {
                $trend[$key]['total']++;
            }
        }
    }

    return response()->json([
        'metrics' => [
            'menus' => $totalMenus,
            'users' => $totalUsers,
            'distributions' => $totalDistributions,
        ],
        'latest_users' => $latestUsers,
        'bar_chart' => array_values($barChart),
        'trend' => array_values($trend)
    ]);
}
}