import 'package:get/get.dart';
import 'app_routes.dart';

// Import semua halaman (View) dari setiap fitur
import '../features/auth/views/login_page.dart';
import '../features/auth/views/register_page.dart';
import '../features/dashboard/views/main_navigation.dart';
import '../features/chatbot/views/chatbot_screen.dart';
import '../features/camera_ai/views/camera_screen.dart';

class AppPages {
  // initialRoute menentukan halaman mana yang pertama kali muncul
  static const initial = Routes.LOGIN;

  static final pages = [
    GetPage(
      name: Routes.LOGIN,
      page: () => LoginPage(),
      transition: Transition.fadeIn, // Animasi perpindahan halaman
    ),
    GetPage(
      name: Routes.REGISTER,
      page: () => RegisterPage(),
      transition: Transition.rightToLeft,
    ),
    /*GetPage(
      name: Routes.MAIN,
      page: () => MainNavigation(),
      transition: Transition.cupertino,
    ),
    GetPage(
      name: Routes.CHATBOT,
      page: () => ChatbotScreen(),
    ),
    GetPage(
      name: Routes.CAMERA_AI,
      page: () => CameraScreen(),
    ),*/
  ];
}