import 'package:flutter/material.dart';

class HistoryPage extends StatelessWidget {
  const HistoryPage({super.key});
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Color(0xFFF7F8F8),
      appBar: AppBar(
        title: Text("Riwayat Nutrisi", style: TextStyle(color: Colors.green.shade900, fontWeight: FontWeight.bold, fontSize: 18)),
        backgroundColor: Colors.white,
        elevation: 0,
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Kalender Strip
            Container(
              color: Colors.white,
              padding: EdgeInsets.symmetric(vertical: 20),
              child: SingleChildScrollView(
                scrollDirection: Axis.horizontal,
                padding: EdgeInsets.symmetric(horizontal: 24),
                child: Row(
                  children: [
                    _buildDateItem("Sen", "12", false),
                    _buildDateItem("Sel", "13", false),
                    _buildDateItem("Rab", "14", true), // Hari ini (Aktif)
                    _buildDateItem("Kam", "15", false),
                    _buildDateItem("Jum", "16", false),
                  ],
                ),
              ),
            ),
            
            Padding(
              padding: EdgeInsets.all(24),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Summary Card
                  Container(
                    padding: EdgeInsets.all(20),
                    decoration: BoxDecoration(
                      color: Colors.green.shade800,
                      borderRadius: BorderRadius.circular(24),
                      boxShadow: [BoxShadow(color: Colors.green.shade200, blurRadius: 15, offset: Offset(0, 8))],
                    ),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text("Total Konsumsi", style: TextStyle(color: Colors.green.shade200, fontSize: 12)),
                            SizedBox(height: 8),
                            Text("1,450 kcal", style: TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold)),
                          ],
                        ),
                        Container(
                          padding: EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                          decoration: BoxDecoration(color: Colors.white.withValues(alpha: 0.2), borderRadius: BorderRadius.circular(12)),
                          child: Text("Target: 2,000", style: TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.bold)),
                        )
                      ],
                    ),
                  ),
                  SizedBox(height: 30),
                  
                  // Meal Sections
                  _buildMealSection("Sarapan", "380 kcal", [
                    _buildHistoryItem("Roti Gandum & Telur", "250 kcal", "07:30 AM"),
                    _buildHistoryItem("Susu Almond", "130 kcal", "07:45 AM"),
                  ]),
                  SizedBox(height: 20),
                  _buildMealSection("Makan Siang", "620 kcal", [
                    _buildHistoryItem("Salad Quinoa Mediterania", "420 kcal", "12:30 PM"),
                    _buildHistoryItem("Dada Ayam Panggang", "200 kcal", "12:45 PM"),
                  ]),
                  
                  SizedBox(height: 80),
                ],
              ),
            )
          ],
        ),
      ),
    );
  }

  Widget _buildDateItem(String day, String date, bool isActive) {
    return Container(
      margin: EdgeInsets.only(right: 16),
      padding: EdgeInsets.symmetric(horizontal: 20, vertical: 16),
      decoration: BoxDecoration(
        color: isActive ? Colors.green.shade800 : Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: isActive ? null : Border.all(color: Colors.grey.shade200),
      ),
      child: Column(
        children: [
          Text(day, style: TextStyle(color: isActive ? Colors.white70 : Colors.grey.shade500, fontSize: 12)),
          SizedBox(height: 8),
          Text(date, style: TextStyle(color: isActive ? Colors.white : Colors.black87, fontSize: 18, fontWeight: FontWeight.bold)),
        ],
      ),
    );
  }

  Widget _buildMealSection(String title, String totalCal, List<Widget> items) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(title, style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
            Text(totalCal, style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: Colors.green.shade800)),
          ],
        ),
        SizedBox(height: 12),
        ...items,
      ],
    );
  }

  Widget _buildHistoryItem(String name, String cal, String time) {
    return Container(
      margin: EdgeInsets.only(bottom: 12),
      padding: EdgeInsets.all(16),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(name, style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
              SizedBox(height: 4),
              Text(time, style: TextStyle(color: Colors.grey.shade500, fontSize: 11)),
            ],
          ),
          Text(cal, style: TextStyle(fontWeight: FontWeight.bold, color: Colors.grey.shade700, fontSize: 13)),
        ],
      ),
    );
  }
}