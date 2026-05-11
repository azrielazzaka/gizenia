@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-end mb-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Distribusi Makanan</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola penyaluran menu bergizi harian kepada penerima.</p>
    </div>
    <button onclick="openModalForAdd()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md flex items-center transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Distribusikan Baru
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
    <table class="w-full text-left">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500">
                <th class="py-4 px-6 font-semibold">Tanggal</th>
                <th class="py-4 px-6 font-semibold">Menu Didistribusikan</th>
                <th class="py-4 px-6 font-semibold">Target Penerima</th>
                <th class="py-4 px-6 font-semibold text-center">Status</th>
                <th class="py-4 px-6 font-semibold text-right">Aksi</th>
            </tr>
        </thead>
        <tbody id="distTableBody" class="text-sm divide-y divide-gray-50">
            <tr><td colspan="5" class="py-8 text-center text-gray-400">Memuat data...</td></tr>
        </tbody>
    </table>
    
    <div class="p-4 border-t border-gray-100 flex items-center justify-between bg-gray-50/50">
        <span id="paginationInfo" class="text-xs text-gray-500">Menghitung...</span>
        <div id="paginationControls" class="flex space-x-1"></div>
    </div>
</div>

<div id="distModal" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden mt-10 mb-10">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 id="modalTitle" class="text-lg font-bold text-gray-900">Form Distribusi Makanan</h3>
            <button type="button" onclick="toggleModal('distModal')" class="text-gray-400 hover:text-red-500 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="distForm" class="p-6">
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-bold text-gray-800">Pilih Makanan & Berat</label>
                    <button type="button" onclick="addFoodRow()" class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-lg hover:bg-emerald-100">+ Tambah Menu</button>
                </div>
                <div id="foodListContainer" class="space-y-3">
                    </div>
            </div>

            <div class="mb-6 border-t border-gray-100 pt-4">
                <label class="block text-sm font-bold text-gray-800 mb-3">Target Penerima (Checklist)</label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="flex items-center space-x-2 bg-gray-50 p-2 rounded-lg cursor-pointer hover:bg-blue-50 border border-transparent hover:border-blue-100 transition"><input type="checkbox" value="Kelas 1" class="class-checkbox w-4 h-4 text-blue-600 rounded"> <span class="text-sm font-medium text-gray-700">Kelas 1</span></label>
                    <label class="flex items-center space-x-2 bg-gray-50 p-2 rounded-lg cursor-pointer hover:bg-blue-50 border border-transparent hover:border-blue-100 transition"><input type="checkbox" value="Kelas 2" class="class-checkbox w-4 h-4 text-blue-600 rounded"> <span class="text-sm font-medium text-gray-700">Kelas 2</span></label>
                    <label class="flex items-center space-x-2 bg-gray-50 p-2 rounded-lg cursor-pointer hover:bg-blue-50 border border-transparent hover:border-blue-100 transition"><input type="checkbox" value="Kelas 3" class="class-checkbox w-4 h-4 text-blue-600 rounded"> <span class="text-sm font-medium text-gray-700">Kelas 3</span></label>
                    <label class="flex items-center space-x-2 bg-gray-50 p-2 rounded-lg cursor-pointer hover:bg-blue-50 border border-transparent hover:border-blue-100 transition"><input type="checkbox" value="Kelas 4" class="class-checkbox w-4 h-4 text-blue-600 rounded"> <span class="text-sm font-medium text-gray-700">Kelas 4</span></label>
                    <label class="flex items-center space-x-2 bg-gray-50 p-2 rounded-lg cursor-pointer hover:bg-blue-50 border border-transparent hover:border-blue-100 transition"><input type="checkbox" value="Kelas 5" class="class-checkbox w-4 h-4 text-blue-600 rounded"> <span class="text-sm font-medium text-gray-700">Kelas 5</span></label>
                    <label class="flex items-center space-x-2 bg-gray-50 p-2 rounded-lg cursor-pointer hover:bg-blue-50 border border-transparent hover:border-blue-100 transition"><input type="checkbox" value="Kelas 6" class="class-checkbox w-4 h-4 text-blue-600 rounded"> <span class="text-sm font-medium text-gray-700">Kelas 6</span></label>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="toggleModal('distModal')" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition shadow-md">Simpan & Distribusikan</button>
            </div>
        </form>
    </div>
