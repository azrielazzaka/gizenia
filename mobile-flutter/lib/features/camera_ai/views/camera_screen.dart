import 'package:flutter/material.dart';
import 'package:get/get.dart';

// Controller Statis Lokal
class StaticCameraController extends GetxController {
  var isScanning = false.obs;
  var aiResult = "".obs;

  void scanFoodDummy() async {
    isScanning.value = true;
    aiResult.value = "Menganalisis nutrisi gambar...";
    
    // Simulasi proses scanning AI (2 detik)
    await Future.delayed(const Duration(seconds: 2));
    
    aiResult.value = "Deteksi GIZENIA AI: Salad Buah\nKalori: 250 kkal\n(Backend Flask belum aktif)";
    isScanning.value = false;
  }
}

class CameraScreen extends StatelessWidget {
  CameraScreen({super.key});
  
  final StaticCameraController controller = Get.put(StaticCameraController());

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF7F8F8),
      body: SafeArea(
        child: Column(
          children: [
            // --- HEADER CUSTOM ---
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
              child: Row(
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
                  Text("Pemindai Nutrisi", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.green.shade900)),
                ],
              ),
            ),

            Expanded(
              child: SingleChildScrollView(
                padding: const EdgeInsets.all(24),
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const SizedBox(height: 20),
                    
                    // --- AREA SCANNER ---
                    Container(
                      width: double.infinity,
                      height: 300,
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(40),
                        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 20)],
                      ),
                      child: Stack(
                        alignment: Alignment.center,
                        children: [
                          Container(
                            width: 150,
                            height: 150,
                            decoration: BoxDecoration(shape: BoxShape.circle, color: Colors.green.shade50),
                            child: Icon(Icons.document_scanner_rounded, size: 70, color: Colors.green.shade800),
                          ),
                          // Corner brackets effect
                          Positioned(top: 30, left: 30, child: _buildCorner(false, false)),
                          Positioned(top: 30, right: 30, child: _buildCorner(false, true)),
                          Positioned(bottom: 30, left: 30, child: _buildCorner(true, false)),
                          Positioned(bottom: 30, right: 30, child: _buildCorner(true, true)),
                        ],
                      ),
                    ),
                    const SizedBox(height: 40),

                    // --- HASIL ANALISIS ---
                    Container(
                      width: double.infinity,
                      padding: const EdgeInsets.all(24),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(24),
                        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 15)],
                      ),
                      child: Obx(() => controller.isScanning.value
                          ? Column(
                              children: [
                                CircularProgressIndicator(color: Colors.green.shade800), 
                                const SizedBox(height: 16), 
                                Text("AI sedang menganalisis...", style: TextStyle(color: Colors.grey.shade600, fontWeight: FontWeight.w500))
                              ]
                            )
                          : Column(
                              children: [
                                Icon(Icons.analytics_outlined, color: Colors.green.shade800, size: 30),
                                const SizedBox(height: 12),
                                Text(
                                  controller.aiResult.value.isEmpty ? "Arahkan kamera ke makanan Anda untuk memulai pemindaian." : controller.aiResult.value, 
                                  textAlign: TextAlign.center, 
                                  style: TextStyle(fontSize: 14, height: 1.5, color: Colors.black87)
                                ),
                              ],
                            )
                      ),
                    ),
                    const SizedBox(height: 40),

                    // --- TOMBOL BUKA KAMERA ---
                    ElevatedButton.icon(
                      onPressed: () => controller.scanFoodDummy(),
                      icon: const Icon(Icons.camera),
                      label: const Text("Buka Kamera", style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                      style: ElevatedButton.styleFrom(
                        minimumSize: const Size(double.infinity, 55), 
                        backgroundColor: Colors.green.shade800, 
                        foregroundColor: Colors.white, 
                        elevation: 2,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20))
                      ),
                    )
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  // Helper untuk membuat garis sudut (viewfinder effect)
  Widget _buildCorner(bool isBottom, bool isRight) {
    return Container(
      width: 40,
      height: 40,
      decoration: BoxDecoration(
        border: Border(
          top: isBottom ? BorderSide.none : BorderSide(color: Colors.green.shade200, width: 4),
          bottom: isBottom ? BorderSide(color: Colors.green.shade200, width: 4) : BorderSide.none,
          left: isRight ? BorderSide.none : BorderSide(color: Colors.green.shade200, width: 4),
          right: isRight ? BorderSide(color: Colors.green.shade200, width: 4) : BorderSide.none,
        ),
        borderRadius: BorderRadius.only(
          topLeft: Radius.circular(isBottom || isRight ? 0 : 12),
          topRight: Radius.circular(isBottom || !isRight ? 0 : 12),
          bottomLeft: Radius.circular(!isBottom || isRight ? 0 : 12),
          bottomRight: Radius.circular(!isBottom || !isRight ? 0 : 12),
        ),
      ),
    );
  }
}