<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GIZENIA - AI-Powered</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; } </style>
</head>
<div id="fiturModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white w-[95%] max-w-4xl max-h-[90vh] overflow-y-auto p-8 rounded-2xl shadow-xl relative">

        <!-- Tombol Close -->
        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-500 hover:text-black text-lg">✖</button>

        <!-- Judul -->
        <h2 class="text-2xl font-bold mb-2">Fitur Lengkap Aplikasi</h2>
        <p class="text-gray-500 mb-6 text-sm">
            Berikut adalah fitur utama dalam sistem untuk mendukung program MBG berbasis AI.
        </p>

        <!-- GRID FITUR -->
        <div class="grid md:grid-cols-2 gap-6">

            <!-- 1 -->
            <div class="border p-5 rounded-xl">
                <h3 class="font-bold text-lg mb-2">🔥 Cek Kalori Otomatis</h3>
                <p class="text-sm text-gray-500">
                    Pengguna dapat memasukkan jenis dan berat makanan untuk mendapatkan estimasi kalori secara otomatis menggunakan AI.
                </p>
            </div>

            <!-- 2 -->
            <div class="border p-5 rounded-xl">
                <h3 class="font-bold text-lg mb-2">📊 Status Nutrisi</h3>
                <p class="text-sm text-gray-500">
                    Sistem memberikan informasi apakah makanan tersebut sesuai, berlebih, atau kurang dari kebutuhan kalori harian.
                </p>
            </div>

            <!-- 3 -->
            <div class="border p-5 rounded-xl">
                <h3 class="font-bold text-lg mb-2">📦 Distribusi Pangan MBG</h3>
                <p class="text-sm text-gray-500">
                    Admin dapat mengelola dan memantau penyaluran makanan kepada penerima program MBG secara terstruktur.
                </p>
            </div>

            <!-- 4 -->
            <div class="border p-5 rounded-xl">
                <h3 class="font-bold text-lg mb-2">👤 Data Penerima</h3>
                <p class="text-sm text-gray-500">
                    Menyimpan dan menampilkan data penerima bantuan makanan untuk memastikan distribusi tepat sasaran.
                </p>
            </div>

            <!-- 5 -->
            <div class="border p-5 rounded-xl">
                <h3 class="font-bold text-lg mb-2">🔔 Notifikasi</h3>
                <p class="text-sm text-gray-500">
                    Pengguna akan mendapatkan notifikasi terkait status penerimaan makanan dan update penting lainnya.
                </p>
            </div>

            <!-- 6 -->
            <div class="border p-5 rounded-xl">
                <h3 class="font-bold text-lg mb-2">📜 Riwayat Penerimaan</h3>
                <p class="text-sm text-gray-500">
                    Menampilkan riwayat distribusi makanan yang telah diterima oleh pengguna secara transparan.
                </p>
            </div>

            <!-- 7 -->
            <div class="border p-5 rounded-xl">
                <h3 class="font-bold text-lg mb-2">📈 Dashboard Admin</h3>
                <p class="text-sm text-gray-500">
                    Menyediakan ringkasan data seperti total penerima, distribusi, dan laporan dalam bentuk grafik.
                </p>
            </div>

            <!-- 8 -->
            <div class="border p-5 rounded-xl">
                <h3 class="font-bold text-lg mb-2">🍽️ Manajemen Menu</h3>
                <p class="text-sm text-gray-500">
                    Admin dapat mengelola data makanan serta melakukan pencarian menu untuk kebutuhan sistem.
                </p>
            </div>

        </div>

    </div>
