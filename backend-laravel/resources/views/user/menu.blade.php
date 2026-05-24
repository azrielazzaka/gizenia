@extends('layouts.user')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Katalog Menu Nutrisi</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola pola makan sehat Anda dengan asisten rekomendasi AI cerdas.</p>
            </div>
            
            <div id="userStatsBadge" class="flex flex-wrap gap-2 bg-white p-3 rounded-2xl border border-gray-100 shadow-sm text-xs font-medium text-gray-600">
                <span class="bg-gray-100 text-gray-500 px-3 py-1.5 rounded-xl">Data fisik belum diisi</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Hitung Kebutuhan Gizi & Rekomendasi Menu AI</h3>
            
            <div id="calculatorForm" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Berat Badan (kg)</label>
                    <input type="number" id="inputWeight" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-emerald-500" placeholder="Contoh: 60">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Tinggi Badan (cm)</label>
                    <input type="number" id="inputHeight" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-emerald-500" placeholder="Contoh: 165">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Umur (Tahun)</label>
                    <input type="number" id="inputAge" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-emerald-500" placeholder="Contoh: 21">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Gender</label>
                    <select id="inputGender" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-emerald-500">
                        <option value="male">Laki-laki</option>
                        <option value="female">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Aktivitas Fisik</label>
                    <select id="inputActivity" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-emerald-500">
                        <option value="sedentary">Jarang Olahraga</option>
                        <option value="lightly">Ringan (1-3 hari/minggu)</option>
                        <option value="moderate">Sedang (3-5 hari/minggu)</option>
                        <option value="active">Berat (6-7 hari/minggu)</option>
                    </select>
                </div>
                <div class="md:col-span-5 text-right mt-2">
                    <button type="button" onclick="jalankanAnalisis()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-6 py-3 rounded-xl transition text-sm shadow-sm">
                        Analisis Gizi & Temukan Menu AI
                    </button>
                </div>
            </div>
        </div>

        <div id="aiSection" class="mb-10 hidden">
            <div class="p-5 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-3xl text-blue-900 text-sm md:text-base font-semibold shadow-sm mb-6 flex items-center gap-3">
                <div class="p-2 bg-blue-500 text-white rounded-xl shadow-sm animate-bounce">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <p id="clusterStatement">Silakan isi data fisik Anda pada form di atas.</p>
            </div>

            <div id="aiResultsContainer" class="hidden">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Target Kalori / Makan</span>
                        <div class="flex items-baseline gap-1 mt-2">
                            <span id="targetCalories" class="text-2xl font-black text-gray-800">-</span>
                            <span class="text-xs text-gray-500 font-medium">kcal</span>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <span class="block text-xs font-bold text-emerald-500 uppercase tracking-wider">Target Protein</span>
                        <div class="flex items-baseline gap-1 mt-2">
                            <span id="targetProtein" class="text-2xl font-black text-emerald-600">-</span>
                            <span class="text-xs text-emerald-500 font-medium">gram</span>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <span class="block text-xs font-bold text-amber-500 uppercase tracking-wider">Target Lemak</span>
                        <div class="flex items-baseline gap-1 mt-2">
                            <span id="targetFat" class="text-2xl font-black text-amber-600">-</span>
                            <span class="text-xs text-amber-500 font-medium">gram</span>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <span class="block text-xs font-bold text-cyan-500 uppercase tracking-wider">Target Karbohidrat</span>
                        <div class="flex items-baseline gap-1 mt-2">
                            <span id="targetCarbs" class="text-2xl font-black text-cyan-600">-</span>
                            <span class="text-xs text-cyan-500 font-medium">gram</span>
                        </div>
                    </div>
                </div>

                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    Menu Teratas Rekomendasi AI
                </h2>
                <div id="aiRecommendationGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-6"></div>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-8 mt-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Semua Pilihan Menu Nusantara</h2>
                
                <div class="flex flex-wrap items-center gap-3">
                    <div class="relative min-w-[240px]">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" id="searchInput" placeholder="Cari nama menu makanan..." 
                               class="w-full bg-white border border-gray-200 rounded-xl pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition shadow-sm">
                    </div>

                    <select id="sortInput" class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-emerald-500 shadow-sm font-medium text-gray-700">
                        <option value="">Urutkan Katalog</option>
                        <option value="az">Nama: A ke Z</option>
                        <option value="za">Nama: Z ke A</option>
                        <option value="cal_low">Kalori: Terendah</option>
                        <option value="cal_high">Kalori: Tertinggi</option>
                    </select>
                </div>
            </div>

            <div id="allMenusGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"></div>
            <div id="paginationContainer" class="mt-8 flex justify-center"></div>
        </div>

    </div>
