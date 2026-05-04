import 'package:flutter/material.dart';
import 'package:percent_indicator/percent_indicator.dart';

class HomePage extends StatelessWidget {
  const HomePage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF4F6F4),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 20),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _buildHeader(),
              const SizedBox(height: 24),
              _buildNutritionCard(),
              const SizedBox(height: 20),
              _buildDeliveryStatus(),
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
                        Text("Analisis asupan 24 jam terakhir", style: TextStyle(fontSize: 12, color: Colors.grey.shade600)),
                      ],
                    ),
                  ),
                  Text("LIHAT\nSEMUA", textAlign: TextAlign.right, style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: Colors.green.shade800, letterSpacing: 1.2)),
                ],
              ),
              
              const SizedBox(height: 20),
              _buildFoodItem("Salad Quinoa\nMediterania", "420 kcal • Tinggi Serat", "12:30\nPM", "LUNCH"),
              const SizedBox(height: 16),
              _buildFoodItem("Salmon\nPanggang\nAsparagus", "380 kcal • Tinggi Protein", "07:45\nAM", "BREAKFAST"),
              const SizedBox(height: 30),
              
              _buildAiInsight(),
              
              const SizedBox(height: 120), 
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildHeader() {
    return Row(
      children: [
        CircleAvatar(
          radius: 22,
          backgroundColor: Colors.white,
          child: Icon(Icons.person, color: Colors.green.shade800),
        ),
        const SizedBox(width: 12),
        Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text("HI, USER 👋", style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: Colors.grey.shade600, letterSpacing: 1.0)),
            Text("GIZENIA", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.green.shade800)),
          ],
        ),
        const Spacer(),
        Icon(Icons.notifications_rounded, color: Colors.grey.shade600),
      ],
    );
  }

  Widget _buildNutritionCard() {
    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(30),
        // PERBAIKAN: Mengganti withOpacity dengan withValues(alpha: ...)
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 20)],
      ),
      child: Column(
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text("Kondisi Nutrisi", style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                decoration: BoxDecoration(color: Colors.green.shade100, borderRadius: BorderRadius.circular(20)),
                child: Text("SEIMBANG", style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: Colors.green.shade800)),
              )
            ],
          ),
          Align(
            alignment: Alignment.centerLeft,
            child: Text("Status hari ini berdasarkan\nAI", style: TextStyle(fontSize: 12, color: Colors.grey.shade600, height: 1.5)),
          ),
          const SizedBox(height: 30),
          CircularPercentIndicator(
            radius: 80.0,
            lineWidth: 12.0,
            percent: 0.6,
            center: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                const Text("1,450", style: TextStyle(fontSize: 28, fontWeight: FontWeight.bold)),
                Text("KCAL LEFT", style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: Colors.grey.shade600, letterSpacing: 1.0)),
              ],
            ),
            progressColor: Colors.green.shade800,
            backgroundColor: Colors.grey.shade100,
            circularStrokeCap: CircularStrokeCap.round,
          ),
          const SizedBox(height: 30),
          _buildLinearProgress("PROTEINS", "45g / 70g", 0.64, Colors.grey.shade300),
          const SizedBox(height: 16),
          _buildLinearProgress("CARBS", "120g / 250g", 0.48, Colors.grey.shade200),
          const SizedBox(height: 16),
          _buildLinearProgress("FATS", "32g / 60g", 0.53, const Color(0xFFB54D69)),
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
        LinearPercentIndicator(
          lineHeight: 6.0,
          percent: percent,
          progressColor: color,
          backgroundColor: Colors.grey.shade100,
          barRadius: const Radius.circular(10),
          padding: EdgeInsets.zero,
        ),
      ],
    );
  }

  Widget _buildDeliveryStatus() {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(color: Colors.grey.shade200, borderRadius: BorderRadius.circular(24)),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(12),
            decoration: const BoxDecoration(color: Colors.white, shape: BoxShape.circle),
            child: Icon(Icons.delivery_dining, color: Colors.green.shade800),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text("Status\nPenerimaan\nMakanan", style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold, height: 1.3)),
                const SizedBox(height: 4),
                Text("Paket diet pagi ini", style: TextStyle(fontSize: 12, color: Colors.grey.shade600)),
              ],
            ),
          ),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
            decoration: BoxDecoration(color: Colors.green.shade800, borderRadius: BorderRadius.circular(20)),
            child: const Row(
              children: [
                Icon(Icons.check_circle, color: Colors.white, size: 14),
                SizedBox(width: 4),
                Text("YES", style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 12)),
              ],
            ),
          )
        ],
      ),
    );
  }

  Widget _buildFoodItem(String title, String desc, String time, String badge) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        // PERBAIKAN: Mengganti withOpacity dengan withValues(alpha: ...)
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 15)],
      ),
      child: Row(
        children: [
          CircleAvatar(
            radius: 35,
            backgroundColor: Colors.grey.shade100,
            child: Icon(Icons.restaurant, size: 30, color: Colors.grey.shade400), 
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Expanded(child: Text(title, style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold, height: 1.3))),
                    Text(time, textAlign: TextAlign.right, style: TextStyle(fontSize: 10, color: Colors.grey.shade500)),
                  ],
                ),
                const SizedBox(height: 6),
                Text(desc, style: TextStyle(fontSize: 11, color: Colors.grey.shade600)),
                const SizedBox(height: 10),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(
                    color: badge == "LUNCH" ? Colors.green.shade100 : Colors.pink.shade50, 
                    borderRadius: BorderRadius.circular(8)
                  ),
                  child: Text(badge, style: TextStyle(fontSize: 9, fontWeight: FontWeight.bold, color: badge == "LUNCH" ? Colors.green.shade800 : Colors.pink.shade800)),
                )
              ],
            ),
          ),
          const SizedBox(width: 8),
          Icon(Icons.chevron_right, color: Colors.grey.shade400),
        ],
      ),
    );
  }

  Widget _buildAiInsight() {
    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: Colors.green.shade900,
        borderRadius: BorderRadius.circular(30),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text("AI NUTRITION INSIGHT", style: TextStyle(color: Colors.green.shade300, fontSize: 10, fontWeight: FontWeight.bold, letterSpacing: 1.5)),
          const SizedBox(height: 16),
          const Text("Konsumsi air Anda\nkurang 800ml hari ini.", style: TextStyle(color: Colors.white, fontSize: 22, fontWeight: FontWeight.bold, height: 1.3)),
          const SizedBox(height: 12),
          const Text("AI kami mendeteksi tingkat hidrasi Anda menurun. Minum segelas air sekarang untuk menjaga metabolisme tetap optimal.", style: TextStyle(color: Colors.white70, fontSize: 13, height: 1.5)),
          const SizedBox(height: 24),
          ElevatedButton(
            onPressed: () {},
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.white,
              foregroundColor: Colors.green.shade900,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
              elevation: 0,
            ),
            child: const Text("INGATKAN SAYA", style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
          )
        ],
      ),
    );
  }
}