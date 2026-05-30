import 'dart:io';
import 'dart:convert';
import 'package:get/get.dart';
import 'package:image_picker/image_picker.dart';
import 'package:http/http.dart' as http;
import '../../../../core/constants/api_endpoints.dart';

class CameraAIController extends GetxController {
  var isScanning = false.obs;
  var isPickingImage = false.obs; // Pengaman agar tidak double klik
  var selectedImagePath = "".obs;
  var aiResult = "".obs;
  
  final ImagePicker _picker = ImagePicker();

  // Fungsi dinamis: Bisa untuk Kamera atau Galeri
  Future<void> pickImage(ImageSource source) async {
    if (isPickingImage.value) return;

    try {
      isPickingImage.value = true;
      final XFile? image = await _picker.pickImage(
        source: source, 
        maxWidth: 1080, // Resolusi tinggi agar AI lebih akurat
        imageQuality: 85
      );
      
      if (image != null) {
        selectedImagePath.value = image.path;
        aiResult.value = ""; // Reset hasil lama
      }
    } catch (e) {
      Get.snackbar("Error", "Gagal mengakses media: $e");
    } finally {
      isPickingImage.value = false;
    }
  }

  Future<void> scanFood() async {
    if (selectedImagePath.value.isEmpty) {
      Get.snackbar("Info", "Pilih atau ambil foto makanan dulu ya!");
      return;
    }
    
    isScanning.value = true;
    aiResult.value = "Sedang menganalisis...";

    try {
      var request = http.MultipartRequest('POST', Uri.parse(ApiEndpoints.scanFood));
      request.files.add(await http.MultipartFile.fromPath('image', selectedImagePath.value));
      
      var response = await request.send();
      var responseData = await response.stream.bytesToString();
      
      if (response.statusCode == 200) {
        var result = jsonDecode(responseData);
        var n = result['nutrisi'];
        
        // --- TAMBAHAN UNTUK CONFIDENCE (AKURASI) ---
        // Mengubah format desimal (misal 0.98) menjadi persentase (98.0%)
        double confidence = (result['confidence'] ?? 0.0).toDouble() * 100;
        String akurasi = "${confidence.toStringAsFixed(1)}%";
        
        if (n != null) {
          aiResult.value = "🎯 ${result['makanan_terdeteksi']} ($akurasi)\n\n"
                           "🔥 Kalori: ${n['kalori']} kcal\n"
                           "🍗 Protein: ${n['protein']}g | 🍚 Karbo: ${n['karbohidrat']}g";
        } else {
          aiResult.value = "🎯 Terdeteksi: ${result['makanan_terdeteksi']} ($akurasi)\nNutrisi tidak ditemukan...";
        }
      } else {
        aiResult.value = "Gagal menganalisis. (Kode: ${response.statusCode})";
      }
    } catch (e) {
      aiResult.value = "Terjadi kesalahan jaringan.";
      Get.snackbar("Error", "Gagal menghubungi AI: $e");
    } finally {
      isScanning.value = false;
    }
  }
}