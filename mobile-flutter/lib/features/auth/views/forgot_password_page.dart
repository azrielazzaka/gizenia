import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '../../../routes/app_routes.dart';
import '../controllers/auth_controller.dart';

class ForgotPasswordPage extends StatefulWidget {
  const ForgotPasswordPage({super.key});

  @override
  State<ForgotPasswordPage> createState() => _ForgotPasswordPageState();
}

class _ForgotPasswordPageState extends State<ForgotPasswordPage> {
  final emailController = TextEditingController();
  // Ganti Get.find menjadi Get.put
final authController = Get.put(AuthController());

 void handleSendLink() async {
    String email = emailController.text.trim();

    if (email.isNotEmpty) {
      // 1. Panggil API kirim OTP
      await authController.sendResetLink(email);
      
      // 2. Jika berhasil (isLoading jadi false dan tidak ada error)
      // Kita asumsikan sukses jika fungsi selesai tanpa throw error
      if (!authController.isLoading.value) {
        Get.toNamed(Routes.RESET_PASSWORD, arguments: email); // Kirim email sebagai argumen
      }
    } else {
      Get.snackbar("Peringatan", "Masukkan email terlebih dahulu",
          backgroundColor: Colors.orange.shade100);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF4F6F4),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.symmetric(horizontal: 24),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const SizedBox(height: 20),

              // LOGO GIZENIA
              Row(
                children: [
                  Icon(Icons.food_bank, color: Colors.green.shade800, size: 28),
                  const SizedBox(width: 8),
                  Text(
                    "GIZENIA",
                    style: TextStyle(
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                      color: Colors.green.shade900,
                    ),
                  ),
                ],
              ),

              const SizedBox(height: 24),

              // TOMBOL KEMBALI
              GestureDetector(
                onTap: () => Navigator.pop(context),
                child: Row(
                  children: [
                    Icon(Icons.arrow_back, size: 16, color: Colors.green.shade800),
                    const SizedBox(width: 4),
                    Text(
                      "KEMBALI KE LOGIN",
                      style: TextStyle(
                        color: Colors.green.shade800,
                        fontWeight: FontWeight.w600,
                        fontSize: 13,
                        letterSpacing: 1,
                      ),
                    ),
                  ],
                ),
              ),

              const SizedBox(height: 24),

              // JUDUL & DESKRIPSI
              const Text(
                "Lupa Password?",
                style: TextStyle(fontSize: 32, fontWeight: FontWeight.bold, color: Colors.black87),
              ),
              const SizedBox(height: 6),
              Text(
                "Masukkan email untuk menerima link reset password",
                style: TextStyle(color: Colors.grey.shade600, fontSize: 14),
              ),

              const SizedBox(height: 40),

              // INPUT EMAIL
              const Text(
                "Alamat Email",
                style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: Colors.black87),
              ),
              const SizedBox(height: 8),
              TextField(
                controller: emailController,
                keyboardType: TextInputType.emailAddress,
                decoration: InputDecoration(
                  hintText: "admin@gizenia.com",
                  prefixIcon: const Icon(Icons.email_outlined, color: Colors.grey),
                  filled: true,
                  fillColor: Colors.white,
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: BorderSide.none,
                  ),
                ),
              ),

              const SizedBox(height: 40),

              // TOMBOL KIRIM LINK
              Obx(() => ElevatedButton(
                onPressed: authController.isLoading.value ? null : handleSendLink,
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.green.shade800,
                  minimumSize: const Size(double.infinity, 55),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                  elevation: 2,
                ),
                child: authController.isLoading.value
                    ? const SizedBox(height: 20, width: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                    : const Text(
                        "Kirim Link Reset",
                        style: TextStyle(fontSize: 16, color: Colors.white, fontWeight: FontWeight.bold),
                      ),
              )),
            ],
          ),
        ),
      ),
    );
  }
}