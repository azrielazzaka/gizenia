import 'package:get/get.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../../../../core/constants/api_endpoints.dart';
import '../../../../routes/app_routes.dart';

class DashboardController extends GetxController {
  // 1. STATE MANAGEMENT (Observables / .obs)
  var allMenus = [].obs;
  var isLoading = true.obs;
  
  // Data Profil Utama
  var userName = "Memuat...".obs;
  var userBmi = 0.0.obs;

  // Target Nutrisi (Dihitung otomatis oleh AI dari data profil)
  var targetCalories = 2100.obs;
  
  // Data Makronutrisi Hari Ini
  var consumedCalories = 0.0.obs;
  var consumedProtein = 0.0.obs;
  var consumedCarbs = 0.0.obs;
  var consumedFats = 0.0.obs;

  // Status Notifikasi (Untuk Popup Makanan Tiba)
  var hasNotification = false.obs;
  var activeDistributionId = "".obs;
  var foodListText = "".obs;

  // Riwayat Distribusi
  var recentHistory = [].obs;

  // 2. LIFECYCLE
  @override
  void onInit() {
    super.onInit();
    fetchAllData();
  }

  // 3. FUNGSI UTAMA (Menjalankan semua fetch secara berurutan)
  Future<void> fetchAllData() async {
    isLoading.value = true;
    
    // Cek Token terlebih dahulu
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String token = prefs.getString('jwt_token') ?? '';
    if (token.isEmpty) {
      Get.offAllNamed(Routes.LOGIN);
      return;
    }

    // Jalankan permintaan data ke Laravel
    await fetchMenusDatabase(token);
    await fetchProfile(token);
    await checkNotification(token);
    await fetchHistory(token);
    
    isLoading.value = false;
  }

  // --- A. AMBIL PROFIL & HITUNG TARGET ---
  Future<void> fetchProfile(String token) async {
    try {
      var res = await http.get(
        Uri.parse("${ApiEndpoints.baseUrlLaravel}/auth/me"),
        headers: {"Authorization": "Bearer $token", "Accept": "application/json"},
      );

      if (res.statusCode == 200) {
        var user = jsonDecode(res.body);
        userName.value = user['name'].toString().split(' ')[0].toUpperCase();

        // Kalkulator Fisik Cerdas (BMR & TDEE)
        double w = double.tryParse(user['weight'].toString()) ?? 40.0;
        double h = double.tryParse(user['height'].toString()) ?? 140.0;
        int a = int.tryParse(user['age'].toString()) ?? 12;

        double bmr = (10 * w) + (6.25 * h) - (5 * a) + 5;
        double tdee = bmr * 1.375;
        
        // Target 1x Makan Utama (35% dari harian)
        targetCalories.value = (tdee * 0.35).round(); 
        
        // Kalkulasi BMI
        double heightInMeter = h / 100;
        userBmi.value = w / (heightInMeter * heightInMeter);
        
      } else if (res.statusCode == 401) {
        SharedPreferences prefs = await SharedPreferences.getInstance();
        await prefs.remove('jwt_token');
        Get.offAllNamed(Routes.LOGIN);
      }
    } catch (e) {
      print("Error Fetch Profile: $e");
    }
  }

  // --- B. CEK NOTIFIKASI DISTRIBUSI MAKANAN ---
  Future<void> checkNotification(String token) async {
  try {
    var res = await http.get(
      Uri.parse("${ApiEndpoints.baseUrlLaravel}/user/notification"),
      headers: {
        "Authorization": "Bearer $token",
        "Accept": "application/json"
      },
    );

    print("NOTIF RESPONSE: ${res.body}");

    if (res.statusCode == 200) {
      var data = jsonDecode(res.body);

      if (data['has_notification'] == true &&
          data['distribution'] != null) {

        hasNotification.value = true;

        final distribution = data['distribution'];

        // SAFE ID CONVERSION
        activeDistributionId.value =
            (distribution['_id'] ?? distribution['id']).toString();

        // SAFE FOOD PARSING
        List foods = distribution['foods'] ?? [];

        foodListText.value = foods
            .map((f) => "${f['name']} (${f['weight']}g)")
            .join(', ');

      } else {
        hasNotification.value = false;
      }
    } else {
      hasNotification.value = false;
    }

  } catch (e) {
    hasNotification.value = false;
    print("Error Check Notif: $e");
  }
}