</div>

<div id="toastContainer" class="fixed top-5 right-5 z-50 flex flex-col gap-3 pointer-events-none"></div>
@endsection

@section('script')
<script>
    let currentSearch = '';
    let currentSort = '';
    let currentPage = 1;
    let physicalData = null; 

    // Gambar cadangan global resolusi tinggi dari Unsplash jika gambar asli error/kosong
    const fallbackImage = 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80';

    document.addEventListener('DOMContentLoaded', function() {
        // Event listener untuk pengurutan data
        const sortInput = document.getElementById('sortInput');
        if(sortInput) {
            sortInput.addEventListener('change', function(e) {
                currentSort = e.target.value; 
                currentPage = 1; 
                fetchKatalogData();
            });
        }

        // Event listener untuk pencarian dengan teknik Debounce (menunda ketikan 400ms)
        const searchInput = document.getElementById('searchInput');
        if(searchInput) {
            let debounceTimer;
            searchInput.addEventListener('input', function(e) {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => { 
                    currentSearch = e.target.value; 
                    currentPage = 1; 
                    fetchKatalogData(); 
                }, 400);
            });
        }

        // Panggil muatan awal saat halaman dibuka pertama kali
        fetchKatalogData();
    });

    // Fungsi pemicu tombol Analisis Gizi
    function jalankanAnalisis() {
        let inputW = document.getElementById('inputWeight').value;
        let inputH = document.getElementById('inputHeight').value;
        let inputA = document.getElementById('inputAge').value;

        // Validasi kustom Tailwind Toast, bukan alert bawaan windows
        if (!inputW || !inputH || !inputA) {
            showCustomAlert("Mohon lengkapi form Berat Badan, Tinggi Badan, dan Umur terlebih dahulu!");
            return;
        }

        physicalData = {
            weight: inputW,
            height: inputH,
            age: inputA,
            gender: document.getElementById('inputGender').value || 'male',
            activity: document.getElementById('inputActivity').value || 'moderate'
        };

        currentPage = 1;
        fetchKatalogData();
    }

    // Fungsi notifikasi melayang kustom mewah
    function showCustomAlert(message) {
        const container = document.getElementById('toastContainer');
        if(!container) return;
        
        const alertBox = document.createElement('div');
        alertBox.className = "bg-white border-l-4 border-red-500 text-gray-800 px-5 py-4 rounded-xl shadow-2xl font-semibold flex items-center gap-3 transform transition-all duration-300 translate-x-full opacity-0 pointer-events-auto max-w-md border border-gray-100";
        
        alertBox.innerHTML = `
            <div class="p-1.5 bg-red-100 text-red-600 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <span class="text-xs md:text-sm leading-relaxed">${message}</span>
        `;
        
        container.appendChild(alertBox);
        
        // Animasi Sliding masuk ke layar
        setTimeout(() => { alertBox.classList.remove('translate-x-full', 'opacity-0'); }, 10);
        
        // Menghilang otomatis setelah 3.5 detik
        setTimeout(() => {
            alertBox.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => alertBox.remove(), 300);
        }, 3500);
    }

    // Fungsi utama mengambil data gizi & katalog menu dari backend API Laravel
    function fetchKatalogData() {
        let url = `/api/user/menus?page=${currentPage}&search=${currentSearch}&sort=${currentSort}`;
        
        if (physicalData) {
            url += `&weight=${physicalData.weight}&height=${physicalData.height}&age=${physicalData.age}&gender=${physicalData.gender}&activity=${physicalData.activity}`;
        }

        const badge = document.getElementById('userStatsBadge');
        if(badge) badge.innerHTML = `<span class="bg-blue-50 text-blue-700 px-3 py-1.5 rounded-xl font-bold animate-pulse flex items-center gap-1">⚙️ Memproses AI...</span>`;
        
        let localToken = localStorage.getItem('jwt_token') || localStorage.getItem('token');

        fetch(url, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localToken, 
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(async res => {
            if (!res.ok) {
                const errText = await res.text();
                throw new Error(`Gagal memuat data dari server.`);
            }
            return res.json();
        })
        .then(res => {
            if (res.message && res.message === "Unauthenticated.") {
                throw new Error("Sesi login Anda habis. Silakan login kembali.");
            }

            // Atur tampilan badge status fisik di kanan atas halaman
            if (res.user_stats) {
                if(badge) {
                    badge.innerHTML = `
                        <span class="bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-xl border border-emerald-100 shadow-sm font-bold">Umur: ${res.user_stats.age} thn</span>
                        <span class="bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-xl border border-emerald-100 shadow-sm font-bold">BB: ${res.user_stats.weight} kg</span>
                        <span class="bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-xl border border-emerald-100 shadow-sm font-bold">TB: ${res.user_stats.height} cm</span>
                    `;
                }
            } else {
                 if(badge) badge.innerHTML = `<span class="bg-gray-100 text-gray-400 px-3 py-1.5 rounded-xl font-semibold">Data fisik belum diisi</span>`;
            }

            // Jalankan fungsi cetak data ke UI kustom
            renderAIRecommendations(res.ai_analysis, res.recommendations);
            renderAllMenus(res.all_menus ? res.all_menus.data : res.data);
            renderPagination(res.all_menus);
        })
        .catch(err => {
            console.error(err);
            if(badge) badge.innerHTML = `<span class="bg-red-100 text-red-700 px-3 py-1.5 rounded-xl font-bold">Sistem Bermasalah</span>`;
            showCustomAlert(err.message);
        });
    }

    // Fungsi cetak kartu menu rekomendasi AI teratas
    function renderAIRecommendations(analysis, recommendations) {
        const aiSection = document.getElementById('aiSection');
        const resultsContainer = document.getElementById('aiResultsContainer');
        
        if (!analysis || !aiSection || !resultsContainer) return;
        
        aiSection.classList.remove('hidden');
        document.getElementById('clusterStatement').innerText = analysis.message || "Rekomendasi berhasil dimuat.";
        
        if (!recommendations || recommendations.length === 0) {
            resultsContainer.classList.add('hidden'); 
            return;
        }

        resultsContainer.classList.remove('hidden');

        if(analysis.target_nutrisi) {
            document.getElementById('targetCalories').innerText = analysis.target_nutrisi.calories;
            document.getElementById('targetProtein').innerText = analysis.target_nutrisi.protein;
            document.getElementById('targetFat').innerText = analysis.target_nutrisi.fat;
            document.getElementById('targetCarbs').innerText = analysis.target_nutrisi.carbohydrates;
        }

        const grid = document.getElementById('aiRecommendationGrid');
        grid.innerHTML = '';

        recommendations.forEach((menu, index) => {
            let rankStyle = index === 0 ? 'bg-amber-500 text-white font-black' : 'bg-emerald-600 text-white font-bold';
            
            grid.innerHTML += `
                <div class="bg-white rounded-2xl border border-emerald-200 shadow-md overflow-hidden relative flex flex-col hover:scale-[1.02] transition duration-300 group">
                    <div class="absolute top-2 left-2 z-10 text-[10px] uppercase px-2 py-1 rounded-lg shadow-sm ${rankStyle}">
                        Rank #${index + 1}
                    </div>
                    <div class="absolute top-2 right-2 z-10 text-[10px] font-black bg-white/95 text-emerald-700 px-2 py-1 rounded-lg border border-emerald-100 shadow-sm">
                        Cocok: ${menu.match_score}%
                    </div>
                    <div class="h-36 w-full bg-gray-100 overflow-hidden relative">
                        <img src="${menu.image_url || fallbackImage}" 
                             onerror="this.onerror=null; this.src='${fallbackImage}';" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    </div>
                    <div class="p-3.5 flex-1 flex flex-col justify-between">
                        <h4 class="font-bold text-gray-800 text-sm line-clamp-2 leading-tight">${menu.name}</h4>
                        <div class="flex items-center gap-1.5 mt-3 text-xs font-bold text-emerald-600 bg-emerald-50/50 px-2.5 py-1.5 rounded-xl w-max">
                            <span>🔥</span> <span>${menu.calories} kcal</span>
                        </div>
                    </div>
                </div>
            `;
        });
    }

    // Fungsi cetak semua pilihan menu di database reguler
    function renderAllMenus(menus) {
        const grid = document.getElementById('allMenusGrid');
        if(!grid) return;
        grid.innerHTML = '';
        
        if (!menus || menus.length === 0) {
            grid.innerHTML = `<div class="col-span-full text-center py-12 text-gray-400 font-medium">Menu makanan tidak ditemukan dalam database.</div>`;
            return;
        }

        menus.forEach(menu => {
            grid.innerHTML += `
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition duration-200 group">
                    <div class="h-44 w-full bg-gray-100 overflow-hidden relative">
                        <img src="${menu.image_url || fallbackImage}" 
                             onerror="this.onerror=null; this.src='${fallbackImage}';" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900 text-base line-clamp-1 group-hover:text-emerald-600 transition">${menu.name}</h3>
                            <div class="grid grid-cols-2 gap-2 mt-4 text-xs font-semibold text-gray-600">
                                <div class="bg-gray-50 px-2.5 py-2 rounded-xl flex items-center gap-1.5">
                                    <span>🔥</span> <span><b>${menu.calories}</b> kcal</span>
                                </div>
                                <div class="bg-emerald-50 text-emerald-700 px-2.5 py-2 rounded-xl flex items-center gap-1.5">
                                    <span>🥩</span> <span>Prot: <b>${menu.protein}g</b></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
    }

    // Fungsi pembentuk tombol halaman (Pagination) otomatis dari database
    function renderPagination(paginationData) {
        const container = document.getElementById('paginationContainer');
        if (!container) return;
        container.innerHTML = '';
        if (!paginationData || paginationData.last_page <= 1) return;

        let links = paginationData.links;
        let navHtml = `<nav class="relative z-0 inline-flex rounded-xl shadow-sm -space-x-px bg-white border border-gray-200 p-1">`;

        links.forEach(link => {
            let activeClass = link.active ? 'bg-emerald-600 text-white rounded-lg font-bold' : 'text-gray-500 hover:bg-gray-50 rounded-lg';
            let disabledClass = !link.url ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer';
            let pageNum = 1;
            if (link.url) pageNum = new URLSearchParams(link.url.split('?')[1]).get('page') || 1;

            navHtml += `<button onclick="${link.url ? `goToPage(${pageNum})` : ''}" class="relative inline-flex items-center px-3.5 py-2 text-xs font-bold transition ${activeClass} ${disabledClass}" ${!link.url ? 'disabled' : ''}>${link.label}</button>`;
        });
        container.innerHTML = navHtml + `</nav>`;
    }

    // Fungsi perpindahan halaman katalog tanpa hilangkan data analisis AI harian
    function goToPage(page) {
        currentPage = page;
        fetchKatalogData();
        const grid = document.getElementById('allMenusGrid');
        if(grid) window.scrollTo({ top: grid.offsetTop - 120, behavior: 'smooth' });
    }
</script>