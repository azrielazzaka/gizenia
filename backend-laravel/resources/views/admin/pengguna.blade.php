@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-end mb-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Manajemen Pengguna</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola data administrator dan penerima nutrisi.</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-[0_2px_10px_rgba(0,0,0,0.04)] overflow-hidden border border-gray-50">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <div class="relative w-72">
            <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" id="searchInput" placeholder="Cari nama atau email..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>
        <select id="roleFilter" class="border border-gray-200 rounded-lg px-4 py-2 text-sm text-gray-600 focus:outline-none">
            <option value="">Semua Role</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
    </div>
    
    <table class="w-full text-left">
        <thead>
            <tr class="bg-white border-b border-gray-100 text-xs uppercase tracking-wider text-gray-400">
                <th class="py-4 px-6 font-semibold">Pengguna</th>
                <th class="py-4 px-6 font-semibold">Role</th>
                <th class="py-4 px-6 font-semibold">Kelas</th> 
                <th class="py-4 px-6 font-semibold">Fisik (U/B/T)</th>
                <th class="py-4 px-6 font-semibold">Status</th>
                <th class="py-4 px-6 font-semibold text-right">Aksi</th>
            </tr>
        </thead>
        <tbody id="userTableBody" class="text-sm divide-y divide-gray-50">
            <tr>
                <td colspan="6" class="py-8 text-center text-gray-400">
                    <svg class="animate-spin h-6 w-6 mx-auto text-emerald-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Memuat data...
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="p-4 border-t border-gray-100 flex items-center justify-between bg-gray-50/30">
        <span id="paginationInfo" class="text-xs text-gray-500">Menghitung data...</span>
        <div id="paginationControls" class="flex space-x-1"></div>
    </div>
</div>

<div id="editUserModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900">Edit Data Pengguna</h3>
            <button type="button" onclick="toggleModal('editUserModal')" class="text-gray-400 hover:text-red-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="editUserForm" class="p-6">
            <div class="space-y-4">
                <div><label class="block text-xs font-medium text-gray-700 mb-1">Nama Lengkap</label><input type="text" id="u_name" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 outline-none"></div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-xs font-medium text-gray-700 mb-1">Role Akun</label><select id="u_role" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none"><option value="user">User Biasa</option><option value="admin">Administrator</option></select></div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Kelas Target</label>
                        <select id="u_class" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none">
                            <option value="">Belum Diatur</option>
                            <option value="Kelas 1">Kelas 1</option>
                            <option value="Kelas 2">Kelas 2</option>
                            <option value="Kelas 3">Kelas 3</option>
                            <option value="Kelas 4">Kelas 4</option>
                            <option value="Kelas 5">Kelas 5</option>
                            <option value="Kelas 6">Kelas 6</option>
                            <option value="Guru/Staff">Guru / Staff</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3 pt-2">
                    <div><label class="block text-xs font-medium text-gray-700 mb-1">Umur</label><input type="number" id="u_age" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none"></div>
                    <div><label class="block text-xs font-medium text-gray-700 mb-1">Berat (Kg)</label><input type="number" id="u_weight" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none"></div>
                    <div><label class="block text-xs font-medium text-gray-700 mb-1">Tinggi (Cm)</label><input type="number" id="u_height" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none"></div>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-100">
                <button type="button" onclick="toggleModal('editUserModal')" class="px-5 py-2 rounded-xl text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Batal</button>
                <button type="submit" id="btnSubmitUser" class="px-5 py-2 rounded-xl text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-md">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteUserModal" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all">
        <div class="p-6 text-center">
            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Pengguna?</h3>
            <p class="text-sm text-gray-500 mb-6">Tindakan ini tidak dapat dibatalkan. Seluruh data pengguna ini akan hilang.</p>
            <div class="flex justify-center space-x-3">
                <button type="button" onclick="toggleModal('deleteUserModal')" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition flex-1">Batal</button>
                <button type="button" id="btnConfirmDeleteUser" onclick="executeDeleteUser()" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-red-500 hover:bg-red-600 transition shadow-md flex-1">Ya, Hapus!</button>
            </div>
        </div>
    </div>
</div>

