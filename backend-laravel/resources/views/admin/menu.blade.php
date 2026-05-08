@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-3xl font-bold text-gray-900">Menu Data</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola dataset distribusi makanan bergizi dengan presisi AI.</p>
    </div>
    
    <div class="flex space-x-3">
        <input type="file" id="csvFileInput" accept=".csv" class="hidden" onchange="handleCsvUpload(event)">
        
        <button onclick="document.getElementById('csvFileInput').click()" class="bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-100 px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm flex items-center transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            Import CSV
        </button>

        <button onclick="openModalForAdd()" class="bg-[#10B981] hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md flex items-center transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Menu
        </button>
    </div>
</div>

<div id="importAlert" class="hidden mb-6 p-4 rounded-xl font-medium text-sm flex items-center shadow-sm"></div>

<div class="bg-[#F0F4F8] border border-blue-50/50 rounded-2xl p-5 flex items-start mb-8">
    <div class="bg-emerald-100/70 p-2 rounded-xl text-emerald-600 shrink-0 mr-4">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3l-1.5 3.5L14 8l3.5 1.5L19 13l1.5-3.5L24 8l-3.5-1.5L19 3zm-8 4L8 0 5 7 0 10l5 3 3 7 3-7 5-3-5-3z"/></svg>
    </div>
    <div>
        <h4 class="font-bold text-emerald-800 text-sm mb-1">Integrasi Machine Learning</h4>
        <p class="text-sm text-gray-600">Sistem AI akan merekomendasikan menu lengkap (Kalori, Protein, Lemak) untuk memenuhi kebutuhan gizi harian pengguna.</p>
    </div>
</div>

<div id="menuGridContainer" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-5 mb-12">
    <div id="loadingIndicator" class="col-span-full py-10 text-center text-gray-400">
        <svg class="animate-spin h-8 w-8 mx-auto text-emerald-500 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        Memuat dataset menu dari Database...
    </div>
</div>