</div>

<div id="confirmDistModal" class="fixed inset-0 z-[60] hidden bg-gray-900/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all">
        <div class="p-6 text-center">
            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Sebarkan Notifikasi?</h3>
            <p class="text-sm text-gray-500 mb-6">Sistem akan mengirimkan notifikasi "Apakah sudah menerima makanan?" ke Dashboard pengguna yang berada di kelas target.</p>
            <div class="flex justify-center space-x-3">
                <button type="button" onclick="toggleModal('confirmDistModal')" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition flex-1">Batal</button>
                <button type="button" id="btnExecuteDist" onclick="executeDistribute()" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition shadow-md flex-1">Ya, Sebarkan!</button>
            </div>
        </div>
    </div>
</div>

<div id="successDistModal" class="fixed inset-0 z-[70] hidden bg-gray-900/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all text-center p-8">
        <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Distribusi Berhasil!</h3>
        <p class="text-sm text-gray-500 mb-8">Data distribusi telah tersimpan dan notifikasi akan dikirimkan ke target penerima.</p>
        <button type="button" onclick="toggleModal('successDistModal')" class="w-full py-3.5 rounded-xl text-sm font-bold text-white bg-emerald-500 hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">
            Selesai
        </button>
    </div>
</div>

<div id="deleteDistModal" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all">
        <div class="p-6 text-center">
            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Tarik Distribusi?</h3>
            <p class="text-sm text-gray-500 mb-6">Data ini akan dihapus permanen. Pengguna target tidak akan melihat notifikasi ini lagi di Dashboard mereka.</p>
            <div class="flex justify-center space-x-3">
                <button type="button" onclick="toggleModal('deleteDistModal')" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition flex-1">Batal</button>
                <button type="button" onclick="executeDeleteDist()" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-red-500 hover:bg-red-600 transition shadow-md flex-1">Ya, Hapus!</button>
            </div>
        </div>
    </div>
</div>

<script>
    const token = localStorage.getItem('jwt_token');

if (!token) {
    window.location.href = '/login';
}

let availableMenus = [];
let allDistData = [];
let isEditMode = false;
let currentDistId = null;
let distToDeleteId = null;
let currentPage = 1;
let payloadToSubmit = null;

/* =========================================================
   MODAL
========================================================= */
function toggleModal(id, show = null) {
    const modal = document.getElementById(id);

    if (!modal) return;

    const isHidden = modal.classList.contains('hidden');

    if (show === true || (show === null && isHidden)) {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    } else {
        modal.classList.add('hidden');

        // cek apakah masih ada modal lain terbuka
        const openedModal = document.querySelector(
            '#distModal:not(.hidden), #confirmDistModal:not(.hidden), #successDistModal:not(.hidden), #deleteDistModal:not(.hidden)'
        );

        if (!openedModal) {
            document.body.classList.remove('overflow-hidden');
        }
    }
}

/* =========================================================
   LOAD MENU
========================================================= */
async function loadMenusForDropdown() {
    try {
        const res = await fetch('/api/menus', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) {
            throw new Error('Gagal mengambil menu');
        }

        const result = await res.json();

        console.log('MENU RESPONSE:', result);

        availableMenus = result.data || result || [];

    } catch (e) {
        console.error('Gagal memuat menu:', e);
        availableMenus = [];
    }
}

function populateSelect(selectElement) {
    selectElement.innerHTML = `
        <option value="">-- Pilih Menu Makanan --</option>
    `;

    if (!Array.isArray(availableMenus)) {
        console.error('availableMenus bukan array:', availableMenus);
        return;
    }

    availableMenus.forEach(menu => {
        const id = menu._id || menu.id;
        const name =
            menu.name ||
            menu.nama ||
            menu.nama_makanan ||
            'Tanpa Nama';

        selectElement.innerHTML += `
            <option value="${id}" data-name="${name}">
                ${name}
            </option>
        `;
    });
}

