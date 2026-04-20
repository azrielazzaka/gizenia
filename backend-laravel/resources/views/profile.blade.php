<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Profil Saya - NutriSehat</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Tambahan style hijau-putih di atas Tailwind */
        .bg-gradient-green {
            background: linear-gradient(145deg, #d9e6cf 0%, #c2ddb3 100%);
        }
        .bmi-card {
            transition: all 0.2s ease;
        }
        .status-normal {
            background: #c8e6b5;
            color: #154f21;
        }
        .status-kurus, .status-gemuk {
            background: #f2f9ee;
            color: #2e6b3e;
        }
        .btn-hijau {
            background: #1a4d2e;
            transition: 0.2s;
        }
        .btn-hijau:hover {
            background: #0f3a22;
        }
    </style>
</head>
<body class="bg-gradient-green font-sans">

<div class="max-w-md mx-auto min-h-screen py-4 px-3">
    
    <!-- KARTU PROFIL UTAMA -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
        
        <!-- HEADER HIJAU -->
        <div class="bg-emerald-800 px-5 py-5 text-white">
            <div class="flex items-center justify-between">
                <a href="/user/dashboard" class="text-white text-2xl hover:opacity-80 transition">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-lg font-bold">Profil Saya</h1>
                <button id="settingsBtn" class="text-white hover:opacity-80 transition">
                    <i class="fas fa-sliders-h text-xl"></i>
                </button>
            </div>
        </div>

        <!-- FOTO & NAMA -->
        <div class="flex flex-col items-center py-6 bg-white border-b border-emerald-100">
            <div id="avatar"
                class="w-28 h-28 rounded-full bg-emerald-500 text-white flex items-center justify-center text-3xl font-bold shadow-md">
                ?
            </div>
            <h2 id="name" class="mt-3 text-xl font-bold text-emerald-800">-</h2>
            <p id="email" class="text-emerald-600 text-sm">-</p>
        </div>

        <!-- STAT FISIK (Usia, Berat, Tinggi) - versi card hijau -->
        <div class="grid grid-cols-3 gap-3 p-5 bg-emerald-50">
            <div class="bg-white rounded-2xl p-3 text-center shadow-sm border border-emerald-100">
                <i class="fas fa-calendar-alt text-emerald-600 text-xl mb-1 block"></i>
                <span id="age" class="font-bold text-emerald-800 text-lg">-</span>
                <p class="text-xs text-emerald-600">tahun</p>
            </div>
            <div class="bg-white rounded-2xl p-3 text-center shadow-sm border border-emerald-100">
                <i class="fas fa-weight-scale text-emerald-600 text-xl mb-1 block"></i>
                <span id="weight" class="font-bold text-emerald-800 text-lg">-</span>
                <p class="text-xs text-emerald-600">kg</p>
            </div>
            <div class="bg-white rounded-2xl p-3 text-center shadow-sm border border-emerald-100">
                <i class="fas fa-ruler text-emerald-600 text-xl mb-1 block"></i>
                <span id="height" class="font-bold text-emerald-800 text-lg">-</span>
                <p class="text-xs text-emerald-600">cm</p>
            </div>
        </div>

        <!-- BMI CARD (dihitung otomatis) -->
        <div class="mx-5 mt-3 mb-2 p-4 bg-emerald-50 rounded-2xl border border-emerald-200">
            <div class="flex justify-between items-center flex-wrap">
                <div>
                    <h3 class="font-bold text-emerald-800"><i class="fas fa-heartbeat text-emerald-600 mr-1"></i> Indeks Massa Tubuh</h3>
                    <p class="text-xs text-emerald-600">Berdasarkan berat & tinggi</p>
                </div>
                <div class="text-right">
                    <span id="bmiValue" class="text-2xl font-black text-emerald-800">-</span>
                    <span class="text-emerald-600 text-sm"> kg/m²</span>
                </div>
            </div>
            <div class="flex gap-2 mt-3">
                <span id="statusKurus" class="status-kurus px-3 py-1 rounded-full text-xs font-semibold">Kurus</span>
                <span id="statusNormal" class="status-normal px-3 py-1 rounded-full text-xs font-semibold">Normal</span>
                <span id="statusGemuk" class="status-gemuk px-3 py-1 rounded-full text-xs font-semibold">Gemuk</span>
            </div>
            <p id="bmiNote" class="text-xs text-emerald-700 mt-2"></p>
        </div>

        <!-- DATA LENGKAP -->
        <div class="mt-2 bg-white rounded-xl overflow-hidden mx-4 mb-4">
            <div class="flex justify-between p-4 border-b border-emerald-100">
                <span class="text-gray-600 font-medium"><i class="fas fa-graduation-cap text-emerald-600 w-5"></i> Kelas</span>
                <span id="class_room" class="text-emerald-800 font-medium">-</span>
            </div>
            <div class="flex justify-between p-4 border-b border-emerald-100">
                <span class="text-gray-600 font-medium"><i class="fas fa-fire text-emerald-600 w-5"></i> Kalori / hari</span>
                <span id="kalori" class="text-emerald-800 font-bold">-</span>
            </div>
            <div class="flex justify-between p-4">
                <span class="text-gray-600 font-medium"><i class="fas fa-lock text-emerald-600 w-5"></i> Password</span>
                <button id="ubahPasswordBtn" class="text-emerald-600 text-sm font-semibold hover:text-emerald-800">Ubah</button>
            </div>
        </div>

        <!-- TIPS GIZI HIJAU -->
        <div class="m-4 p-4 bg-emerald-50 rounded-2xl border border-emerald-200">
            <h4 class="font-bold text-emerald-800 mb-2"><i class="fas fa-leaf text-emerald-600 mr-1"></i> Tips gizi untukmu</h4>
            <ul class="space-y-2 text-sm text-emerald-700">
                <li><i class="fas fa-apple-alt text-emerald-500 w-5"></i> Konsumsi sayur & buah minimal 5 porsi sehari</li>
                <li><i class="fas fa-tint text-emerald-500 w-5"></i> Minum air putih minimal 8 gelas per hari</li>
                <li><i class="fas fa-bread-slice text-emerald-500 w-5"></i> Pilih karbohidrat kompleks seperti nasi merah atau oat</li>
                <li><i class="fas fa-walking text-emerald-500 w-5"></i> Aktif bergerak 30 menit setiap hari</li>
            </ul>
        </div>

        <footer class="text-center py-4 text-emerald-500 text-xs">
            🌱 Gizi seimbang, tumbuh kuat bersama NutriSehat
        </footer>
    </div>
</div>

<!-- MODAL PENGAKTUAN SEDERHANA -->
<div id="settingsModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl max-w-xs w-full p-6 text-center">
        <i class="fas fa-user-cog text-4xl text-emerald-600 mb-3"></i>
        <h3 class="text-lg font-bold text-emerald-800">Pengaturan Akun</h3>
        <p class="text-gray-500 text-sm mt-2">Fitur edit profil & ganti password segera hadir ✨</p>
        <button id="closeSettingsBtn" class="mt-4 bg-emerald-600 text-white px-5 py-2 rounded-full font-semibold w-full">Tutup</button>
    </div>
</div>

<script>
const token = localStorage.getItem('jwt_token');

// Kalau belum login
if (!token) {
    window.location.href = '/login';
}

// Fungsi hitung BMI
function hitungBMI(berat, tinggiCm) {
    if (!berat || !tinggiCm || berat <= 0 || tinggiCm <= 0) return null;
    const tinggiM = tinggiCm / 100;
    const bmi = berat / (tinggiM * tinggiM);
    return bmi.toFixed(1);
}

// Fungsi update status BMI
function updateBMIStatus(bmi) {
    const statusKurus = document.getElementById('statusKurus');
    const statusNormal = document.getElementById('statusNormal');
    const statusGemuk = document.getElementById('statusGemuk');
    const bmiNote = document.getElementById('bmiNote');
    
    // Reset semua class
    statusKurus.className = 'px-3 py-1 rounded-full text-xs font-semibold status-kurus';
    statusNormal.className = 'px-3 py-1 rounded-full text-xs font-semibold status-normal';
    statusGemuk.className = 'px-3 py-1 rounded-full text-xs font-semibold status-gemuk';
    
    if (bmi === null) {
        bmiNote.innerText = '⚠️ Masukkan berat & tinggi untuk melihat BMI';
        return;
    }
    
    if (bmi < 18.5) {
        statusKurus.className += ' status-normal'; // aktif
        bmiNote.innerText = '📈 Perlu peningkatan asupan gizi seimbang.';
    } else if (bmi >= 18.5 && bmi <= 24.9) {
        statusNormal.className += ' status-normal';
        bmiNote.innerText = '✅ Bagus! Pertahankan pola makan sehatmu.';
    } else {
        statusGemuk.className += ' status-normal';
        bmiNote.innerText = '⚠️ Mulai kurangi gula & perbanyak gerak.';
    }
}

// Hitung kalori (40 kcal per kg berat)
function hitungKalori(berat) {
    if (!berat) return '-';
    return Math.round(berat * 40) + ' kcal';
}

// Ambil data user dari API
async function loadProfile() {
    try {
        const res = await fetch('/api/auth/me', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        if (!res.ok) {
            localStorage.removeItem('jwt_token');
            window.location.href = '/login';
            return;
        }

        const user = await res.json();
        console.log('Data user:', user);

        // Isi data ke elemen
        document.getElementById('name').innerText = user.name ?? '-';
        document.getElementById('email').innerText = user.email ?? '-';
        document.getElementById('class_room').innerText = user.class_room ?? '-';
        
        // Usia
        const age = user.age;
        document.getElementById('age').innerText = age ? age : '-';
        
        // Berat & Tinggi
        const weight = user.weight ? parseFloat(user.weight) : null;
        const height = user.height ? parseFloat(user.height) : null;
        
        document.getElementById('weight').innerText = weight ? weight + ' kg' : '-';
        document.getElementById('height').innerText = height ? height + ' cm' : '-';
        
        // Hitung BMI
        let bmi = null;
        if (weight && height) {
            bmi = hitungBMI(weight, height);
            document.getElementById('bmiValue').innerText = bmi;
            updateBMIStatus(parseFloat(bmi));
        } else {
            document.getElementById('bmiValue').innerText = '-';
            updateBMIStatus(null);
        }
        
        // Hitung kalori
        if (weight) {
            document.getElementById('kalori').innerHTML = hitungKalori(weight);
        } else {
            document.getElementById('kalori').innerHTML = '-';
        }
        
        // Avatar (inisial)
        const avatar = document.getElementById('avatar');
        const initials = user.name ? user.name.substring(0, 2).toUpperCase() : '?';
        avatar.innerText = initials;
        avatar.style.backgroundImage = 'none';
        avatar.style.backgroundSize = 'cover';
        
        // Kalau ada avatar dari API
        if (user.avatar) {
            avatar.style.backgroundImage = `url(${user.avatar})`;
            avatar.style.backgroundSize = 'cover';
            avatar.innerText = '';
        }
        
        // Set warna background avatar random berdasarkan nama
        const colors = ['#1a4d2e', '#2d6a4f', '#40916c', '#52b788', '#74c69d'];
        const colorIndex = (user.name?.length || 0) % colors.length;
        avatar.style.backgroundColor = colors[colorIndex];
        
    } catch (err) {
        console.error('Error load profile:', err);
    }
}

// Modal pengaturan
const settingsModal = document.getElementById('settingsModal');
const settingsBtn = document.getElementById('settingsBtn');
const closeSettingsBtn = document.getElementById('closeSettingsBtn');
const ubahPasswordBtn = document.getElementById('ubahPasswordBtn');

settingsBtn?.addEventListener('click', () => {
    settingsModal.style.display = 'flex';
});

closeSettingsBtn?.addEventListener('click', () => {
    settingsModal.style.display = 'none';
});

ubahPasswordBtn?.addEventListener('click', () => {
    settingsModal.style.display = 'flex';
});

// Klik di luar modal
window.addEventListener('click', (e) => {
    if (e.target === settingsModal) {
        settingsModal.style.display = 'none';
    }
});

// Jalankan load profile
loadProfile();
</script>

</body>
</html>