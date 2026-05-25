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
            tabs: const [Tab(text: "KATALOG MENUKU"), Tab(text: "SIMULASI PIRINGKU")],
          ),
        ),
        body: Obx(() {
          if (controller.isLoading.value && controller.allMenus.isEmpty) {
            return const Center(child: CircularProgressIndicator(color: Colors.green));
          }
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
  // TAB 1: KATALOG (SESUAI WEB)
  // ==========================================
  Widget _buildTabKatalog(CalorieCheckerController controller) {
    return RefreshIndicator(
      onRefresh: () => controller.fetchMenus(fromForm: false),
      child: SingleChildScrollView(
        padding: const EdgeInsets.symmetric(vertical: 20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            
            const Padding(padding: EdgeInsets.symmetric(horizontal: 20), child: Text("Hitung Kebutuhan Gizi & AI", style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold))),
            const SizedBox(height: 12),
            Container(
              margin: const EdgeInsets.symmetric(horizontal: 20),
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(24), border: Border.all(color: Colors.grey.shade200)),
              child: Column(
                children: [
                  Row(
                    children: [
                      Expanded(child: _buildTextField("Berat (kg)", controller.weightCtrl)),
                      const SizedBox(width: 8),
                      Expanded(child: _buildTextField("Tinggi (cm)", controller.heightCtrl)),
                      const SizedBox(width: 8),
                      Expanded(child: _buildTextField("Umur", controller.ageCtrl)),
                    ],
                  ),
                  const SizedBox(height: 12),
                  Row(
                    children: [
                      Expanded(
                        child: Obx(() => _buildDropdown("Gender", controller.gender.value, ['male', 'female'], ['Laki-laki', 'Perempuan'], (val) => controller.gender.value = val!)),
                      ),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Obx(() => _buildDropdown("Aktivitas", controller.activity.value, 
                          ['sedentary', 'lightly', 'moderate', 'active'], 
                          ['Jarang Olahraga', 'Ringan', 'Sedang', 'Berat'], 
                          (val) => controller.activity.value = val!)),
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton.icon(
                      onPressed: () => controller.runAiAnalysis(),
                      icon: const Icon(Icons.auto_awesome, size: 18),
                      label: const Text("Analisis Gizi & Temukan Menu AI"),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.green.shade700, foregroundColor: Colors.white,
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12))
                      ),
                    ),
                  )
                ],
              ),
            ),
            const SizedBox(height: 24),

            Obx(() => Container(
              margin: const EdgeInsets.symmetric(horizontal: 20),
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(color: Colors.blue.shade50, borderRadius: BorderRadius.circular(20), border: Border.all(color: Colors.blue.shade100)),
              child: Row(
                children: [
                  Container(padding: const EdgeInsets.all(8), decoration: BoxDecoration(color: Colors.blue.shade500, borderRadius: BorderRadius.circular(12)), child: const Icon(Icons.info_outline, color: Colors.white, size: 20)),
                  const SizedBox(width: 12),
                  Expanded(child: Text(controller.aiMessage.value, style: TextStyle(color: Colors.blue.shade900, fontSize: 13, fontWeight: FontWeight.w600))),
                ],
              ),
            )),
            const SizedBox(height: 24),

            Obx(() {
              if (!controller.hasAiData.value) return const SizedBox.shrink();
              return Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 20),
                    child: GridView.count(
                      crossAxisCount: 2, shrinkWrap: true, physics: const NeverScrollableScrollPhysics(),
                      crossAxisSpacing: 10, mainAxisSpacing: 10, childAspectRatio: 2.2,
                      children: [
                        _buildMacroBox("KALORI (kcal)", "${controller.aiMacros['calories'] ?? '-'}", Colors.grey.shade800),
                        _buildMacroBox("PROTEIN (g)", "${controller.aiMacros['protein'] ?? '-'}", Colors.green.shade700),
                        _buildMacroBox("LEMAK (g)", "${controller.aiMacros['fat'] ?? '-'}", Colors.orange.shade700),
                        _buildMacroBox("KARBOHIDRAT (g)", "${controller.aiMacros['carbohydrates'] ?? '-'}", Colors.blue.shade700),
                      ],
                    ),
                  ),
                  const SizedBox(height: 24),
                  
                  const Padding(padding: EdgeInsets.symmetric(horizontal: 20), child: Text("Rekomendasi Teratas AI", style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold))),
                  const SizedBox(height: 16),
                  SizedBox(
                    height: 250,
                    child: ListView.builder(
                      scrollDirection: Axis.horizontal,
                      padding: const EdgeInsets.symmetric(horizontal: 16),
                      itemCount: controller.recommendations.length,
                      itemBuilder: (context, index) => _buildMenuCard(controller.recommendations[index], true, rank: index + 1),
                    ),
                  ),
                  const SizedBox(height: 24),
                ],
              );
            }),

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
                            DropdownMenuItem(value: "cal_low", child: Text("Kalori Min", style: TextStyle(fontSize: 12))),
                            DropdownMenuItem(value: "cal_high", child: Text("Kalori Max", style: TextStyle(fontSize: 12))),
                          ],
                          onChanged: (val) => controller.sortType.value = val ?? "",
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),

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
  // TAB 2: PELACAK & EVALUATOR AI
  // ==========================================
  Widget _buildTabPelacak(CalorieCheckerController controller) {
    final portionController = TextEditingController(text: "100");
    Map<String, dynamic>? selectedMenu;

    return SingleChildScrollView(
      padding: const EdgeInsets.all(20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
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
                  const Text("Profil Nutrisi Piringku", style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 24),
                  
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
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                    decoration: BoxDecoration(color: Colors.black26, borderRadius: BorderRadius.circular(12)),
                    child: Text("Target Makan: ${controller.targetMealCalories.value} kkal", style: const TextStyle(color: Colors.yellowAccent, fontSize: 12, fontWeight: FontWeight.bold)),
                  ),
                  const SizedBox(height: 24),

                  _buildLinearMacro("Karbohidrat", controller.currentCarbs.value, Colors.orangeAccent),
                  const SizedBox(height: 12),
                  _buildLinearMacro("Protein", controller.currentPro.value, Colors.blueAccent),
                  const SizedBox(height: 12),
                  _buildLinearMacro("Lemak", controller.currentFat.value, Colors.purpleAccent),
                  
                  const SizedBox(height: 24),
                  
                  // KOTAK PESAN AI (DIBAWAH GRAFIK)
                  if (controller.aiVerdictTitle.value != "Belum Ada Data" || controller.isAiLoading.value)
                    Container(
                      width: double.infinity,
                      padding: const EdgeInsets.all(16),
                      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(20)),
                      child: Column(
                        children: [
                          if (controller.isAiLoading.value)
                            const Padding(padding: EdgeInsets.all(8.0), child: Center(child: CircularProgressIndicator(color: Colors.green)))
                          else ...[
                            Text(
                              // Jika status warnanya hijau/merah/orange, berarti itu hasil evaluasi. 
                              // Jika abu-abu (gray), berarti piring baru diubah dan belum dievaluasi.
                              controller.aiStatusColor.value != "gray" ? "GIZENIA.AI: ${controller.aiVerdictTitle.value}" : controller.aiVerdictTitle.value, 
                              textAlign: TextAlign.center, 
                              style: TextStyle(fontSize: 14, fontWeight: FontWeight.w900, color: controller.aiStatusColor.value == "green" ? Colors.green.shade700 : (controller.aiStatusColor.value == "red" ? Colors.red.shade700 : (controller.aiStatusColor.value == "orange" ? Colors.orange.shade700 : Colors.grey.shade800)))
                            ),
                            const SizedBox(height: 8),
                            Text(controller.aiVerdictMessage.value, style: TextStyle(fontSize: 12, color: Colors.grey.shade700, height: 1.5)),
                          ]
                        ],
                      ),
                    ),
                  
                  const SizedBox(height: 16),

                  // TOMBOL EVALUASI AI
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton.icon(
                      onPressed: controller.isAiLoading.value ? null : () => controller.evaluateWithAI(),
                      icon: controller.isAiLoading.value 
                        ? SizedBox(width: 16, height: 16, child: CircularProgressIndicator(strokeWidth: 2, color: boxColor))
                        : Icon(Icons.auto_awesome, color: boxColor),
                      label: Text(
                        controller.isAiLoading.value ? "Menganalisis..." : "Evaluasi Piring dengan AI", 
                        style: TextStyle(color: boxColor, fontWeight: FontWeight.bold)
                      ),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.white, 
                        disabledBackgroundColor: Colors.grey.shade300,
                        padding: const EdgeInsets.symmetric(vertical: 14), 
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16))
                      ),
                    ),
                  ),
                ],
              ),
            );
          }),
          const SizedBox(height: 24),

          Container(
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(24), boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 10)]),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text("Tambahkan Makanan", style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                const SizedBox(height: 16),
                
                Autocomplete<Map<String, dynamic>>(
                  displayStringForOption: (option) => option['name'].toString(),
                  optionsBuilder: (TextEditingValue textVal) async {
                    if (textVal.text.length < 2) return const Iterable<Map<String, dynamic>>.empty();
                    return await controller.searchFoodsDynamic(textVal.text);
                  },
                  onSelected: (option) => selectedMenu = option,
                  fieldViewBuilder: (context, textController, focusNode, onFieldSubmitted) {
                    return TextField(
                      controller: textController,
                      focusNode: focusNode,
                      decoration: InputDecoration(hintText: "Ketik nama makanan...", filled: true, fillColor: Colors.grey.shade50, border: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: BorderSide.none), prefixIcon: const Icon(Icons.search)),
                    );
                  },
                ),
                const SizedBox(height: 12),
                
                TextField(
                  controller: portionController,
                  keyboardType: TextInputType.number,
                  decoration: InputDecoration(labelText: "Porsi (Gram)", filled: true, fillColor: Colors.grey.shade50, border: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: BorderSide.none), suffixText: "g"),
                ),
                const SizedBox(height: 16),
                
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton.icon(
                    onPressed: () {
                      if (selectedMenu != null) {
                        controller.addFoodToPlate(selectedMenu, double.tryParse(portionController.text) ?? 0);
                        selectedMenu = null; 
                      } else {
                        Get.snackbar("Pilih Makanan", "Silakan ketik dan pilih makanan dari dropdown terlebih dahulu.", backgroundColor: Colors.white);
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

  Widget _buildTextField(String label, TextEditingController ctrl) {
    return TextField(
      controller: ctrl,
      keyboardType: TextInputType.number,
      decoration: InputDecoration(
        labelText: label, labelStyle: const TextStyle(fontSize: 11),
        filled: true, fillColor: Colors.grey.shade50,
        contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide.none),
      ),
    );
  }

  Widget _buildDropdown(String label, String value, List<String> values, List<String> texts, Function(String?) onChanged) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12),
      decoration: BoxDecoration(color: Colors.grey.shade50, borderRadius: BorderRadius.circular(12)),
      child: DropdownButtonHideUnderline(
        child: DropdownButton<String>(
          value: value, isExpanded: true, style: const TextStyle(fontSize: 12, color: Colors.black87),
          items: List.generate(values.length, (i) => DropdownMenuItem(value: values[i], child: Text(texts[i]))),
          onChanged: onChanged,
        ),
      ),
    );
  }

  Widget _buildMacroBox(String label, String value, Color color) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16), border: Border.all(color: Colors.grey.shade200)),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text(label, style: const TextStyle(fontSize: 9, color: Colors.grey, fontWeight: FontWeight.bold)),
          const SizedBox(height: 4),
          Text(value, style: TextStyle(fontSize: 18, fontWeight: FontWeight.w900, color: color)),
        ],
      ),
    );
  }

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
          lineHeight: 6.0, percent: (value / 200).clamp(0.0, 1.0), progressColor: color,
          backgroundColor: Colors.white.withValues(alpha: 0.2), barRadius: const Radius.circular(10), padding: EdgeInsets.zero,
        ),
      ],
    );
  }

  Widget _buildMenuCard(dynamic menu, bool isRecommended, {int rank = 1}) {
    double matchScore = (menu['match_score'] ?? 0).toDouble();
    String imgUrl = menu['image'] ?? menu['image_url'] ?? 'https://images.unsplash.com/photo-1490645935967-10de6ba17061';

    return Container(
      width: 220, margin: const EdgeInsets.only(right: 16, bottom: 8, left: 4, top: 4),
      decoration: BoxDecoration(
        color: Colors.white, borderRadius: BorderRadius.circular(24), 
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 10, offset: const Offset(0, 4))],
        border: isRecommended ? Border.all(color: Colors.green.shade200, width: 1.5) : null,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Stack(
            children: [
              ClipRRect(
                borderRadius: const BorderRadius.vertical(top: Radius.circular(22)), 
                child: Image.network(imgUrl, height: 130, width: double.infinity, fit: BoxFit.cover, errorBuilder: (c, e, s) => Container(height: 130, width: double.infinity, color: Colors.grey.shade200, child: const Icon(Icons.fastfood, color: Colors.grey, size: 40))),
              ),
              if (isRecommended)
                Positioned(
                  top: 10, left: 10,
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                    decoration: BoxDecoration(color: rank == 1 ? Colors.orange.shade500 : Colors.green.shade600, borderRadius: BorderRadius.circular(8)),
                    child: Text("Rank #$rank", style: const TextStyle(color: Colors.white, fontSize: 9, fontWeight: FontWeight.bold)),
                  ),
                ),
              if (isRecommended && matchScore > 0)
                Positioned(
                  top: 10, right: 10,
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                    decoration: BoxDecoration(color: Colors.white.withOpacity(0.9), borderRadius: BorderRadius.circular(12), border: Border.all(color: Colors.green.shade100, width: 1)),
                    child: Text("Cocok: ${matchScore.toStringAsFixed(1)}%", style: TextStyle(fontSize: 9, fontWeight: FontWeight.w900, color: Colors.green.shade800)),
                  ),
                ),
            ],
          ),
          Padding(
            padding: const EdgeInsets.all(14),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(menu['name'] ?? '-', style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold), maxLines: 1, overflow: TextOverflow.ellipsis),
                const SizedBox(height: 4),
                Text((menu['category'] ?? 'UMUM').toString().toUpperCase(), style: TextStyle(fontSize: 9, fontWeight: FontWeight.bold, color: Colors.green.shade600, letterSpacing: 0.5)),
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
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(20), border: Border.all(color: Colors.grey.shade100)),
      child: Row(
        children: [
          ClipRRect(borderRadius: const BorderRadius.only(topLeft: Radius.circular(20), bottomLeft: Radius.circular(20)), child: Image.network(imgUrl, height: 100, width: 100, fit: BoxFit.cover, errorBuilder: (c, e, s) => Container(height: 100, width: 100, color: Colors.grey.shade300))),
          Expanded(
            child: Padding(
              padding: const EdgeInsets.all(12),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(menu['name'] ?? '-', style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  Text("🔥 ${menu['calories']} kkal", style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: Colors.grey.shade700)),
                ],
              ),
            ),
          )
        ],
      ),
    );
  }
}