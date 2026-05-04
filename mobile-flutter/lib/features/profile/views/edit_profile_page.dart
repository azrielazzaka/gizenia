import 'package:flutter/material.dart';
import 'package:get/get.dart';

class EditProfilePage extends StatelessWidget {
  EditProfilePage({super.key});

  // Controller dengan data statis bawaan (sesuai data profil)
  final nameController = TextEditingController(text: "Alexandra Chen");
  final emailController = TextEditingController(text: "alexandra.chen@healthmail.com");
  final ageController = TextEditingController(text: "26");
  final weightController = TextEditingController(text: "58");
  final heightController = TextEditingController(text: "168");

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8FAF8), // Latar belakang senada dengan GIZENIA
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 30),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // --- HEADER CUSTOM ---
              Row(
                children: [
                  GestureDetector(
                    onTap: () => Get.back(),
                    child: Container(
                      padding: const EdgeInsets.all(10),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        shape: BoxShape.circle,
                        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 10)],
                      ),
                      child: Icon(Icons.arrow_back, color: Colors.green.shade800, size: 20),
                    ),
                  ),
                  const SizedBox(width: 16),
                  Text("Edit Profil", style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.green.shade900)),
                ],
              ),
              const SizedBox(height: 40),

              // --- FOTO PROFIL (STATIS / TIDAK BISA DIUBAH) ---
              Center(
                child: Column(
                  children: [
                    Container(
                      width: 100,
                      height: 100,
                      decoration: BoxDecoration(
                        color: const Color(0xFF2C2C2C), 
                        borderRadius: BorderRadius.circular(35), // Bentuk Squircle
                        border: Border.all(color: Colors.white, width: 4),
                        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 10)],
                      ),
                      child: const Icon(Icons.person, size: 60, color: Colors.white),
                    ),
                    const SizedBox(height: 12),
                    const Text(
                      "Foto profil dikelola oleh sistem", 
                      style: TextStyle(fontSize: 12, color: Colors.grey, fontStyle: FontStyle.italic)
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 40),

              // --- FORM INPUT ---
              _buildInputField("Nama Lengkap", Icons.person, nameController),
              _buildInputField("Alamat Email", Icons.email, emailController, type: TextInputType.emailAddress),
              
              Row(
                children: [
                  Expanded(child: _buildInputField("Usia (Thn)", Icons.cake, ageController, type: TextInputType.number)),
                  const SizedBox(width: 16),
                  Expanded(child: _buildInputField("Berat (Kg)", Icons.monitor_weight, weightController, type: TextInputType.number)),
                  const SizedBox(width: 16),
                  Expanded(child: _buildInputField("Tinggi (Cm)", Icons.height, heightController, type: TextInputType.number)),
                ],
              ),
              const SizedBox(height: 40),

              // --- TOMBOL SIMPAN ---
              ElevatedButton(
                onPressed: () {
                  // Simulasi Notifikasi Sukses lalu kembali ke halaman Profile
                  Get.snackbar(
                    "Berhasil", 
                    "Perubahan profil berhasil disimpan!", 
                    backgroundColor: Colors.green.shade100,
                    colorText: Colors.green.shade900,
                    snackPosition: SnackPosition.TOP,
                  );
                  Future.delayed(const Duration(seconds: 1), () => Get.back());
                },
                style: ElevatedButton.styleFrom(
                  minimumSize: const Size(double.infinity, 55),
                  backgroundColor: const Color(0xFF22762A), // Warna hijau gelap GIZENIA
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                  elevation: 2,
                ),
                child: const Text("Simpan Perubahan", style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
              ),
            ],
          ),
        ),
      ),
    );
  }

  // --- WIDGET HELPER FORM ---
  Widget _buildInputField(String label, IconData icon, TextEditingController controller, {TextInputType type = TextInputType.text}) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w600, color: Colors.black87)),
          const SizedBox(height: 8),
          TextField(
            controller: controller,
            keyboardType: type,
            style: const TextStyle(fontWeight: FontWeight.w500),
            decoration: InputDecoration(
              prefixIcon: Icon(icon, color: Colors.green.shade800, size: 20),
              filled: true,
              fillColor: Colors.white, // Input field putih
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(16), 
                borderSide: BorderSide.none
              ),
              contentPadding: const EdgeInsets.symmetric(vertical: 16),
            ),
          ),
        ],
      ),
    );
  }
}