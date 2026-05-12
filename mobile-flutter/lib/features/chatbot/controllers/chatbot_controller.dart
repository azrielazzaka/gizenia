import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

// --- MODEL DATA CHAT ---
class ChatMessage {
  final String text;
  final bool isUser;
  ChatMessage({required this.text, required this.isUser});
}

// --- CONTROLLER CHATBOT DENGAN GROQ API ---
class ChatbotController extends GetxController {
  var messages = <ChatMessage>[].obs;
  var isLoading = false.obs;
  final textController = TextEditingController();

  // 🔴 MASUKKAN API KEY GROQ ANDA DI SINI (Dimulai dengan gsk_...)
  final String apiKey = "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"; 
  final String apiUrl = "https://api.groq.com/openai/v1/chat/completions";

  // Menyimpan riwayat chat (Sangat penting agar AI ingat percakapan sebelumnya)
  List<Map<String, String>> chatHistory = [];

  @override
  void onInit() {
    super.onInit();
    
    // Memberikan "Nyawa" dan "Sifat" kepada AI
    chatHistory.add({
      "role": "system",
      "content": "Kamu adalah GIZENIA, asisten ahli gizi dari Indonesia. Jawablah pertanyaan seputar kesehatan, kalori, dan makanan dengan ramah, informatif, dan singkat. Gunakan bahasa Indonesia yang santai tapi sopan."
    });

    // Pesan sapaan pertama
    messages.add(ChatMessage(
      text: "Halo! Saya GIZENIA, asisten nutrisi pintar kamu. Ada yang ingin kamu tanyakan soal makanan atau kesehatan hari ini?", 
      isUser: false
    ));
  }

  // --- FUNGSI MENGIRIM PESAN ---
  Future<void> sendMessage() async {
    final text = textController.text.trim();
    if (text.isEmpty) return;

    // Tampilkan pesan user ke layar
    messages.add(ChatMessage(text: text, isUser: true));
    
    // Masukkan pesan ke dalam memori riwayat chat
    chatHistory.add({"role": "user", "content": text});
    
    textController.clear();
    isLoading.value = true;

    try {
      // Mengirim request ke server Groq
      final response = await http.post(
        Uri.parse(apiUrl),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer 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"
        },
        body: jsonEncode({
          "model": "llama-3.3-70b-versatile", // Model Open-Source yang super cepat & pintar
          "messages": chatHistory,
          "temperature": 0.7, // Tingkat kreativitas jawaban (0.0 - 2.0)
        }),
      );

      // Jika berhasil tembus
      if (response.statusCode == 200) {
        var data = jsonDecode(response.body);
        String aiResponse = data['choices'][0]['message']['content'];

        // Tampilkan balasan AI
        messages.add(ChatMessage(text: aiResponse, isUser: false));
        
        // Simpan balasan AI ke memori agar dia ingat
        chatHistory.add({"role": "assistant", "content": aiResponse});
      } else {
        // Jika Server error (misal API key salah)
        messages.add(ChatMessage(text: "Waduh, ada kendala di server. (Error: ${response.statusCode})", isUser: false));
        print("GROQ ERROR: ${response.body}");
      }
    } catch (e) {
      // Jika internet HP mati
      messages.add(ChatMessage(text: "Gagal terhubung. Pastikan internet kamu menyala ya! \n\nDetail: $e", isUser: false));
      print("NETWORK ERROR: $e");
    } finally {
      isLoading.value = false;
    }
  }
}