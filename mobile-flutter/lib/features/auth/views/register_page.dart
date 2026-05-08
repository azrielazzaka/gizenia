import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '../../../routes/app_routes.dart';

class RegisterPage extends StatelessWidget {
  RegisterPage({super.key});

  // Controllers untuk input teks
  final nameController = TextEditingController();
  final emailController = TextEditingController();
  final passwordController = TextEditingController();
  final confirmPasswordController = TextEditingController();
  final ageController = TextEditingController();
  final weightController = TextEditingController();
  final heightController = TextEditingController();

  // State lokal untuk UI statis menggunakan Rx (GetX)
  final isObscure = true.obs;
  final isConfirmObscure = true.obs;
  final isChecked = false.obs;
  final selectedKelas = '1'.obs; // Default kelas 1

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8FAF8), // Latar belakang off-white
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 30),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // 1. Logo GIZENIA
              Row(
                children: [
                  Icon(Icons.eco, color: Colors.green.shade800, size: 28),
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
              const SizedBox(height: 30),

              // 2. Tombol Kembali
              GestureDetector(
                onTap: () => Get.back(),
                child: Row(
                  children: [
                    Icon(
                      Icons.arrow_back,
                      color: Colors.green.shade800,
                      size: 18,
                    ),
                    const SizedBox(width: 8),
                    Text(
                      "KEMBALI KE LOGIN",
                      style: TextStyle(
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                        letterSpacing: 1.0,
                        color: Colors.green.shade800,
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 24),

              // 3. Judul Halaman
              const Text(
                "Buat Akun",
                style: TextStyle(
                  fontSize: 32,
                  fontWeight: FontWeight.bold,
                  color: Color(0xFF1A1A1A),
                ),
              ),
              const SizedBox(height: 8),
              Text(
                "Beritahu kami sedikit tentang dirimu untuk memulai.",
                style: TextStyle(fontSize: 14, color: Colors.grey.shade700),
              ),
              const SizedBox(height: 32),

              // 4. Form Inputs
              _buildInputField(
                "Nama Lengkap",
                "Masukkan nama",
                Icons.person,
                nameController,
              ),
              _buildInputField(
                "Alamat Email",
                "nama@email.com",
                Icons.email,
                emailController,
                type: TextInputType.emailAddress,
              ),

              // Input Kata Sandi dengan fitur hide/show
              _buildPasswordField(
                "Kata Sandi",
                "••••••••",
                passwordController,
                isObscure,
              ),
              _buildPasswordField(
                "Konfirmasi Kata Sandi",
                "••••••••",
                confirmPasswordController,
                isConfirmObscure,
              ),
              // 5. Grid untuk Umur, Kelas, Berat, dan Tinggi Badan
              Row(
                children: [
                  Expanded(
                    child: _buildInputField(
                      "Usia (Thn)",
                      "Mis: 20",
                      Icons.cake,
                      ageController,
                      type: TextInputType.number,
                    ),
                  ),
                  const SizedBox(width: 16),
                  Expanded(child: _buildDropdownKelas()), // Dropdown Kelas 1-6
                ],
              ),
              Row(
                children: [
                  Expanded(
                    child: _buildInputField(
                      "Berat (Kg)",
                      "Mis: 55",
                      Icons.monitor_weight,
                      weightController,
                      type: TextInputType.number,
                    ),
                  ),
                  const SizedBox(width: 16),
                  Expanded(
                    child: _buildInputField(
                      "Tinggi (Cm)",
                      "Mis: 165",
                      Icons.height,
                      heightController,
                      type: TextInputType.number,
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 16),

              // 6. Checkbox Syarat & Ketentuan
              Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Obx(
                    () => SizedBox(
                      width: 24,
                      height: 24,
                      child: Checkbox(
                        value: isChecked.value,
                        onChanged: (val) => isChecked.value = val ?? false,
                        activeColor: Colors.green.shade800,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(4),
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: RichText(
                      text: TextSpan(
                        style: TextStyle(
                          fontSize: 13,
                          color: Colors.grey.shade800,
                          height: 1.5,
                        ),
                        children: [
                          const TextSpan(text: "Saya setuju dengan "),
                          TextSpan(
                            text: "Syarat & Ketentuan",
                            style: TextStyle(
                              color: Colors.green.shade800,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          const TextSpan(text: " dan "),
                          TextSpan(
                            text: "Kebijakan Privasi",
                            style: TextStyle(
                              color: Colors.green.shade800,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          const TextSpan(text: " dari GIZENIA."),
                        ],
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 32),

              // 7. Tombol Daftar
              ElevatedButton(
                onPressed: () async {
                  await register();
                },
                style: ElevatedButton.styleFrom(
                  minimumSize: const Size(double.infinity, 55),
                  backgroundColor: const Color(0xFF22762A), // Warna hijau gelap
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(20),
                  ),
                  elevation: 2,
                ),
                child: const Text(
                  "Buat Akun",
                  style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                ),
              ),
              const SizedBox(height: 32),

              // 8. Footer Login Link
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text(
                    "Sudah menjadi bagian dari kami? ",
                    style: TextStyle(color: Colors.grey.shade700, fontSize: 13),
                  ),
                  GestureDetector(
                    onTap: () => Get.offAllNamed(Routes.LOGIN),
                    child: Text(
                      "Masuk di sini",
                      style: TextStyle(
                        color: Colors.green.shade800,
                        fontWeight: FontWeight.bold,
                        fontSize: 13,
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 20),
            ],
          ),
        ),
      ),
    );
  }

  Future<void> register() async {
    if (passwordController.text != confirmPasswordController.text) {
      Get.snackbar(
        "Error",
        "Password tidak sama",
        backgroundColor: Colors.red,
        colorText: Colors.white,
      );
      return;
    }

    try {
      final url = Uri.parse('http://192.168.1.14:8000/api/auth/register');

      final response = await http.post(
        url,
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        body: jsonEncode({
          'name': nameController.text.trim(),
          'email': emailController.text.trim(),
          'password': passwordController.text.trim(),
          'password_confirmation': confirmPasswordController.text.trim(),
          'age': ageController.text.trim(),
          'class': selectedKelas.value,
          'weight': weightController.text.trim(),
          'height': heightController.text.trim(),
        }),
      );

      final data = jsonDecode(response.body);

      if (response.statusCode == 200 || response.statusCode == 201) {
        Get.snackbar(
          "Berhasil",
          "Akun berhasil dibuat",
          backgroundColor: Colors.green,
          colorText: Colors.white,
        );

        Get.offAllNamed(Routes.LOGIN);
      } else {
        Get.snackbar(
          "Gagal",
          data['message'] ?? 'Register gagal',
          backgroundColor: Colors.red,
          colorText: Colors.white,
        );
      }
    } catch (e) {
      Get.snackbar(
        "Error",
        e.toString(),
        backgroundColor: Colors.orange,
        colorText: Colors.white,
      );
    }
  }

  // --- WIDGET HELPERS --- //

  // Helper untuk input teks standar
  Widget _buildInputField(
    String label,
    String hint,
    IconData icon,
    TextEditingController controller, {
    TextInputType type = TextInputType.text,
  }) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            label,
            style: const TextStyle(
              fontSize: 13,
              fontWeight: FontWeight.w600,
              color: Colors.black87,
            ),
          ),
          const SizedBox(height: 8),
          TextField(
            controller: controller,
            keyboardType: type,
            decoration: InputDecoration(
              hintText: hint,
              hintStyle: TextStyle(color: Colors.grey.shade400, fontSize: 14),
              prefixIcon: Icon(icon, color: Colors.grey.shade500, size: 20),
              filled: true,
              fillColor: const Color(0xFFF4F5F4),
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12),
                borderSide: BorderSide.none,
              ),
              contentPadding: const EdgeInsets.symmetric(vertical: 16),
            ),
          ),
        ],
      ),
    );
  }

  // Helper untuk input password (dengan mata hide/show)
  Widget _buildPasswordField(
    String label,
    String hint,
    TextEditingController controller,
    RxBool isObscure,
  ) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            label,
            style: const TextStyle(
              fontSize: 13,
              fontWeight: FontWeight.w600,
              color: Colors.black87,
            ),
          ),
          const SizedBox(height: 8),
          Obx(
            () => TextField(
              controller: controller,
              obscureText: isObscure.value,
              decoration: InputDecoration(
                hintText: hint,
                hintStyle: TextStyle(color: Colors.grey.shade400, fontSize: 14),
                prefixIcon: Icon(
                  Icons.lock,
                  color: Colors.grey.shade500,
                  size: 20,
                ),
                suffixIcon: IconButton(
                  icon: Icon(
                    isObscure.value ? Icons.visibility_off : Icons.visibility,
                    color: Colors.grey.shade500,
                    size: 20,
                  ),
                  onPressed: () => isObscure.value = !isObscure.value,
                ),
                filled: true,
                fillColor: const Color(0xFFF4F5F4),
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(12),
                  borderSide: BorderSide.none,
                ),
                contentPadding: const EdgeInsets.symmetric(vertical: 16),
              ),
            ),
          ),
        ],
      ),
    );
  }

  // Helper khusus untuk Dropdown Kelas 1-6
  Widget _buildDropdownKelas() {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            "Kelas",
            style: TextStyle(
              fontSize: 13,
              fontWeight: FontWeight.w600,
              color: Colors.black87,
            ),
          ),
          const SizedBox(height: 8),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 12),
            decoration: BoxDecoration(
              color: const Color(0xFFF4F5F4),
              borderRadius: BorderRadius.circular(12),
            ),
            child: Obx(
              () => DropdownButtonHideUnderline(
                child: DropdownButton<String>(
                  value: selectedKelas.value,
                  isExpanded: true,
                  icon: Icon(
                    Icons.keyboard_arrow_down,
                    color: Colors.grey.shade500,
                  ),
                  items: ["1", "2", "3", "4", "5", "6"].map((String value) {
                    return DropdownMenuItem<String>(
                      value: value,
                      child: Row(
                        children: [
                          Icon(
                            Icons.school,
                            color: Colors.grey.shade500,
                            size: 20,
                          ),
                          const SizedBox(width: 12),
                          Text(
                            "Kelas $value",
                            style: const TextStyle(fontSize: 14),
                          ),
                        ],
                      ),
                    );
                  }).toList(),
                  onChanged: (newValue) {
                    if (newValue != null) selectedKelas.value = newValue;
                  },
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
