import 'package:flutter/material.dart';
import 'package:percent_indicator/percent_indicator.dart';

class CalorieCheckerPage extends StatelessWidget {
  const CalorieCheckerPage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF7F8F8), // Background abu-abu terang
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 20),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // 1. Header (Logo & Notifikasi)
              _buildHeader(),
              const SizedBox(height: 30),

              // 2. Judul Halaman
              const Text("Cek Nutrisi Makanan", style: TextStyle(fontSize: 26, fontWeight: FontWeight.bold, height: 1.2)),
              const SizedBox(height: 8),
              Text("Pantau asupan harianmu dengan presisi AI.", style: TextStyle(fontSize: 14, color: Colors.grey.shade700)),
              const SizedBox(height: 30),

              // 3. Form Input Card (Putih)
              _buildFormCard(),
              const SizedBox(height: 40),

              // 4. Header Hasil Analisis
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text("Hasil Analisis", style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                    decoration: BoxDecoration(color: Colors.green.shade200, borderRadius: BorderRadius.circular(20)),
                    child: Row(
                      children: [
                        const Icon(Icons.check_circle, size: 14, color: Colors.black87),
                        const SizedBox(width: 4),
                        const Text("SEIMBANG", style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: Colors.black87)),
                      ],
                    ),
                  )
                ],
              ),
              const SizedBox(height: 20),

              // 5. Total Energi Card
              _buildTotalEnergyCard(),
              const SizedBox(height: 16),

              // 6. Grid Macro (Protein & Karbohidrat)
              Row(
                children: [
                  Expanded(child: _buildMacroCard("PROTEIN", "2.7g", 0.15, Icons.fitness_center, Colors.green.shade800)),
                  const SizedBox(width: 16),
                  Expanded(child: _buildMacroCard("KARBOHIDRAT", "28.2g", 0.7, Icons.grass, Colors.green.shade800)),
                ],
              ),
              const SizedBox(height: 16),

              // 7. Lemak Card (Pink)
              _buildFatCard(),
              const SizedBox(height: 16),

              // 8. AI Insight Card (Hijau Muda)
              _buildInsightCard(),

              // Padding bawah agar tidak tertutup Bottom Navigation / FAB
              const SizedBox(height: 120),
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
          backgroundColor: Colors.grey.shade300,
          child: const Icon(Icons.person, color: Colors.black54),
        ),
        const SizedBox(width: 12),
        Text("GIZENIA", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.green.shade800)),
        const Spacer(),
        Icon(Icons.notifications_rounded, color: Colors.grey.shade600),
      ],
    );
  }

  Widget _buildFormCard() {
    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(30),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 20, offset: const Offset(0, 10))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text("PILIH MAKANAN", style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, letterSpacing: 1.0, color: Colors.black54)),
          const SizedBox(height: 8),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
            decoration: BoxDecoration(color: Colors.grey.shade100, borderRadius: BorderRadius.circular(12)),
            child: DropdownButtonHideUnderline(
              child: DropdownButton<String>(
                value: "Nasi Putih",
                isExpanded: true,
                icon: const Icon(Icons.keyboard_arrow_down, color: Colors.black54),
                items: ["Nasi Putih", "Dada Ayam", "Telur Rebus"].map((String value) {
                  return DropdownMenuItem<String>(
                    value: value,
                    child: Text(value, style: const TextStyle(fontWeight: FontWeight.w500)),
                  );
                }).toList(),
                onChanged: (_) {},
              ),
            ),
          ),
          const SizedBox(height: 20),
          
          const Text("BERAT (GRAMS)", style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, letterSpacing: 1.0, color: Colors.black54)),
          const SizedBox(height: 8),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            decoration: BoxDecoration(color: Colors.grey.shade100, borderRadius: BorderRadius.circular(12)),
            child: const TextField(
              decoration: InputDecoration(
                hintText: "100",
                hintStyle: TextStyle(fontWeight: FontWeight.w500, color: Colors.black87),
                border: InputBorder.none,
                suffixText: "gr",
                suffixStyle: TextStyle(color: Colors.black54, fontWeight: FontWeight.w500),
              ),
              keyboardType: TextInputType.number,
            ),
          ),
          const SizedBox(height: 30),

          ElevatedButton(
            onPressed: () {},
            style: ElevatedButton.styleFrom(
              minimumSize: const Size(double.infinity, 55),
              backgroundColor: Colors.green.shade800,
              foregroundColor: Colors.white,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
              elevation: 0,
            ),
            child: const Text("Cek Nutrisi", style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
          )
        ],
      ),
    );
  }

  Widget _buildTotalEnergyCard() {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(30)),
      child: Row(
        children: [
          CircleAvatar(
            radius: 30,
            backgroundColor: Colors.green.shade200,
            child: Icon(Icons.local_fire_department, color: Colors.green.shade900, size: 30),
          ),
          const SizedBox(width: 16),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text("TOTAL ENERGI", style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, letterSpacing: 1.0, color: Colors.black54)),
              Row(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  const Text("130", style: TextStyle(fontSize: 32, fontWeight: FontWeight.bold, height: 1.1)),
                  const SizedBox(width: 4),
                  Padding(
                    padding: const EdgeInsets.only(bottom: 4),
                    child: Text("kkal", style: TextStyle(fontSize: 16, color: Colors.grey.shade600, fontWeight: FontWeight.w500)),
                  ),
                ],
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildMacroCard(String title, String value, double percent, IconData icon, Color color) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(24)),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, color: color, size: 24),
          const SizedBox(height: 20),
          Text(title, style: const TextStyle(fontSize: 10, fontWeight: FontWeight.bold, letterSpacing: 1.0, color: Colors.black54)),
          const SizedBox(height: 4),
          Text(value, style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
          const SizedBox(height: 12),
          LinearPercentIndicator(
            lineHeight: 6.0,
            percent: percent,
            progressColor: color,
            backgroundColor: Colors.grey.shade200,
            barRadius: const Radius.circular(10),
            padding: EdgeInsets.zero,
          ),
        ],
      ),
    );
  }

  Widget _buildFatCard() {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: const Color(0xFFFFF0F3), // Latar belakang pink pucat
        borderRadius: BorderRadius.circular(24),
        border: Border.all(color: Colors.pink.shade50),
      ),
      child: Row(
        children: [
          const Icon(Icons.water_drop, color: Color(0xFF6B1D39), size: 24), // Ikon tetesan ungu/merah marun
          const SizedBox(width: 16),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text("LEMAK TOTAL", style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, letterSpacing: 1.0, color: Color(0xFF6B1D39))),
              const SizedBox(height: 2),
              const Text("0.3g", style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.black87)),
            ],
          ),
          const Spacer(),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
            decoration: BoxDecoration(color: const Color(0xFFFFD6E0), borderRadius: BorderRadius.circular(20)),
            child: const Text("RENDAH LEMAK", style: TextStyle(fontSize: 9, fontWeight: FontWeight.bold, color: Color(0xFF6B1D39))),
          )
        ],
      ),
    );
  }

  Widget _buildInsightCard() {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(color: const Color(0xFFEAF5E5), borderRadius: BorderRadius.circular(30)),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(Icons.lightbulb, color: Colors.green.shade800),
          const SizedBox(width: 16),
          Expanded(
            child: Text(
              "Nasi putih merupakan sumber energi cepat. Untuk keseimbangan lebih baik, padukan dengan serat dari sayuran hijau.",
              style: TextStyle(fontSize: 13, color: Colors.green.shade900, height: 1.5, fontWeight: FontWeight.w500),
            ),
          ),
        ],
      ),
    );
  }
}