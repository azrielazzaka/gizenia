import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '../controllers/auth_controller.dart';

class ResetPasswordPage extends StatefulWidget {
  const ResetPasswordPage({super.key});

  @override
  State<ResetPasswordPage> createState() => _ResetPasswordPageState();
}

class _ResetPasswordPageState extends State<ResetPasswordPage> {
  final authController = Get.find<AuthController>();
  
  // Ambil email dari halaman sebelumnya
  final emailController = TextEditingController(text: Get.arguments ?? "");
  final otpController = TextEditingController();
  final passwordController = TextEditingController();
  final confirmPasswordController = TextEditingController();

  bool isPasswordVisible = false;

  void handleReset() async {
    if (otpController.text.length < 4) {
      Get.snackbar("Error", "Masukkan kode OTP yang valid");
      return;
    }
    if (passwordController.text != confirmPasswordController.text) {
      Get.snackbar("Error", "Konfirmasi password tidak cocok");
      return;
    }

    // Panggil fungsi reset di controller
    // Kamu perlu menambahkan fungsi 'resetPassword' ini di AuthController nanti
    await authController.resetPassword(
      email: emailController.text,
      otp: otpController.text,
      password: passwordController.text,
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF4F6F4),
      appBar: AppBar(
        title: const Text("Reset Password", style: TextStyle(color: Colors.black, fontSize: 18)),
        backgroundColor: Colors.transparent,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back, color: Colors.black),
          onPressed: () => Get.back(),
        ),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              "Buat Password Baru",
              style: TextStyle(fontSize: 26, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            Text(
              "Masukkan kode OTP yang dikirim ke email dan tentukan password baru Anda.",
              style: TextStyle(color: Colors.grey.shade600),
            ),
            const SizedBox(height: 32),

            // INPUT EMAIL (Read Only)
            customLabel("Email"),
            TextField(
              controller: emailController,
              readOnly: true,
              decoration: customInputDecoration("Email", Icons.email, isGray: true),
            ),
            
            const SizedBox(height: 20),

            // INPUT OTP
            customLabel("Kode OTP"),
            TextField(
              controller: otpController,
              keyboardType: TextInputType.number,
              decoration: customInputDecoration("Masukkan 6 digit OTP", Icons.lock_clock),
            ),

            const SizedBox(height: 20),

            // PASSWORD BARU
            customLabel("Password Baru"),
            TextField(
              controller: passwordController,
              obscureText: !isPasswordVisible,
              decoration: customInputDecoration("Minimal 8 karakter", Icons.lock_outline, isPassword: true),
            ),

            const SizedBox(height: 20),

            // KONFIRMASI PASSWORD
            customLabel("Ulangi Password"),
            TextField(
              controller: confirmPasswordController,
              obscureText: !isPasswordVisible,
              decoration: customInputDecoration("Ketik ulang password", Icons.lock_reset, isPassword: true),
            ),

            const SizedBox(height: 40),

            // TOMBOL RESET
            Obx(() => ElevatedButton(
              onPressed: authController.isLoading.value ? null : handleReset,
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.green.shade800,
                minimumSize: const Size(double.infinity, 55),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
              ),
              child: authController.isLoading.value
                  ? const CircularProgressIndicator(color: Colors.white)
                  : const Text("Update Password", style: TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold)),
            )),
          ],
        ),
      ),
    );
  }

  Widget customLabel(String text) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8, left: 4),
      child: Text(text, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
    );
  }

  InputDecoration customInputDecoration(String hint, IconData icon, {bool isPassword = false, bool isGray = false}) {
    return InputDecoration(
      hintText: hint,
      prefixIcon: Icon(icon, color: Colors.grey),
      filled: true,
      fillColor: isGray ? Colors.grey.shade200 : Colors.white,
      suffixIcon: isPassword 
        ? IconButton(
            icon: Icon(isPasswordVisible ? Icons.visibility : Icons.visibility_off),
            onPressed: () => setState(() => isPasswordVisible = !isPasswordVisible),
          )
        : null,
      border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide.none),
    );
  }
}