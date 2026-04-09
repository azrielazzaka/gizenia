@extends('layouts.user')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-900">Katalog Menu & Gizi</h2>
    <p class="text-gray-500 text-sm mt-1">Eksplorasi nilai nutrisi dari makanan yang disajikan oleh GIZENIA.</p>
</div>

<div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-3xl p-8 text-white shadow-lg shadow-emerald-600/20 mb-10 flex flex-col md:flex-row items-center justify-between relative overflow-hidden">
    <svg class="absolute -right-10 -bottom-10 w-64 h-64 text-white opacity-10 transform -rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2zm0 3.83L19.5 19h-15L12 5.83z"/></svg>

    <div class="md:w-2/3 z-10">
        <span class="inline-flex items-center bg-white/20 backdrop-blur-sm text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wide mb-4 border border-white/30">
            <svg class="w-3 h-3 mr-1.5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"/></svg>
            Analisis Gizi AI
        </span>
        <h3 class="text-2xl font-bold leading-tight mb-3">Rekomendasi Menu Pribadi Anda</h3>
        <p class="text-emerald-50 text-sm leading-relaxed mb-4">
            Berdasarkan profil fisik Anda (Umur: <span id="aiAge" class="font-bold text-white">0</span> thn, BB: <span id="aiWeight" class="font-bold text-white">0</span> kg, TB: <span id="aiHeight" class="font-bold text-white">0</span> cm), 
            kebutuhan kalori harian Anda diperkirakan <span id="aiTdee" class="font-bold text-yellow-300">0 kkal</span>. 
            Berikut adalah menu yang paling cocok untuk memenuhi target <span id="aiTarget" class="font-bold text-yellow-300">0 kkal</span> per porsi makan utama Anda.
        </p>
    </div>
</div>

<h3 class="text-lg font-bold text-gray-900 mb-4 px-1 flex items-center">
    <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
    Sangat Disarankan Untuk Anda
</h3>
<div id="recommendationGrid" class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-12">
    <div class="col-span-full py-6 text-center text-gray-400">Menghitung rekomendasi AI...</div>
</div>

<h3 class="text-lg font-bold text-gray-900 mb-4 px-1">Katalog Semua Menu</h3>
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
        const imgUrl = menu.image_url || 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80';
        const badge = isRecommended 
            ? `<span class="absolute top-3 left-3 bg-yellow-400 text-yellow-900 text-[9px] font-bold px-2 py-1 rounded shadow-sm uppercase z-10"><i class="fas fa-star mr-1"></i> Cocok</span>` 
            : '';
        
        const borderClass = isRecommended ? 'border-emerald-200 shadow-emerald-100' : 'border-gray-100 shadow-sm';

        return `
        <div class="bg-white rounded-2xl border ${borderClass} overflow-hidden flex flex-col hover:shadow-md transition-all group">
            <div class="relative h-40 bg-gray-800">
                ${badge}
                <img src="${imgUrl}" class="w-full h-full object-cover opacity-90 group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent"></div>
                <h3 class="absolute bottom-4 left-4 text-white font-bold text-base leading-tight pr-4">${menu.name}</h3>
            </div>
            <div class="p-5 flex-1 flex flex-col">
                <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-50">
                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded uppercase">${menu.category || 'UMUM'}</span>
                    <span class="text-xs font-bold text-gray-500">${menu.serving_size_g}g / Porsi</span>
                </div>
                <div class="flex justify-between text-center mb-2">
                    <div><p class="text-[10px] text-gray-400 font-medium mb-0.5">Kalori</p><p class="font-bold text-emerald-600 text-sm">${menu.calories}</p></div>
                    <div class="w-px bg-gray-100"></div>
                    <div><p class="text-[10px] text-gray-400 font-medium mb-0.5">Protein</p><p class="font-bold text-gray-900 text-sm">${menu.protein}g</p></div>
                    <div class="w-px bg-gray-100"></div>
                    <div><p class="text-[10px] text-gray-400 font-medium mb-0.5">Lemak</p><p class="font-bold text-gray-900 text-sm">${menu.fat}g</p></div>
                </div>
            </div>
        </div>`;
    }

    async function loadMenus(page = 1) {
        try {
            // Fetch dengan parameter halaman (page)
            const res = await fetch(`/api/user/menus?page=${page}`, { headers: { 'Authorization': `Bearer ${token}` }});
            const data = await res.json();
            
            // 1. Update AI Banner Stats (Hanya render jika ada, untuk menghindari kedip saat pindah halaman)
            if (page === 1) {
                document.getElementById('aiAge').innerText = data.user_stats.age;
                document.getElementById('aiWeight').innerText = data.user_stats.weight;
                document.getElementById('aiHeight').innerText = data.user_stats.height;
                document.getElementById('aiTdee').innerText = data.user_stats.tdee.toLocaleString() + ' kkal';
                document.getElementById('aiTarget').innerText = data.user_stats.target_meal.toLocaleString() + ' kkal';

                // 2. Render Recommended Menus (Rekomendasi tidak ikut dipaginasi)
                const recContainer = document.getElementById('recommendationGrid');
                recContainer.innerHTML = '';
                if(data.recommendations.length > 0) {
                    data.recommendations.forEach(menu => recContainer.insertAdjacentHTML('beforeend', createMenuCard(menu, true)));
                } else {
                    recContainer.innerHTML = '<div class="col-span-full py-6 text-gray-400 text-sm">Belum ada menu di database.</div>';
                }
            }

            // 3. Render Katalog Semua Menu (Dipaginasi)
            const allContainer = document.getElementById('allMenuGrid');
            const menuData = data.all_menus.data; // Data Array dari paginator Laravel
            allContainer.innerHTML = '';
            
            if(menuData.length > 0) {
                menuData.forEach(menu => allContainer.insertAdjacentHTML('beforeend', createMenuCard(menu, false)));
            } else {
                allContainer.innerHTML = '<div class="col-span-full py-6 text-center text-gray-400 text-sm">Katalog menu masih kosong.</div>';
            }

            // 4. Render Tombol Paginasi
            const meta = data.all_menus;
            document.getElementById('paginationInfo').innerText = `Menampilkan ${meta.from || 0} - ${meta.to || 0} dari total ${meta.total} menu`;
            
            const controls = document.getElementById('paginationControls');
            controls.innerHTML = '';

            // Tombol "Sebelumnya"
            if (meta.current_page > 1) {
                controls.innerHTML += `<button onclick="loadMenus(${meta.current_page - 1})" class="px-4 py-2 bg-gray-50 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-100 text-xs font-bold transition-colors shadow-sm">Sebelumnya</button>`;
            }
            
            // Indikator Halaman
            controls.innerHTML += `<span class="px-4 py-2 text-emerald-700 text-sm font-black">${meta.current_page} <span class="text-gray-400 text-xs font-medium mx-1">dari</span> ${meta.last_page}</span>`;
            
            // Tombol "Selanjutnya"
            if (meta.current_page < meta.last_page) {
                controls.innerHTML += `<button onclick="loadMenus(${meta.current_page + 1})" class="px-4 py-2 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl hover:bg-emerald-100 text-xs font-bold transition-colors shadow-sm">Selanjutnya</button>`;
            }

        } catch (e) {
            console.error("Gagal memuat katalog", e);
            if(page === 1) document.getElementById('recommendationGrid').innerHTML = '<div class="col-span-full text-red-500 text-sm">Gagal terhubung ke server.</div>';
        }
    }

    // Panggil halaman pertama saat awal dimuat
    loadMenus(1);
</script>
@endsection