import 'package:flutter/material.dart';
import 'package:percent_indicator/percent_indicator.dart';
import 'package:get/get.dart';
import '../controllers/dashboard_controller.dart'; // ✅ IMPORT CONTROLLER

class HomePage extends StatelessWidget {
  const HomePage({super.key});

  @override
  Widget build(BuildContext context) {
    // ✅ INISIALISASI CONTROLLER
    final controller = Get.put(DashboardController());

    return Scaffold(
      backgroundColor: const Color(0xFFF4F6F4),
      body: SafeArea(
        // Obx untuk loading state
        child: Obx(() => controller.isLoading.value 
          ? const Center(child: CircularProgressIndicator()) 
          : RefreshIndicator(
              onRefresh: controller.fetchAllData,
              child: SingleChildScrollView(
                physics: const AlwaysScrollableScrollPhysics(),
                padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 20),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _buildHeader(controller),
                    const SizedBox(height: 24),
                    
                    // Jika ada notifikasi makanan, tampilkan card ini
                    if (controller.hasNotification.value)
                      _buildNotificationCard(controller)
                    else
                      _buildNutritionCard(controller),
                      
                    const SizedBox(height: 30),
                    
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      crossAxisAlignment: CrossAxisAlignment.end,
                      children: [
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text("Riwayat Makanan\nTerbaru", style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold, height: 1.2)),
                              const SizedBox(height: 4),
                              Text("Berdasarkan konfirmasi Anda", style: TextStyle(fontSize: 12, color: Colors.grey.shade600)),
                            ],
                          ),
                        ),
                        Text("LIHAT\nSEMUA", textAlign: TextAlign.right, style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: Colors.green.shade800, letterSpacing: 1.2)),
                      ],
                    ),
                    const SizedBox(height: 20),

                    // Render list history dinamis
                    controller.recentHistory.isEmpty 
                        ? Padding(
                            padding: const EdgeInsets.symmetric(vertical: 20),
                            child: Center(child: Text("Belum ada riwayat.", style: TextStyle(color: Colors.grey.shade500))),
                          )
                        : Column(
                            children: controller.recentHistory.map((item) {
                              String date = item['distribution_date'] ?? 'Hari ini';
                              String foods = (item['foods'] as List).map((f) => f['name']).join(', ');
                              return Padding(
                                padding: const EdgeInsets.only(bottom: 16),
                                child: _buildFoodItem(foods, "Porsi Makan Utama", date, "Makanan Bergizi"),
                              );
                            }).toList(),
                          ),
                    
                    const SizedBox(height: 30),
                    _buildAiInsight(controller),
                    const SizedBox(height: 120), 
                  ],
                ),
              ),
            )
        ),
      ),
    );
  }

  Widget _buildHeader(DashboardController controller) {
    return Row(
      children: [
        CircleAvatar(radius: 22, backgroundColor: Colors.white, child: Icon(Icons.person, color: Colors.green.shade800)),
        const SizedBox(width: 12),
        Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text("HI, ${controller.userName.value} 👋", style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: Colors.grey.shade600, letterSpacing: 1.0)),
            Text("GIZENIA", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.green.shade800)),
          ],
        ),
        const Spacer(),
        Icon(Icons.notifications_rounded, color: Colors.grey.shade600),
      ],
    );
  }

  // ✅ KARTU NOTIFIKASI JIKA ADA MAKANAN BARU
  Widget _buildNotificationCard(DashboardController controller) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(24), border: Border.all(color: Colors.green.shade200, width: 2), boxShadow: [BoxShadow(color: Colors.green.withValues(alpha: 0.1), blurRadius: 20)]),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(padding: const EdgeInsets.all(10), decoration: BoxDecoration(color: Colors.green.shade50, shape: BoxShape.circle), child: Icon(Icons.restaurant_menu, color: Colors.green.shade800)),
              const SizedBox(width: 12),
              const Expanded(child: Text("Makanan Anda sudah tiba!", style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold))),
            ],
          ),
          const SizedBox(height: 12),
          Text(controller.foodListText.value, style: TextStyle(fontSize: 13, color: Colors.green.shade800, fontWeight: FontWeight.w600)),
          const SizedBox(height: 20),
          Row(
            children: [
              Expanded(
                child: ElevatedButton(
                  onPressed: () => controller.submitResponse('Tidak'),
                  style: ElevatedButton.styleFrom(backgroundColor: Colors.grey.shade100, foregroundColor: Colors.grey.shade700, elevation: 0),
                  child: const Text("TIDAK"),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: ElevatedButton(
                  onPressed: () => controller.submitResponse('Ya'),
                  style: ElevatedButton.styleFrom(backgroundColor: Colors.green.shade800, foregroundColor: Colors.white, elevation: 0),
                  child: const Text("SAYA TERIMA"),
                ),
              ),
            ],
          )
        ],
      ),
    );
  }

  Widget _buildNutritionCard(DashboardController controller) {
    double percent = (controller.consumedCalories.value / controller.targetCalories.value).clamp(0.0, 1.0);
    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(30), boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 20)]),
      child: Column(
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text("Kondisi Nutrisi", style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                decoration: BoxDecoration(color: Colors.green.shade100, borderRadius: BorderRadius.circular(20)),
                child: Text("MEMUAT", style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: Colors.green.shade800)),
              )
            ],
          ),
          Align(alignment: Alignment.centerLeft, child: Text("Status hari ini berdasarkan AI", style: TextStyle(fontSize: 12, color: Colors.grey.shade600, height: 1.5))),
          const SizedBox(height: 30),
          CircularPercentIndicator(
            radius: 80.0,
            lineWidth: 12.0,
            percent: percent,
            center: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Text("${controller.targetCalories.value}", style: const TextStyle(fontSize: 28, fontWeight: FontWeight.bold)),
                Text("TARGET (KCAL)", style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: Colors.grey.shade600, letterSpacing: 1.0)),
              ],
            ),
            progressColor: Colors.green.shade800,
            backgroundColor: Colors.grey.shade100,
            circularStrokeCap: CircularStrokeCap.round,
          ),
          const SizedBox(height: 30),
          _buildLinearProgress("PROTEINS", "${controller.consumedProtein.value}g", 0.0, Colors.grey.shade300),
          const SizedBox(height: 16),
          _buildLinearProgress("CARBS", "${controller.consumedCarbs.value}g", 0.0, Colors.grey.shade200),
          const SizedBox(height: 16),
          _buildLinearProgress("FATS", "${controller.consumedFats.value}g", 0.0, const Color(0xFFB54D69)),
        ],
      ),
    );
  }

  Widget _buildLinearProgress(String title, String value, double percent, Color color) {
    return Column(
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(title, style: const TextStyle(fontSize: 10, fontWeight: FontWeight.bold, letterSpacing: 0.5)),
            Text(value, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold)),
          ],
        ),
        const SizedBox(height: 8),
        LinearPercentIndicator(lineHeight: 6.0, percent: percent, progressColor: color, backgroundColor: Colors.grey.shade100, barRadius: const Radius.circular(10), padding: EdgeInsets.zero),
      ],
    );
  }

  Widget _buildFoodItem(String title, String desc, String time, String badge) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(24), boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 15)]),
      child: Row(
        children: [
          CircleAvatar(radius: 35, backgroundColor: Colors.grey.shade100, child: Icon(Icons.restaurant, size: 30, color: Colors.grey.shade400)),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(time, style: TextStyle(fontSize: 10, color: Colors.grey.shade500)),
                const SizedBox(height: 4),
                Text(title, style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold, height: 1.3), maxLines: 2, overflow: TextOverflow.ellipsis),
                const SizedBox(height: 6),
                Container(padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4), decoration: BoxDecoration(color: Colors.green.shade100, borderRadius: BorderRadius.circular(8)), child: Text(badge, style: TextStyle(fontSize: 9, fontWeight: FontWeight.bold, color: Colors.green.shade800)))
              ],
            ),
          ),
          const SizedBox(width: 8),
          Icon(Icons.check_circle, color: Colors.green.shade600),
        ],
      ),
    );
  }

  Widget _buildAiInsight(DashboardController controller) {
    String insightText = "Pertumbuhan Anda sangat baik! Pertahankan asupan menu gizi seimbang ini setiap hari.";
    if (controller.userBmi.value < 18.5 && controller.userBmi.value > 0) insightText = "Indeks Massa Tubuh Anda di bawah standar. Pastikan selalu menghabiskan makanan Anda.";
    else if (controller.userBmi.value >= 25) insightText = "Utamakan menghabiskan porsi sayuran dan serat untuk mengontrol gula darah.";

    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(color: Colors.green.shade900, borderRadius: BorderRadius.circular(30)),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text("PERINGATAN KESEHATAN AI", style: TextStyle(color: Colors.green.shade300, fontSize: 10, fontWeight: FontWeight.bold, letterSpacing: 1.5)),
          const SizedBox(height: 16),
          const Text("Analisis Fisik", style: TextStyle(color: Colors.white, fontSize: 22, fontWeight: FontWeight.bold, height: 1.3)),
          const SizedBox(height: 12),
          Text(insightText, style: const TextStyle(color: Colors.white70, fontSize: 13, height: 1.5)),
        ],
      ),
    );
  }
}