/* =========================================================
   TAMBAH ROW MAKANAN
========================================================= */
function addFoodRow(menuId = '', weight = '') {
    const container = document.getElementById('foodListContainer');

    const row = document.createElement('div');

    row.className = 'flex space-x-3 food-row mt-3';

    row.innerHTML = `
        <select class="food-select flex-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none" required></select>

        <input
            type="number"
            class="food-weight w-32 px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none"
            placeholder="Berat (g)"
            value="${weight}"
            required
        >

        <button
            type="button"
            onclick="this.parentElement.remove()"
            class="text-red-400 hover:text-red-600 px-2"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                </path>
            </svg>
        </button>
    `;

    container.appendChild(row);

    const select = row.querySelector('.food-select');

    populateSelect(select);

    if (menuId) {
        select.value = menuId;
    }
}

/* =========================================================
   FETCH DISTRIBUTIONS
========================================================= */
async function fetchDistributions(page = 1) {
    currentPage = page;

    try {
        const res = await fetch(`/api/distributions?page=${page}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) {
            throw new Error('Gagal fetch distribusi');
        }

        const data = await res.json();

        console.log('DIST RESPONSE:', data);

        allDistData = data.data || [];

        const tbody = document.getElementById('distTableBody');

        tbody.innerHTML = '';

        if (allDistData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-400">
                        Belum ada riwayat distribusi.
                    </td>
                </tr>
            `;
        } else {
            allDistData.forEach(dist => {
                const distId = dist._id || dist.id;

                const foodHtml = (dist.foods || [])
                    .map(f => `
                        <span class="inline-block bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2 py-1 rounded mr-1 mb-1">
                            ${f.name} (${f.weight}g)
                        </span>
                    `)
                    .join('');

                const classHtml = (dist.target_classes || [])
                    .map(c => `
                        <span class="inline-block bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-1 rounded mr-1 mb-1">
                            ${c}
                        </span>
                    `)
                    .join('');

                tbody.innerHTML += `
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6 font-bold text-gray-800 text-xs">
                            ${dist.distribution_date || '-'}
                        </td>

                        <td class="py-4 px-6">
                            ${foodHtml}
                        </td>

                        <td class="py-4 px-6">
                            ${classHtml}
                        </td>

                        <td class="py-4 px-6 text-center">
                            <span class="text-xs font-bold text-emerald-600">
                                <i class="fas fa-check-circle"></i> Terkirim
                            </span>
                        </td>

                        <td class="py-4 px-6 text-right space-x-3">
                            <button
                                onclick="editDist('${distId}')"
                                class="text-blue-500 hover:text-blue-700 font-semibold text-xs"
                            >
                                Edit
                            </button>

                            <button
                                onclick="promptDeleteDist('${distId}')"
                                class="text-red-500 hover:text-red-700 font-semibold text-xs"
                            >
                                Hapus
                            </button>
                        </td>
                    </tr>
                `;
            });
        }

        document.getElementById('paginationInfo').innerText =
            `Menampilkan ${data.from || 0} - ${data.to || 0} dari ${data.total || 0} distribusi`;

        const controls = document.getElementById('paginationControls');

        controls.innerHTML = '';

        if (data.current_page > 1) {
            controls.innerHTML += `
                <button
                    onclick="fetchDistributions(${data.current_page - 1})"
                    class="px-3 py-1 bg-white border border-gray-200 text-gray-600 rounded-md hover:bg-gray-50 text-xs font-medium transition"
                >
                    Prev
                </button>
            `;
        }

        controls.innerHTML += `
            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-md text-xs font-bold">
                ${data.current_page || 1} / ${data.last_page || 1}
            </span>
        `;

        if (data.current_page < data.last_page) {
            controls.innerHTML += `
                <button
                    onclick="fetchDistributions(${data.current_page + 1})"
                    class="px-3 py-1 bg-white border border-gray-200 text-gray-600 rounded-md hover:bg-gray-50 text-xs font-medium transition"
                >
                    Next
                </button>
            `;
        }

    } catch (e) {
        console.error(e);
    }
}