</div>
<script>
function openModal() {
    document.getElementById('fiturModal').classList.remove('hidden');
    document.getElementById('fiturModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('fiturModal').classList.add('hidden');
    document.getElementById('fiturModal').classList.remove('flex');
}
</script>
<body class="bg-white text-gray-800 antialiased selection:bg-emerald-200 selection:text-emerald-900">

    <nav class="w-full bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="flex items-center font-extrabold text-xl tracking-tight text-gray-900">
                <span class="text-emerald-600 mr-1">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/></svg>
                </span>
                GIZENIA<span class="font-medium text-gray-600"></span>
            </div>

            <div class="hidden md:flex space-x-8 text-sm font-medium text-gray-500">
                <a href="#tentang" class="hover:text-emerald-600 transition">Tentang Kami</a>
                <a href="#layanan" class="hover:text-emerald-600 transition">Layanan</a>
                <a href="#nutrisi" class="hover:text-emerald-600 transition">Nutrisi</a>
                <a href="#kontak" class="hover:text-emerald-600 transition">Kontak</a>
        </div>

            <div>
                <a href="/login" class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-2.5 rounded-full text-sm font-bold shadow-sm shadow-emerald-200 transition-all">
                    Mulai Sekarang
                </a>
            </div>
        </div>
    </nav>

    <section id="tentang" class="max-w-7xl mx-auto px-6 lg:px-8 py-16 lg:py-24 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center relative">
        <div class="hidden lg:flex absolute top-32 right-[45%] bg-white px-4 py-2 rounded-full shadow-lg items-center space-x-2 z-20 border border-gray-50">
            <div class="w-6 h-6 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <span class="text-xs font-bold text-gray-700">Vitality Assistant</span>
        </div>

        <div class="max-w-2xl">
            <span class="inline-block bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-[10px] font-bold tracking-widest uppercase mb-6">
                AI-Powered
            </span>
            <h1 class="text-4xl lg:text-[54px] font-extrabold text-gray-900 leading-[1.1] tracking-tight mb-6">
                Kembalikan <span class="text-emerald-600 italic font-bold">Keseimbangan</span><br>Nutrisi Anda.
            </h1>
            <p class="text-gray-500 text-base leading-relaxed mb-10 max-w-lg">
                Platform cerdas berbasis AI untuk mengoptimalkan asupan nutrisi dan mendistribusikan pangan sehat secara presisi ke seluruh penjuru.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="/register" class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3.5 rounded-full text-sm font-bold shadow-lg shadow-emerald-200 transition-all transform hover:-translate-y-0.5">
                    Mulai Perjalanan Anda
                </a>
                <a href="#fitur" class="bg-blue-50/80 hover:bg-blue-100 text-blue-900 px-8 py-3.5 rounded-full text-sm font-bold transition-all">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>

        <div class="relative w-full">
            <img src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Sayuran Segar" class="w-full h-[400px] lg:h-[480px] object-cover rounded-3xl shadow-2xl">
            
            <div class="absolute -bottom-6 -left-6 lg:-left-12 bg-white p-5 rounded-2xl shadow-xl w-64 border border-gray-50 flex items-start space-x-4">
                <div class="bg-emerald-50 p-2 rounded-xl text-emerald-600 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-gray-900 mb-1">Nutrisi Terverifikasi</h4>
                    <p class="text-[10px] text-gray-500 leading-relaxed">98% Akurasi AI dalam memetakan profil mikronutrisi harian Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#F8F9FA] py-20 mt-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <h2 class="text-center text-2xl lg:text-3xl font-extrabold text-gray-900 mb-12">
                Dipercaya oleh ribuan pengguna untuk hidup lebih sehat.
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                <div class="bg-white rounded-2xl p-8 text-center shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-4xl font-black text-gray-900 mb-2">50k+</h3>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Pengguna Aktif</p>
                </div>
                <div class="bg-white rounded-2xl p-8 text-center shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <h3 class="text-4xl font-black text-gray-900 mb-2">1M+</h3>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Kalori Terlacak</p>
                </div>
                <div class="bg-white rounded-2xl p-8 text-center shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-4xl font-black text-gray-900 mb-2">500+</h3>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Wilayah Distribusi</p>
                </div>
            </div>
        </div>
    </section>

    <section id="layanan" class="max-w-7xl mx-auto px-6 lg:px-8 py-24">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12">
            <div class="max-w-xl mb-6 md:mb-0">
                <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mb-4">Solusi Nutrisi Pintar</h2>
                <p class="text-sm text-gray-500 leading-relaxed">Kami mengintegrasikan teknologi AI tercanggih untuk memastikan setiap aspek kebutuhan pangan Anda terpenuhi dengan presisi maksimal.</p>
            </div>
            <a href="#" onclick="openModal()" class="text-emerald-600 text-sm font-bold">
    Lihat Semua Fitur →
</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
            <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-[0_2px_15px_rgba(0,0,0,0.03)] hover:-translate-y-1 transition-transform duration-300">
                <div class="w-10 h-10 bg-emerald-600 text-white rounded-lg flex items-center justify-center mb-6">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">Cek Kalori Otomatis</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Hitung kebutuhan kalori berdasarkan jenis dan berat makanan secara otomatis menggunakan AI.</p>
            </div>
            <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-[0_2px_15px_rgba(0,0,0,0.03)] hover:-translate-y-1 transition-transform duration-300">
                <div class="w-10 h-10 bg-emerald-600 text-white rounded-lg flex items-center justify-center mb-6">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">Distribusi Pangan MBG</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Mengelola dan memantau penyaluran makanan kepada penerima program MBG secara terstruktur.</p>
            </div>
            <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-[0_2px_15px_rgba(0,0,0,0.03)] hover:-translate-y-1 transition-transform duration-300">
                <div class="w-10 h-10 bg-emerald-600 text-white rounded-lg flex items-center justify-center mb-6">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">Monitoring & Riwayat</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Melihat riwayat penerimaan makanan dan status distribusi secara transparan dan real-time.</p>
            </div>
        </div>
    </section>
    <section id="nutrisi" class="bg-white py-24">
    <div class="max-w-7xl mx-auto px-6">

        <h2 class="text-3xl font-bold mb-6 text-center">Informasi Nutrisi</h2>
        <p class="text-gray-500 text-center mb-12 max-w-2xl mx-auto">
            Pelajari dasar nutrisi untuk membantu Anda memahami hasil perhitungan kalori dari sistem kami.
        </p>

        <div class="grid md:grid-cols-3 gap-6">

            <!-- 1 -->
            <div class="bg-gray-50 p-6 rounded-xl shadow">
                <h3 class="font-bold mb-2">Apa itu Kalori?</h3>
                <p class="text-sm text-gray-500">
                    Kalori adalah energi yang dibutuhkan tubuh untuk beraktivitas. Kebutuhan setiap orang berbeda.
                </p>
            </div>

            <!-- 2 -->
            <div class="bg-gray-50 p-6 rounded-xl shadow">
                <h3 class="font-bold mb-2">Status Nutrisi</h3>
                <p class="text-sm text-gray-500">
                    Sistem akan menentukan apakah asupan Anda kurang, cukup, atau berlebih berdasarkan perhitungan AI.
                </p>
            </div>

            <!-- 3 -->
            <div class="bg-gray-50 p-6 rounded-xl shadow">
                <h3 class="font-bold mb-2">Tips Pola Makan</h3>
                <p class="text-sm text-gray-500">
                    Konsumsi makanan seimbang yang mengandung karbohidrat, protein, dan serat untuk kesehatan optimal.
                </p>
            </div>

        </div>
    </div>
</section>
    <section class="max-w-7xl mx-auto px-6 lg:px-8 pb-24">
        <div class="relative bg-emerald-700 rounded-[2.5rem] p-12 lg:p-20 text-center text-white overflow-hidden shadow-2xl">
            <div class="absolute inset-0 opacity-20 bg-[url('https://images.unsplash.com/photo-1540420773420-3366772f4999?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80')] bg-cover bg-center mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/80 to-emerald-600/80"></div>
            
            <div class="relative z-10 max-w-2xl mx-auto">
                <h2 class="text-3xl lg:text-4xl font-extrabold mb-6">Siap Untuk Hidup Lebih Sehat?</h2>
                <p class="text-emerald-100 text-sm lg:text-base mb-10 leading-relaxed">Bergabunglah dengan ribuan orang lainnya yang telah bertransformasi bersama Vitality Core.</p>
                <a href="/register" class="bg-white text-emerald-800 hover:bg-gray-100 px-10 py-4 rounded-full font-bold text-sm shadow-lg transition-all transform hover:-translate-y-1 inline-block">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </section>
    
    <footer id="kontak" class="bg-white border-t border-gray-100 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-16 mb-12">
            <div>
                <div class="font-extrabold text-lg tracking-tight text-gray-900 mb-4 flex items-center">
                    <span class="text-emerald-600 mr-1"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/></svg></span>
                    GIZENIA<span class="text-gray-600 font-medium"></span>
                </div>
                <p class="text-xs text-gray-500 leading-relaxed">Prediksi kalori harian dengan AI untuk mendukung program MBG yang lebih sehat dan tepat.</p>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 text-sm mb-4">Navigasi</h4>
                <ul class="space-y-3 text-xs text-gray-500">
                    <li><a href="#tentang" class="hover:text-emerald-600 transition">Tentang Kami</a></li>
                    <li><a href="#layanan" class="hover:text-emerald-600 transition">Layanan</a></li>
                    <li><a href="#nutrisi" class="hover:text-emerald-600 transition">Nutrisi</a></li>
                    <li><a href="#kontak" class="hover:text-emerald-600 transition">Kontak</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 text-sm mb-4">Kontak</h4>
                <ul class="space-y-3 text-xs text-gray-500">
                    <li>
                        Email: 
                        <a href="mailto:support@vitalitycore.com" class="hover:text-emerald-600">
                        gizeniaaaaa@gmail.com
                        </a>
                    </li>
                    <li>
                        WhatsApp: 
                        <a href="https://wa.me/6282331215252" class="hover:text-emerald-600">
                        +62 8223-3121-5252
                        </a>
                </li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 text-sm mb-4">Berlangganan</h4>
                <form class="flex flex-col space-y-3">
                    <input type="email" placeholder="Email Anda" class="bg-blue-50/50 border border-blue-100 rounded-lg px-4 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg px-4 py-2.5 text-xs font-bold transition">Langganan</button>
                </form>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto px-6 lg:px-8 pt-8 border-t border-gray-100 text-center">
            <p class="text-[10px] text-gray-400">© 2024 GIZENIA. Elevating Nutrition with Intelligent Calorie Prediction.</p>
        </div>
    </footer>
<div class="fixed bottom-6 right-6 z-50 font-sans">
        <div id="chatWindow" class="hidden w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 flex-col overflow-hidden mb-4 origin-bottom-right transition-all duration-300 transform scale-95 opacity-0">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-4 flex justify-between items-center text-white shadow-md">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm">Vitality Assistant</h4>
                        <p class="text-[10px] text-emerald-100 flex items-center"><span class="w-1.5 h-1.5 bg-green-300 rounded-full mr-1.5 animate-pulse"></span> Online</p>
                    </div>
                </div>
                <button id="closeChat" class="text-white/70 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div id="chatMessages" class="p-4 h-80 overflow-y-auto flex flex-col space-y-4 bg-gray-50/50">
                <div class="flex items-start max-w-[85%]">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mr-2 mt-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/></svg>
                    </div>
                    <div class="bg-white border border-gray-100 p-3 rounded-2xl rounded-tl-none shadow-sm text-sm text-gray-600 leading-relaxed">
                        Halo! Saya asisten AI VitalityCore. Ada yang bisa saya bantu mengenai nutrisi atau pendaftaran hari ini? 🌱
                    </div>
                </div>
            </div>

            <div class="p-3 bg-white border-t border-gray-100 flex items-center">
                <input type="text" id="chatInput" placeholder="Ketik pertanyaan Anda..." class="flex-1 bg-gray-50 border-none rounded-full px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                <button id="sendChat" class="ml-2 w-10 h-10 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full flex items-center justify-center transition shadow-sm">
                    <svg class="w-4 h-4 translate-x-[-1px] translate-y-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </div>
        </div>

        <button id="chatToggleBtn" class="float-right w-14 h-14 bg-emerald-600 rounded-full shadow-lg shadow-emerald-600/30 flex items-center justify-center text-white hover:bg-emerald-700 transition-all transform hover:scale-105 relative group">
            <span class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-red-500 border-2 border-white rounded-full"></span>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            
            <span class="absolute right-16 bg-gray-800 text-white text-[10px] px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Chat dengan AI</span>
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatWindow = document.getElementById('chatWindow');
            const chatToggleBtn = document.getElementById('chatToggleBtn');
            const closeChat = document.getElementById('closeChat');
            const chatInput = document.getElementById('chatInput');
            const sendChat = document.getElementById('sendChat');
            const chatMessages = document.getElementById('chatMessages');

            let isChatOpen = false;

            // Fungsi Buka/Tutup Chat
            function toggleChat() {
                isChatOpen = !isChatOpen;
                if (isChatOpen) {
                    chatWindow.classList.remove('hidden');
                    // setTimeout trick for transition to trigger
                    setTimeout(() => {
                        chatWindow.classList.remove('scale-95', 'opacity-0');
                        chatWindow.classList.add('scale-100', 'opacity-100', 'flex');
                    }, 10);
                } else {
                    chatWindow.classList.remove('scale-100', 'opacity-100');
                    chatWindow.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => {
                        chatWindow.classList.add('hidden');
                        chatWindow.classList.remove('flex');
                    }, 300);
                }
            }

            chatToggleBtn.addEventListener('click', toggleChat);
            closeChat.addEventListener('click', toggleChat);

            // Fungsi Kirim Pesan
            function sendMessage() {
                const message = chatInput.value.trim();
                if (!message) return;

                // Tambahkan pesan User
                const userBubble = `
                <div class="flex items-end justify-end max-w-[85%] self-end">
                    <div class="bg-emerald-600 text-white p-3 rounded-2xl rounded-tr-none shadow-sm text-sm leading-relaxed">
                        ${message}
                    </div>
                </div>`;
                chatMessages.insertAdjacentHTML('beforeend', userBubble);
                chatInput.value = '';
                scrollToBottom();

                // Simulasi AI Mengetik & Membalas
                setTimeout(() => {
                    const botBubble = `
                    <div class="flex items-start max-w-[85%]">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mr-2 mt-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/></svg>
                        </div>
                        <div class="bg-white border border-gray-100 p-3 rounded-2xl rounded-tl-none shadow-sm text-sm text-gray-600 leading-relaxed">
                            Terima kasih atas pesan Anda! Saat ini saya masih dalam versi simulasi awal. Silakan klik tombol <strong>"Mulai Sekarang"</strong> untuk membuat akun dan menggunakan fitur penuhmnya! ✨
                        </div>
                    </div>`;
                    chatMessages.insertAdjacentHTML('beforeend', botBubble);
                    scrollToBottom();
                }, 1000);
            }

            // Scroll ke bawah saat ada pesan baru
            function scrollToBottom() {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            // Kirim saat tombol panah diklik atau Enter ditekan
            sendChat.addEventListener('click', sendMessage);
            chatInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
        });
    </script>
</body>
</html>