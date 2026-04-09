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

        // Cari distribusi hari ini yang target kelasnya cocok dengan kelas user
        $distribution = Distribution::where('distribution_date', $today)
            ->where('target_classes', $user->class_room)
            ->first();

        if (!$distribution) {
            return response()->json(['has_notification' => false]);
        }

        // Cek apakah user sudah menjawab 'Ya' atau 'Tidak' sebelumnya
        $responses = $distribution->responses ?? [];
        $hasResponded = collect($responses)->contains('user_id', $user->_id ?? $user->id);

        if ($hasResponded) {
            return response()->json(['has_notification' => false]);
        }

        return response()->json([
            'has_notification' => true,
            'distribution' => $distribution
        ]);
    }

    // 2. Simpan jawaban 'Ya' atau 'Tidak' dari User
    public function submitResponse(Request $request, $id)
    {
        $request->validate(['answer' => 'required|in:Ya,Tidak']);
        
        $dist = Distribution::find($id);
        if (!$dist) return response()->json(['error' => 'Data tidak ditemukan'], 404);

        $user = auth()->user();
        $responses = $dist->responses ?? [];

        // Masukkan jawaban user ke dalam array responses
        $responses[] = [
            'user_id' => $user->_id ?? $user->id,
            'user_name' => $user->name,
            'answer' => $request->answer,
            'responded_at' => now()->toDateTimeString()
        ];

        $dist->responses = $responses;
        $dist->save();

        return response()->json(['message' => 'Terima kasih atas konfirmasi Anda!']);
    }

    // 3. Ambil riwayat distribusi yang sudah diterima user (Paginasi 5)
    public function history()
    {
        $user = auth()->user();
        
        // Ambil semua distribusi yang pernah dikirim ke kelas user ini
        $history = Distribution::where('target_classes', $user->class_room)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return response()->json($history);
    }
}