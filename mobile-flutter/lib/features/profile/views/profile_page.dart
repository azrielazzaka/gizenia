import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '../../../routes/app_routes.dart';
import 'edit_profile_page.dart';

class ProfilePage extends StatelessWidget {
  const ProfilePage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF7F8F8),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 20),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              // 1. Header (Logo & Notifikasi)
              _buildHeader(),
              const SizedBox(height: 30),

              // 2. Foto Profil & Status
              _buildProfilePicture(),
              const SizedBox(height: 16),
              const Text("Alexandra Chen", style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold)),
              const SizedBox(height: 4),
              const Text("alexandra.chen@healthmail.com", style: TextStyle(fontSize: 14, color: Colors.black87)),
              const SizedBox(height: 20),

              // 3. Tombol Edit Profil
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: () => Get.to(() => EditProfilePage()),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.grey.shade200,
                    foregroundColor: Colors.black87,
                    elevation: 0,
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(24)),
                  ),
                  child: const Text("Edit Profile", style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                ),
              ),
              const SizedBox(height: 30),

              // 4. Body Data Section
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text("Body Data", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                    decoration: BoxDecoration(color: Colors.green.shade100, borderRadius: BorderRadius.circular(20)),
                    child: Text("UPDATED TODAY", style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: Colors.green.shade800, letterSpacing: 0.5)),
                  )
                ],
              ),
              const SizedBox(height: 16),

              // Kartu Berat & Tinggi
              Row(
                children: [
                  Expanded(child: _buildDataCard("BERAT\nBADAN", "58", "kg", Icons.monitor_weight_outlined)),
                  const SizedBox(width: 16),
                  Expanded(child: _buildDataCard("TINGGI\nBADAN", "168", "cm", Icons.height)),
                ],
              ),
              const SizedBox(height: 16),

              // Kartu Usia (Lebar Penuh dengan warna hijau pastel)
              _buildAgeCard(),
              const SizedBox(height: 30),

              // 5. Settings Section
              const Align(
                alignment: Alignment.centerLeft,
                child: Text("Settings", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
              ),
              const SizedBox(height: 16),
              _buildSettingsBox(),
              const SizedBox(height: 40),

              // 6. Tombol Logout
              GestureDetector(
                onTap: () => Get.offAllNamed(Routes.LOGIN),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(Icons.logout, color: Colors.red.shade700, size: 20),
                    const SizedBox(width: 8),
                    Text("Logout", style: TextStyle(color: Colors.red.shade700, fontWeight: FontWeight.bold, fontSize: 16)),
                  ],
                ),
              ),
              const SizedBox(height: 24),

              // 7. Versi Aplikasi
              Text("GIZENIA V2.4.0", style: TextStyle(fontSize: 10, letterSpacing: 2.0, color: Colors.grey.shade400, fontWeight: FontWeight.bold)),

              // Padding ekstra bawah agar tidak tertutup Bottom Navigation Bar
              const SizedBox(height: 100),
            ],
          ),
        ),
      ),
    );
  }

  // --- KOMPONEN WIDGET (HELPERS) ---

  Widget _buildHeader() {
    return Row(
      children: [
        CircleAvatar(
          radius: 18,
          backgroundColor: Colors.grey.shade800,
          child: const Icon(Icons.person, color: Colors.white, size: 20),
        ),
        const SizedBox(width: 12),
        Text("GIZENIA", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.green.shade800)),
        const Spacer(),
        Icon(Icons.notifications_rounded, color: Colors.grey.shade600),
      ],
    );
  }

  Widget _buildProfilePicture() {
    return Stack(
      alignment: Alignment.bottomRight,
      children: [
        Container(
          width: 110,
          height: 110,
          decoration: BoxDecoration(
            color: const Color(0xFF2C2C2C), // Warna gelap seperti di gambar
            borderRadius: BorderRadius.circular(40), // Membuat efek Squircle (kotak melengkung)
            border: Border.all(color: Colors.white, width: 4),
            boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 10)],
          ),
          child: const Icon(Icons.person, size: 70, color: Colors.white), // Ganti dengan Image.network jika punya URL foto
        ),
        Container(
          padding: const EdgeInsets.all(6),
          decoration: BoxDecoration(
            color: Colors.green.shade800,
            shape: BoxShape.circle,
            border: Border.all(color: Colors.white, width: 3),
          ),
          child: const Icon(Icons.edit, color: Colors.white, size: 16),
        ),
      ],
    );
  }

  Widget _buildDataCard(String title, String value, String unit, IconData icon) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.02), blurRadius: 10)],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Icon(icon, color: Colors.green.shade800, size: 18),
              const SizedBox(width: 8),
              Text(title, style: const TextStyle(fontSize: 10, fontWeight: FontWeight.bold, letterSpacing: 0.5)),
            ],
          ),
          const SizedBox(height: 16),
          Row(
            crossAxisAlignment: CrossAxisAlignment.baseline,
            textBaseline: TextBaseline.alphabetic,
            children: [
              Text(value, style: const TextStyle(fontSize: 32, fontWeight: FontWeight.bold)),
              const SizedBox(width: 4),
              Text(unit, style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold)),
            ],
          )
        ],
      ),
    );
  }

  Widget _buildAgeCard() {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: const Color(0xFFF0F9ED), // Hijau sangat muda
        borderRadius: BorderRadius.circular(24),
        border: Border.all(color: Colors.white, width: 2),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.02), blurRadius: 10)],
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  Icon(Icons.cake, color: Colors.green.shade300, size: 18),
                  const SizedBox(width: 8),
                  Text("USIA", style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, letterSpacing: 0.5, color: Colors.green.shade300)),
                ],
              ),
              const SizedBox(height: 8),
              Row(
                crossAxisAlignment: CrossAxisAlignment.baseline,
                textBaseline: TextBaseline.alphabetic,
                children: [
                  Text("26", style: TextStyle(fontSize: 36, fontWeight: FontWeight.bold, color: Colors.green.shade200)),
                  const SizedBox(width: 4),
                  Text("Tahun", style: TextStyle(fontSize: 16, fontWeight: FontWeight.w500, color: Colors.green.shade200)),
                ],
              )
            ],
          ),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
            decoration: BoxDecoration(color: Colors.green.shade50.withValues(alpha: 0.5), borderRadius: BorderRadius.circular(20)),
            child: Text("Goal: Maintain", style: TextStyle(color: Colors.green.shade300, fontWeight: FontWeight.bold, fontSize: 12)),
          )
        ],
      ),
    );
  }

  Widget _buildSettingsBox() {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.02), blurRadius: 10)],
      ),
      child: Column(
        children: [
          _buildSettingsTile(Icons.settings, "AI Preferences"),
          Divider(height: 1, color: Colors.grey.shade100, indent: 20, endIndent: 20),
          _buildSettingsTile(Icons.security, "Privacy & Security"),
        ],
      ),
    );
  }

  Widget _buildSettingsTile(IconData icon, String title) {
    return ListTile(
      contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 4),
      leading: Icon(icon, color: Colors.black87, size: 22),
      title: Text(title, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 15)),
      trailing: const Icon(Icons.chevron_right, color: Colors.black87),
      onTap: () {},
    );
  }
}