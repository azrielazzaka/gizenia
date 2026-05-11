import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '../controllers/history_controller.dart';

class HistoryPage extends StatelessWidget {
  const HistoryPage({super.key});

  @override
  Widget build(BuildContext context) {
    final controller = Get.put(HistoryController());
    final searchController = TextEditingController();

    return Scaffold(
      backgroundColor: const Color(0xFFF4F6F4),
      appBar: AppBar(
        title: Text("Riwayat Distribusi", style: TextStyle(color: Colors.green.shade900, fontWeight: FontWeight.bold, fontSize: 18)),
        backgroundColor: Colors.white,
        elevation: 0,
        centerTitle: true,
      ),
      body: Column(
        children: [
          // 1. AREA PENCARIAN & FILTER
          Container(
            color: Colors.white,
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 20),
            child: Column(
              children: [
                Row(
                  children: [
                    // Field Search
                    Expanded(
                      flex: 2,
                      child: TextField(
                        controller: searchController,
                        onSubmitted: (val) => controller.searchHistory(val),
                        decoration: InputDecoration(
                          hintText: "Cari makanan...",
                          hintStyle: const TextStyle(fontSize: 13),
                          prefixIcon: const Icon(Icons.search, size: 20),
                          filled: true,
                          fillColor: Colors.grey.shade50,
                          border: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: BorderSide.none),
                          contentPadding: const EdgeInsets.symmetric(vertical: 0),
                        ),
                      ),
                    ),
                    const SizedBox(width: 12),
                    // Tombol Pilih Tanggal
                    Expanded(
                      flex: 1,
                      child: ElevatedButton.icon(
                        onPressed: () async {
                          DateTime? picked = await showDatePicker(context: context, initialDate: DateTime.now(), firstDate: DateTime(2020), lastDate: DateTime.now());
                          if (picked != null) {
                            String formattedDate = "${picked.year}-${picked.month.toString().padLeft(2, '0')}-${picked.day.toString().padLeft(2, '0')}";
                            controller.filterByDate(formattedDate);
                          }
                        },
                        icon: const Icon(Icons.calendar_today, size: 16),
                        label: const Text("Tgl", style: TextStyle(fontSize: 12)),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.green.shade50,
                          foregroundColor: Colors.green.shade800,
                          elevation: 0,
                          padding: const EdgeInsets.symmetric(vertical: 14),
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                        ),
                      ),
                    )
                  ],
                ),
                Obx(() {
                  if (controller.selectedDate.value.isNotEmpty || controller.searchQuery.value.isNotEmpty) {
                    return Padding(
                      padding: const EdgeInsets.only(top: 12),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text("Filter Aktif", style: TextStyle(fontSize: 12, color: Colors.green.shade700, fontWeight: FontWeight.bold)),
                          GestureDetector(onTap: () { searchController.clear(); controller.clearFilter(); }, child: const Text("Reset", style: TextStyle(fontSize: 12, color: Colors.red, fontWeight: FontWeight.bold))),
                        ],
                      ),
                    );
                  }
                  return const SizedBox();
                })
              ],
            ),
          ),
          
          // 2. LIST RIWAYAT DARI LARAVEL
          Expanded(
            child: Obx(() {
              if (controller.isLoading.value) {
                return const Center(child: CircularProgressIndicator());
              }

              if (controller.historyList.isEmpty) {
                return Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Icons.history, size: 60, color: Colors.grey.shade300),
                      const SizedBox(height: 16),
                      Text("Belum ada riwayat distribusi.", style: TextStyle(color: Colors.grey.shade500)),
                    ],
                  ),
                );
              }

              return RefreshIndicator(
                onRefresh: controller.fetchHistory,
                child: ListView.builder(
                  padding: const EdgeInsets.all(24),
                  itemCount: controller.historyList.length,
                  itemBuilder: (context, index) {
                    var dist = controller.historyList[index];
                    return _buildHistoryCard(dist);
                  },
                ),
              );
            }),
          )
        ],
      ),
    );
  }

  // WIDGET KARTU RIWAYAT
  Widget _buildHistoryCard(dynamic dist) {
    // 1. Parsing Makanan
    List foods = dist['foods'] ?? [];
    String foodText = foods.map((f) => "${f['name']} (${f['weight']}g)").join(', ');

    // 2. Parsing Status & Waktu dari array Responses
    String statusText = "BELUM DIJAWAB";
    Color statusColor = Colors.orange.shade700;
    Color statusBg = Colors.orange.shade50;
    String timeText = "-";

    List responses = dist['responses'] ?? [];
    if (responses.isNotEmpty) {
      // Ambil respon dari user ini (karena backend hanya melempar milik user yg login)
      var res = responses[0];
      if (res['answer'] == 'Ya') {
        statusText = "DITERIMA";
        statusColor = Colors.green.shade700;
        statusBg = Colors.green.shade50;
      } else {
        statusText = "DITOLAK";
        statusColor = Colors.grey.shade600;
        statusBg = Colors.grey.shade200;
      }

      // Parsing Waktu ISO (Contoh: 2026-05-07T12:30:00Z)
      if (res['responded_at'] != null) {
        try {
          DateTime dt = DateTime.parse(res['responded_at']).toLocal();
          timeText = "${dt.hour.toString().padLeft(2, '0')}:${dt.minute.toString().padLeft(2, '0')} WIB";
        } catch (e) {
          timeText = "Waktu Error";
        }
      }
    }

    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 15, offset: const Offset(0, 8))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(dist['distribution_date'] ?? 'Tanggal Tidak Diketahui', style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: Colors.black87)),
              Text(timeText, style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: Colors.grey.shade500)),
            ],
          ),
          const SizedBox(height: 12),
          const Text("Menu Didistribusikan:", style: TextStyle(fontSize: 10, color: Colors.grey)),
          const SizedBox(height: 4),
          Text(foodText, style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold, height: 1.4)),
          const SizedBox(height: 16),
          
          // Badge Status
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
            decoration: BoxDecoration(color: statusBg, borderRadius: BorderRadius.circular(20), border: Border.all(color: statusColor.withValues(alpha: 0.2))),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(
                  statusText == "DITERIMA" ? Icons.check_circle : (statusText == "DITOLAK" ? Icons.cancel : Icons.help),
                  size: 14, color: statusColor,
                ),
                const SizedBox(width: 6),
                Text(statusText, style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: statusColor, letterSpacing: 0.5)),
              ],
            ),
          )
        ],
      ),
    );
  }
}