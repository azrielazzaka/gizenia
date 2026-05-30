import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/constants/api_endpoints.dart';
import '../../../routes/app_routes.dart';

class AuthController extends GetxController {
  var isLoading = false.obs;

  // ==========================================
  // FUNGSI LOGIN & REGISTER (Tetap Sama)
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
        Get.offAllNamed(Routes.MAIN); 
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

  Future<void> register(Map<String, dynamic> userData) async {
    isLoading.value = true;
    try {
      var response = await http.post(
        Uri.parse(ApiEndpoints.register),
        headers: {"Content-Type": "application/json", "Accept": "application/json"},
        body: jsonEncode(userData),
      );
      if (response.statusCode == 200 || response.statusCode == 201) {
        Get.snackbar("Berhasil", "Akun berhasil dibuat!", backgroundColor: Colors.green.shade100);
        Get.offAllNamed(Routes.LOGIN); 
      } else {
        var err = jsonDecode(response.body);
        String errorMessage = "Periksa kembali data Anda.";
        if (err is Map) {
          if (err.containsKey('message') && err['message'] is String) {
            errorMessage = err['message'];
          } else if (err.containsKey('error') && err['error'] is String) {
            errorMessage = err['error'];
          }
        }
        Get.snackbar("Pendaftaran Ditolak", errorMessage, backgroundColor: Colors.orange.shade100);
      }
    } catch (e) {
      Get.snackbar("Error Koneksi", "Gagal menghubungi server Laravel.");
    } finally {
      isLoading.value = false;
    }
  }

  // ==========================================
  // 1. FUNGSI TRIGGER KIRIM OTP (FORGOT PASSWORD)
  // ==========================================
  Future<void> sendResetLink(String email) async {
    if (email.isEmpty) {
      Get.snackbar("Error", "Email wajib diisi!", backgroundColor: Colors.red.shade100);
      return;
    }

    isLoading.value = true;
    try {
      var response = await http.post(
        Uri.parse(ApiEndpoints.forgotPassword), 
        headers: {"Content-Type": "application/json", "Accept": "application/json"},
        body: jsonEncode({"email": email}), // Laravel butuh email untuk kirim OTP
      );

      print("RESPONS LARAVEL (FORGOT): ${response.body}"); // Untuk debug di terminal

      var data = jsonDecode(response.body);

      if (response.statusCode == 200) {
        Get.snackbar("Berhasil", data['message'] ?? "Kode OTP telah dikirim ke email Anda.", backgroundColor: Colors.green.shade100);
        
        // Pindah ke halaman ResetPasswordPage dengan membawa data email
        Get.toNamed(Routes.RESET_PASSWORD, arguments: email); 
      } else {
        Get.snackbar("Gagal", data['message'] ?? data['error'] ?? "Email tidak terdaftar.", backgroundColor: Colors.red.shade100);
      }
    } catch (e) {
      Get.snackbar("Error Koneksi", "Gagal menghubungi server.");
    } finally {
      isLoading.value = false;
    }
  }

  // ==========================================
  // 2. FUNGSI VERIFIKASI OTP & UPDATE PASSWORD
  // ==========================================
  Future<void> resetPassword({
    required String email, 
    required String otp, 
    required String password,
  }) async {
    isLoading.value = true;
    try {
      var response = await http.post(
        Uri.parse(ApiEndpoints.resetPassword), 
        headers: {"Content-Type": "application/json", "Accept": "application/json"},
        body: jsonEncode({
          "email": email,
          "otp": otp, // ⚠️ JIKA LARAVEL MINTA 'token', UBAH KEY INI MENJADI "token": otp
          "password": password,
          "password_confirmation": password, 
        }),
      );

      print("RESPONS LARAVEL (RESET): ${response.body}"); // Untuk debug di terminal

      var data = jsonDecode(response.body);

      if (response.statusCode == 200) {
        Get.snackbar("Berhasil", "Password berhasil diperbarui. Silakan login.", backgroundColor: Colors.green.shade100);
        Get.offAllNamed(Routes.LOGIN); 
      } else {
        Get.snackbar("Gagal", data['message'] ?? "Kode OTP salah atau kedaluwarsa.", backgroundColor: Colors.red.shade100);
      }
    } catch (e) {
      Get.snackbar("Error Koneksi", "Gagal mereset password.");
    } finally {
      isLoading.value = false;
    }
  }
}