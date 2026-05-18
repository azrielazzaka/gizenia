class ApiEndpoints {
  // KONEKSI KE LARAVEL BACKEND (Port 8000)
  static const String baseUrlLaravel = "http://10.106.143.18:8000/api";
  
  // ✅ PERBAIKAN: Tambahkan /auth sesuai rute Laravel Anda
  static const String login = "$baseUrlLaravel/auth/login";
  static const String register = "$baseUrlLaravel/auth/register";
  static const String sendOtp = "$baseUrlLaravel/auth/send-otp"; 
  static const String getProfile = "$baseUrlLaravel/user/profile";
  static const String updateProfile = "$baseUrlLaravel/user/profile/update";
  static const String getHistory = "$baseUrlLaravel/user/history";
  static const String saveHistory = "$baseUrlLaravel/user/history/add";
  static const String getMenus = "$baseUrlLaravel/user/menus";
  static const String forgotPassword = "$baseUrlLaravel/auth/forgot-password";
  static const String resetPassword = "$baseUrlLaravel/auth/reset-password";

  // KONEKSI KE PYTHON FLASK AI (Port 5000)
  static const String baseUrlFlask = "http://10.106.143.18:5000";
  static const String scanFood = "$baseUrlFlask/predict";
  static const String evaluateMeal = "$baseUrlFlask/predict/evaluation";
  static const String recommendKNN = "$baseUrlFlask/predict/recommendation";
  static const String chatBot = "$baseUrlFlask/chat";
}