import 'package:get/get.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../../../../core/constants/api_endpoints.dart';
import '../../../../routes/app_routes.dart';

class DashboardController extends GetxController {
  // 1. STATE MANAGEMENT (Observables / .obs)
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

  // --- B. CEK NOTIFIKASI MAKANAN DARI ADMIN ---
  Future<void> checkNotification(String token) async {
    try {
      var res = await http.get(
        Uri.parse("${ApiEndpoints.baseUrlLaravel}/user/notification"),
        headers: {"Authorization": "Bearer $token", "Accept": "application/json"},
      );

      if (res.statusCode == 200) {
        var data = jsonDecode(res.body);
        if (data['has_notification'] == true) {
          hasNotification.value = true;
          activeDistributionId.value = data['distribution']['_id'] ?? data['distribution']['id'].toString();
          
          List foods = data['distribution']['foods'];
          foodListText.value = foods.map((f) => "${f['name']} (${f['weight']}g)").join(', ');
        } else {
          hasNotification.value = false;
        }
      }
    } catch (e) {
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
          var histories = data['data'] as List;
          // Ambil 3 data teratas saja untuk ditampilkan di Home
          recentHistory.value = histories.take(3).toList();
        }
      }
    } catch (e) {
      print("Error Fetch History: $e");
    }
  }
}