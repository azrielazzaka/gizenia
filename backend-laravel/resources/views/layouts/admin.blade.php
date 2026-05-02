<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lumina AI - Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-[#F8F9FA] text-gray-800 flex h-screen overflow-hidden">

    <aside class="w-64 bg-[#F4F6F8] border-r border-gray-200 h-full flex flex-col justify-between hidden md:flex">
    <div>
        <div class="h-20 flex items-center px-6">
            <div class="bg-emerald-600 p-1.5 rounded-lg mr-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            </div>
            <div>
                <h1 class="text-lg font-bold text-gray-900 leading-tight">GIZENIA</h1>
                <p class="text-[10px] text-gray-500 uppercase tracking-wider">Makanan Bergizi</p>
            </div>
        </div>

        <nav class="px-4 py-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center px-4 py-3 {{ Request::is('admin/dashboard') ? 'bg-green-100/50 text-emerald-700' : 'text-gray-600 hover:bg-gray-100' }} rounded-xl font-medium text-sm transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>

            <a href="{{ route('admin.pengguna') }}" 
               class="flex items-center px-4 py-3 {{ Request::is('admin/pengguna') ? 'bg-green-100/50 text-emerald-700' : 'text-gray-600 hover:bg-gray-100' }} rounded-xl font-medium text-sm transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Manajemen Pengguna
            </a>

            <a href="{{ route('admin.menu') }}" 
               class="flex items-center px-4 py-3 {{ Request::is('admin/menu') ? 'bg-green-100/50 text-emerald-700' : 'text-gray-600 hover:bg-gray-100' }} rounded-xl font-medium text-sm transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                Menu Data
            </a>

            <a href="{{ route('admin.distribusi') }}" 
               class="flex items-center px-4 py-3 {{ Request::is('admin/distribusi') ? 'bg-green-100/50 text-emerald-700' : 'text-gray-600 hover:bg-gray-100' }} rounded-xl font-medium text-sm transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Distribusi Makanan
            </a>

            <a href="{{ route('admin.laporan') }}" 
               class="flex items-center px-4 py-3 {{ Request::is('admin/laporan') ? 'bg-green-100/50 text-emerald-700' : 'text-gray-600 hover:bg-gray-100' }} rounded-xl font-medium text-sm transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Laporan & Analitik
            </a>

            <a href="{{ route('admin.api') }}" 
               class="flex items-center px-4 py-3 {{ Request::is('admin/api-docs') ? 'bg-green-100/50 text-emerald-700' : 'text-gray-600 hover:bg-gray-100' }} rounded-xl font-medium text-sm transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                Dokumentasi API
            </a>
        </nav>
    </div>

    <div class="px-4 py-6 space-y-4">
        <a href="{{ route('login') }}" class="flex items-center px-4 py-2 text-sm text-gray-500 hover:text-red-600 transition-colors" onclick="localStorage.clear()">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg> 
            Keluar
        </a>
    </div>
</aside>

    <div class="flex-1 flex flex-col h-full overflow-hidden">
        <header class="h-20 flex items-center justify-between px-8 bg-[#F8F9FA]">
            <div class="relative w-96" id="searchContainer">
                <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" id="globalSearchInput" onfocus="showSearchDropdown()" oninput="filterSearch(this.value)" placeholder="Cari halaman, fitur, menu..." class="w-full bg-[#Eef0f2] border-none rounded-full pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 shadow-inner transition-all">
                
                <div id="searchDropdown" class="hidden absolute left-0 top-full mt-2 w-full bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50 transform opacity-100 transition-all duration-200">
                    <div class="px-4 py-2.5 bg-gray-50/80 border-b border-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                        Rekomendasi Halaman
                    </div>
                    <ul id="searchResults" class="max-h-72 overflow-y-auto py-1">
                        </ul>
                </div>
            </div>

            <div class="flex items-center space-x-5 relative">

    <!-- ⚙️ SETTING (SVG LAMA) -->
    <button onclick="toggleSetting()" class="p-2 text-gray-400 hover:text-gray-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0
                a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37
                a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35
                a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37
                a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0
                a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37
                a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35
                a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37
                .996.608 2.296.07 2.572-1.065z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
    </button>

    <!-- 🔽 DROPDOWN SETTING -->
<div id="settingMenu"
    class="hidden absolute right-full mr-3 top-12 w-48
           bg-white border border-gray-200
           rounded-xl shadow-lg overflow-hidden z-50">
           
        <!-- DARK MODE -->
<div class="flex items-center justify-between px-4 py-2.5">
    <div class="flex items-center text-sm text-gray-700">
        <i class="fas fa-moon w-5 text-gray-400 mr-2"></i>
        <span data-lang="dark_mode">Mode Gelap</span>
    </div>

    <label class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" id="darkToggle" class="sr-only peer" onchange="toggleDarkMode()">

        <div class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-emerald-500 transition-all"></div>

        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-all 
                    peer-checked:translate-x-5"></div>
    </label>
</div>

<!-- BAHASA -->
<div class="px-4 py-2.5">
    <div class="flex items-center text-sm text-gray-700">
        <i class="fas fa-globe w-5 text-gray-400 mr-2"></i>
        <span id="langText">Bahasa Indonesia</span>
    </div>
</div>
    </div>

    <div class="h-8 w-px bg-gray-200 mx-2"></div>

    <!-- USER -->
    <div class="flex items-center space-x-3 cursor-pointer">
        <div class="text-right hidden md:block">
            <p class="text-sm font-bold text-gray-800">Pengguna Admin</p>
            <p class="text-[11px] text-gray-500 uppercase">Manajer Sistem</p>
        </div>
        <div class="h-10 w-10 rounded-full bg-slate-800 flex items-center justify-center text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
    </div>
</div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto p-8">
            @yield('content')
        </main>
    </div>
    <script>
const searchPages = [
    {
        title: 'Dashboard',
        desc: 'Ringkasan sistem dan statistik',
        url: '/admin/dashboard',
        icon: '📊'
    },
    {
        title: 'Manajemen Pengguna',
        desc: 'Kelola data pengguna',
        url: '/admin/pengguna',
        icon: '👥'
    },
    {
        title: 'Menu Data',
        desc: 'Kelola menu dan nutrisi',
        url: '/admin/menu',
        icon: '🍽️'
    },
    {
        title: 'Distribusi Makanan',
        desc: 'Riwayat dan proses distribusi',
        url: '/admin/distribusi',
        icon: '🚚'
    },
    {
        title: 'Laporan & Analitik',
        desc: 'Statistik dan laporan sistem',
        url: '/admin/laporan',
        icon: '📈'
    },
    {
        title: 'Dokumentasi API',
        desc: 'API backend GIZENIA',
        url: '/admin/api-docs',
        icon: '🔌'
    },
];

function showSearchDropdown() {
    document.getElementById('searchDropdown').classList.remove('hidden');
    renderResults(searchPages);
}

function filterSearch(keyword) {
    const filtered = searchPages.filter(page =>
        page.title.toLowerCase().includes(keyword.toLowerCase()) ||
        page.desc.toLowerCase().includes(keyword.toLowerCase())
    );
    renderResults(filtered);
}

function renderResults(results) {
    const container = document.getElementById('searchResults');
    container.innerHTML = '';

    if (results.length === 0) {
        container.innerHTML = `
            <li class="px-4 py-3 text-sm text-gray-400">
                Tidak ditemukan
            </li>
        `;
        return;
    }

    results.forEach(page => {
        container.innerHTML += `
            <li>
                <a href="${page.url}" 
                   class="flex items-start gap-3 px-4 py-3 hover:bg-gray-100 transition">
                    <div class="text-xl">${page.icon}</div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">
                            ${page.title}
                        </p>
                        <p class="text-xs text-gray-500">
                            ${page.desc}
                        </p>
                    </div>
                </a>
            </li>
        `;
    });
}

// Tutup dropdown saat klik di luar
document.addEventListener('click', function (e) {
    const searchBox = document.getElementById('searchContainer');
    const dropdown = document.getElementById('searchDropdown');
    if (!searchBox.contains(e.target)) {
        dropdown.classList.add('hidden');
    }
});
</script>
</body>
</html>