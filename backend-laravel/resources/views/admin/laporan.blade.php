@extends('layouts.admin')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
    <div>
        <h2 class="text-3xl font-bold text-gray-900">Laporan & Analitik</h2>
        <p class="text-gray-500 text-sm mt-1">Pantau ringkasan distribusi dan status penerimaan siswa.</p>
    </div>
    <div class="flex space-x-3">
        <button id="btnExport" onclick="exportExcel()" class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-4 py-2.5 rounded-xl text-sm font-bold shadow-sm flex items-center transition-colors">
            <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Ekspor
        </button>
        <button onclick="window.print()" class="bg-gray-900 hover:bg-black text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-md flex items-center transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg> PDF
        </button>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Mulai</label>
            <input type="date" id="startDate" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-emerald-500">
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Sampai</label>
            <input type="date" id="endDate" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-emerald-500">
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Status</label>
            <select id="statusFilter" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="Semua">Semua Status</option>
                <option value="SUDAH DITERIMA">Diterima</option>
                <option value="TIDAK DITERIMA">Tidak Diterima</option>
                <option value="BELUM DIJAWAB">Belum Dijawab</option>
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Kelas</label>
            <select id="classFilter" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="Semua">Semua Kelas</option>
                <option value="Kelas 1">Kelas 1</option>
                <option value="Kelas 2">Kelas 2</option>
                <option value="Kelas 3">Kelas 3</option>
                <option value="Kelas 4">Kelas 4</option>
                <option value="Kelas 5">Kelas 5</option>
                <option value="Kelas 6">Kelas 6</option>
            </select>
        </div>
        <button onclick="fetchReport(1)" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold transition-all">Filter</button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    
    <div class="bg-white rounded-2xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 flex flex-col justify-between group hover:border-emerald-300 hover:shadow-[0_8px_30px_rgb(16,185,129,0.1)] transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-emerald-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-1">Total Porsi Terkonfirmasi</p>
                <h3 class="text-4xl font-extrabold text-slate-800 tracking-tight" id="totDistributed">0</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center border border-emerald-200 text-emerald-600">
                <i class="fas fa-box-open text-lg"></i>
            </div>
        </div>
        
        <div class="flex items-center mt-2">
            <span class="flex items-center text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md text-[10px] font-bold mr-2 border border-emerald-200">
                <i class="fas fa-check-circle mr-1.5"></i> Real-time
            </span>
            <span class="text-slate-400 text-xs font-medium">Dari target distribusi hari ini</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 flex flex-col justify-between group hover:border-indigo-300 hover:shadow-[0_8px_30px_rgb(99,102,241,0.1)] transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-1">Kepatuhan Target</p>
                <div class="flex items-baseline">
                    <h3 class="text-4xl font-extrabold text-slate-800 tracking-tight" id="avgFulfilled">0</h3>
                    <span class="text-2xl font-bold text-slate-400 ml-1">%</span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center border border-indigo-200 text-indigo-600">
                <i class="fas fa-chart-line text-lg"></i>
            </div>
        </div>
        
        <div class="w-full bg-slate-100 rounded-full h-2 mt-2 mb-1 overflow-hidden border border-slate-200">
            <div class="bg-gradient-to-r from-indigo-400 to-indigo-600 h-full rounded-full transition-all duration-1000 ease-out" id="progressAvg" style="width: 0%"></div>
        </div>
        <p class="text-slate-400 text-[10px] font-medium mt-1.5">Tingkat penerimaan gizi sasaran</p>
    </div>

</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left" id="reportTable">
        <thead>
            <tr class="bg-gray-50/50 border-b border-gray-100 text-[10px] uppercase tracking-wider text-gray-400">
                <th class="py-4 px-6 font-bold">Waktu</th>
                <th class="py-4 px-6 font-bold">Siswa</th>
                <th class="py-4 px-6 font-bold">Kelas</th>
                <th class="py-4 px-6 font-bold">Menu</th>
                <th class="py-4 px-6 font-bold text-center">Status</th>
            </tr>
        </thead>
        <tbody id="reportTableBody" class="text-sm divide-y divide-gray-50">
            <tr><td colspan="5" class="py-12 text-center text-gray-400">Menarik data...</td></tr>
        </tbody>
    </table>
    
    <div class="p-4 border-t border-gray-100 flex items-center justify-between bg-gray-50/30">
        <span id="paginationInfo" class="text-xs text-gray-400 font-medium">Menghitung...</span>
        <div id="paginationControls" class="flex space-x-1"></div>
    </div>
</div>

