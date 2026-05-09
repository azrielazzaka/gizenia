import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '../../../routes/app_routes.dart';

// Sesuaikan path import dengan struktur folder Feature-First kamu
import 'home_page.dart';
import '../../calorie_checker/views/calorie_checker_page.dart';
import '../../history/views/history_page.dart';
import '../../profile/views/profile_page.dart';

// Controller untuk mengatur state navigasi secara reaktif (GetX)
class NavigationController extends GetxController {
  var selectedIndex = 0.obs;
}

class MainNavigation extends StatelessWidget {
  MainNavigation({super.key});

  final nav = Get.put(NavigationController());

  // Daftar halaman yang akan diakses
  final List<Widget> _pages = [
    const HomePage(),
    const CalorieCheckerPage(), // Pastikan nama class-nya sesuai dengan file kamu
    const HistoryPage(),        // Ganti dengan RiwayatPage() jika kamu menamainya begitu
    const ProfilePage(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      // IndexedStack menjaga agar halaman tidak reset saat berpindah tab
      body: Obx(() => IndexedStack(
        index: nav.selectedIndex.value,
        children: _pages,
      )),
      
      // Tombol mengambang untuk AI Camera & Chatbot (dikembalikan agar fitur AI tetap ada)
      floatingActionButton: Column(
        mainAxisAlignment: MainAxisAlignment.end,
        children: [
          FloatingActionButton(
            heroTag: "btnChatbot",
            onPressed: () => Get.toNamed(Routes.CHATBOT),
            backgroundColor: Colors.white,
            foregroundColor: Colors.green.shade800,
            elevation: 4,
            child: const Icon(Icons.chat_bubble_outline),
          ),
          const SizedBox(height: 16),
          FloatingActionButton(
            heroTag: "btnCamera",
            onPressed: () => Get.toNamed(Routes.CAMERA_AI),
            backgroundColor: Colors.green.shade800,
            foregroundColor: Colors.white,
            elevation: 4,
            child: const Icon(Icons.camera_alt),
          ),
        ],
      ),
      floatingActionButtonLocation: FloatingActionButtonLocation.endFloat,

      // Bottom Navigation Bar yang reaktif dengan Obx
      bottomNavigationBar: Obx(() => BottomNavigationBar(
        backgroundColor: Colors.white,
        selectedItemColor: Colors.green.shade800,
        unselectedItemColor: Colors.grey.shade400,
        selectedFontSize: 10,
        unselectedFontSize: 10,
        currentIndex: nav.selectedIndex.value,
        type: BottomNavigationBarType.fixed,
        onTap: (index) => nav.selectedIndex.value = index, // Mengubah state GetX
        items: const [
          BottomNavigationBarItem(
            icon: Icon(Icons.grid_view_rounded),
            label: 'DASHBOARD',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.restaurant_menu),
            label: 'CEK KALORI',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.history),
            label: 'RIWAYAT',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.person_outline),
            label: 'PROFILE',
          ),
        ],
      )),
    );
  }
}