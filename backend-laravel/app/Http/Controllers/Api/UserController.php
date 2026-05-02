<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Mengambil data pengguna dengan Pencarian, Filter, dan Paginasi
    public function index(Request $request)
    {
        // 1. Mulai Query
        $query = User::orderBy('created_at', 'desc');

        // 2. Jika ada pencarian (Search)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 3. Jika ada filter Role (dan bukan opsi 'Semua')
        if ($request->has('role') && $request->role != '' && $request->role != 'Semua Role') {
            $query->where('role', strtolower($request->role));
        }

        // 4. Lakukan Paginasi (misal:  data per halaman agar mudah ditest)
        // Kamu bisa menggantinya jadi 10 atau 20 nantinya.
        $users = $query->paginate(10);
        
        return response()->json($users);
    }

    // Mengupdate data pengguna (Edit Role, Fisik, dll)
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Pengguna tidak ditemukan'], 404);
        }

        // Simpan perubahan
        $user->update($request->all());

        return response()->json([
            'message' => 'Data pengguna berhasil diperbarui!',
            'data' => $user
        ]);
    }

    // Menghapus pengguna
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Pengguna tidak ditemukan'], 404);
        }

        // Mencegah Admin menghapus akunnya sendiri
        if (auth()->user()->_id == $id || auth()->user()->id == $id) {
            return response()->json(['error' => 'Anda tidak bisa menghapus akun yang sedang Anda gunakan!'], 400);
        }

        $user->delete();
        return response()->json(['message' => 'Pengguna berhasil dihapus!']);
    }

   public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password,
        'role' => 'admin'
    ]);

    return response()->json([
        'message' => 'Admin berhasil dibuat',
        'user' => $user
    ], 201);
}
}