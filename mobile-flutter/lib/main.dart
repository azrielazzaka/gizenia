import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'routes/app_pages.dart';
import 'routes/app_routes.dart';

void main() {
  // Memastikan binding Flutter sudah siap sebelum menjalankan aplikasi
  WidgetsFlutterBinding.ensureInitialized();
  
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    // Menggunakan GetMaterialApp (bukan MaterialApp biasa) agar fitur GetX berfungsi
    return GetMaterialApp(
      title: 'Gizenia',
      debugShowCheckedModeBanner: false,
      
      // Mengatur halaman pertama yang muncul (Login)
      initialRoute: Routes.LOGIN, 
      
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