  // --- C. JAWAB NOTIFIKASI (TERIMA / TOLAK) ---
  Future<void> submitResponse(String answer) async {
    if (activeDistributionId.value.isEmpty) return;
    isLoading.value = true;

    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String token = prefs.getString('jwt_token') ?? '';

      var res = await http.post(
        Uri.parse("${ApiEndpoints.baseUrlLaravel}/user/distributions/${activeDistributionId.value}/respond"),
        headers: {"Content-Type": "application/json", "Authorization": "Bearer $token"},
        body: jsonEncode({"answer": answer}),
      );

      if (res.statusCode == 200) {
        hasNotification.value = false;
        Get.snackbar("Berhasil", "Tanggapan Anda telah dicatat sistem.", backgroundColor: Get.theme.colorScheme.primaryContainer);
        
        // Refresh riwayat setelah merespon
        await fetchHistory(token); 
      }
    } catch (e) {
      print("Error Submit Response: $e");
    } finally {
      isLoading.value = false;
    }
  }

  Future<void> fetchMenusDatabase(String token) async {
    try {
      var res = await http.get(
        Uri.parse("${ApiEndpoints.baseUrlLaravel}/menus"),
        headers: {"Authorization": "Bearer $token", "Accept": "application/json"},
      );
      if (res.statusCode == 200) {
        var data = jsonDecode(res.body);
        // Sesuaikan mapping data seperti di web
        allMenus.value = data['all_menus']?['data'] ?? data['data'] ?? data ?? [];
      }
    } catch (e) {
      print("Error Fetch Menus: $e");
    }
  }

  // --- D. AMBIL RIWAYAT MAKANAN ---
  Future<void> fetchHistory(String token) async {
    try {
      var res = await http.get(
        Uri.parse("${ApiEndpoints.baseUrlLaravel}/user/history"),
        headers: {"Authorization": "Bearer $token", "Accept": "application/json"},
      );

      if (res.statusCode == 200) {
        var data = jsonDecode(res.body);
        if (data['data'] != null) {
          List histories = data['data'];
          recentHistory.value = histories.take(3).toList();

          // LOGIKA PERHITUNGAN PERSIS VERSI WEB
          double totalCals = 0;
          double totalPro = 0;
          double totalCarbs = 0;
          double totalFats = 0;

          String today = DateTime.now().toIso8601String().split('T')[0];

          for (var dist in histories) {
            // 1. Filter Tanggal Hari Ini
            if (dist['distribution_date'] == today) {
              // 2. Cek apakah user menjawab "Ya"
              var responses = dist['responses'] as List? ?? [];
              bool isAccepted = responses.any((r) => r['answer'] == 'Ya');

              if (isAccepted) {
                List foods = dist['foods'] ?? [];
                for (var f in foods) {
                  // 3. Cari detail nutrisi di database menu
                  var menuId = f['menu_id']?.toString();
                  var menuData = allMenus.firstWhere(
                    (m) => m['id']?.toString() == menuId || m['_id']?.toString() == menuId,
                    orElse: () => null,
                  );

                  print("--- DEBUG DATA MENU ---");
    print("Nama Makanan di History: ${f['name']}");
    if (menuData != null) {
      print("Data Menu Ditemukan: ${menuData['name']}");
      print("Isi Raw menuData: $menuData"); // Ini akan menampilkan semua key (fat, fats, atau lemak)
    } else {
      print("Data Menu TIDAK DITEMUKAN untuk ID: $menuId");
    }
    print("-----------------------");

                 if (menuData != null) {
  double foodWeight = double.tryParse(f['weight'].toString()) ?? 100;
  double servingSize = double.tryParse((menuData['serving_size_g'] ?? menuData['serving_size'] ?? 100).toString()) ?? 100;
  double ratio = foodWeight / servingSize;

  // Kalori
  totalCals += (double.tryParse((menuData['calories'] ?? menuData['kalori'] ?? 0).toString()) ?? 0) * ratio;
  
  // Protein
  totalPro += (double.tryParse((menuData['protein'] ?? 0).toString()) ?? 0) * ratio;
  
  // Karbohidrat (di log tertulis 'carbohydrates')
  totalCarbs += (double.tryParse((menuData['carbohydrates'] ?? menuData['carbs'] ?? menuData['karbohidrat'] ?? 0).toString()) ?? 0) * ratio;
  
  // Lemak (DI LOG ANDA TERTULIS 'fat')
  // Kita tambahkan pengecekan 'fat' di sini agar terbaca
  totalFats += (double.tryParse((menuData['fat'] ?? menuData['fats'] ?? menuData['lemak'] ?? 0).toString()) ?? 0) * ratio;
}
                }
              }
            }
          }

          // Update UI
          consumedCalories.value = totalCals;
          consumedProtein.value = totalPro;
          consumedCarbs.value = totalCarbs;
          consumedFats.value = totalFats;
        }
      }
    } catch (e) {
      print("Error Fetch History: $e");
    }
  }
}