/* =========================================================
   OPEN MODAL ADD
========================================================= */
function openModalForAdd() {
    isEditMode = false;
    currentDistId = null;

    document.getElementById('distForm').reset();

    document.getElementById('modalTitle').innerText =
        'Distribusi Makanan Baru';

    document.getElementById('foodListContainer').innerHTML = '';

    addFoodRow();

    document.querySelectorAll('.class-checkbox')
        .forEach(cb => cb.checked = false);

    toggleModal('distModal', true);
}

/* =========================================================
   EDIT
========================================================= */
function editDist(id) {
    isEditMode = true;
    currentDistId = id;

    document.getElementById('modalTitle').innerText =
        'Edit Distribusi Makanan';

    const dist = allDistData.find(
        d => (d._id === id || d.id === id)
    );

    if (!dist) return;

    document.getElementById('foodListContainer').innerHTML = '';

    (dist.foods || []).forEach(f => {
        addFoodRow(f.menu_id, f.weight);
    });

    document.querySelectorAll('.class-checkbox')
        .forEach(cb => cb.checked = false);

    (dist.target_classes || []).forEach(c => {
        const cb = document.querySelector(
            `.class-checkbox[value="${c}"]`
        );

        if (cb) cb.checked = true;
    });

    toggleModal('distModal', true);
}

/* =========================================================
   SUBMIT FORM
========================================================= */
document.getElementById('distForm')
.addEventListener('submit', function(e) {

    e.preventDefault();

    const foods = [];

    document.querySelectorAll('.food-row').forEach(row => {

        const select = row.querySelector('.food-select');

        const weight = row.querySelector('.food-weight').value;

        if (select.value && weight) {
            foods.push({
                menu_id: select.value,
                name: select.options[select.selectedIndex]
                    ?.getAttribute('data-name'),
                weight: parseFloat(weight)
            });
        }
    });

    const targetClasses = [];

    document.querySelectorAll('.class-checkbox:checked')
        .forEach(cb => targetClasses.push(cb.value));

    if (foods.length === 0) {
        alert('Pilih minimal 1 makanan!');
        return;
    }

    if (targetClasses.length === 0) {
        alert('Pilih minimal 1 kelas penerima!');
        return;
    }

    payloadToSubmit = {
        foods,
        target_classes: targetClasses
    };

    toggleModal('distModal', false);
    toggleModal('confirmDistModal', true);
});

/* =========================================================
   EXECUTE DISTRIBUTE
========================================================= */
async function executeDistribute() {
    const btn = document.getElementById('btnExecuteDist');

    btn.innerText = 'Menyebarkan...';
    btn.disabled = true;

    const url = isEditMode
        ? `/api/distributions/${currentDistId}`
        : '/api/distributions';

    const method = isEditMode ? 'PUT' : 'POST';

    try {
        const res = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payloadToSubmit)
        });

        if (!res.ok) {
            throw new Error('Gagal distribusi');
        }

        toggleModal('confirmDistModal', false);

        await fetchDistributions(currentPage);

        setTimeout(() => {
            toggleModal('successDistModal', true);
        }, 300);

    } catch (e) {
        console.error(e);
        alert('Terjadi kesalahan server');
    } finally {
        btn.innerText = 'Ya, Sebarkan!';
        btn.disabled = false;
    }
}

/* =========================================================
   DELETE
========================================================= */
function promptDeleteDist(id) {
    distToDeleteId = id;
    toggleModal('deleteDistModal', true);
}

async function executeDeleteDist() {
    try {
        const res = await fetch(
            `/api/distributions/${distToDeleteId}`,
            {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            }
        );

        if (!res.ok) {
            throw new Error('Gagal hapus');
        }

        toggleModal('deleteDistModal', false);

        fetchDistributions(currentPage);

    } catch (e) {
        console.error(e);
        alert('Error server.');
    }
}

/* =========================================================
   INIT
========================================================= */
(async function init() {
    await loadMenusForDropdown();
    await fetchDistributions(1);
})();
</script>
@endsection