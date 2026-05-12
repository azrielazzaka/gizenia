import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../../../../core/constants/api_endpoints.dart';

class HistoryController extends GetxController {
  var isLoading = true.obs;
  
  // Data Riwayat dari Laravel
  var historyList = [].obs;
  var totalRecords = 0.obs;

  // Filter & Search
  var searchQuery = "".obs;
  var selectedDate = "".obs;

  @override
  void onInit() {
    super.onInit();
    fetchHistory();
  }

  // Mengambil data dari Endpoint Laravel
  Future<void> fetchHistory() async {
    isLoading.value = true;
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String token = prefs.getString('jwt_token') ?? '';

      // Endpoint disertai query pencarian dan tanggal seperti di Web
      String url = "${ApiEndpoints.getHistory}?page=1&search=${searchQuery.value}&date=${selectedDate.value}";

      var res = await http.get(
        Uri.parse(url),
        headers: {"Authorization": "Bearer $token", "Accept": "application/json"},
      );

      if (res.statusCode == 200) {
        var data = jsonDecode(res.body);
        
        // Asumsi data Laravel dibungkus dalam pagination 'data'
        if (data['data'] != null) {
          historyList.value = data['data'];
          totalRecords.value = data['total'] ?? 0;
        }
      }
    } catch (e) {
      print("Error Fetch History: $e");
      Get.snackbar("Error", "Gagal memuat riwayat distribusi.", backgroundColor: Colors.red.shade100);
    } finally {
      isLoading.value = false;
    }
  }

  // Fungsi untuk fitur pencarian
  void searchHistory(String query) {
    searchQuery.value = query;
    fetchHistory();
  }

  // Fungsi untuk filter tanggal
  void filterByDate(String date) {
    selectedDate.value = date;
    fetchHistory();
  }
  
  // Fungsi Reset
  void clearFilter() {
    searchQuery.value = "";
    selectedDate.value = "";
    fetchHistory();
  }
}