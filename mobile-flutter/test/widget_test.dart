import 'package:flutter_test/flutter_test.dart';
import 'package:mobile_flutter/main.dart'; // Sesuaikan jika nama package berbeda
import 'package:mobile_flutter/routes/app_routes.dart'; // Import Rute

void main() {
  testWidgets('Aplikasi GIZENIA berhasil di-build (Smoke Test)', (WidgetTester tester) async {
    // Build aplikasi kita dan masukkan parameter wajib initialRoute
    await tester.pumpWidget(const MyApp(initialRoute: Routes.LOGIN));

    // Karena aplikasi ini bukan lagi aplikasi counter bawaan, 
    // kita cukup mengecek apakah widget MyApp berhasil dirender tanpa error.
    expect(find.byType(MyApp), findsOneWidget);
  });
}