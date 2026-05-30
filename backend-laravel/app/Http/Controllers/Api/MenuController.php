<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FoodMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    // Mengambil semua data menu
    public function index()
{
    $menus = FoodMenu::latest()->paginate(10); // 10 data per page
    return response()->json($menus);
}

    // Menyimpan menu baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'serving_size_g' => 'required|numeric',
            'calories' => 'required|numeric',
            'protein' => 'required|numeric',
            'carbohydrates' => 'required|numeric',
            'fat' => 'required|numeric',
            'fiber' => 'required|numeric',
            // Field opsional di bawah ini
            'description' => 'nullable|string',
            'vitamin_a' => 'nullable|numeric',
            'vitamin_c' => 'nullable|numeric',
            'calcium' => 'nullable|numeric',
            'iron' => 'nullable|numeric',
            'sodium' => 'nullable|numeric',
            'category' => 'nullable|string',
            'meal_time' => 'nullable|string',
            'image_url' => 'nullable|string',
            'kaggle_id' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $menu = FoodMenu::create($request->all());
        return response()->json(['message' => 'Menu berhasil ditambahkan!', 'data' => $menu]);
    }

    // Menghapus menu
    public function destroy($id)
    {
        $menu = FoodMenu::find($id);
        if (!$menu) {
            return response()->json(['error' => 'Menu tidak ditemukan'], 404);
        }

        $menu->delete();
        return response()->json(['message' => 'Menu berhasil dihapus!']);
    }

    // Memperbarui data menu yang sudah ada
    public function update(Request $request, $id)
    {
        $menu = FoodMenu::find($id);
        if (!$menu) {
            return response()->json(['error' => 'Menu tidak ditemukan'], 404);
        }

        $menu->update($request->all());

        return response()->json([
            'message' => 'Menu berhasil diperbarui!',
            'data' => $menu
        ]);
    }

    // Import Data dari CSV Makanan Indonesia (nutrition.csv)
    // Import Data dari CSV Makanan Indonesia (nutrition.csv)
    public function importCsv(Request $request)
    {
        // 1. Mencegah Timeout untuk file CSV yang besar
        set_time_limit(0);

        // Validasi file
        $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('file');
        $fileHandle = fopen($file->getPathname(), 'r'); 

        $importedCount = 0;
        $isFirstRow = true;
        $header = [];

        // 2. Menggunakan fgetcsv (LEBIH AMAN dari explode). 
        // Ini otomatis mengabaikan koma yang ada di dalam tanda kutip teks.
        while (($row = fgetcsv($fileHandle, 10000, ',')) !== false) {
            
            // Menangani Header (Baris Pertama)
            if ($isFirstRow) {
                $header = $row;
                // Hapus karakter BOM tak kasat mata yang sering ada di awal file CSV
                $header[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header[0]);
                $isFirstRow = false;
                continue;
            }

            // Lewati jika jumlah kolom data tidak sama dengan jumlah header (mencegah error)
            if (count($header) !== count($row)) {
                continue;
            }

            // Gabungkan header sebagai Key dan row sebagai Value
            $data = array_combine($header, $row);

            // 3. Gunakan Try-Catch agar jika 1 baris gagal, aplikasi TIDAK CRASH (lanjut ke baris berikutnya)
            try {
                FoodMenu::create([
                    'kaggle_id'      => $data['id'] ?? null,
                    'name'           => ucwords($data['name'] ?? 'Menu Tanpa Nama'),
                    'serving_size_g' => 100, 
                    'calories'       => (float)($data['calories'] ?? 0),
                    'protein'        => (float)($data['proteins'] ?? 0), 
                    'carbohydrates'  => (float)($data['carbohydrate'] ?? 0), 
                    'fat'            => (float)($data['fat'] ?? 0),
                    
                    // cluster_id dari K-Means
                    'cluster_id'     => isset($data['cluster']) ? (int)$data['cluster'] : null, 

                    // Kolom mikronutrisi dan UI 
                    'fiber'          => 0, 
                    'vitamin_a'      => 0,
                    'vitamin_c'      => 0, 
                    'calcium'        => 0, 
                    'iron'           => 0, 
                    'sodium'         => 0, 
                    'category'       => 'UMUM', 
                    'meal_time'      => 'BEBAS',
                    
                    // Mengambil gambar dari CSV, jika kosong gunakan default image
                    'image_url'      => !empty($data['image']) ? $data['image'] : 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
                ]);
                
                $importedCount++;
            } catch (\Exception $e) {
                // Abaikan baris yang error, lanjutkan loop ke data berikutnya
                continue;
            }
        }

        fclose($fileHandle);
        
        return response()->json([
            'message' => "$importedCount Makanan Indonesia berhasil diimport ke database!"
        ], 200);
    }
}