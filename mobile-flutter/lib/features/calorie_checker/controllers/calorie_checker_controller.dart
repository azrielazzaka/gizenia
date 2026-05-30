import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../../../../core/constants/api_endpoints.dart';

class CalorieCheckerController extends GetxController {
  var isLoading = true.obs;
  
  // ================= DATA KATALOG & FORM =================
  final weightCtrl = TextEditingController();
  final heightCtrl = TextEditingController();
  final ageCtrl = TextEditingController();
  var gender = 'male'.obs;
  var activity = 'moderate'.obs;

  var targetMealCalories = 700.obs; 
  var recommendations = [].obs;
  var allMenus = [].obs;

  var hasAiData = false.obs;
  var aiMessage = "Silakan isi data fisik Anda pada form di atas untuk analisis AI.".obs;
  var aiMacros = {}.obs;

  var searchQuery = "".obs;
  var sortType = "".obs;

  List get filteredMenus {
    var list = allMenus.where((menu) => 
      menu['name'].toString().toLowerCase().contains(searchQuery.value.toLowerCase())
    ).toList();

    if (sortType.value == 'az') {
      list.sort((a, b) => a['name'].toString().compareTo(b['name'].toString()));
    } else if (sortType.value == 'za') {
      list.sort((a, b) => b['name'].toString().compareTo(a['name'].toString()));
    } else if (sortType.value == 'cal_low') {
      list.sort((a, b) => (double.tryParse(a['calories'].toString()) ?? 0).compareTo(double.tryParse(b['calories'].toString()) ?? 0));
    } else if (sortType.value == 'cal_high') {
      list.sort((a, b) => (double.tryParse(b['calories'].toString()) ?? 0).compareTo(double.tryParse(a['calories'].toString()) ?? 0));
    }
    return list;
  }

  // ================= DATA PELACAK (PIRINGKU) =================
  var myPlate = <Map<String, dynamic>>[].obs;
  var currentCals = 0.0.obs;
  var currentPro = 0.0.obs;
  var currentCarbs = 0.0.obs;
  var currentFat = 0.0.obs;

  var isAiLoading = false.obs;
  var aiVerdictTitle = "Belum Ada Data".obs;
  var aiVerdictMessage = "Tambahkan makanan untuk melihat apakah piringmu sudah seimbang.".obs;
  var aiStatusColor = "gray".obs; 

  @override
  void onInit() {
    super.onInit();
    fetchMenus(fromForm: false);
  }

  void runAiAnalysis() {
    if (weightCtrl.text.isEmpty || heightCtrl.text.isEmpty || ageCtrl.text.isEmpty) {
      Get.snackbar(
        "Perhatian", "Mohon lengkapi form Berat Badan, Tinggi Badan, dan Umur terlebih dahulu!",
        backgroundColor: Colors.red.shade100, colorText: Colors.red.shade900,
        icon: Icon(Icons.warning_amber_rounded, color: Colors.red.shade900),
      );
      return;
    }
    fetchMenus(fromForm: true);
  }

  Future<void> fetchMenus({bool fromForm = false}) async {
    try {
      isLoading.value = true;
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String token = prefs.getString('jwt_token') ?? '';

      String url = ApiEndpoints.getMenus;
      if (fromForm) {
        url += "?weight=${weightCtrl.text}&height=${heightCtrl.text}&age=${ageCtrl.text}&gender=${gender.value}&activity=${activity.value}";
      }

      var res = await http.get(
        Uri.parse(url),
        headers: {"Authorization": "Bearer $token", "Accept": "application/json"},
      );

      if (res.statusCode == 200) {
        var data = jsonDecode(res.body);
        
        if (data['ai_analysis'] != null) {
          aiMessage.value = data['ai_analysis']['message'] ?? "Rekomendasi berhasil dimuat.";
          aiMacros.value = data['ai_analysis']['target_nutrisi'] ?? {};
        }
        
        recommendations.value = data['recommendations'] ?? [];
        allMenus.value = data['all_menus'] != null ? data['all_menus']['data'] : data['data'] ?? [];
        
        hasAiData.value = recommendations.isNotEmpty;

        if (data['user_stats'] != null) {
          var stats = data['user_stats'];
          if (!fromForm && stats['weight'] != null) {
            weightCtrl.text = stats['weight'].toString();
            heightCtrl.text = stats['height'].toString();
            ageCtrl.text = stats['age'].toString();
            gender.value = stats['gender'] ?? 'male';
            activity.value = stats['activity'] ?? 'moderate';
          }
          
          targetMealCalories.value = (stats['target_meal'] ?? 700).round();
          if (targetMealCalories.value <= 0) targetMealCalories.value = 700;
        }
      }
    } catch (e) {
      Get.snackbar("Error", "Gagal memuat data dari server.");
    } finally {
      isLoading.value = false;
    }
  }

