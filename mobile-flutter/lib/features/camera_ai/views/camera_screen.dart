import 'dart:io';
import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:image_picker/image_picker.dart';
import '../controllers/camera_controller.dart';

class CameraScreen extends StatelessWidget {
  const CameraScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final controller = Get.put(CameraAIController());

    return Scaffold(
      appBar: AppBar(title: const Text("GIZENIA Scan Makanan")),
      body: Column(
        children: [
          // Area Tampilan Gambar
          Expanded(
            child: Container(
              width: double.infinity,
              margin: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.grey.shade200,
                borderRadius: BorderRadius.circular(15),
                border: Border.all(color: Colors.grey.shade400),
              ),
              child: Obx(() => controller.selectedImagePath.isEmpty 
                ? const Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Icons.image_search, size: 80, color: Colors.grey),
                      Text("Belum ada foto yang dipilih"),
                    ],
                  )
                : ClipRRect(
                    borderRadius: BorderRadius.circular(14),
                    child: Image.file(File(controller.selectedImagePath.value), fit: BoxFit.cover),
                  )),
            ),
          ),

          // Hasil AI
          Obx(() => controller.aiResult.isNotEmpty 
            ? Container(
                width: double.infinity,
                padding: const EdgeInsets.all(16),
                margin: const EdgeInsets.symmetric(horizontal: 16),
                decoration: BoxDecoration(
                  color: Colors.green.shade50,
                  borderRadius: BorderRadius.circular(10),
                  border: Border.all(color: Colors.green),
                ),
                child: Text(controller.aiResult.value, style: TextStyle(color: Colors.green.shade900, fontWeight: FontWeight.bold)),
              )
            : const SizedBox()),

          // Tombol Aksi
          Padding(
            padding: const EdgeInsets.all(20),
            child: Column(
              children: [
                Row(
                  children: [
                    // Tombol Kamera
                    Expanded(
                      child: ElevatedButton.icon(
                        icon: const Icon(Icons.camera_alt),
                        label: const Text("Kamera"),
                        onPressed: () => controller.pickImage(ImageSource.camera),
                        style: ElevatedButton.styleFrom(backgroundColor: Colors.blue.shade700, foregroundColor: Colors.white),
                      ),
                    ),
                    const SizedBox(width: 10),
                    // Tombol Galeri (FITUR BARU)
                    Expanded(
                      child: ElevatedButton.icon(
                        icon: const Icon(Icons.photo_library),
                        label: const Text("Galeri"),
                        onPressed: () => controller.pickImage(ImageSource.gallery),
                        style: ElevatedButton.styleFrom(backgroundColor: Colors.orange.shade700, foregroundColor: Colors.white),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 10),
                // Tombol Analisis
                SizedBox(
                  width: double.infinity,
                  child: Obx(() => ElevatedButton(
                    onPressed: controller.isScanning.value ? null : () => controller.scanFood(),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.green.shade800,
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(vertical: 15),
                    ),
                    child: controller.isScanning.value 
                      ? const CircularProgressIndicator(color: Colors.white)
                      : const Text("ANALISIS MAKANAN", style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                  )),
                ),
              ],
            ),
          )
        ],
      ),
    );
  }
}