<script>
    const token = localStorage.getItem('jwt_token');
    if (!token) window.location.href = '/login';

    let allUsersData = []; 
    let currentUserId = null;
    let userToDeleteId = null;
    let currentPage = 1;

    function toggleModal(modalID) {
        document.getElementById(modalID).classList.toggle('hidden');
    }

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    async function fetchUsers(page = 1) {
        currentPage = page;
        const searchVal = document.getElementById('searchInput').value;
        const roleVal = document.getElementById('roleFilter').value;

        document.getElementById('userTableBody').innerHTML = '<tr><td colspan="6" class="py-8 text-center text-gray-400"><svg class="animate-spin h-6 w-6 mx-auto text-emerald-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memuat data...</td></tr>';

        const params = new URLSearchParams({
            page: page,
            search: searchVal,
            role: roleVal
        });

        try {
            const res = await fetch(`/api/users?${params.toString()}`, {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            
            if (!res.ok) throw new Error('Gagal memuat data');

            const responseData = await res.json();
            allUsersData = responseData.data; 
            
            renderTable(allUsersData);
            renderPagination(responseData);

        } catch (error) {
            document.getElementById('userTableBody').innerHTML = '<tr><td colspan="6" class="py-8 text-center text-red-500 text-sm">Gagal memuat data dari server.</td></tr>';
        }
    }

    function renderTable(users) {
        const tbody = document.getElementById('userTableBody');
        tbody.innerHTML = ''; 

        if(users.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="py-8 text-center text-gray-400">Tidak ada data yang ditemukan.</td></tr>';
            return;
        }

        users.forEach(user => {
            const userId = user._id || user.id;
            const roleBadge = user.role === 'admin' 
                ? '<span class="bg-red-50 text-red-600 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase">Admin</span>' 
                : '<span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase">User</span>';
            
            const fisikInfo = (user.age && user.weight && user.height) 
                ? `${user.age}th / ${user.weight}kg / ${user.height}cm` 
                : '<span class="text-gray-400 italic">Belum diisi</span>';
            
            // Format Kelas yang cantik
            const classInfo = user.class_room 
                ? `<span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2 py-1 rounded border border-gray-200">${user.class_room}</span>` 
                : `<span class="text-gray-400 italic text-xs">-</span>`;

            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50 transition-colors';
            tr.innerHTML = `
                <td class="py-4 px-6">
                    <p class="font-bold text-gray-800">${user.name}</p>
                    <p class="text-xs text-gray-500">${user.email}</p>
                </td>
                <td class="py-4 px-6">${roleBadge}</td>
                <td class="py-4 px-6">${classInfo}</td>
                <td class="py-4 px-6 text-gray-600 text-xs font-medium">${fisikInfo}</td>
                <td class="py-4 px-6"><span class="text-emerald-600 flex items-center text-[11px] font-bold"><div class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></div>Aktif</span></td>
                <td class="py-4 px-6 text-right space-x-3">
                    <button onclick="editUser('${userId}')" class="text-blue-500 hover:text-blue-700 font-semibold text-xs transition">Edit</button>
                    <button onclick="promptDeleteUser('${userId}')" class="text-red-500 hover:text-red-700 font-semibold text-xs transition">Hapus</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function renderPagination(meta) {
        document.getElementById('paginationInfo').innerText = `Menampilkan ${meta.from || 0} - ${meta.to || 0} dari ${meta.total} pengguna`;
        const controls = document.getElementById('paginationControls');
        controls.innerHTML = '';

        if (meta.current_page > 1) {
            controls.innerHTML += `<button onclick="fetchUsers(${meta.current_page - 1})" class="px-3 py-1 bg-white border border-gray-200 text-gray-600 rounded-md hover:bg-gray-50 text-xs font-medium transition">Prev</button>`;
        } else {
            controls.innerHTML += `<button disabled class="px-3 py-1 bg-gray-50 border border-gray-200 text-gray-400 rounded-md text-xs font-medium cursor-not-allowed">Prev</button>`;
        }

        controls.innerHTML += `<span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-md text-xs font-bold">${meta.current_page} / ${meta.last_page}</span>`;

        if (meta.current_page < meta.last_page) {
            controls.innerHTML += `<button onclick="fetchUsers(${meta.current_page + 1})" class="px-3 py-1 bg-white border border-gray-200 text-gray-600 rounded-md hover:bg-gray-50 text-xs font-medium transition">Next</button>`;
        } else {
            controls.innerHTML += `<button disabled class="px-3 py-1 bg-gray-50 border border-gray-200 text-gray-400 rounded-md text-xs font-medium cursor-not-allowed">Next</button>`;
        }
    }

    document.getElementById('searchInput').addEventListener('input', debounce(() => fetchUsers(1), 500));
    document.getElementById('roleFilter').addEventListener('change', () => fetchUsers(1));

    function editUser(id) {
        currentUserId = id;
        const user = allUsersData.find(u => (u._id === id || u.id === id));
        if (user) {
            document.getElementById('u_name').value = user.name || '';
            document.getElementById('u_role').value = user.role || 'user';
            document.getElementById('u_class').value = user.class_room || ''; // Tampilkan nilai kelas
            document.getElementById('u_age').value = user.age || '';
            document.getElementById('u_weight').value = user.weight || '';
            document.getElementById('u_height').value = user.height || '';
            toggleModal('editUserModal');
        }
    }

    document.getElementById('editUserForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmitUser');
        btn.innerText = 'Menyimpan...';
        btn.disabled = true;

        const payload = {
            name: document.getElementById('u_name').value,
            role: document.getElementById('u_role').value,
            class_room: document.getElementById('u_class').value, // Ambil dan simpan nilai kelas
            age: document.getElementById('u_age').value,
            weight: document.getElementById('u_weight').value,
            height: document.getElementById('u_height').value
        };

        try {
            const res = await fetch(`/api/users/${currentUserId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
                body: JSON.stringify(payload)
            });

            if (res.ok) {
                toggleModal('editUserModal');
                fetchUsers(currentPage); 
            } else {
                alert('Gagal memperbarui data pengguna.');
            }
        } catch (e) { alert('Server error.'); } 
        finally {
            btn.innerText = 'Simpan Perubahan';
            btn.disabled = false;
        }
    });

    function promptDeleteUser(id) {
        userToDeleteId = id;
        toggleModal('deleteUserModal');
    }

    async function executeDeleteUser() {
        if (!userToDeleteId) return;
        const btn = document.getElementById('btnConfirmDeleteUser');
        btn.innerText = 'Menghapus...';
        btn.disabled = true;

        try {
            const res = await fetch(`/api/users/${userToDeleteId}`, { 
                method: 'DELETE', headers: { 'Authorization': `Bearer ${token}` }
            });
            const data = await res.json();

            if (res.ok) {
                toggleModal('deleteUserModal');
                fetchUsers(currentPage); 
            } else {
                alert(data.error || 'Gagal menghapus pengguna.');
                toggleModal('deleteUserModal');
            }
        } catch (e) { alert('Error server.'); } 
        finally {
            btn.innerText = 'Ya, Hapus!';
            btn.disabled = false;
            userToDeleteId = null;
        }
    }

    fetchUsers(1);
</script>
@endsection