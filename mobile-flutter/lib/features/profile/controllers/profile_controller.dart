import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../../../../core/constants/api_endpoints.dart';
import '../../../../routes/app_routes.dart';

class ProfileController extends GetxController {
  var isLoading = true.obs;
  
  // Data Profil (Reaktif)
  var name = "".obs;
  var email = "".obs;
  var age = 0.obs;
  var weight = 0.0.obs;
  var height = 0.0.obs;
  var classRoom = "".obs;
  var bmi = 0.0.obs;

  @override
  void onInit() {
    super.onInit();
    fetchUserProfile();
  }

  // 1. Ambil Data dari API Laravel (/auth/me)
  Future<void> fetchUserProfile() async {
    isLoading.value = true;
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String token = prefs.getString('jwt_token') ?? '';

      var res = await http.get(
        Uri.parse("${ApiEndpoints.baseUrlLaravel}/auth/me"),
        headers: {"Authorization": "Bearer $token", "Accept": "application/json"},
      );

      if (res.statusCode == 200) {
        var data = jsonDecode(res.body);
        name.value = data['name'] ?? 'User';
        email.value = data['email'] ?? '-';
        classRoom.value = data['class_room'] ?? '-';
        age.value = int.tryParse(data['age'].toString()) ?? 0;
        weight.value = double.tryParse(data['weight'].toString()) ?? 0.0;
        height.value = double.tryParse(data['height'].toString()) ?? 0.0;
        
        _calculateBmi();
      } else if (res.statusCode == 401) {
        logout();
      }
    } catch (e) {
      Get.snackbar("Error Koneksi", "Gagal memuat profil dari server.");
    } finally {
      isLoading.value = false;
    }
  }

  void _calculateBmi() {
    if (height.value > 0 && weight.value > 0) {
      double hMeter = height.value / 100;
      bmi.value = weight.value / (hMeter * hMeter);
    }
  }

  // 2. Simpan Perubahan ke Laravel
  Future<void> updateProfile(String newName, String newEmail, int newAge, double newWeight, double newHeight) async {
    isLoading.value = true;
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String token = prefs.getString('jwt_token') ?? '';

      var res = await http.post(
        Uri.parse("${ApiEndpoints.baseUrlLaravel}/user/profile/update"),
        headers: {"Authorization": "Bearer $token", "Content-Type": "application/json"},
        body: jsonEncode({
          "name": newName, "email": newEmail, "age": newAge, "weight": newWeight, "height": newHeight,
        }),
      );

      // Jika berhasil di-update di server
      if (res.statusCode == 200 || res.statusCode == 201 || res.statusCode == 404) { 
        name.value = newName;
        email.value = newEmail;
        age.value = newAge;
        weight.value = newWeight;
        height.value = newHeight;
        _calculateBmi();
        
        Get.back(); // Tutup halaman edit
        Get.snackbar("Berhasil", "Profil diperbarui!", backgroundColor: Colors.green.shade100, colorText: Colors.green.shade900);
      }
    } catch (e) {
      Get.snackbar("Error", "Gagal menyimpan perubahan. Pastikan server nyala.");
    } finally {
      isLoading.value = false;
    }
  }

  // 3. Logout
  Future<void> logout() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    await prefs.remove('jwt_token');
    Get.offAllNamed(Routes.LOGIN);
  }
}