  Future<Iterable<Map<String, dynamic>>> searchFoodsDynamic(String query) async {
    if (query.isEmpty) return const Iterable<Map<String, dynamic>>.empty();
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String token = prefs.getString('jwt_token') ?? '';
      
      var res = await http.get(
        Uri.parse(ApiEndpoints.getMenus + "?search=$query"),
        headers: {"Authorization": "Bearer $token", "Accept": "application/json"},
      );
      
      if (res.statusCode == 200) {
        var data = jsonDecode(res.body);
        List menus = data['all_menus'] != null ? data['all_menus']['data'] : data['data'] ?? [];
        return menus.cast<Map<String, dynamic>>();
      }
    } catch (e) {}
    return const Iterable<Map<String, dynamic>>.empty();
  }

  // ================= FUNGSI PIRINGKU =================
  void addFoodToPlate(dynamic menu, double portionGrams) {
    if (portionGrams <= 0) return;
    double standardPortion = double.tryParse(menu['serving_size_g'].toString()) ?? 100.0;
    double ratio = portionGrams / standardPortion;

    myPlate.add({
      'name': menu['name'], 'weight': portionGrams,
      'calories': (double.tryParse(menu['calories'].toString()) ?? 0.0) * ratio,
      'protein': (double.tryParse(menu['protein'].toString()) ?? 0.0) * ratio,
      'carbohydrates': (double.tryParse(menu['carbohydrates'].toString()) ?? 0.0) * ratio,
      'fat': (double.tryParse(menu['fat'].toString()) ?? 0.0) * ratio,
    });
    _calculateMacros();
  }

  void removeFood(int index) {
    myPlate.removeAt(index);
    _calculateMacros();
  }

  void _calculateMacros() {
    currentCals.value = myPlate.fold(0, (sum, item) => sum + item['calories']);
    currentPro.value = myPlate.fold(0, (sum, item) => sum + item['protein']);
    currentCarbs.value = myPlate.fold(0, (sum, item) => sum + item['carbohydrates']);
    currentFat.value = myPlate.fold(0, (sum, item) => sum + item['fat']);
    
    // Reset Vonis setiap kali piring berubah agar user tahu harus klik evaluasi lagi
    aiVerdictTitle.value = "Piring Berubah";
    aiVerdictMessage.value = "Tekan 'Evaluasi Piring dengan AI' untuk menganalisis komposisi baru.";
    aiStatusColor.value = "gray";
  }

  Future<void> evaluateWithAI() async {
    if (myPlate.isEmpty) {
      Get.snackbar("Piring Kosong!", "Pilih minimal 1 makanan terlebih dahulu.", backgroundColor: Colors.red.shade100, colorText: Colors.red.shade900);
      return;
    }
    
    // Cegah klik berulang saat masih loading
    if (isAiLoading.value) return; 

    isAiLoading.value = true;
    try {
      var payload = {
        "foods": myPlate.map((f) => {
          "name": f['name'], "weight": f['weight'], "calories": f['calories'],
          "protein": f['protein'], "carbohydrates": f['carbohydrates'], "fat": f['fat']
        }).toList()
      };

      String targetUrl = ApiEndpoints.evaluateMeal;
      if (targetUrl.isEmpty) {
        targetUrl = 'http://127.0.0.1:5000/api/predict/evaluation';
      }

      // BUG FIX: Jika pakai Emulator Android, localhost (127.0.0.1) harus diubah ke 10.0.2.2
      if (GetPlatform.isAndroid) {
        targetUrl = targetUrl.replaceAll('127.0.0.1', '10.0.2.2').replaceAll('localhost', '10.0.2.2');
      }

      // BUG FIX: Tambahkan Timeout 10 detik agar tidak nge-hang selamanya jika Python mati
      var res = await http.post(
        Uri.parse(targetUrl),
        headers: {"Content-Type": "application/json"},
        body: jsonEncode(payload),
      ).timeout(const Duration(seconds: 10));

      if (res.statusCode == 200) {
        var data = jsonDecode(res.body);
        if (data['status'] == 'success') {
          String verdict = data['evaluation']['verdict'];
          aiVerdictTitle.value = verdict;
          
          List msgs = data['evaluation']['messages'];
          aiVerdictMessage.value = msgs.map((m) => "• $m").join("\n");

          if (verdict.toLowerCase().contains("seimbang")) aiStatusColor.value = "green";
          else if (verdict.toLowerCase().contains("tinggi")) aiStatusColor.value = "orange";
          else aiStatusColor.value = "red";
        }
      } else {
        Get.snackbar("Error ${res.statusCode}", "Terjadi kesalahan pada Server AI Python.");
      }
    } catch (e) {
      Get.snackbar("AI Offline", "Gagal terhubung ke AI Python. Pastikan server Flask aktif.", backgroundColor: Colors.red.shade100, colorText: Colors.red.shade900);
    } finally {
      // Pastikan status loading dikembalikan menjadi false agar tombol hidup kembali
      isAiLoading.value = false;
    }
  }
}