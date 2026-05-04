@extends('layouts.admin')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Ikhtisar Metrik Kesehatan</h2>
        <p class="text-gray-500 text-sm mt-1">Wawasan real-time tentang distribusi pangan dan kesetaraan gizi.</p>
    </div>
    <div class="flex items-center space-x-4">
        <button class="flex items-center bg-white border border-gray-200 px-4 py-2 rounded-lg text-sm font-medium text-gray-600 shadow-sm hover:bg-gray-50 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Live Data Terkini
        </button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-[0_2px_10px_rgba(0,0,0,0.04)] relative overflow-hidden group hover:border-emerald-200 border border-transparent transition-all">
        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Total Menu</h3>
        <p class="text-4xl font-bold text-gray-900 mb-2" id="statMenus">0</p>
        <p class="text-xs text-emerald-600 font-medium flex items-center">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg> Tersedia di Database
        </p>
        <svg class="absolute bottom-0 right-0 w-32 h-16 text-emerald-50 group-hover:text-emerald-100 transition-colors" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 100 50"><path d="M0 40 Q 25 10, 50 30 T 100 20"></path></svg>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-[0_2px_10px_rgba(0,0,0,0.04)] relative overflow-hidden group hover:border-blue-200 border border-transparent transition-all">
        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Total Penerima</h3>
        <p class="text-4xl font-bold text-gray-900 mb-2" id="statUsers">0</p>
        <p class="text-xs text-blue-600 font-medium flex items-center">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg> Siswa Terdaftar
        </p>
        <svg class="absolute bottom-0 right-0 w-32 h-16 text-blue-50 group-hover:text-blue-100 transition-colors" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 100 50"><path d="M0 20 Q 25 50, 50 30 T 100 10"></path></svg>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-[0_2px_10px_rgba(0,0,0,0.04)] relative overflow-hidden group hover:border-purple-200 border border-transparent transition-all">
        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Total Distribusi</h3>
        <p class="text-4xl font-bold text-gray-900 mb-2" id="statDists">0</p>
        <p class="text-xs text-purple-600 font-medium flex items-center">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Jadwal Tersalurkan
        </p>
        <svg class="absolute bottom-0 right-0 w-32 h-16 text-purple-50 group-hover:text-purple-100 transition-colors" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 100 50"><path d="M0 30 Q 30 40, 50 20 T 100 10"></path></svg>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    
    <div class="bg-white rounded-2xl p-6 shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-gray-50">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h3 class="font-bold text-lg text-gray-900">Analitik Penerima</h3>
                <p class="text-xs text-gray-500">Pendaftaran siswa dalam 6 bulan terakhir</p>
            </div>
            <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg"><i class="fas fa-users"></i></div>
        </div>
        <div class="flex items-end space-x-3 h-48 mt-4" id="barChartContainer">
            </div>
        <div class="flex justify-between mt-2 text-[10px] text-gray-400 font-bold uppercase" id="barLabelContainer">
            </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-gray-50">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h3 class="font-bold text-lg text-gray-900">Tren Operasional</h3>
                <p class="text-xs text-gray-500">Ilustrasi kelancaran sistem bulan ini</p>
            </div>
            <div class="flex items-center text-xs text-gray-500 font-bold"><span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span> ONLINE</div>
        </div>
        <div class="h-48 mt-4">
    <svg id="trendLineChart" class="w-full h-full"></svg>
</div>

