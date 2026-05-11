import 'package:flutter/material.dart'; // Tambahkan ini untuk Colors
import 'package:get/get.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/constants/api_endpoints.dart';
import '../../../routes/app_routes.dart';

class AuthController extends GetxController {
  var isLoading = false.obs;

  // ==========================================
  // FUNGSI LOGIN
  // ==========================================
  Future<void> login(String email, String password) async {
    if (email.isEmpty || password.isEmpty) {
      Get.snackbar("Error", "Email dan kata sandi wajib diisi!");
      return;
    }

    isLoading.value = true;
    try {
      var response = await http.post(
        Uri.parse(ApiEndpoints.login),
        headers: {"Content-Type": "application/json", "Accept": "application/json"},
        body: jsonEncode({"email": email, "password": password}),
      );

      if (response.statusCode == 200) {
        var data = jsonDecode(response.body);
        
        SharedPreferences prefs = await SharedPreferences.getInstance();
        await prefs.setString('jwt_token', data['access_token']); 

        Get.offAllNamed(Routes.MAIN); // ✅ Pindah ke Dashboard
      } else {
        var err = jsonDecode(response.body);
        Get.snackbar("Gagal", err['error'] ?? "Email atau kata sandi salah.", backgroundColor: Colors.red.shade100);
      }
    } catch (e) {
      Get.snackbar("Error Koneksi", "Gagal menghubungi server Laravel.");
    } finally {
      isLoading.value = false;
    }
  }

  // ==========================================
  // FUNGSI REGISTER
  // ==========================================
  Future<void> register(Map<String, dynamic> userData) async {
    isLoading.value = true;
    try {
      var response = await http.post(
        Uri.parse(ApiEndpoints.register),
        headers: {"Content-Type": "application/json", "Accept": "application/json"},
        body: jsonEncode(userData),
      );

      // Kode 200/201 artinya Data Masuk ke Database
      if (response.statusCode == 200 || response.statusCode == 201) {

        Get.snackbar("Berhasil", "Akun berhasil dibuat!", backgroundColor: Colors.green.shade100);
        
        Get.offAllNamed(Routes.LOGIN); 
      } 
      // Kode Error dari Laravel
      else {
        var err = jsonDecode(response.body);
        print("====== ERROR DARI LARAVEL ======");
        print(response.body);

        String errorMessage = "Periksa kembali data Anda.";
        
        // ✅ PERBAIKAN 2: Penangkap Error Anti-Badai (Tahan semua format JSON)
        if (err is Map) {
          if (err.containsKey('message') && err['message'] is String) {
            errorMessage = err['message'];
          } else if (err.containsKey('error') && err['error'] is String) {
            errorMessage = err['error'];
          } else if (err.isNotEmpty) {
            var firstVal = err.values.first;
            if (firstVal is List && firstVal.isNotEmpty) {
              errorMessage = firstVal[0].toString();
            } else {
              errorMessage = firstVal.toString();
            }
          }
        }

        Get.snackbar(
          "Pendaftaran Ditolak", 
          errorMessage, 
          backgroundColor: Colors.orange.shade100, 
          colorText: Colors.orange.shade900,
          duration: const Duration(seconds: 4),
        );
      }
    } catch (e) {
      print("Error System: $e");
      Get.snackbar("Error Koneksi", "Gagal menghubungi server Laravel.");
    } finally {
      isLoading.value = false;
    }
  }
}