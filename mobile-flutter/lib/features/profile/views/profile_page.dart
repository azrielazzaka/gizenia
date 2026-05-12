import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '../controllers/profile_controller.dart';
import 'edit_profile_page.dart';

class ProfilePage extends StatelessWidget {
  const ProfilePage({super.key});

  @override
  Widget build(BuildContext context) {
    final controller = Get.put(ProfileController());

    return Scaffold(
      backgroundColor: const Color(0xFFF7F8F8),
      body: SafeArea(
        child: Obx(() {
          if (controller.isLoading.value &&
              controller.name.value.isEmpty) {
            return const Center(
              child: CircularProgressIndicator(),
            );
          }

          return SingleChildScrollView(
            padding: const EdgeInsets.symmetric(
              horizontal: 24,
              vertical: 20,
            ),
            child: Column(
              crossAxisAlignment:
                  CrossAxisAlignment.center,
              children: [

                // HEADER
                _buildHeader(),

                const SizedBox(height: 30),

                // PROFILE IMAGE
                _buildProfilePicture(),

                const SizedBox(height: 16),

                // NAME
                Text(
                  controller.name.value,
                  style: const TextStyle(
                    fontSize: 22,
                    fontWeight: FontWeight.bold,
                  ),
                ),

                const SizedBox(height: 4),

                // EMAIL
                Text(
                  controller.email.value,
                  style: const TextStyle(
                    fontSize: 14,
                    color: Colors.black87,
                  ),
                ),

                const SizedBox(height: 20),

                // EDIT BUTTON
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: () =>
                        Get.to(() => EditProfilePage()),
                    style: ElevatedButton.styleFrom(
                      backgroundColor:
                          Colors.grey.shade200,
                      foregroundColor:
                          Colors.black87,
                      elevation: 0,
                      padding:
                          const EdgeInsets.symmetric(
                        vertical: 14,
                      ),
                      shape:
                          RoundedRectangleBorder(
                        borderRadius:
                            BorderRadius.circular(24),
                      ),
                    ),
                    child: const Text(
                      "Edit Profile",
                      style: TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 14,
                      ),
                    ),
                  ),
                ),

                const SizedBox(height: 30),

                // TITLE
                const Align(
                  alignment: Alignment.centerLeft,
                  child: Text(
                    "Data Fisik Anda",
                    style: TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),

                const SizedBox(height: 16),

                // GRID DATA
                GridView.count(
                  crossAxisCount: 2,
                  shrinkWrap: true,
                  physics:
                      const NeverScrollableScrollPhysics(),
                  crossAxisSpacing: 16,
                  mainAxisSpacing: 16,
                  childAspectRatio: 1.35,
                  children: [

                    // BERAT BADAN
                    _buildDataCard(
                      "BERAT\nBADAN",
                      "${controller.weight.value}",
                      "kg",
                      Icons.monitor_weight_outlined,
                    ),

                    // TINGGI BADAN
                    _buildDataCard(
                      "TINGGI\nBADAN",
                      "${controller.height.value}",
                      "cm",
                      Icons.height,
                    ),

                    // USIA
                    _buildDataCard(
                      "USIA",
                      "${controller.age.value}",
                      "Tahun",
                      Icons.cake,
                    ),

                    // KELAS
                    _buildDataCard(
                      "KELAS",
                      controller.classRoom.value,
                      "",
                      Icons.school_outlined,
                    ),
                  ],
                ),

                const SizedBox(height: 20),

                // BMI CARD
                _buildBmiCard(controller),

                const SizedBox(height: 20),

                // TIPS CARD
                _buildTipsCard(),

                const SizedBox(height: 40),

                // LOGOUT
                GestureDetector(
                  onTap: () => controller.logout(),
                  child: Row(
                    mainAxisAlignment:
                        MainAxisAlignment.center,
                    children: [

                      Icon(
                        Icons.logout,
                        color: Colors.red.shade700,
                        size: 20,
                      ),

                      const SizedBox(width: 8),

                      Text(
                        "Logout",
                        style: TextStyle(
                          color: Colors.red.shade700,
                          fontWeight:
                              FontWeight.bold,
                          fontSize: 16,
                        ),
                      ),
                    ],
                  ),
                ),

                const SizedBox(height: 24),

                // VERSION
                Text(
                  "GIZENIA V2.4.0",
                  style: TextStyle(
                    fontSize: 10,
                    letterSpacing: 2.0,
                    color: Colors.grey.shade400,
                    fontWeight: FontWeight.bold,
                  ),
                ),

                const SizedBox(height: 100),
              ],
            ),
          );
        }),
      ),
    );
  }

  // HEADER
  Widget _buildHeader() {
    return Row(
      children: [

        CircleAvatar(
          radius: 18,
          backgroundColor:
              Colors.grey.shade800,
          child: const Icon(
            Icons.person,
            color: Colors.white,
            size: 20,
          ),
        ),

        const SizedBox(width: 12),

        Text(
          "GIZENIA",
          style: TextStyle(
            fontSize: 18,
            fontWeight: FontWeight.bold,
            color: Colors.green.shade800,
          ),
        ),

        const Spacer(),

        Icon(
          Icons.notifications_rounded,
          color: Colors.grey.shade600,
        ),
      ],
    );
  }

  // PROFILE IMAGE
  Widget _buildProfilePicture() {
    return Stack(
      alignment: Alignment.bottomRight,
      children: [

        Container(
          width: 110,
          height: 110,
          decoration: BoxDecoration(
            color: const Color(0xFF2C2C2C),
            borderRadius:
                BorderRadius.circular(40),
            border: Border.all(
              color: Colors.white,
              width: 4,
            ),
            boxShadow: [
              BoxShadow(
                color: Colors.black.withValues(
                  alpha: 0.05,
                ),
                blurRadius: 10,
              ),
            ],
          ),
          child: const Icon(
            Icons.person,
            size: 70,
            color: Colors.white,
          ),
        ),

        Container(
          padding: const EdgeInsets.all(6),
          decoration: BoxDecoration(
            color: Colors.green.shade800,
            shape: BoxShape.circle,
            border: Border.all(
              color: Colors.white,
              width: 3,
            ),
          ),
          child: const Icon(
            Icons.edit,
            color: Colors.white,
            size: 16,
          ),
        ),
      ],
    );
  }

  // DATA CARD
  Widget _buildDataCard(
    String title,
    String value,
    String unit,
    IconData icon,
  ) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius:
            BorderRadius.circular(24),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(
              alpha: 0.02,
            ),
            blurRadius: 10,
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment:
            CrossAxisAlignment.start,
        mainAxisAlignment:
            MainAxisAlignment.spaceBetween,
        children: [

          Row(
            children: [

              Icon(
                icon,
                color: Colors.green.shade800,
                size: 18,
              ),

              const SizedBox(width: 8),

              Text(
                title,
                style: const TextStyle(
                  fontSize: 10,
                  fontWeight:
                      FontWeight.bold,
                  letterSpacing: 0.5,
                ),
              ),
            ],
          ),

          Row(
            crossAxisAlignment:
                CrossAxisAlignment.baseline,
            textBaseline:
                TextBaseline.alphabetic,
            children: [

              Flexible(
                child: Text(
                  value,
                  overflow:
                      TextOverflow.ellipsis,
                  style: const TextStyle(
                    fontSize: 30,
                    fontWeight:
                        FontWeight.bold,
                  ),
                ),
              ),

              const SizedBox(width: 4),

              Text(
                unit,
                style: const TextStyle(
                  fontSize: 14,
                  fontWeight:
                      FontWeight.bold,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  // BMI CARD
  Widget _buildBmiCard(
    ProfileController controller,
  ) {
    double bmi = controller.bmi.value;

    String statusBmi = "Normal";
    Color bmiColor = Colors.green.shade400;

    if (bmi < 18.5 && bmi > 0) {
      statusBmi = "Kurus";
      bmiColor = Colors.blue.shade300;
    } else if (bmi >= 25) {
      statusBmi = "Gemuk";
      bmiColor = Colors.orange.shade400;
    }

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: const Color(0xFFF0F9ED),
        borderRadius:
            BorderRadius.circular(24),
      ),
      child: Column(
        children: [

          Text(
            "BMI",
            style: TextStyle(
              fontSize: 14,
              color: Colors.green.shade700,
              fontWeight: FontWeight.w600,
            ),
          ),

          const SizedBox(height: 8),

          Text(
            bmi.toStringAsFixed(1),
            style: TextStyle(
              fontSize: 42,
              fontWeight: FontWeight.bold,
              color: Colors.green.shade900,
            ),
          ),

          const SizedBox(height: 8),

          Container(
            padding:
                const EdgeInsets.symmetric(
              horizontal: 14,
              vertical: 6,
            ),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius:
                  BorderRadius.circular(20),
            ),
            child: Text(
              statusBmi,
              style: TextStyle(
                color: bmiColor,
                fontWeight:
                    FontWeight.bold,
              ),
            ),
          ),
        ],
      ),
    );
  }

  // TIPS CARD
  Widget _buildTipsCard() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius:
            BorderRadius.circular(24),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(
              alpha: 0.02,
            ),
            blurRadius: 10,
          ),
        ],
      ),
      child: Row(
        crossAxisAlignment:
            CrossAxisAlignment.start,
        children: [

          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              color: Colors.orange.shade100,
              borderRadius:
                  BorderRadius.circular(14),
            ),
            child: Icon(
              Icons.lightbulb,
              color: Colors.orange.shade700,
            ),
          ),

          const SizedBox(width: 14),

          const Expanded(
            child: Column(
              crossAxisAlignment:
                  CrossAxisAlignment.start,
              children: [

                Text(
                  "Tips Hari Ini",
                  style: TextStyle(
                    fontWeight:
                        FontWeight.bold,
                    fontSize: 15,
                  ),
                ),

                SizedBox(height: 6),

                Text(
                  "Perbanyak minum air putih dan konsumsi makanan bergizi agar tubuh tetap sehat.",
                  style: TextStyle(
                    fontSize: 13,
                    height: 1.5,
                    color: Colors.black87,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}