<script>
    const token = localStorage.getItem('jwt_token');
    if (!token) window.location.href = '/login';

    document.getElementById('startDate').valueAsDate = new Date();
    document.getElementById('endDate').valueAsDate = new Date();

    // 1. FUNGSI UNTUK TABEL (MENGGUNAKAN PAGINASI 25)
    async function fetchReport(page = 1) {
        const params = new URLSearchParams({
            start_date: document.getElementById('startDate').value,
            end_date: document.getElementById('endDate').value,
            status: document.getElementById('statusFilter').value,
            class_room: document.getElementById('classFilter').value,
            page: page
        });

        const tbody = document.getElementById('reportTableBody');
        tbody.innerHTML = '<tr><td colspan="5" class="py-10 text-center"><i class="fas fa-spinner fa-spin text-emerald-500"></i></td></tr>';

        try {
            const res = await fetch(`/api/reports?${params.toString()}`, {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            const data = await res.json();
            const report = data.report_data; // Objek Paginasi

            document.getElementById('totDistributed').innerText = data.total_distributed;
            document.getElementById('avgFulfilled').innerText = data.avg_fulfilled;

            tbody.innerHTML = '';
            if (report.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="py-10 text-center text-gray-400">Data tidak ditemukan.</td></tr>';
            } else {
                report.data.forEach(row => {
                    let badge = row.status === 'SUDAH DITERIMA' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 
                                (row.status === 'TIDAK DITERIMA' ? 'bg-red-50 text-red-600 border-red-100' : 'bg-yellow-50 text-yellow-600 border-yellow-100');
                    
                    tbody.innerHTML += `
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6 text-gray-500 font-medium">${row.date}<br><span class="text-[10px]">${row.time}</span></td>
                            <td class="py-4 px-6 font-bold text-gray-800">${row.user_name}</td>
                            <td class="py-4 px-6"><span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-[10px] font-bold uppercase border">${row.class_room}</span></td>
                            <td class="py-4 px-6 text-gray-500 text-xs">${row.menu}</td>
                            <td class="py-4 px-6 text-center"><span class="${badge} border text-[10px] font-bold px-3 py-1 rounded-full uppercase">${row.status}</span></td>
                        </tr>
                    `;
                });
            }

            renderPagination(report);

        } catch (e) { tbody.innerHTML = '<tr><td colspan="5" class="py-10 text-center text-red-500">Gagal memuat data.</td></tr>'; }
    }

    function renderPagination(meta) {
        document.getElementById('paginationInfo').innerText = `Data ${meta.from || 0} - ${meta.to || 0} dari ${meta.total}`;
        const controls = document.getElementById('paginationControls');
        controls.innerHTML = '';

        if (meta.current_page > 1) controls.innerHTML += `<button onclick="fetchReport(${meta.current_page - 1})" class="px-3 py-1 bg-white border border-gray-200 text-gray-500 rounded-lg hover:bg-gray-50 text-xs font-bold transition">Prev</button>`;
        controls.innerHTML += `<span class="px-4 py-1 text-emerald-700 text-xs font-black">${meta.current_page}</span>`;
        if (meta.current_page < meta.last_page) controls.innerHTML += `<button onclick="fetchReport(${meta.current_page + 1})" class="px-3 py-1 bg-white border border-gray-200 text-gray-500 rounded-lg hover:bg-gray-50 text-xs font-bold transition">Next</button>`;
    }

    // 2. FUNGSI UNTUK EKSPOR (MENGAMBIL SELURUH DATA TANPA PAGINASI)
    async function exportExcel() {
        const btn = document.getElementById('btnExport');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
        btn.disabled = true;

        const start = document.getElementById('startDate').value;
        const end = document.getElementById('endDate').value;

        const params = new URLSearchParams({
            start_date: start,
            end_date: end,
            status: document.getElementById('statusFilter').value,
            class_room: document.getElementById('classFilter').value,
            export: 'true' // <-- PARAMETER AJAIB INI AKAN MEMAKSA BACKEND MENGIRIM SEMUA DATA
        });

        try {
            const res = await fetch(`/api/reports?${params.toString()}`, {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            
            const data = await res.json();
            const fullData = data.report_data; // Array langsung, bukan objek paginasi

            if (!fullData || fullData.length === 0) {
                alert('Tidak ada data untuk filter tersebut yang bisa diekspor.');
                return;
            }

            let csv = "Tanggal,Waktu,Siswa,Kelas,Menu,Status\n";
            fullData.forEach(r => {
                const safeMenu = `"${r.menu}"`; // Mencegah koma merusak struktur kolom
                csv += `${r.date},${r.time},${r.user_name},${r.class_room},${safeMenu},${r.status}\n`;
            });

            // Eksekusi Download
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.setAttribute('href', url);
            a.setAttribute('download', `Laporan_GIZENIA_${start}_sd_${end}.csv`);
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

        } catch (e) {
            alert('Terjadi kesalahan saat mengekspor data.');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    fetchReport(1);
</script>
@endsection