<div id="addMenuModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center overflow-y-auto pt-20 pb-10">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl mx-4 overflow-hidden border border-gray-100 relative">
        <div class="px-8 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-900">Tambah Dataset Menu Baru</h3>
            <button type="button" onclick="toggleModal('addMenuModal')" class="text-gray-400 hover:text-red-500 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="menuForm" class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="space-y-4 col-span-1 border-r pr-6 border-gray-100">
                    <h4 class="text-xs font-bold text-emerald-600 uppercase tracking-widest border-b pb-2">Informasi Menu</h4>
                    <div><label class="block text-xs font-medium text-gray-700 mb-1">ID (Opsional)</label><input type="text" id="m_kaggle" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none"></div>
                    <div><label class="block text-xs font-medium text-gray-700 mb-1">Nama Menu</label><input type="text" id="m_name" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 outline-none"></div>
                    <div><label class="block text-xs font-medium text-gray-700 mb-1">URL Gambar</label><input type="url" id="m_image" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none" placeholder="https://..."></div>
                    <div class="grid grid-cols-2 gap-3">
                        <div><label class="block text-xs font-medium text-gray-700 mb-1">Kategori</label><input type="text" id="m_category" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none" placeholder="Cth: VEGAN"></div>
                        <div><label class="block text-xs font-medium text-gray-700 mb-1">Waktu</label><select id="m_meal_time" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none"><option value="SARAPAN">Sarapan</option><option value="MAKAN SIANG">Makan Siang</option><option value="MAKAN MALAM">Makan Malam</option><option value="BEBAS">Bebas</option></select></div>
                    </div>
                </div>

                <div class="space-y-4 col-span-1 border-r pr-6 border-gray-100">
                    <h4 class="text-xs font-bold text-blue-600 uppercase tracking-widest border-b pb-2">Makro & Porsi</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div><label class="block text-xs font-medium text-gray-700 mb-1">Porsi (Gram)</label><input type="number" id="m_serving" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none"></div>
                        <div><label class="block text-xs font-medium text-gray-700 mb-1">Kalori (Kcal)</label><input type="number" id="m_calories" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none"></div>
                        <div><label class="block text-xs font-medium text-gray-700 mb-1">Protein (g)</label><input type="number" id="m_protein" step="0.1" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none"></div>
                        <div><label class="block text-xs font-medium text-gray-700 mb-1">Karbohidrat (g)</label><input type="number" id="m_carbs" step="0.1" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none"></div>
                        <div><label class="block text-xs font-medium text-gray-700 mb-1">Lemak (g)</label><input type="number" id="m_fat" step="0.1" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none"></div>
                        <div><label class="block text-xs font-medium text-gray-700 mb-1">Serat (g)</label><input type="number" id="m_fiber" step="0.1" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none"></div>
                    </div>
                </div>

                <div class="space-y-4 col-span-1">
                    <h4 class="text-xs font-bold text-purple-600 uppercase tracking-widest border-b pb-2">Mikronutrisi (opsional)</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div><label class="block text-xs font-medium text-gray-700 mb-1">Vitamin A (IU)</label><input type="number" id="m_vit_a" step="0.1" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none"></div>
                        <div><label class="block text-xs font-medium text-gray-700 mb-1">Vitamin C (mg)</label><input type="number" id="m_vit_c" step="0.1" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none"></div>
                        <div><label class="block text-xs font-medium text-gray-700 mb-1">Kalsium (mg)</label><input type="number" id="m_calcium" step="0.1" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none"></div>
                        <div><label class="block text-xs font-medium text-gray-700 mb-1">Zat Besi (mg)</label><input type="number" id="m_iron" step="0.1" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none"></div>
                        <div><label class="block text-xs font-medium text-gray-700 mb-1">Sodium (mg)</label><input type="number" id="m_sodium" step="0.1" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none"></div>
                    </div>
                </div>
            </div>

            <div id="formError" class="hidden mb-4 p-3 bg-red-50 text-red-600 text-xs rounded-xl font-medium"></div>

            <div class="flex justify-end space-x-3 mt-4 border-t border-gray-100 pt-6">
                <button type="button" onclick="toggleModal('addMenuModal')" class="px-6 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Batal</button>
                <button type="submit" id="btnSubmitMenu" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-md">Simpan Menu</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteConfirmModal" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all">
        <div class="p-6 text-center">
            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Menu Ini?</h3>
            <p class="text-sm text-gray-500 mb-6">Tindakan ini tidak dapat dibatalkan. Data menu akan dihapus secara permanen dari database.</p>
            
            <div class="flex justify-center space-x-3">
                <button type="button" onclick="toggleModal('deleteConfirmModal')" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition flex-1">
                    Batal
                </button>
                <button type="button" id="btnConfirmDelete" onclick="executeDelete()" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-red-500 hover:bg-red-600 transition shadow-md flex-1">
                    Ya, Hapus!
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const token = localStorage.getItem('jwt_token');
    if (!token) window.location.href = '/login';

    let isEditMode = false;
    let currentEditId = null;
    let menuToDeleteId = null; // Menyimpan ID yang akan dihapus
    let allMenusData = [];

    function toggleModal(modalID) { 
        document.getElementById(modalID).classList.toggle('hidden'); 
    }

    function openModalForAdd() {
        isEditMode = false;
        currentEditId = null;
        document.getElementById('menuForm').reset();
        document.getElementById('modalTitle').innerText = "Tambah Dataset Menu Baru";
        document.getElementById('btnSubmitMenu').innerText = "Simpan Menu Lengkap";
        toggleModal('addMenuModal');
    }

    function editMenu(id) {
        isEditMode = true;
        currentEditId = id;
        
        const menu = allMenusData.find(m => m._id === id || m.id === id);
        
        if (menu) {
            document.getElementById('m_kaggle').value = menu.kaggle_id || '';
            document.getElementById('m_name').value = menu.name || '';
            document.getElementById('m_image').value = menu.image_url || '';
            document.getElementById('m_category').value = menu.category || '';
            document.getElementById('m_meal_time').value = menu.meal_time || 'BEBAS';
            document.getElementById('m_serving').value = menu.serving_size_g || 100;
            document.getElementById('m_calories').value = menu.calories || 0;
            document.getElementById('m_protein').value = menu.protein || 0;
            document.getElementById('m_carbs').value = menu.carbohydrates || 0;
            document.getElementById('m_fat').value = menu.fat || 0;
            document.getElementById('m_fiber').value = menu.fiber || 0;
            document.getElementById('m_vit_a').value = menu.vitamin_a || 0;
            document.getElementById('m_vit_c').value = menu.vitamin_c || 0;
            document.getElementById('m_calcium').value = menu.calcium || 0;
            document.getElementById('m_iron').value = menu.iron || 0;
            document.getElementById('m_sodium').value = menu.sodium || 0;

            document.getElementById('modalTitle').innerText = "Edit Data Menu";
            document.getElementById('btnSubmitMenu').innerText = "Simpan Perubahan";
            toggleModal('addMenuModal');
        } else {
            alert('Sistem tidak dapat menemukan data makanan ini. Coba muat ulang halaman.');
        }
    }

    // FUNGSI MEMBUKA MODAL HAPUS
    function promptDelete(id) {
        menuToDeleteId = id;
        toggleModal('deleteConfirmModal');
    }

    // FUNGSI EKSEKUSI HAPUS (DARI MODAL)
    async function executeDelete() {
        if (!menuToDeleteId) return;
        
        const btn = document.getElementById('btnConfirmDelete');
        const originalText = btn.innerText;
        btn.innerText = 'Menghapus...';
        btn.disabled = true;

        try {
            const res = await fetch(`/api/menus/${menuToDeleteId}`, { 
                method: 'DELETE', 
                headers: { 'Authorization': `Bearer ${token}` }
            });
            
            if (res.ok) {
                fetchMenus(); // Refresh data
                toggleModal('deleteConfirmModal'); // Tutup modal
            } else {
                alert('Gagal menghapus data.');
            }
        } catch (e) { 
            alert('Error server. Periksa koneksi internet Anda.'); 
        } finally {
            btn.innerText = originalText;
            btn.disabled = false;
            menuToDeleteId = null; // Reset ID
        }
    }

    async function handleCsvUpload(event) {
        const file = event.target.files[0];
        if (!file) return;

        const alertBox = document.getElementById('importAlert');
        alertBox.innerHTML = `<svg class="animate-spin w-5 h-5 mr-3" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengimpor data CSV... Mohon tunggu.`;
        alertBox.classList.remove('hidden');
        alertBox.className = 'mb-6 p-4 rounded-xl font-medium text-sm flex items-center shadow-sm bg-blue-50 text-blue-700 border border-blue-100';
        
        const formData = new FormData();
        formData.append('file', file);

        try {
            const res = await fetch('/api/menus/import', {
                method: 'POST',
                headers: { 'Authorization': `Bearer ${token}` },
                body: formData
            });

            const data = await res.json();
            
            if (res.ok) {
                alertBox.innerHTML = `✅ ${data.message}`;
                alertBox.className = 'mb-6 p-4 rounded-xl font-medium text-sm flex items-center shadow-sm bg-green-50 text-green-700 border border-green-100';
                fetchMenus(); 
            } else {
                alertBox.innerHTML = `❌ Gagal: ${data.message || 'Format CSV tidak sesuai.'}`;
                alertBox.className = 'mb-6 p-4 rounded-xl font-medium text-sm flex items-center shadow-sm bg-red-50 text-red-700 border border-red-100';
            }
        } catch (e) {
            alertBox.innerHTML = `❌ Terjadi kesalahan server saat mengimpor.`;
            alertBox.className = 'mb-6 p-4 rounded-xl font-medium text-sm flex items-center shadow-sm bg-red-50 text-red-700 border border-red-100';
        }
        event.target.value = '';
    }

    function createMenuCard(menu) {
        const menuId = menu._id || menu.id; 
        const imgUrl = menu.image_url || 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80';
        
        return `
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md transition-shadow">
            <div class="relative h-36 bg-gray-800 group">
                <img src="${imgUrl}" class="w-full h-full object-cover opacity-90 group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                
                <div class="absolute top-3 right-3 flex space-x-2 z-50">
                    <button type="button" onclick="editMenu('${menuId}')" class="bg-blue-500/80 hover:bg-blue-600 text-white p-2 rounded-lg opacity-80 hover:opacity-100 transition-opacity backdrop-blur-md shadow-sm" title="Edit Menu">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </button>
                    <button type="button" onclick="promptDelete('${menuId}')" class="bg-red-500/80 hover:bg-red-600 text-white p-2 rounded-lg opacity-80 hover:opacity-100 transition-opacity backdrop-blur-md shadow-sm" title="Hapus Menu">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>

                <h3 class="absolute bottom-4 left-4 text-white font-bold text-sm leading-tight pr-4">${menu.name}</h3>
            </div>
            <div class="p-4 flex-1 flex flex-col">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">${menu.serving_size_g}g Porsi</span>
                </div>
                <div class="flex justify-between text-center mb-4">
                    <div><p class="text-[10px] text-gray-400 font-medium mb-0.5">Kal</p><p class="font-bold text-emerald-600 text-sm">${menu.calories}</p></div>
                    <div class="w-px bg-gray-100"></div>
                    <div><p class="text-[10px] text-gray-400 font-medium mb-0.5">Pro</p><p class="font-bold text-gray-900 text-sm">${menu.protein}g</p></div>
                    <div class="w-px bg-gray-100"></div>
                    <div><p class="text-[10px] text-gray-400 font-medium mb-0.5">Lemak</p><p class="font-bold text-gray-900 text-sm">${menu.fat}g</p></div>
                </div>
                <div class="mt-auto flex flex-wrap gap-1.5">
                    <span class="bg-emerald-50 text-emerald-700 text-[9px] font-bold px-2 py-1 rounded border border-emerald-100 uppercase line-clamp-1">${menu.category || 'UMUM'}</span>
                </div>
            </div>
        </div>`;
    }

    const addCardHTML = `<div onclick="openModalForAdd()" class="rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50/50 flex flex-col items-center justify-center p-6 cursor-pointer hover:bg-gray-100 hover:border-emerald-300 transition-colors h-full min-h-[250px]"><div class="w-10 h-10 bg-slate-300 text-white rounded-full flex items-center justify-center mb-4 shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg></div><span class="text-sm font-semibold text-slate-400 text-center leading-snug">Tambah Entri<br>Dataset</span></div>`;

    async function fetchMenus() {
        try {
            const res = await fetch('/api/menus', { headers: { 'Authorization': `Bearer ${token}` }});
            const menus = await res.json();
            
            allMenusData = menus;

            const container = document.getElementById('menuGridContainer');
            container.innerHTML = '';
            menus.forEach(menu => container.insertAdjacentHTML('beforeend', createMenuCard(menu)));
            container.insertAdjacentHTML('beforeend', addCardHTML);
        } catch (e) { console.error(e); }
    }

    document.getElementById('menuForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmitMenu');
        const errBox = document.getElementById('formError');
        
        btn.innerHTML = 'Memproses...';
        btn.disabled = true;
        errBox.classList.add('hidden');

        const payload = {
            kaggle_id: document.getElementById('m_kaggle').value,
            name: document.getElementById('m_name').value,
            image_url: document.getElementById('m_image').value,
            category: document.getElementById('m_category').value,
            meal_time: document.getElementById('m_meal_time').value,
            serving_size_g: document.getElementById('m_serving').value,
            calories: document.getElementById('m_calories').value,
            protein: document.getElementById('m_protein').value,
            carbohydrates: document.getElementById('m_carbs').value,
            fat: document.getElementById('m_fat').value,
            fiber: document.getElementById('m_fiber').value,
            vitamin_a: document.getElementById('m_vit_a').value,
            vitamin_c: document.getElementById('m_vit_c').value,
            calcium: document.getElementById('m_calcium').value,
            iron: document.getElementById('m_iron').value,
            sodium: document.getElementById('m_sodium').value
        };

        const url = isEditMode ? `/api/menus/${currentEditId}` : '/api/menus';
        const method = isEditMode ? 'PUT' : 'POST';

        try {
            const res = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
                body: JSON.stringify(payload)
            });

            if (res.ok) {
                toggleModal('addMenuModal');
                document.getElementById('menuForm').reset();
                isEditMode = false;
                fetchMenus(); 
            } else {
                errBox.innerHTML = 'Gagal menyimpan. Pastikan data bernilai angka valid.';
                errBox.classList.remove('hidden');
            }
        } catch (e) {
            errBox.innerHTML = 'Server error.';
            errBox.classList.remove('hidden');
        } finally {
            btn.innerHTML = isEditMode ? 'Simpan Perubahan' : 'Simpan Menu Lengkap';
            btn.disabled = false;
        }
    });

    fetchMenus();
</script>
@endsection