import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:shared_preferences/shared_preferences.dart'; // Tambahkan library ini
import 'routes/app_pages.dart';
import 'routes/app_routes.dart';

void main() async { // Ubah menjadi async
  // Memastikan binding Flutter sudah siap sebelum menjalankan aplikasi
  WidgetsFlutterBinding.ensureInitialized();
  
  // Logika Tambahan: Cek keberadaan token JWT dari Laravel di penyimpanan lokal
  SharedPreferences prefs = await SharedPreferences.getInstance();
  String? token = prefs.getString('jwt_token');

  // Tentukan rute awal: Jika ada token, langsung ke MAIN, jika tidak ke LOGIN
  String initialRoute = (token != null && token.isNotEmpty) ? Routes.MAIN : Routes.LOGIN;
  
  runApp(MyApp(initialRoute: initialRoute));
}

class MyApp extends StatelessWidget {
  final String initialRoute; // Tambahkan variabel rute awal
  const MyApp({super.key, required this.initialRoute});

  @override
  Widget build(BuildContext context) {
    // Menggunakan GetMaterialApp agar fitur GetX berfungsi
    return GetMaterialApp(
      title: 'Gizenia',
      debugShowCheckedModeBanner: false,
      
      // Mengatur halaman pertama secara dinamis berdasarkan status login
      initialRoute: initialRoute, 
      
      // Menghubungkan daftar halaman yang sudah didefinisikan di app_pages.dart
      getPages: AppPages.pages,
      
      // Tema aplikasi sesuai dengan identitas GIZENIA
      theme: ThemeData(
        useMaterial3: true,
        primaryColor: Colors.green.shade800,
        colorScheme: ColorScheme.fromSeed(
          seedColor: Colors.green.shade800,
          primary: Colors.green.shade800,
        ),
        scaffoldBackgroundColor: const Color(0xFFF4F6F4),
        appBarTheme: AppBarTheme(
          backgroundColor: Colors.green.shade800,
          foregroundColor: Colors.white,
          elevation: 0,
        ),
      ),
    );
  }
}