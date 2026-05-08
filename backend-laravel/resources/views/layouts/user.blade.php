<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GIZENIA.AI - User Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-[#F8F9FA] text-gray-800 flex h-screen overflow-hidden">

    <aside class="w-64 bg-[#F4F6F8] border-r border-gray-200 h-full flex flex-col justify-between hidden md:flex shrink-0">
        <div>
            <div class="h-20 flex items-center px-6">
                <div>
                    <h1 class="text-lg font-bold text-gray-900 leading-tight">GIZENIA.AI</h1>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider">Lacak Nutrisimu</p>
                </div>
            </div>

            <nav class="space-y-1.5 mt-4">
                <a href="/user/dashboard" class="flex items-center px-4 py-3 {{ Request::is('user/dashboard') ? 'bg-emerald-100/50 text-emerald-700' : 'text-gray-600 hover:bg-gray-100' }} rounded-xl font-medium text-sm transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg> Dashboard
                </a>
                <a href="/user/menu" class="flex items-center px-4 py-3 {{ Request::is('user/menu') ? 'bg-emerald-100/50 text-emerald-700' : 'text-gray-600 hover:bg-gray-100' }} rounded-xl font-medium text-sm transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg> Menu Rekomendasi
                </a>
                <a href="/user/riwayat" class="flex items-center px-4 py-3 {{ Request::is('user/riwayat') ? 'bg-emerald-100/50 text-emerald-700' : 'text-gray-600 hover:bg-gray-100' }} rounded-xl font-medium text-sm transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Riwayat Penerimaan
                </a>
                <a href="/user/pelacak" class="flex items-center px-4 py-3 {{ Request::is('user/pelacak') ? 'bg-emerald-100/50 text-emerald-700' : 'text-gray-600 hover:bg-gray-100' }} rounded-xl font-medium text-sm transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Pelacak Nutrisi
                </a>
            </nav>
        </div>

        <div class="p-6">
            <button onclick="handleLogout()" class="flex items-center text-sm text-gray-500 hover:text-red-600 transition-colors w-full">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg> Keluar
            </button>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-full overflow-hidden relative">
        
        <header class="h-20 flex items-center justify-between px-8 bg-[#F8F9FA] border-b border-gray-100 z-50">
            
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

            <div class="flex items-center space-x-3 md:space-x-5">
                
                <div class="relative dropdown-container">
                    <button onclick="toggleDropdown('notifDropdown')" class="relative p-2 text-gray-400 hover:text-emerald-600 transition-colors focus:outline-none rounded-full hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span id="globalNotifDot" class="hidden absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full animate-pulse"></span>
                    </button>
                    <div id="notifDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform opacity-0 transition-opacity duration-200">
                        <div class="px-4 py-3 border-b border-gray-50 bg-gray-50/50"><h3 class="text-sm font-bold text-gray-800">Notifikasi Anda</h3></div>
                        <div class="p-3 max-h-64 overflow-y-auto" id="notifListContainer">
                            <div class="py-6 text-center text-xs text-gray-400"><i class="fas fa-spinner fa-spin mb-2"></i><br>Memuat...</div>
                        </div>
                    </div>
                </div>
                
                <div class="relative dropdown-container">
                    <button onclick="toggleDropdown('settingsDropdown')" class="p-2 text-gray-400 hover:text-emerald-600 transition-colors focus:outline-none rounded-full hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </button>
                    <div id="settingsDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform opacity-0 transition-opacity duration-200">
                       <div class="p-2">

    <!-- BAHASA -->
    <div class="px-4 py-2.5">
    <div class="flex items-center justify-between">
        <div class="flex items-center text-sm text-gray-700 dark:text-gray-700">
            <i class="fas fa-globe w-5 text-gray-400 mr-2"></i>
            <span data-lang="language">Bahasa Indonesia</span>
        </div>
    </div>
</div>

</div>
                    </div>
                </div>

                <div class="relative dropdown-container">
                    <div onclick="toggleDropdown('profileDropdown')" class="flex items-center space-x-3 cursor-pointer pl-4 border-l border-gray-200 hover:opacity-80 transition-opacity rounded-full p-1">
                        <div class="text-right hidden md:block">
                            <p id="topbarName" class="text-sm font-bold text-gray-800 leading-tight">Memuat...</p>
                            <p id="topbarRole" class="text-[11px] text-gray-500">Anggota GIZENIA</p>
                        </div>
                        <div id="topbarAvatar" class="h-10 w-10 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 text-white border-2 border-white shadow-sm flex items-center justify-center font-bold text-sm">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </div>
                    <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform opacity-0 transition-opacity duration-200">
                        <div class="px-4 py-3 border-b border-gray-50 bg-gray-50/50">
                            <p class="text-xs text-gray-500">Masuk sebagai</p>
                            <p class="text-sm font-bold text-gray-900 truncate" id="topbarEmail">memuat@email.com</p>
                        </div>
                        <div class="p-2">
                            <a href="/profile" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                                <i class="far fa-id-badge w-5 text-gray-400"></i> Akun Saya
                            </a>
                            <a href="/forgot-password"
                                    class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                                <i class="fas fa-key w-5 text-gray-400"></i> Ubah Password
                            </a>
                        </div>
                        <div class="p-2 border-t border-gray-50">
                            <button onclick="handleLogout()" class="w-full text-left px-4 py-2 text-sm text-red-600 font-bold hover:bg-red-50 rounded-xl transition-colors"><i class="fas fa-sign-out-alt w-5"></i> Keluar Aman</button>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto p-8 relative">
            @yield('content')
            <div class="text-center text-[10px] text-gray-400 mt-10 mb-2 flex justify-between w-full border-t border-gray-100 pt-4">
                <span>© 2026 GIZENIA Cek Nutrisi Piring MBG.</span>
                <span class="space-x-4"><a href="#" class="hover:text-gray-600">KEBIJAKAN PRIVASI</a> <a href="#" class="hover:text-gray-600">KETENTUAN LAYANAN</a></span>
            </div>
        </main>
    </div>

    <script>
        const globalToken = localStorage.getItem('jwt_token');
        
        function handleLogout() {
            localStorage.removeItem('jwt_token');
            window.location.href = '/login';
        }

        if (!globalToken) handleLogout(); 

        /* =======================================================
           FUNGSI PENCARIAN NAVIGASI (QUICK SEARCH)
        ======================================================= */
        // Daftar Halaman & Fitur Aplikasi
        const searchPages = [
            { title: 'Dashboard Utama', desc: 'Ringkasan asupan dan profil kalori hari ini', url: '/user/dashboard', icon: 'fas fa-home', color: 'text-emerald-500 bg-emerald-100' },
            { title: 'Katalog Menu Data', desc: 'Eksplorasi makanan, kalori, dan rekomendasi AI', url: '/user/menu', icon: 'fas fa-utensils', color: 'text-orange-500 bg-orange-100' },
            { title: 'Riwayat Penerimaan', desc: 'Cek catatan distribusi makanan sebelumnya', url: '/user/riwayat', icon: 'fas fa-history', color: 'text-blue-500 bg-blue-100' },
            { title: 'Pelacak Nutrisi', desc: 'Hitung dan rancang piring makananmu sendiri', url: '/user/pelacak', icon: 'fas fa-chart-pie', color: 'text-purple-500 bg-purple-100' },
            { title: 'Pengaturan Akun', desc: 'Ubah profil, password, dan preferensi aplikasi', url: '#', icon: 'fas fa-cog', color: 'text-gray-500 bg-gray-100' },
        ];

        // Tampilkan kotak rekomendasi saat input di-klik/fokus
        function showSearchDropdown() {
            document.getElementById('searchDropdown').classList.remove('hidden');
            renderSearchResults(searchPages); // Tampilkan semua sebagai rekomendasi awal
        }

        // Saring (Filter) data saat user mengetik
        function filterSearch(query) {
            const listContainer = document.getElementById('searchDropdown');
            listContainer.classList.remove('hidden');

            if (!query) {
                renderSearchResults(searchPages); // Kembali ke default jika kosong
                return;
            }

            const lowerQuery = query.toLowerCase();
            const filtered = searchPages.filter(page => 
                page.title.toLowerCase().includes(lowerQuery) || 
                page.desc.toLowerCase().includes(lowerQuery)
            );
            renderSearchResults(filtered);
        }

        // Cetak HTML daftar halaman
        function renderSearchResults(results) {
            const list = document.getElementById('searchResults');
            list.innerHTML = '';
            
            if (results.length === 0) {
                list.innerHTML = '<li class="px-4 py-6 text-sm text-gray-400 text-center"><i class="fas fa-search-minus text-2xl mb-2"></i><br>Tidak menemukan fitur tersebut.</li>';
                return;
            }

            results.forEach(res => {
                list.innerHTML += `
                    <li>
                        <a href="${res.url}" class="flex items-center px-4 py-3 hover:bg-gray-50 border-b border-gray-50 transition-colors">
                            <div class="w-9 h-9 rounded-full ${res.color} flex items-center justify-center mr-4 shrink-0 shadow-sm">
                                <i class="${res.icon}"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 leading-tight">${res.title}</p>
                                <p class="text-[11px] text-gray-500 mt-0.5">${res.desc}</p>
                            </div>
                            <div class="ml-auto text-gray-300">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </div>
                        </a>
                    </li>
                `;
            });
        }


        // Fungsi Toggle Dropdown Standar (Notif, Setting, Profil)
        function toggleDropdown(dropdownId) {
            const dropdowns = ['notifDropdown', 'settingsDropdown', 'profileDropdown'];
            document.getElementById('searchDropdown').classList.add('hidden'); // Tutup search jika buka ini
            
            dropdowns.forEach(id => {
                const el = document.getElementById(id);
                if (id === dropdownId) {
                    if (el.classList.contains('hidden')) {
                        el.classList.remove('hidden');
                        setTimeout(() => el.classList.remove('opacity-0'), 10);
                    } else {
                        el.classList.add('opacity-0');
                        setTimeout(() => el.classList.add('hidden'), 200);
                    }
                } else {
                    el.classList.add('opacity-0');
                    setTimeout(() => el.classList.add('hidden'), 200);
                }
            });
        }

        // Menutup Dropdown (Termasuk Search) jika diklik di tempat kosong
        document.addEventListener('click', function(event) {
            const isClickInsideDropdown = event.target.closest('.dropdown-container');
            const isClickInsideSearch = event.target.closest('#searchContainer');
            
            if (!isClickInsideDropdown) {
                ['notifDropdown', 'settingsDropdown', 'profileDropdown'].forEach(id => {
                    const el = document.getElementById(id);
                    if (!el.classList.contains('hidden')) {
                        el.classList.add('opacity-0');
                        setTimeout(() => el.classList.add('hidden'), 200);
                    }
                });
            }

            if (!isClickInsideSearch) {
                document.getElementById('searchDropdown').classList.add('hidden');
            }
        });

        // Tarik Data Profil & Notifikasi saat dimuat
        document.addEventListener("DOMContentLoaded", async function() {
            try {
                const resUser = await fetch('/api/auth/me', { headers: { 'Authorization': `Bearer ${globalToken}` }});
                if (resUser.ok) {
                    const user = await resUser.json();
                    document.getElementById('topbarName').innerText = user.name.split(' ')[0];
                    document.getElementById('topbarEmail').innerText = user.email;
                    
                    const roleText = user.class_room ? `Siswa ${user.class_room}` : 'Siswa GIZENIA';
                    document.getElementById('topbarRole').innerText = roleText;

                    const initials = user.name.substring(0, 2).toUpperCase();
                    document.getElementById('topbarAvatar').innerHTML = `<span class="tracking-widest">${initials}</span>`;
                }
            } catch(e) {}

            try {
                const resNotif = await fetch('/api/user/notification', { headers: { 'Authorization': `Bearer ${globalToken}` }});
                if (resNotif.ok) {
                    const dataNotif = await resNotif.json();
                    const notifContainer = document.getElementById('notifListContainer');

                    if (dataNotif.has_notification) {
                        document.getElementById('globalNotifDot').classList.remove('hidden');
                        const foods = dataNotif.distribution.foods.map(f => f.name).join(', ');
                        notifContainer.innerHTML = `
                            <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100 hover:bg-emerald-100 transition cursor-pointer" onclick="window.location.href='/user/dashboard'">
                                <p class="text-xs font-bold text-gray-900 mb-1"><i class="fas fa-bell text-emerald-500 mr-1"></i> Tindakan Diperlukan</p>
                                <p class="text-[11px] text-gray-600 leading-snug">Mohon konfirmasi penerimaan makanan: <b>${foods}</b> di Dashboard Anda.</p>
                                <p class="text-[10px] font-bold text-emerald-600 mt-2">Buka Dashboard &rarr;</p>
                            </div>
                        `;
                    } else {
                        notifContainer.innerHTML = `<div class="py-6 text-center text-gray-400"><i class="fas fa-check-circle text-2xl text-gray-300 mb-2"></i><p class="text-xs font-medium">Belum ada notifikasi baru.</p></div>`;
                    }
                }
            } catch(e) {}
        });
    </script>
</body>
</html>