<div id="trendLabel" class="flex justify-between mt-2 text-[10px] text-gray-400 font-bold uppercase"></div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-[0_2px_10px_rgba(0,0,0,0.04)] p-6 border border-gray-50">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="font-bold text-lg text-gray-900">Penerima Terbaru</h3>
            <p class="text-xs text-gray-500">Pendaftaran terbaru dalam program nutrisi</p>
        </div>
        <a href="/admin/pengguna" class="text-xs font-semibold text-gray-700 bg-white border border-gray-200 px-4 py-2 rounded-full hover:bg-gray-50 transition-colors">
            Lihat Semua Data
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-100 text-[10px] uppercase tracking-wider text-gray-400">
                    <th class="pb-3 font-semibold">Nama Siswa</th>
                    <th class="pb-3 font-semibold">Tgl Mendaftar</th>
                    <th class="pb-3 font-semibold">Kelas Ruang</th>
                    <th class="pb-3 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="text-sm" id="latestUsersTable">
                <tr><td colspan="5" class="py-6 text-center text-gray-400">Memuat data pengguna...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    const token = localStorage.getItem('jwt_token');
    if (!token) window.location.href = '/login';

    async function loadDashboardStats() {
        try {
            const res = await fetch('/api/dashboard-stats', {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            const data = await res.json();

            // 1. Update Metrik Atas
            document.getElementById('statMenus').innerText = data.metrics.menus.toLocaleString();
            document.getElementById('statUsers').innerText = data.metrics.users.toLocaleString();
            document.getElementById('statDists').innerText = data.metrics.distributions.toLocaleString();

            // 2. Update Tabel Pengguna Terbaru
            const tbody = document.getElementById('latestUsersTable');
            tbody.innerHTML = '';
            
            if(data.latest_users.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="py-6 text-center text-gray-400">Belum ada pengguna terdaftar.</td></tr>';
            } else {
                const colors = ['bg-emerald-200 text-emerald-700', 'bg-blue-200 text-blue-700', 'bg-purple-200 text-purple-700', 'bg-red-200 text-red-700', 'bg-orange-200 text-orange-700'];
                
                data.latest_users.forEach((user, index) => {
                    const colorClass = colors[index % colors.length]; // Rotasi warna inisial
                    const classBadge = user.class_room 
                        ? `<span class="bg-gray-100 text-gray-700 border border-gray-200 text-[11px] font-semibold px-2 py-1 rounded">${user.class_room}</span>` 
                        : `<span class="text-gray-400 italic text-xs">Belum diatur</span>`;

                    tbody.innerHTML += `
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 flex items-center">
                                <div class="w-8 h-8 rounded-full ${colorClass} flex items-center justify-center font-bold text-xs mr-3">${user.initials}</div>
                                <span class="font-semibold text-gray-800">${user.name}</span>
                            </td>
                            <td class="py-4 text-gray-500 text-xs">${user.formatted_date}</td>
                            <td class="py-4">${classBadge}</td>
                            <td class="py-4"><span class="flex items-center text-[11px] font-bold text-emerald-600"><div class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></div>Aktif</span></td>
                            <td class="py-4 text-gray-400"><a href="/admin/pengguna" class="hover:text-emerald-600 transition-colors"><i class="fas fa-edit"></i></a></td>
                        </tr>
                    `;
                });
            }

            // 3. Update Grafik Batang (Dinamis CSS)
            const barContainer = document.getElementById('barChartContainer');
            const labelContainer = document.getElementById('barLabelContainer');
            barContainer.innerHTML = '';
            labelContainer.innerHTML = '';

            // Cari nilai tertinggi untuk menghitung persentase tinggi batang (Maksimal tinggi 100%)
            const maxCount = Math.max(...data.bar_chart.map(item => item.count));
            
            data.bar_chart.forEach((item, index) => {
                // Jika data kosong semua, minimal tinggi 5% agar batangnya tetap terlihat estetik sedikit
                let heightPercent = maxCount === 0 ? 5 : (item.count / maxCount) * 100;
                if (heightPercent < 5) heightPercent = 5; 

                // Gradasi warna dari pudar ke tebal (biru kehijauan)
                const opacity = 40 + (index * 10); 

                barContainer.innerHTML += `<div class="flex-1 bg-emerald-500 rounded-t-md transition-all duration-1000 ease-out hover:bg-emerald-400 relative group cursor-pointer" style="height: ${heightPercent}%; opacity: ${opacity}%;">
                    <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-[10px] px-2 py-1 rounded hidden group-hover:block z-10">${item.count} User</div>
                </div>`;
                
                labelContainer.innerHTML += `<span>${item.label}</span>`;
            });

            // 4. TREND DISTRIBUSI PER BULAN
// =====================
// TREND LINE CHART FIX
// =====================
const svg = document.getElementById('trendLineChart');
const trendLabel = document.getElementById('trendLabel');

svg.innerHTML = '';
trendLabel.innerHTML = '';

const dataTrend = data.trend.slice(0, 6);

const width = 400;
const height = 150;

const max = Math.max(...dataTrend.map(d => d.total)) || 1;

let points = '';

// BUAT TITIK GARIS
dataTrend.forEach((item, i) => {
    const x = (i / (dataTrend.length - 1)) * width;
    const y = height - ((item.total / max) * height);

    points += `${x},${y} `;

    // LABEL BULAN
    trendLabel.innerHTML += `<span>${item.label}</span>`;
});

// AREA BAWAH (gradient feel)
svg.innerHTML += `
<polygon 
    fill="rgba(16,185,129,0.15)" 
    points="${points} ${width},${height} 0,${height}"
/>
`;

// GARIS UTAMA
svg.innerHTML += `
<polyline 
    fill="none" 
    stroke="#10B981" 
    stroke-width="3"
    points="${points}"
/>
`;

// TITIK BULAT
dataTrend.forEach((item, i) => {
    const x = (i / (dataTrend.length - 1)) * width;
    const y = height - ((item.total / max) * height);

    svg.innerHTML += `
        <circle cx="${x}" cy="${y}" r="4" fill="white" stroke="#10B981" stroke-width="2"/>
    `;
});

        } catch (e) {
            console.error("Gagal memuat dashboard", e);
        }
    }

    // Jalankan saat halaman dibuka
    loadDashboardStats();
</script>
@endsection