import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '../../../routes/app_routes.dart';
import '../controllers/auth_controller.dart'; 

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
  
  final selectedKelas = '1'.obs; // Default kelas 1

  final authController = Get.put(AuthController());

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
                    Icon(Icons.arrow_back, color: Colors.green.shade800, size: 18),
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
                style: TextStyle(fontSize: 32, fontWeight: FontWeight.bold, color: Color(0xFF1A1A1A)),
              ),
              const SizedBox(height: 8),
              Text(
                "Beritahu kami sedikit tentang dirimu untuk memulai.",
                style: TextStyle(fontSize: 14, color: Colors.grey.shade700),
              ),
              const SizedBox(height: 32),

              // 4. Form Inputs
              _buildInputField("Nama Lengkap", "Masukkan nama", Icons.person, nameController),
              _buildInputField("Alamat Email", "nama@email.com", Icons.email, emailController, type: TextInputType.emailAddress),
              
              // Input Kata Sandi dengan fitur hide/show
              _buildPasswordField("Kata Sandi", "••••••••", passwordController, isObscure),
              
              // 5. Grid untuk Umur, Kelas, Berat, dan Tinggi Badan
              Row(
                children: [
                  Expanded(child: _buildInputField("Usia (Thn)", "Mis: 20", Icons.cake, ageController, type: TextInputType.number)),
                  const SizedBox(width: 16),
                  Expanded(child: _buildDropdownKelas()), // Dropdown Kelas 1-6
                ],
              ),
              Row(
                children: [
                  Expanded(child: _buildInputField("Berat (Kg)", "Mis: 55", Icons.monitor_weight, weightController, type: TextInputType.number)),
                  const SizedBox(width: 16),
                  Expanded(child: _buildInputField("Tinggi (Cm)", "Mis: 165", Icons.height, heightController, type: TextInputType.number)),
                ],
              ),
              const SizedBox(height: 16),

              Obx(() => ElevatedButton(
  onPressed: authController.isLoading.value
      ? null
      : () {
                  // Memanggil fungsi register dari AuthController
                  authController.register({
                    "name": nameController.text,
                    "email": emailController.text,
                    "password": passwordController.text,
                    "age": int.tryParse(ageController.text) ?? 0,
                    "weight": double.tryParse(weightController.text) ?? 0.0,
                    "height": double.tryParse(heightController.text) ?? 0.0,
                    "class_room": "Kelas ${selectedKelas.value}",
                  });
                },
                style: ElevatedButton.styleFrom(
                  minimumSize: const Size(double.infinity, 55),
                  backgroundColor: const Color(0xFF22762A), // Warna hijau gelap
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                  elevation: 2,
                ),
                child: authController.isLoading.value
                    ? const SizedBox(height: 20, width: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                    : const Text("Buat Akun", style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
              )),
              const SizedBox(height: 32),

              // 8. Footer Login Link
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text("Sudah menjadi bagian dari kami? ", style: TextStyle(color: Colors.grey.shade700, fontSize: 13)),
                  GestureDetector(
                    onTap: () => Get.offAllNamed(Routes.LOGIN),
                    child: Text("Masuk di sini", style: TextStyle(color: Colors.green.shade800, fontWeight: FontWeight.bold, fontSize: 13)),
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

  // --- WIDGET HELPERS --- //

  Widget _buildInputField(String label, String hint, IconData icon, TextEditingController controller, {TextInputType type = TextInputType.text}) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w600, color: Colors.black87)),
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
              border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide.none),
              contentPadding: const EdgeInsets.symmetric(vertical: 16),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPasswordField(String label, String hint, TextEditingController controller, RxBool isObscure) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w600, color: Colors.black87)),
          const SizedBox(height: 8),
          Obx(() => TextField(
            controller: controller,
            obscureText: isObscure.value,
            decoration: InputDecoration(
              hintText: hint,
              hintStyle: TextStyle(color: Colors.grey.shade400, fontSize: 14),
              prefixIcon: Icon(Icons.lock, color: Colors.grey.shade500, size: 20),
              suffixIcon: IconButton(
                icon: Icon(isObscure.value ? Icons.visibility_off : Icons.visibility, color: Colors.grey.shade500, size: 20),
                onPressed: () => isObscure.value = !isObscure.value,
              ),
              filled: true,
              fillColor: const Color(0xFFF4F5F4),
              border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide.none),
              contentPadding: const EdgeInsets.symmetric(vertical: 16),
            ),
          )),
        ],
      ),
    );
  }

  Widget _buildDropdownKelas() {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text("Kelas", style: TextStyle(fontSize: 13, fontWeight: FontWeight.w600, color: Colors.black87)),
          const SizedBox(height: 8),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 12),
            decoration: BoxDecoration(
              color: const Color(0xFFF4F5F4),
              borderRadius: BorderRadius.circular(12),
            ),
            child: Obx(() => DropdownButtonHideUnderline(
              child: DropdownButton<String>(
                value: selectedKelas.value,
                isExpanded: true,
                icon: Icon(Icons.keyboard_arrow_down, color: Colors.grey.shade500),
                items: ["1", "2", "3", "4", "5", "6"].map((String value) {
                  return DropdownMenuItem<String>(
                    value: value,
                    child: Row(
                      children: [
                        Icon(Icons.school, color: Colors.grey.shade500, size: 20),
                        const SizedBox(width: 12),
                        Text("Kelas $value", style: const TextStyle(fontSize: 14)),
                      ],
                    ),
                  );
                }).toList(),
                onChanged: (newValue) {
                  if (newValue != null) selectedKelas.value = newValue;
                },
              ),
            )),
          ),
        ],
      ),
    );
  }
}
