import 'package:get/get.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../../../../core/constants/api_endpoints.dart';

class HistoryController extends GetxController {
  var isLoading = true.obs;
  var totalCaloriesConsumed = 0.obs;
  var targetCalories = 2000.obs;
  
  // List riwayat dinamis
  var breakfastList = [].obs;
  var lunchList = [].obs;

  @override
  void onInit() {
    super.onInit();
    fetchHistory();
  }

  Future<void> fetchHistory() async {
    isLoading.value = true;
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String token = prefs.getString('jwt_token') ?? '';

      var res = await http.get(
        Uri.parse(ApiEndpoints.getHistory),
        headers: {"Authorization": "Bearer $token", "Accept": "application/json"},
      );

      if (res.statusCode == 200) {
        var data = jsonDecode(res.body);
        totalCaloriesConsumed.value = data['summary']['total_calories'];
        
        // Asumsi backend memisahkan history berdasarkan kategori (Sarapan, Makan Siang)
        breakfastList.value = data['history_breakfast'] ?? [];
        lunchList.value = data['history_lunch'] ?? [];
      }
    } catch (e) {
      // Dummy data jika backend offline
      totalCaloriesConsumed.value = 1450;
      breakfastList.value = [
        {"name": "Roti Gandum & Telur", "cal": "250 kcal", "time": "07:30 AM"},
        {"name": "Susu Almond", "cal": "130 kcal", "time": "07:45 AM"},
      ];
      lunchList.value = [
        {"name": "Salad Quinoa Mediterania", "cal": "420 kcal", "time": "12:30 PM"},
      ];
    } finally {
      isLoading.value = false;
    }
  }
}