<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Distribution;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Distribution::orderBy('distribution_date', 'desc');

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('distribution_date', [$request->start_date, $request->end_date]);
        }

        $distributions = $query->get();
        $history = [];
        $totalDistributed = 0;
        $totalTargeted = 0;

        foreach ($distributions as $dist) {
            $foodsText = collect($dist->foods)->pluck('name')->join(', ');
            $responses = collect($dist->responses ?? []);
            
            // Filter User berdasarkan kelas yang ada di distribusi
            $targetClasses = $dist->target_classes ?? [];
            $userQuery = User::whereIn('class_room', $targetClasses);

            // Filter Kelas dari Request
            if ($request->class_room && $request->class_room !== 'Semua') {
                $userQuery->where('class_room', $request->class_room);
            }

            $usersInClasses = $userQuery->get();

            foreach ($usersInClasses as $user) {
                $totalTargeted++;
                $userResponse = $responses->firstWhere('user_id', $user->_id ?? $user->id);
                
                if ($userResponse) {
                    $status = $userResponse['answer'] === 'Ya' ? 'SUDAH DITERIMA' : 'TIDAK DITERIMA';
                    $respondedAt = date('H:i', strtotime($userResponse['responded_at']));
                    if ($status === 'SUDAH DITERIMA') $totalDistributed++;
                } else {
                    $status = 'BELUM DIJAWAB';
                    $respondedAt = '-';
                }

                // Filter Status dari Request
                if ($request->status && $request->status !== 'Semua' && $request->status !== $status) {
                    $totalTargeted--; 
                    if ($status === 'SUDAH DITERIMA') $totalDistributed--;
                    continue;
                }

                $history[] = [
                    'date' => $dist->distribution_date,
                    'time' => $respondedAt,
                    'user_name' => $user->name,
                    'class_room' => $user->class_room ?? 'Tanpa Kelas',
                    'menu' => $foodsText,
                    'status' => $status
                ];
            }
        }

        $avgFulfilled = $totalTargeted > 0 ? round(($totalDistributed / $totalTargeted) * 100) : 0;

        // JALUR KHUSUS EKSPOR: Kembalikan SEMUA data tanpa dipotong (Paginasi)
        // Menggunakan ->input() sesuai standar Laravel terbaru
        if ($request->has('export') && $request->input('export') === 'true') {
            return response()->json([
                'report_data' => $history
            ]);
        }

        // JALUR REGULER (LAYAR TABEL): Gunakan Paginasi 25 Data
        // Menggunakan ->input() alih-alih ->get()
        $currentPage = $request->input('page', 1);
        $perPage = 25;
        $currentItems = array_slice($history, ($currentPage - 1) * $perPage, $perPage);
        
        $paginatedHistory = new LengthAwarePaginator(
            $currentItems, 
            count($history), 
            $perPage, 
            $currentPage
        );

        return response()->json([
            'total_distributed' => $totalDistributed,
            'avg_fulfilled' => $avgFulfilled,
            'report_data' => $paginatedHistory 
        ]);
    }
}