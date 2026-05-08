<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Distribution;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    // 1. Cek apakah ada notifikasi distribusi hari ini untuk user
    public function checkNotification()
    {
        $user = auth()->user();
        $today = now()->toDateString();

        

        // Cari distribusi hari ini sesuai kelas user
        $distribution = Distribution::where('distribution_date', $today)
            ->where('target_classes', 'all', [$user->class_room])
            ->first();

            return response()->json([
    'today' => $today,
    'user_class' => $user->class_room,
    'distribution_found' => $distribution,
    'responses' => $distribution->responses ?? []
]);

        // Jika tidak ada distribusi
        if (!$distribution) {
            return response()->json([
                'has_notification' => false,
                'data' => []
            ]);
        }

        // Ambil response user sebelumnya
        $responses = $distribution->responses ?? [];

        $hasResponded = collect($responses)->contains(function ($response) use ($user) {
            return ($response['user_id'] ?? null) == ($user->_id ?? $user->id);
        });

        // Jika user sudah menjawab
        if ($hasResponded) {
            return response()->json([
                'has_notification' => false,
                'data' => []
            ]);
        }

        // Jika ada notif
        return response()->json([
            'has_notification' => true,
            'data' => [
                [
                    'id' => $distribution->_id ?? $distribution->id,
                    'title' => 'Distribusi Makanan',
                    'message' => 'Apakah sudah menerima makanan?',
                    'created_at' => $distribution->distribution_date
                ]
            ]
        ]);
    }

    // 2. Simpan jawaban user
    public function submitResponse(Request $request, $id)
    {
        $request->validate([
            'answer' => 'required|in:Ya,Tidak'
        ]);

        $dist = Distribution::find($id);

        if (!$dist) {
            return response()->json([
                'error' => 'Data tidak ditemukan'
            ], 404);
        }

        $user = auth()->user();

        $responses = $dist->responses ?? [];

        // Cek apakah user sudah pernah jawab
        $alreadyResponded = collect($responses)->contains(function ($response) use ($user) {
            return ($response['user_id'] ?? null) == ($user->_id ?? $user->id);
        });

        if ($alreadyResponded) {
            return response()->json([
                'message' => 'Anda sudah memberikan jawaban sebelumnya.'
            ]);
        }

        // Simpan jawaban baru
        $responses[] = [
            'user_id' => $user->_id ?? $user->id,
            'user_name' => $user->name,
            'answer' => $request->answer,
            'responded_at' => now()->toDateTimeString()
        ];

        $dist->responses = $responses;
        $dist->save();

        return response()->json([
            'message' => 'Terima kasih atas konfirmasi Anda!'
        ]);
    }

    // 3. Riwayat distribusi user
    public function history(Request $request)
    {
        $user = auth()->user();

        $all = Distribution::where('target_classes', 'all', [$user->class_room])
            ->orderBy('distribution_date', 'desc')
            ->get();

        // Filter tanggal
        if ($request->date) {
            $filterDate = $request->date;

            $all = $all->filter(function ($item) use ($filterDate) {
                return str_starts_with($item->distribution_date, $filterDate);
            })->values();
        }

        // Search makanan
        if ($request->search) {
            $search = strtolower($request->search);

            $all = $all->filter(function ($item) use ($search) {

                foreach ($item->foods as $food) {

                    if (
                        isset($food['name']) &&
                        str_contains(strtolower($food['name']), $search)
                    ) {
                        return true;
                    }
                }

                return false;
            })->values();
        }

        // Pagination manual
        $page = $request->page ?? 1;
        $perPage = 5;

        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $all->forPage($page, $perPage),
            $all->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );

        return response()->json($paginated);
    }
}