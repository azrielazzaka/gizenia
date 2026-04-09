@extends('layouts.user')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-900">Riwayat Distribusi Makanan</h2>
    <p class="text-gray-500 text-sm mt-1">Lacak seluruh rekam jejak penerimaan nutrisi harian Anda di sini.</p>
</div>

<div class="bg-white rounded-3xl p-6 shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-gray-50 min-h-[60vh] flex flex-col">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-gray-100 text-[11px] uppercase tracking-wider text-gray-400 bg-gray-50/50">
                    <th class="py-4 px-6 font-semibold rounded-tl-xl">Tanggal</th>
                    <th class="py-4 px-6 font-semibold">Menu Didistribusikan</th>
                    <th class="py-4 px-6 font-semibold text-center">Status Anda</th>
                    <th class="py-4 px-6 font-semibold text-right rounded-tr-xl">Catatan Waktu</th>
                </tr>
            </thead>
            <tbody id="historyTableBody" class="text-sm">
                <tr><td colspan="4" class="py-12 text-center text-gray-400">Memuat data riwayat...</td></tr>
            </tbody>
        </table>
    </div>
    
    <div class="mt-auto pt-6 flex justify-between items-center border-t border-gray-50">
        <span id="historyInfo" class="text-xs text-gray-500 font-medium">Menampilkan 0 data</span>
        <div id="historyPagination" class="flex space-x-2"></div>
    </div>
</div>

<script>
    const token = localStorage.getItem('jwt_token');
    if (!token) window.location.href = '/login';

    let currentUserId = null;

    async function init() {
        try {
            const res = await fetch('/api/auth/me', { headers: { 'Authorization': `Bearer ${token}` }});
            if(res.ok) {
                const user = await res.json();
                currentUserId = user._id || user.id;
                loadHistory(1);
            }
        } catch(e) {}
    }

    async function loadHistory(page = 1) {
        if (!currentUserId) return;
        try {
            const res = await fetch(`/api/user/history?page=${page}`, { headers: { 'Authorization': `Bearer ${token}` }});
            const data = await res.json();
            
            const tbody = document.getElementById('historyTableBody');
            tbody.innerHTML = '';

            if(data.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="py-12 text-center text-gray-400">Belum ada riwayat distribusi makanan.</td></tr>';
                return;
            }

            data.data.forEach(dist => {
                const foodsText = dist.foods.map(f => `<span class="font-semibold text-gray-800">${f.name}</span> <span class="text-gray-400 text-xs">(${f.weight}g)</span>`).join(', ');
                let statusHtml = `<span class="bg-yellow-50 border border-yellow-100 text-yellow-600 text-[10px] font-bold px-3 py-1.5 rounded-full"><i class="fas fa-clock mr-1"></i> BELUM DIJAWAB</span>`;
                let timeHtml = '-';
                
                if (dist.responses && dist.responses.length > 0) {
                    const userResponse = dist.responses.find(r => r.user_id === currentUserId);
                    if (userResponse) {
                        timeHtml = new Date(userResponse.responded_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                        if (userResponse.answer === 'Ya') {
                            statusHtml = `<span class="bg-green-50 border border-green-100 text-emerald-600 text-[10px] font-bold px-3 py-1.5 rounded-full"><i class="fas fa-check mr-1"></i> SUDAH DITERIMA</span>`;
                        } else {
                            statusHtml = `<span class="bg-gray-100 border border-gray-200 text-gray-500 text-[10px] font-bold px-3 py-1.5 rounded-full"><i class="fas fa-times mr-1"></i> TIDAK DITERIMA</span>`;
                        }
                    }
                }

                tbody.innerHTML += `
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                        <td class="py-5 px-6 font-bold text-gray-900">${dist.distribution_date}</td>
                        <td class="py-5 px-6">${foodsText}</td>
                        <td class="py-5 px-6 text-center">${statusHtml}</td>
                        <td class="py-5 px-6 font-bold text-gray-400 text-right text-xs">${timeHtml} WIB</td>
                    </tr>
                `;
            });

            // Kontrol Paginasi
            document.getElementById('historyInfo').innerText = `Menampilkan ${data.from || 0} - ${data.to || 0} dari total ${data.total} catatan`;
            const controls = document.getElementById('historyPagination');
            controls.innerHTML = '';
            
            if (data.current_page > 1) controls.innerHTML += `<button onclick="loadHistory(${data.current_page - 1})" class="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 text-xs font-bold transition shadow-sm">Sebelumnya</button>`;
            controls.innerHTML += `<span class="px-4 py-2 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-xl text-sm font-black shadow-sm">${data.current_page}</span>`;
            if (data.current_page < data.last_page) controls.innerHTML += `<button onclick="loadHistory(${data.current_page + 1})" class="px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 text-xs font-bold transition shadow-md">Selanjutnya</button>`;

        } catch(e) {}
    }

    init();
</script>
@endsection