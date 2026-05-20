import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:percent_indicator/percent_indicator.dart';
import '../controllers/calorie_checker_controller.dart';

class CalorieCheckerPage extends StatelessWidget {
  const CalorieCheckerPage({super.key});

  @override
  Widget build(BuildContext context) {
    final controller = Get.put(CalorieCheckerController());

    return DefaultTabController(
      length: 2,
      child: Scaffold(
        backgroundColor: const Color(0xFFF4F6F4),
        appBar: AppBar(
          backgroundColor: Colors.white,
          elevation: 0,
          title: Text("Pelacak Nutrisi AI", style: TextStyle(color: Colors.green.shade900, fontWeight: FontWeight.bold, fontSize: 18)),
          centerTitle: true,
          bottom: TabBar(
            labelColor: Colors.green.shade800,
            unselectedLabelColor: Colors.grey.shade400,
            indicatorColor: Colors.green.shade800,
            labelStyle: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12),
            tabs: const [Tab(text: "KATALOG MENU"), Tab(text: "SIMULASI PIRINGKU")],
          ),
        ),
        body: Obx(() {
          if (controller.isLoading.value) return const Center(child: CircularProgressIndicator());
          return TabBarView(
            children: [
              _buildTabKatalog(controller), 
              _buildTabPelacak(controller),
            ],
          );
        }),
      ),
    );
  }

  // ==========================================
  // TAB 1: KATALOG (DENGAN SEARCH & FILTER)
  // ==========================================
  Widget _buildTabKatalog(CalorieCheckerController controller) {
    return RefreshIndicator(
      onRefresh: controller.fetchMenus,
      child: SingleChildScrollView(
        padding: const EdgeInsets.symmetric(vertical: 20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // AI Recommendation Banner
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              child: Container(
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(gradient: LinearGradient(colors: [Colors.green.shade700, Colors.teal.shade800]), borderRadius: BorderRadius.circular(24)),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Container(padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4), decoration: BoxDecoration(color: Colors.white.withValues(alpha: 0.2), borderRadius: BorderRadius.circular(20)), child: const Text("Analisis Gizi AI", style: TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold))),
                    const SizedBox(height: 16),
                    const Text("Rekomendasi Personal", style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 8),
                    Text("Target 1x Makan: ${controller.targetMealCalories.value} kkal", style: const TextStyle(color: Colors.yellowAccent, fontSize: 20, fontWeight: FontWeight.w900)),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 24),

            // Rekomendasi Horizontal
            if (controller.recommendations.isNotEmpty) ...[
              const Padding(padding: EdgeInsets.symmetric(horizontal: 20), child: Text("Sangat Disarankan", style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold))),
              const SizedBox(height: 16),
              SizedBox(
                height: 250,
                child: ListView.builder(
                  scrollDirection: Axis.horizontal,
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  itemCount: controller.recommendations.length,
                  itemBuilder: (context, index) => _buildMenuCard(controller.recommendations[index], true),
                ),
              ),
              const SizedBox(height: 24),
            ],

            // Filter & Search
            const Padding(padding: EdgeInsets.symmetric(horizontal: 20), child: Text("Katalog Semua Menu", style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold))),
            Padding(
              padding: const EdgeInsets.all(20),
              child: Row(
                children: [
                  Expanded(
                    flex: 2,
                    child: TextField(
                      onChanged: (val) => controller.searchQuery.value = val,
                      decoration: InputDecoration(hintText: "Cari menu...", filled: true, fillColor: Colors.white, prefixIcon: const Icon(Icons.search, size: 20), border: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: BorderSide.none), contentPadding: const EdgeInsets.symmetric(vertical: 0)),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    flex: 1,
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 12),
                      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)),
                      child: DropdownButtonHideUnderline(
                        child: DropdownButton<String>(
                          isExpanded: true,
                          value: controller.sortType.value.isEmpty ? null : controller.sortType.value,
                          hint: const Text("Urutkan", style: TextStyle(fontSize: 12)),
                          items: const [
                            DropdownMenuItem(value: "", child: Text("Default", style: TextStyle(fontSize: 12))),
                            DropdownMenuItem(value: "az", child: Text("A - Z", style: TextStyle(fontSize: 12))),
                            DropdownMenuItem(value: "cal_low", child: Text("Kalori Terendah", style: TextStyle(fontSize: 12))),
                            DropdownMenuItem(value: "cal_high", child: Text("Kalori Tertinggi", style: TextStyle(fontSize: 12))),
                          ],
                          onChanged: (val) => controller.sortType.value = val ?? "",
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),

            // List Semua Menu
            Obx(() {
              var list = controller.filteredMenus;
              if (list.isEmpty) return const Center(child: Text("Menu tidak ditemukan."));
              return ListView.builder(
                physics: const NeverScrollableScrollPhysics(),
                shrinkWrap: true,
                padding: const EdgeInsets.symmetric(horizontal: 20),
                itemCount: list.length,
                itemBuilder: (context, index) => Padding(padding: const EdgeInsets.only(bottom: 16), child: _buildListMenuCard(list[index])),
              );
            }),
          ],
        ),
      ),
    );
  }

  // ==========================================
  // TAB 2: PELACAK (GRAFIK & AI EVALUATOR)
  // ==========================================
  Widget _buildTabPelacak(CalorieCheckerController controller) {
    final portionController = TextEditingController(text: "100");
    Map<String, dynamic>? selectedMenu; // ✅ PERBAIKAN 1: Tipe data yang benar

    return SingleChildScrollView(
      padding: const EdgeInsets.all(20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // 1. KARTU ANALISIS GIZI (VISUAL GRAFIK)
          Obx(() {
            double calPercent = (controller.currentCals.value / controller.targetMealCalories.value).clamp(0.0, 1.0);
            
            Color boxColor = Colors.green.shade900;
            if (controller.aiStatusColor.value == "red") boxColor = Colors.red.shade900;
            if (controller.aiStatusColor.value == "orange") boxColor = Colors.orange.shade800;

            return Container(
              padding: const EdgeInsets.all(24),
              decoration: BoxDecoration(color: boxColor, borderRadius: BorderRadius.circular(30), boxShadow: [BoxShadow(color: boxColor.withValues(alpha: 0.3), blurRadius: 15, offset: const Offset(0, 8))]),
              child: Column(
                children: [
                  const Text("Analisis Profil Gizi", style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 24),
                  
                  // Circular Progress (Total Kalori)
                  CircularPercentIndicator(
                    radius: 70.0,
                    lineWidth: 12.0,
                    percent: calPercent,
                    center: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Text("${controller.currentCals.value.round()}", style: const TextStyle(color: Colors.white, fontSize: 28, fontWeight: FontWeight.bold)),
                        const Text("KCAL", style: TextStyle(color: Colors.white70, fontSize: 10, fontWeight: FontWeight.bold)),
                      ],
                    ),
                    progressColor: Colors.yellowAccent,
                    backgroundColor: Colors.white.withValues(alpha: 0.2),
                    circularStrokeCap: CircularStrokeCap.round,
                  ),
                  const SizedBox(height: 12),
                  Text("Target: ${controller.targetMealCalories.value} kkal", style: const TextStyle(color: Colors.white70, fontSize: 12)),
                  const SizedBox(height: 24),

                  // Linear Progress (Makronutrisi)
                  _buildLinearMacro("Karbohidrat", controller.currentCarbs.value, Colors.orangeAccent),
                  const SizedBox(height: 12),
                  _buildLinearMacro("Protein", controller.currentPro.value, Colors.blueAccent),
                  const SizedBox(height: 12),
                  _buildLinearMacro("Lemak", controller.currentFat.value, Colors.purpleAccent),
                  
                  const SizedBox(height: 24),
                  
                  // Kotak Vonis AI
                  Container(
                    width: double.infinity,
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(20)),
                    child: Column(
                      children: [
                        if (controller.isAiLoading.value)
                          const Center(child: CircularProgressIndicator())
                        else ...[
                          Text(controller.aiVerdictTitle.value, textAlign: TextAlign.center, style: TextStyle(fontSize: 16, fontWeight: FontWeight.w900, color: controller.aiStatusColor.value == "green" ? Colors.green.shade700 : (controller.aiStatusColor.value == "red" ? Colors.red.shade700 : Colors.orange.shade700))),
                          const SizedBox(height: 8),
                          Text(controller.aiVerdictMessage.value, style: TextStyle(fontSize: 12, color: Colors.grey.shade700, height: 1.5)),
                        ]
                      ],
                    ),
                  ),
                  const SizedBox(height: 16),

                  // Tombol Tanya AI
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton.icon(
                      onPressed: () => controller.evaluateWithAI(),
                      icon: const Icon(Icons.auto_awesome),
                      label: const Text("Evaluasi dengan AI"),
                      style: ElevatedButton.styleFrom(backgroundColor: Colors.white, foregroundColor: boxColor, padding: const EdgeInsets.symmetric(vertical: 14), shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16))),
                    ),
                  ),
                ],
              ),
            );
          }),
          const SizedBox(height: 24),

          // 2. KARTU TAMBAH MAKANAN
          Container(
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(24), boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 10)]),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text("Tambahkan Makanan", style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                const SizedBox(height: 16),
                
                // ✅ PERBAIKAN 2: Autocomplete Type Cast
                Autocomplete<Map<String, dynamic>>(
                  displayStringForOption: (option) => option['name'].toString(),
                  optionsBuilder: (TextEditingValue textVal) {
                    if (textVal.text.isEmpty) return const Iterable<Map<String, dynamic>>.empty();
                    return controller.allMenus
                        .where((menu) => menu['name'].toString().toLowerCase().contains(textVal.text.toLowerCase()))
                        .cast<Map<String, dynamic>>();
                  },
                  onSelected: (option) => selectedMenu = option,
                  fieldViewBuilder: (context, textController, focusNode, onFieldSubmitted) {
                    return TextField(
                      controller: textController,
                      focusNode: focusNode,
                      decoration: InputDecoration(hintText: "Cari makanan...", filled: true, fillColor: Colors.grey.shade50, border: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: BorderSide.none), prefixIcon: const Icon(Icons.search)),
                    );
                  },
                ),
                const SizedBox(height: 12),
                
                // Porsi Input
                TextField(
                  controller: portionController,
                  keyboardType: TextInputType.number,
                  decoration: InputDecoration(labelText: "Porsi (Gram)", filled: true, fillColor: Colors.grey.shade50, border: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: BorderSide.none), suffixText: "g"),
                ),
                const SizedBox(height: 16),
                
                // Tombol Tambah
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton.icon(
                    onPressed: () {
                      if (selectedMenu != null) {
                        controller.addFoodToPlate(selectedMenu, double.tryParse(portionController.text) ?? 0);
                        selectedMenu = null; // Reset setelah ditambah
                      } else {
                        Get.snackbar("Pilih Makanan", "Silakan pilih makanan dari dropdown terlebih dahulu.");
                      }
                    },
                    icon: const Icon(Icons.add),
                    label: const Text("Masukkan ke Piring"),
                    style: ElevatedButton.styleFrom(backgroundColor: Colors.green.shade800, foregroundColor: Colors.white, padding: const EdgeInsets.symmetric(vertical: 16), shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16))),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 24),

          // 3. DAFTAR PIRING (CART)
          const Text("Isi Piring Saya", style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
          const SizedBox(height: 12),
          Obx(() {
            if (controller.myPlate.isEmpty) {
              return Container(padding: const EdgeInsets.all(30), decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(24)), child: Center(child: Text("Piring masih kosong.", style: TextStyle(color: Colors.grey.shade400))));
            }
            return ListView.builder(
              physics: const NeverScrollableScrollPhysics(),
              shrinkWrap: true,
              itemCount: controller.myPlate.length,
              itemBuilder: (context, index) {
                var item = controller.myPlate[index];
                return Container(
                  margin: const EdgeInsets.only(bottom: 12),
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(20), boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.02), blurRadius: 8)]),
                  child: Row(
                    children: [
                      CircleAvatar(backgroundColor: Colors.green.shade50, child: Text("${index + 1}", style: TextStyle(color: Colors.green.shade800, fontWeight: FontWeight.bold))),
                      const SizedBox(width: 16),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(item['name'], style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                            Text("${item['weight']}g • ${item['calories'].round()} kkal", style: TextStyle(fontSize: 12, color: Colors.grey.shade500)),
                          ],
                        ),
                      ),
                      IconButton(icon: Icon(Icons.delete, color: Colors.red.shade300), onPressed: () => controller.removeFood(index)),
                    ],
                  ),
                );
              },
            );
          }),
        ],
      ),
    );
  }

  // ==========================================
  // WIDGET BANTUAN (HELPERS)
  // ==========================================
  
  Widget _buildLinearMacro(String title, double value, Color color) {
    return Column(
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(title, style: const TextStyle(color: Colors.white70, fontSize: 10, fontWeight: FontWeight.bold)),
            Text("${value.round()}g", style: const TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.bold)),
          ],
        ),
        const SizedBox(height: 6),
        LinearPercentIndicator(
          lineHeight: 6.0,
          percent: (value / 200).clamp(0.0, 1.0), // Asumsi max 200g untuk display bar
          progressColor: color,
          backgroundColor: Colors.white.withValues(alpha: 0.2),
          barRadius: const Radius.circular(10),
          padding: EdgeInsets.zero,
        ),
      ],
    );
  }

  Widget _buildMenuCard(dynamic menu, bool isRecommended) {
    double matchScore = (menu['match_score'] ?? 0).toDouble();
    String imgUrl = menu['image'] ?? menu['image_url'] ?? 'https://images.unsplash.com/photo-1490645935967-10de6ba17061';

    return Container(
      width: 220, 
      margin: const EdgeInsets.only(right: 16, bottom: 8, left: 4, top: 4),
      decoration: BoxDecoration(
        color: Colors.white, 
        borderRadius: BorderRadius.circular(24), 
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 10,
            offset: const Offset(0, 4),
          )
        ],
        border: isRecommended ? Border.all(color: Colors.green.shade100, width: 2) : null,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Stack(
            children: [
              ClipRRect(
                borderRadius: const BorderRadius.vertical(top: Radius.circular(22)), 
                child: Image.network(
                  imgUrl, 
                  height: 130, 
                  width: double.infinity, 
                  fit: BoxFit.cover, 
                  errorBuilder: (c, e, s) => Container(height: 130, color: Colors.grey.shade200, child: const Icon(Icons.fastfood, color: Colors.grey))
                ),
              ),
              if (isRecommended && matchScore > 0)
                Positioned(
                  top: 12,
                  left: 12,
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                    decoration: BoxDecoration(
                      color: Colors.white.withOpacity(0.9),
                      borderRadius: BorderRadius.circular(12),
                      border: Border.all(color: Colors.green.shade100, width: 1),
                    ),
                    child: Row(
                      children: [
                        Icon(Icons.check_circle, size: 12, color: Colors.green.shade600),
                        const SizedBox(width: 4),
                        Text(
                          "${matchScore.toStringAsFixed(1)}%",
                          style: TextStyle(fontSize: 10, fontWeight: FontWeight.w900, color: Colors.green.shade800),
                        ),
                      ],
                    ),
                  ),
                ),
            ],
          ),
          Padding(
            padding: const EdgeInsets.all(14),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  menu['name'] ?? '-', 
                  style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold), 
                  maxLines: 1, 
                  overflow: TextOverflow.ellipsis
                ),
                const SizedBox(height: 4),
                Text(
                  (menu['category'] ?? 'UMUM').toString().toUpperCase(),
                  style: TextStyle(fontSize: 9, fontWeight: FontWeight.bold, color: Colors.green.shade600, letterSpacing: 0.5),
                ),
                const SizedBox(height: 12),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    _buildNutrientInfo("KALORI", "${menu['calories']}"),
                    Container(width: 1, height: 15, color: Colors.grey.shade200),
                    _buildNutrientInfo("PROTEIN", "${menu['protein'] ?? 0}g"),
                    Container(width: 1, height: 15, color: Colors.grey.shade200),
                    _buildNutrientInfo("LEMAK", "${menu['fat'] ?? 0}g"),
                  ],
                ),
              ],
            ),
          )
        ],
      ),
    );
  }

  // Tambahkan fungsi pembantu ini tepat di bawah _buildMenuCard
  Widget _buildNutrientInfo(String label, String value) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.center,
      children: [
        Text(label, style: const TextStyle(fontSize: 8, color: Colors.grey, fontWeight: FontWeight.bold)),
        Text(value, style: const TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: Colors.black87)),
      ],
    );
  }

  Widget _buildListMenuCard(dynamic menu) {
    String imgUrl = menu['image'] ?? menu['image_url'] ?? 'https://images.unsplash.com/photo-1490645935967-10de6ba17061';
    return Container(
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(20)),
      child: Row(
        children: [
          ClipRRect(borderRadius: const BorderRadius.only(topLeft: Radius.circular(20), bottomLeft: Radius.circular(20)), child: Image.network(imgUrl, height: 100, width: 100, fit: BoxFit.cover, errorBuilder: (c, e, s) => Container(height: 100, width: 100, color: Colors.grey.shade300))),
          
          // ✅ PERBAIKAN 3: Membungkus padding dengan widget Padding, bukan meletakkannya di dalam Expanded
          Expanded(
            child: Padding(
              padding: const EdgeInsets.all(12),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(menu['name'] ?? '-', style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  Text("${menu['calories']} kkal", style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold)),
                ],
              ),
            ),
          )
        ],
      ),
    );
  }
}