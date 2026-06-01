# GIZENIA - Sistem Rekomendasi Menu Makanan Bergizi AI

![GIZENIA Banner](https://img.shields.io/badge/Status-Production_Ready-success?style=for-the-badge) ![Lisensi](https://img.shields.io/badge/License-MIT-blue?style=for-the-badge)

**GIZENIA** adalah platform *Fullstack* (Web & Mobile) yang dirancang untuk mengelola dan mengoptimalkan Program Makan Bergizi Gratis (MBG) Nasional. 

Sistem ini dilengkapi dengan integrasi Kecerdasan Buatan (**K-Means Clustering**) untuk mengevaluasi status gizi secara *real-time* dan memberikan rekomendasi menu yang dipersonalisasi berdasarkan metrik tubuh pengguna (BMI).

---

## 🚀 Fitur Utama (Core Features)

### 👑 Admin (Web Dashboard)
* **Manajemen Distribusi:** Penjadwalan dan pelacakan logistik MBG ke kelompok sasaran.
* **Manajemen Pengguna:** CRUD data penerima manfaat (siswa) dan metrik antropometri.
* **Katalog Makanan AI:** CRUD dataset master makanan yang terintegrasi langsung dengan mesin *clustering* AI.
* **Laporan & Analitik:** Dasbor interaktif dengan grafik pantauan distribusi harian dan metrik kepatuhan.

### 👤 User (Mobile App & Web)
* **Pelacak Nutrisi AI (Piringku):** Analisis otomatis keseimbangan kalori harian dengan input berat makanan.
* **Rekomendasi Cerdas:** Saran menu makanan terdekat (*Match Score*) menggunakan perhitungan jarak Euclidean.
* **Riwayat Distribusi:** Konfirmasi penerimaan (Yes/No) dan log transparansi makanan MBG.
* **Dasbor Personal:** Visualisasi pemenuhan gizi makro (Protein, Karbohidrat, Lemak, Kalori).

---

## 🛠️ Tech Stack & Arsitektur
Proyek ini memisahkan *logic* antarmuka dan pemrosesan data menggunakan pendekatan *Clean Architecture*:

* **Database:** MongoDB Atlas (NoSQL) - *Embedded Documents & Referencing*
* **Backend API & Web Admin:** Laravel 11 (PHP 8.2) + Tailwind CSS (Blade)
* **AI Engine / ML Service:** Flask (Python 3.10), Scikit-Learn, Pandas
* **Mobile App:** Flutter (Dart) + GetX State Management

---

## ⚙️ Persyaratan Sistem (Prerequisites)
Pastikan environment lokal Anda telah terinstal:
* [PHP 8.2+](https://www.php.net/downloads) & [Composer](https://getcomposer.org/)
* [Python 3.10+](https://www.python.org/downloads/)
* [Flutter SDK (v3.19+)](https://docs.flutter.dev/get-started/install)
* Akses ke **MongoDB Atlas** (atau Local Compass)

---

## 📖 Panduan Instalasi (Step-by-Step)

Untuk menjalankan GIZENIA secara penuh, Anda harus mengaktifkan ketiga servis (Backend, AI, Mobile) secara bersamaan. Ikuti langkah-langkah di bawah ini:

### 1. Kloning Repositori
Buka terminal/CMD, lalu jalankan:
```bash
git clone [https://github.com/azrielazzaka/gizenia.git](https://github.com/azrielazzaka/gizenia.git)
cd gizenia
```
### 2. Konfigurasi Backend (Laravel) - Port 8000
Buka terminal dan arahkan ke folder Laravel:
```bash
cd backend-laravel
composer install
cp .env.example .env
```
Sesuaikan konfigurasi MongoDB di dalam file .env:
```bash
DB_CONNECTION=mongodb
MONGO_URI=mongodb+srv://<username>:<password>@cluster.mongodb.net/gizenia_db
AI_URL=[http://127.0.0.1:5000](http://127.0.0.1:5000)
```
Jalankan server aplikasi:
```bash
php artisan key:generate
php artisan serve
```
### 3. Konfigurasi AI Engine (Python Flask) - Port 5000
Buka terminal baru, lalu arahkan ke folder AI:
```bash
cd ai-engine
python -m venv venv
```
Aktivasi environment:
Windows: venv\Scripts\activate
Mac/Linux: source venv/bin/activate
Instal dependensi dan jalankan server Machine Learning:
```bash
pip install -r requirements.txt
python app.py
```
### 4. Konfigurasi Mobile App (Flutter)
Buka terminal baru lagi, arahkan ke folder Flutter:
```bash
cd mobile-flutter
flutter pub get
```
Jalankan aplikasi di HP atau emulator:
```bash
flutter run
```
Struktur Direktori Utama

gizenia/

├── backend-laravel/       # Core API, MVC Controllers, Blade Views

├── ai-engine/             # Flask API, ML Models (.pkl), Dataset CSV

├── mobile-flutter/        # Clean Architere GctuetX

└── README.md

Tim Pengembang (Kelompok C2)

Muhammad Azriel Azzakaria , Angga Ilham Fafirullah , Diva Hafizdatul Albin , Revy Mariska Putri 
