@extends('layouts.user')

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Katalog Menu & Gizi</h2>
        <p class="text-gray-500 text-sm mt-1">Eksplorasi nilai nutrisi dari makanan yang disajikan oleh GIZENIA.</p>
    </div>

    <div
        class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-3xl p-8 text-white shadow-lg shadow-emerald-600/20 mb-10 flex flex-col md:flex-row items-center justify-between relative overflow-hidden">
        <svg class="absolute -right-10 -bottom-10 w-64 h-64 text-white opacity-10 transform -rotate-12" fill="currentColor"
            viewBox="0 0 24 24">
            <path d="M12 2L1 21h22L12 2zm0 3.83L19.5 19h-15L12 5.83z" />
        </svg>

        <div class="md:w-2/3 z-10">
            <span
                class="inline-flex items-center bg-white/20 backdrop-blur-sm text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wide mb-4 border border-white/30">
                <svg class="w-3 h-3 mr-1.5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z" />
                </svg>
                Analisis Gizi AI
            </span>
            <h3 class="text-2xl font-bold leading-tight mb-3">Rekomendasi Menu Pribadi Anda</h3>
            <p class="text-emerald-50 text-sm leading-relaxed mb-4">
                Berdasarkan profil fisik Anda (Umur: <span id="aiAge" class="font-bold text-white">0</span> thn, BB: <span
                    id="aiWeight" class="font-bold text-white">0</span> kg, TB: <span id="aiHeight"
                    class="font-bold text-white">0</span> cm),
                kebutuhan kalori harian Anda diperkirakan <span id="aiTdee" class="font-bold text-yellow-300">0 kkal</span>.
                Berikut adalah menu yang paling cocok untuk memenuhi target <span id="aiTarget"
                    class="font-bold text-yellow-300">0 kkal</span> per porsi makan utama Anda.
            </p>
        </div>
    </div>

    <h3 class="text-lg font-bold text-gray-900 mb-4 px-1 flex items-center">
        <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
            </path>
        </svg>
        Sangat Disarankan Untuk Anda
    </h3>

    <div class="relative mb-12">
        <button id="recPrev" class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow p-3 rounded-full">
            &#9664;
        </button>

        <div id="recommendationGrid" class="flex gap-5 overflow-x-auto scroll-smooth px-12">
            <div class="text-gray-400">Menghitung rekomendasi AI...</div>
        </div>

        <button id="recNext" class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow p-3 rounded-full">
            &#9654;
        </button>
    </div>

    <h3 class="text-lg font-bold text-gray-900 mb-4 px-1">Katalog Semua Menu</h3>
    <div class="flex flex-col md:flex-row gap-3 mb-4">

        <!-- search -->
        <input type="text" id="searchInput" placeholder="Cari menu..."
            class="border rounded-xl px-3 py-2 text-sm w-full md:w-1/3">

        <!-- filter -->
        <select id="sortSelect" class="border rounded-xl px-3 py-2 text-sm">
            <option value="">Urutan Default</option>
            <option value="az">A - Z</option>
            <option value="za">Z - A</option>
            <option value="cal_low">Kalori Terendah</option>
            <option value="cal_high">Kalori Tertinggi</option>
        </select>

        <button onclick="applyFilter()" class="bg-emerald-600 text-white px-4 rounded-xl text-sm">
            Terapkan
        </button>
    </div>

    function createMenuCard(menu, isRecommended = false) {
    // 1. Ambil URL gambar
    const imgUrl = menu.image || menu.image_url || 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80';
    
    // 2. Deklarasi string kosong untuk badge
    let badge = '';
    
    // 3. Desain badge akurasi (Estetik & Presisi)
    if (isRecommended) {
        const akurasi = menu.match_score || menu.tingkat_akurasi || 0; 
        
        badge = `
        <div class="absolute top-3 left-3 z-20">
            <span class="inline-flex items-center gap-1.5 bg-white/90 backdrop-blur-md text-emerald-700 text-[10px] font-extrabold px-3 py-1.5 rounded-full shadow-sm border border-emerald-100 uppercase tracking-wide">
                <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                Cocok ${akurasi}%
            </span>
        </div>`;
    }
    
    // 4. Pengaturan border kartu (Lebih menonjol jika direkomendasikan)
    const borderClass = isRecommended 
        ? 'border-emerald-300 shadow-emerald-100 ring-4 ring-emerald-50' 
        : 'border-gray-100 shadow-sm';

    // 5. Kembalikan HTML
    return `
    <div class="bg-white rounded-2xl border ${borderClass} overflow-hidden flex flex-col hover:shadow-lg transition-all duration-300 group relative">
        <div class="relative h-44 bg-gray-100 overflow-hidden">
            ${badge}
            <img src="${imgUrl}" onerror="this.src='https://images.unsplash.com/photo-1490645935967-10de6ba17061?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
            
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
            
            <h3 class="absolute bottom-4 left-4 right-4 text-white font-bold text-lg leading-tight truncate shadow-sm">${menu.name}</h3>
        </div>
        
        <div class="p-5 flex-1 flex flex-col">
            <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-100">
                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md uppercase tracking-wider">${menu.category || 'UMUM'}</span>
                <span class="text-xs font-semibold text-gray-400 bg-gray-50 px-2 py-1 rounded-md">${menu.serving_size_g || 100}g / Porsi</span>
            </div>
            
            <div class="flex justify-between text-center items-center mt-auto">
                <div class="flex-1">
                    <p class="text-[10px] text-gray-400 font-medium mb-1 uppercase tracking-wider">Kalori</p>
                    <p class="font-bold text-emerald-600 text-sm">${menu.calories}</p>
                </div>
                <div class="w-px h-8 bg-gray-200"></div>
                <div class="flex-1">
                    <p class="text-[10px] text-gray-400 font-medium mb-1 uppercase tracking-wider">Protein</p>
                    <p class="font-bold text-gray-800 text-sm">${menu.protein}g</p>
                </div>
                <div class="w-px h-8 bg-gray-200"></div>
                <div class="flex-1">
                    <p class="text-[10px] text-gray-400 font-medium mb-1 uppercase tracking-wider">Lemak</p>
                    <p class="font-bold text-gray-800 text-sm">${menu.fat || 0}g</p>
                </div>
            </div>
        </div>
    </div>`;
}

    <div id="allMenuGrid" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-5 mb-6">
        <div class="col-span-full py-6 text-center text-gray-400">Memuat katalog menu...</div>
    </div>

    <div class="flex items-center justify-between mb-12 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
        <span id="paginationInfo" class="text-xs text-gray-500 font-medium">Menghitung...</span>
        <div id="paginationControls" class="flex space-x-2"></div>
    </div>

    <script>
        const token = localStorage.getItem('jwt_token');
        if (!token) window.location.href = '/login';

        function createMenuCard(menu, isRecommended = false) {
            // PERBAIKAN: Membaca menu.image (dari AI) atau menu.image_url (dari Laravel)
            const imgUrl = menu.image || menu.image_url || 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80';

            const badge = isRecommended
                ? `<span class="absolute top-3 left-3 bg-yellow-400 text-yellow-900 text-[9px] font-bold px-2 py-1 rounded shadow-sm uppercase z-10"><i class="fas fa-star mr-1"></i> Cocok</span>`
                : '';

            const borderClass = isRecommended ? 'border-emerald-200 shadow-emerald-100' : 'border-gray-100 shadow-sm';

            return `
                    <div class="bg-white rounded-2xl border ${borderClass} overflow-hidden flex flex-col hover:shadow-md transition-all group">
                        <div class="relative h-40 bg-gray-800">
                            ${badge}
                            <img src="${imgUrl}" onerror="this.src='https://images.unsplash.com/photo-1490645935967-10de6ba17061?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'" class="w-full h-full object-cover opacity-90 group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent"></div>
                            <h3 class="absolute bottom-4 left-4 text-white font-bold text-base leading-tight pr-4">${menu.name}</h3>
                        </div>
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-50">
                                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded uppercase">${menu.category || 'UMUM'}</span>
                                <span class="text-xs font-bold text-gray-500">${menu.serving_size_g || 100}g / Porsi</span>
                            </div>
                            <div class="flex justify-between text-center mb-2">
                                <div><p class="text-[10px] text-gray-400 font-medium mb-0.5">Kalori</p><p class="font-bold text-emerald-600 text-sm">${menu.calories}</p></div>
                                <div class="w-px bg-gray-100"></div>
                                <div><p class="text-[10px] text-gray-400 font-medium mb-0.5">Protein</p><p class="font-bold text-gray-900 text-sm">${menu.protein}g</p></div>
                                <div class="w-px bg-gray-100"></div>
                                <div><p class="text-[10px] text-gray-400 font-medium mb-0.5">Lemak</p><p class="font-bold text-gray-900 text-sm">${menu.fat || 0}g</p></div>
                            </div>
                        </div>
                    </div>`;
        }

        async function loadMenus(page = 1) {
            try {
                // ambil value filter
                const searchInput = document.getElementById('searchInput');
                const sortSelect = document.getElementById('sortSelect');

                const search = searchInput && searchInput.value ? searchInput.value : '';
                const sort = sortSelect && sortSelect.value ? sortSelect.value : '';

                // request ke API + filter
                const res = await fetch(`/api/user/menus?page=${page}&search=${search}&sort=${sort}`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                const data = await res.json();

                // ================= AI RECOMMENDATION =================
                if (page === 1) {
                    document.getElementById('aiAge').innerText = data.user_stats.age;
                    document.getElementById('aiWeight').innerText = data.user_stats.weight;
                    document.getElementById('aiHeight').innerText = data.user_stats.height;
                    document.getElementById('aiTdee').innerText = data.user_stats.tdee.toLocaleString() + ' kkal';
                    document.getElementById('aiTarget').innerText = data.user_stats.target_meal.toLocaleString() + ' kkal';

                    const recContainer = document.getElementById('recommendationGrid');
                    recContainer.innerHTML = '';

                    if (data.recommendations && data.recommendations.length > 0) {
                        let loopData = [...data.recommendations, ...data.recommendations, ...data.recommendations];

                        loopData.forEach(menu => {
                            recContainer.insertAdjacentHTML('beforeend', createMenuCard(menu, true));
                        });

                        // posisi awal di tengah biar bisa geser kiri kanan
                        requestAnimationFrame(() => {
                            recContainer.scrollLeft = recContainer.scrollWidth / 3;
                        });
                    } else {
                        recContainer.innerHTML = '<div class="col-span-full py-6 text-gray-400 text-sm">Belum ada rekomendasi dari AI.</div>';
                    }
                }

                // ================= ALL MENU =================
                const allContainer = document.getElementById('allMenuGrid');
                const menuData = data.all_menus.data;

                allContainer.innerHTML = '';

                if (menuData.length > 0) {
                    menuData.forEach(menu => {
                        allContainer.insertAdjacentHTML('beforeend', createMenuCard(menu, false));
                    });
                } else {
                    allContainer.innerHTML = '<div class="col-span-full py-6 text-center text-gray-400 text-sm">Katalog menu masih kosong.</div>';
                }

                // ================= PAGINATION =================
                const meta = data.all_menus;

                document.getElementById('paginationInfo').innerText =
                    `Menampilkan ${meta.from || 0} - ${meta.to || 0} dari total ${meta.total} menu`;

                const controls = document.getElementById('paginationControls');
                controls.innerHTML = '';

                if (meta.current_page > 1) {
                    controls.innerHTML += `
                            <button onclick="loadMenus(${meta.current_page - 1})"
                                class="px-4 py-2 bg-gray-50 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-100 text-xs font-bold">
                                Sebelumnya
                            </button>`;
                }

                controls.innerHTML += `
                        <span class="px-4 py-2 text-emerald-700 text-sm font-black">
                            ${meta.current_page}
                            <span class="text-gray-400 text-xs mx-1">dari</span>
                            ${meta.last_page}
                        </span>
                    `;

                if (meta.current_page < meta.last_page) {
                    controls.innerHTML += `
                            <button onclick="loadMenus(${meta.current_page + 1})"
                                class="px-4 py-2 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl hover:bg-emerald-100 text-xs font-bold">
                                Selanjutnya
                            </button>`;
                }

            } catch (e) {
                console.error("Gagal memuat katalog", e);

                if (page === 1) {
                    document.getElementById('recommendationGrid').innerHTML =
                        '<div class="col-span-full text-red-500 text-sm">Gagal terhubung ke server.</div>';
                }
            }
        }

        function applyFilter() {
            loadMenus(1);
        }

        document.addEventListener("DOMContentLoaded", function () {
    loadMenus(1);

    const recWrapper = document.getElementById("recommendationGrid");
    const itemWidth = 320;

    document.getElementById("recNext").onclick = () => {
        recWrapper.scrollBy({ left: itemWidth, behavior: "smooth" });
    };

    document.getElementById("recPrev").onclick = () => {
        recWrapper.scrollBy({ left: -itemWidth, behavior: "smooth" });
    };

    let isAdjusting = false;

    recWrapper.addEventListener('scroll', () => {
        if (isAdjusting) return;

        const totalWidth = recWrapper.scrollWidth;
        const visibleWidth = recWrapper.clientWidth;

        // ambil 1/3 karena kamu triple data
        const oneSetWidth = totalWidth / 3;

        // ke kanan terlalu jauh
        if (recWrapper.scrollLeft >= oneSetWidth * 2) {
            isAdjusting = true;
            recWrapper.scrollLeft -= oneSetWidth;
            setTimeout(() => isAdjusting = false, 50);
        }

        // ke kiri terlalu jauh
        if (recWrapper.scrollLeft <= 0) {
            isAdjusting = true;
            recWrapper.scrollLeft += oneSetWidth;
            setTimeout(() => isAdjusting = false, 50);
        }
    });
});
    </script>

    <style>
        #recommendationGrid {
            scroll-snap-type: x mandatory;
        }

        #recommendationGrid>div {
            min-width: 300px;
            /* card BESAR */
            max-width: 300px;
            flex-shrink: 0;
            scroll-snap-align: start;
        }

        #recommendationGrid::-webkit-scrollbar {
            display: none;
        }

        /* efek hover halus */
        #recommendationGrid>div:hover {
            transform: translateY(-4px);
            transition: 0.2s;
        }
    </style>
@endsection