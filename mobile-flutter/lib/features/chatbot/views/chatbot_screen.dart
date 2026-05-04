import 'package:flutter/material.dart';
import 'package:get/get.dart';

// Controller Statis Lokal (Tidak butuh file eksternal)
class StaticChatbotController extends GetxController {
  var messages = <Map<String, dynamic>>[
    {"isUser": false, "text": "Halo! Saya GIZENIA AI. Mode statis sedang aktif, saya siap mendengarkan cerita atau keluhanmu hari ini."}
  ].obs;
  var isTyping = false.obs;

  void sendMessage(String text) async {
    if (text.isEmpty) return;
    messages.add({"isUser": true, "text": text});
    isTyping.value = true;
    
    // Simulasi delay jaringan (2 detik)
    await Future.delayed(const Duration(seconds: 2));
    
    messages.add({"isUser": false, "text": "Ini adalah balasan otomatis. Saat ini aplikasi belum terhubung ke model NLP Flask, namun UI chat sudah selaras dengan desain baru!"});
    isTyping.value = false;
  }
}

class ChatbotScreen extends StatelessWidget {
  ChatbotScreen({super.key});
  
  final StaticChatbotController controller = Get.put(StaticChatbotController());
  final TextEditingController textController = TextEditingController();

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
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text("GIZENIA AI", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.green.shade900)),
                      const Text("Asisten Nutrisi Pintar", style: TextStyle(fontSize: 12, color: Colors.black54)),
                    ],
                  ),
                  const Spacer(),
                  CircleAvatar(radius: 18, backgroundColor: Colors.green.shade100, child: Icon(Icons.smart_toy, size: 20, color: Colors.green.shade800)),
                ],
              ),
            ),

            // --- AREA CHAT ---
            Expanded(
              child: Obx(() => ListView.builder(
                padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
                itemCount: controller.messages.length,
                itemBuilder: (context, index) {
                  var msg = controller.messages[index];
                  bool isUser = msg['isUser'];
                  return Align(
                    alignment: isUser ? Alignment.centerRight : Alignment.centerLeft,
                    child: Container(
                      margin: const EdgeInsets.symmetric(vertical: 8),
                      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 14),
                      constraints: BoxConstraints(maxWidth: MediaQuery.of(context).size.width * 0.75),
                      decoration: BoxDecoration(
                        color: isUser ? Colors.green.shade800 : Colors.white,
                        borderRadius: BorderRadius.only(
                          topLeft: const Radius.circular(20),
                          topRight: const Radius.circular(20),
                          bottomLeft: Radius.circular(isUser ? 20 : 0),
                          bottomRight: Radius.circular(isUser ? 0 : 20),
                        ),
                        boxShadow: [
                          if (!isUser) BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 10, offset: const Offset(0, 4))
                        ],
                      ),
                      child: Text(
                        msg['text'], 
                        style: TextStyle(color: isUser ? Colors.white : Colors.black87, fontSize: 14, height: 1.4),
                      ),
                    ),
                  );
                },
              )),
            ),

            // Indikator Mengetik
            Obx(() => controller.isTyping.value 
              ? Padding(
                  padding: const EdgeInsets.only(bottom: 8, left: 24), 
                  child: Align(alignment: Alignment.centerLeft, child: Text("GIZENIA sedang mengetik...", style: TextStyle(fontStyle: FontStyle.italic, color: Colors.grey.shade500, fontSize: 12)))
                )
              : const SizedBox.shrink()
            ),

            // --- INPUT AREA ---
            Container(
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: const BorderRadius.vertical(top: Radius.circular(30)),
                boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 20, offset: const Offset(0, -5))],
              ),
              child: Row(
                children: [
                  Expanded(
                    child: TextField(
                      controller: textController,
                      decoration: InputDecoration(
                        hintText: "Tanya seputar nutrisi...",
                        hintStyle: TextStyle(color: Colors.grey.shade400, fontSize: 14),
                        filled: true,
                        fillColor: const Color(0xFFF4F5F4),
                        border: OutlineInputBorder(borderRadius: BorderRadius.circular(20), borderSide: BorderSide.none),
                        contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 14),
                      ),
                    ),
                  ),
                  const SizedBox(width: 12),
                  GestureDetector(
                    onTap: () {
                      controller.sendMessage(textController.text);
                      textController.clear();
                    },
                    child: CircleAvatar(
                      radius: 24,
                      backgroundColor: Colors.green.shade800,
                      child: const Icon(Icons.send_rounded, color: Colors.white, size: 20),
                    ),
                  )
                ],
              ),
            )
          ],
        ),
      ),
    );
  }
}