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

  @override
  void onInit() {
    super.onInit();
    fetchUserProfile();
  }

  // 1. Ambil Data dari API Laravel
  Future<void> fetchUserProfile() async {
    isLoading.value = true;
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String token = prefs.getString('jwt_token') ?? '';

      var res = await http.get(
        Uri.parse(ApiEndpoints.getProfile),
        headers: {"Authorization": "Bearer $token", "Accept": "application/json"},
      );

      if (res.statusCode == 200) {
        var data = jsonDecode(res.body)['user'];
        name.value = data['name'] ?? 'Alexandra Chen';
        email.value = data['email'] ?? 'alexandra@mail.com';
        age.value = data['age'] ?? 26;
        weight.value = (data['weight'] ?? 58.0).toDouble();
        height.value = (data['height'] ?? 168.0).toDouble();
      } else if (res.statusCode == 401) {
        logout();
      }
    } catch (e) {
      // Data dummy jika server Laravel belum menyala
      name.value = "Alexandra Chen";
      email.value = "alexandra.chen@healthmail.com";
      age.value = 26;
      weight.value = 58.0;
      height.value = 168.0;
    } finally {
      isLoading.value = false;
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

      // Kita anggap sukses untuk simulasi jika tidak 500
      if (res.statusCode == 200 || res.statusCode == 201 || res.statusCode == 404) { 
        name.value = newName;
        email.value = newEmail;
        age.value = newAge;
        weight.value = newWeight;
        height.value = newHeight;
        
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