import 'package:get/get.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../../../../core/constants/api_endpoints.dart';

class CalorieCheckerController extends GetxController {
  var isLoading = true.obs;
  
  // ================= DATA KATALOG =================
  var targetMealCalories = 2000.obs;
  var userWeight = 0.0.obs;
  var userHeight = 0.0.obs;
  var recommendations = [].obs;
  var allMenus = [].obs;

  // Fitur Filter & Search
  var searchQuery = "".obs;
  var sortType = "".obs;

  // Getter dinamis untuk list menu yang difilter
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

  // Hasil AI
  var isAiLoading = false.obs;
  var aiVerdictTitle = "Belum Ada Data".obs;
  var aiVerdictMessage = "Tambahkan makanan untuk melihat apakah piringmu sudah seimbang.".obs;
  var aiStatusColor = "gray".obs; // gray, green, orange, red

  @override
  void onInit() {
    super.onInit();
    fetchMenus();
  }

  Future<void> fetchMenus() async {
    try {
      isLoading.value = true;
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String token = prefs.getString('jwt_token') ?? '';

      var res = await http.get(
        Uri.parse(ApiEndpoints.getMenus),
        headers: {"Authorization": "Bearer $token", "Accept": "application/json"},
      );

      if (res.statusCode == 200) {
        var data = jsonDecode(res.body);
        var stats = data['user_stats'];
        userWeight.value = double.tryParse(stats['weight'].toString()) ?? 0.0;
        userHeight.value = double.tryParse(stats['height'].toString()) ?? 0.0;
        
        // Pastikan target tidak 0 agar tidak error saat dibagi (grafik lingkaran)
        targetMealCalories.value = (stats['target_meal'] ?? 2000).round();
        if (targetMealCalories.value == 0) targetMealCalories.value = 700; 

        recommendations.value = data['recommendations'] ?? [];
        allMenus.value = data['all_menus']['data'] ?? [];
      }
    } catch (e) {
      Get.snackbar("Error", "Gagal memuat data katalog.");
    } finally {
      isLoading.value = false;
    }
  }

  void addFoodToPlate(dynamic menu, double portionGrams) {
    if (portionGrams <= 0) {
      Get.snackbar("Perhatian", "Porsi harus lebih dari 0 gram", backgroundColor: Get.theme.colorScheme.errorContainer);
      return;
    }

    double standardPortion = double.tryParse(menu['serving_size_g'].toString()) ?? 100.0;
    double ratio = portionGrams / standardPortion;

    myPlate.add({
      'name': menu['name'],
      'weight': portionGrams,
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
    
    aiVerdictTitle.value = "Piring Berubah";
    aiVerdictMessage.value = "Tekan Evaluasi AI untuk menganalisis komposisi baru.";
    aiStatusColor.value = "gray";
  }

  Future<void> evaluateWithAI() async {
    if (myPlate.isEmpty) {
      Get.snackbar("Tunggu Dulu!", "Pilih makanan terlebih dahulu.", backgroundColor: Get.theme.colorScheme.errorContainer);
      return;
    }

    isAiLoading.value = true;
    try {
      var payload = {
        "foods": myPlate.map((f) => {
          "name": f['name'], "weight": f['weight'], "calories": f['calories'],
          "protein": f['protein'], "carbohydrates": f['carbohydrates'], "fat": f['fat']
        }).toList()
      };

      var res = await http.post(
        Uri.parse(ApiEndpoints.evaluateMeal),
        headers: {"Content-Type": "application/json"},
        body: jsonEncode(payload),
      );

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
      }
    } catch (e) {
      Get.snackbar("AI Offline", "Gagal menghubungi Server Python.", backgroundColor: Get.theme.colorScheme.errorContainer);
    } finally {
      isAiLoading.value = false;
    }
  }
}