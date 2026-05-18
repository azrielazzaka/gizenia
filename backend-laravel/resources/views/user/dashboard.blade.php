@extends('layouts.user')

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Selamat datang kembali, <span id="userNameHeader">Memuat...</span></h2>
        <p class="text-gray-500 text-sm mt-1">Sekilas kebutuhan nutrisi Anda untuk hari ini.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">

            <div
                class="bg-white rounded-3xl p-8 shadow-[0_2px_10px_rgba(0,0,0,0.04)] relative overflow-hidden flex flex-col justify-between h-72 border border-gray-50">
                <div class="w-2/3 z-10">
                    <span id="dashBalanceBadge"
                        class="inline-flex items-center bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wide mb-4 transition-colors">
                        <div id="dashBalanceDot" class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></div> <span
                            id="dashBalanceText">Menunggu Data</span>
                    </span>
                    <h3 id="dashMainTitle" class="text-3xl font-bold text-gray-900 leading-tight mb-3">Belum ada asupan yang
                        tercatat hari ini.</h3>
                    <p id="dashSubTitle" class="text-xs text-gray-500 leading-relaxed">Konfirmasi penerimaan makanan Anda
                        untuk melihat analisis AI terhadap target nutrisi mikro harian Anda.</p>
                </div>

                <div class="flex space-x-4 mt-6 z-10">
                    <div class="bg-[#F8F9FA] rounded-2xl px-5 py-3 border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Kalori Masuk</p>
                        <p class="text-lg font-bold text-gray-900"><span id="dashCalText"
                                class="text-emerald-600">0</span><span id="dashTargetCalText" class="text-xs text-gray-400">
                                / 0</span></p>
                    </div>
                    <div class="bg-[#F8F9FA] rounded-2xl px-5 py-3 border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Protein</p>
                        <p class="text-lg font-bold text-gray-900"><span id="dashProText"
                                class="text-blue-600">0</span><span class="text-xs text-gray-400"> g</span></p>
                    </div>
                </div>

                <div
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-48 h-48 opacity-90 flex items-center justify-center">
                    <svg class="absolute top-0 right-8 w-16 h-16 text-gray-100" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" />
                    </svg>
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#F1F3F5" stroke-width="8"></circle>
                        <circle id="dashCircle" cx="50" cy="50" r="40" fill="none" stroke="#059669" stroke-width="8"
                            stroke-dasharray="251.2" stroke-dashoffset="251.2" stroke-linecap="round"
                            class="transition-all duration-1000 ease-out"></circle>
                    </svg>
                    <div class="absolute text-center">
                        <span id="dashPercentText" class="text-3xl font-bold text-gray-900">0%</span>
                        <p class="text-[8px] font-bold text-gray-400 uppercase">Target 1x Makan</p>
                    </div>
                </div>
            </div>

            <div id="notificationCard"
                class="hidden bg-white rounded-3xl p-6 shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-emerald-100 items-center justify-between transform transition-all duration-500 hover:scale-[1.01]">
                <div class="flex items-center space-x-4">
                    <div
                        class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-emerald-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">Apakah kamu sudah menerima makanan bergizi hari ini?
                        </h4>
                        <p class="text-sm text-gray-500 mt-1">Menu: <span id="foodListText"
                                class="text-emerald-600 font-semibold">Memuat...</span></p>
                    </div>
                </div>
                <div class="flex space-x-3 ml-4 shrink-0">
                    <button onclick="submitAnswer('Ya')"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-6 rounded-xl flex items-center text-sm shadow-md transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg> Ya
                    </button>
                    <button onclick="submitAnswer('Tidak')"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2.5 px-6 rounded-xl flex items-center text-sm transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg> Tidak
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-gray-50 pb-8">
                <h3 class="font-bold text-lg text-gray-900 mb-6">Riwayat Penerimaan Makanan Bergizi</h3>
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-100 text-[10px] uppercase tracking-wider text-gray-400">
                            <th class="pb-3 font-semibold">Tanggal</th>
                            <th class="pb-3 font-semibold">Menu</th>
                            <th class="pb-3 font-semibold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody" class="text-sm">
                        <tr>
                            <td colspan="3" class="py-8 text-center text-gray-400">Memuat riwayat...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-emerald-600 rounded-3xl p-6 border border-emerald-500/50 h-full">
                <h3 class="font-bold text-lg text-white flex items-center mb-6">
                    <svg class="w-5 h-5 mr-2 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Peringatan<br>Kesehatan
                </h3>

                <div class="space-y-4" id="healthWarningContainer">
                    <div class="bg-white/15 p-4 rounded-2xl border border-white/20 text-center text-sm text-emerald-100">
                        Menghitung profil gizi Anda...
                    </div>
                </div>

                <div class="mt-6 bg-white/10 rounded-xl p-4 border border-white/20 text-center">
                    <p class="text-xs italic text-white font-medium">"Kesehatan adalah kekayaan terbesar." — GIZENIA</p>
                </div>
            </div>
        </div>
    </div>

    <div id="nutritionCheckModal"
        class="fixed inset-0 z-[80] hidden bg-gray-900/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all p-6">
            <div class="text-center mb-6">
                <div id="modalIconBox"
                    class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-robot text-emerald-500 text-2xl" id="modalIcon"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Analisis Gizi Otomatis</h3>
                <p class="text-sm text-gray-500 mt-1">Sistem AI membandingkan asupan ini dengan profil fisik Anda.</p>
            </div>

            <div class="bg-gray-50 rounded-2xl p-4 mb-6">
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div class="bg-white p-3 rounded-xl shadow-sm border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Kalori Total</p>
                        <p class="text-xl font-bold text-emerald-600" id="calcCalories">0</p>
                    </div>
                    <div class="bg-white p-3 rounded-xl shadow-sm border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Target Porsi</p>
                        <p class="text-xl font-bold text-gray-600" id="calcTarget">0</p>
                    </div>
                </div>
            </div>

            <div id="balanceStatus"
                class="flex items-center p-4 bg-emerald-50 text-emerald-700 rounded-xl text-sm font-semibold mb-6 border border-emerald-100 leading-snug">
            </div>

            <button onclick="closeNutritionModal()"
                class="w-full bg-gray-900 hover:bg-black text-white font-bold py-3.5 px-4 rounded-xl transition-colors shadow-lg">
                Terapkan ke Dashboard
            </button>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('jwt_token');
        if (!token) window.location.href = '/login';

        let activeDistributionData = null;
        let currentUserId = null;
        let allMenusData = [];

        // VARIABEL CERDAS AI
        let userTargetMealCalories = 2100;
        let userBmi = 20;

        function toggleModal(id) { document.getElementById(id).classList.toggle('hidden'); }
        function closeNutritionModal() { toggleModal('nutritionCheckModal'); }

        // 0. Tarik Database Nutrisi
        async function loadMenusDatabase() {
    try {

        const res = await fetch('/api/menus', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        const data = await res.json();

        console.log("MENU RESPONSE :", data);

        // FIX
        allMenusData = data.all_menus?.data || data.data || data || [];

        console.log("ALL MENUS FIX :", allMenusData);

    } catch (e) {

        console.log("ERROR MENU :", e);

    }
}

        // 1. Ambil Profil & Hitung Target (BMR, TDEE, BMI)
        async function loadProfile() {
            try {
                const res = await fetch('/api/auth/me', { headers: { 'Authorization': `Bearer ${token}` } });
                if (res.ok) {
                    const user = await res.json();
                    currentUserId = user._id || user.id;
                    document.getElementById('userNameHeader').innerText = user.name.split(' ')[0];

                    // KALKULATOR FISIK AI
                    const w = user.weight || 40;
                    const h = user.height || 140;
                    const a = user.age || 12;

                    const bmr = (10 * w) + (6.25 * h) - (5 * a) + 5;
                    const tdee = bmr * 1.375;
                    userTargetMealCalories = Math.round(tdee * 0.35); // 35% untuk 1x makan

                    // Kalkulasi BMI (IMT)
                    const heightInMeter = h / 100;
                    userBmi = w / (heightInMeter * heightInMeter);

                    // Set Target Kalori di UI
                    document.getElementById('dashTargetCalText').innerText = ` / ${userTargetMealCalories} kkal`;

                    // Render Peringatan Kesehatan Khusus
                    generateHealthWarning(userBmi);

                    loadHistory();
                }
            } catch (e) { }
        }

        // Fungsi Render Peringatan Kesehatan
        function generateHealthWarning(bmi) {
            const container = document.getElementById('healthWarningContainer');
            if (bmi < 18.5) {
                container.innerHTML = `
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-blue-100 flex items-start space-x-3">
                        <div class="bg-blue-100 p-2 rounded-xl text-blue-500 shrink-0"><i class="fas fa-arrow-up text-lg"></i></div>
                        <div><h4 class="font-bold text-sm text-gray-900 mb-1">Fokus Berat Badan</h4><p class="text-xs text-gray-500 leading-snug">Indeks Massa Tubuh Anda di bawah standar. Pastikan selalu menghabiskan makanan Anda, terutama sumber protein dan karbohidrat.</p></div>
                    </div>`;
            } else if (bmi >= 25) {
                container.innerHTML = `
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-red-100 flex items-start space-x-3">
                        <div class="bg-red-100 p-2 rounded-xl text-red-500 shrink-0"><i class="fas fa-leaf text-lg"></i></div>
                        <div><h4 class="font-bold text-sm text-gray-900 mb-1">Kontrol Kalori Berlebih</h4><p class="text-xs text-gray-500 leading-snug">Berat badan Anda melebihi batas ideal. Utamakan menghabiskan porsi sayuran dan serat untuk mengontrol gula darah.</p></div>
                    </div>`;
            } else {
                container.innerHTML = `
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-emerald-100 flex items-start space-x-3">
                        <div class="bg-emerald-100 p-2 rounded-xl text-emerald-500 shrink-0"><i class="fas fa-check-circle text-lg"></i></div>
                        <div><h4 class="font-bold text-sm text-gray-900 mb-1">Status Ideal</h4><p class="text-xs text-gray-500 leading-snug">Pertumbuhan Anda sangat baik! Pertahankan asupan menu gizi seimbang ini setiap hari agar daya tahan tubuh maksimal.</p></div>
                    </div>`;
            }
        }

        // 2. Cek Notifikasi
        async function checkNotification() {
            try {
                const res = await fetch('/api/user/notification', { headers: { 'Authorization': `Bearer ${token}` } });
                const data = await res.json();
                if (data.has_notification) {
                    activeDistributionData = data.distribution;
                    const foodsText = data.distribution.foods.map(f => `${f.name} (${f.weight}g)`).join(', ');
                    document.getElementById('foodListText').innerText = foodsText;
                    const card = document.getElementById('notificationCard');
                    card.classList.remove('hidden'); card.classList.add('flex');
                }
            } catch (e) { }
        }

        // 3. Jawab & HITUNG GIZI OTOMATIS
        async function submitAnswer(answer) {
            if (!activeDistributionData) return;
            const distId = activeDistributionData._id || activeDistributionData.id;

            try {
                const res = await fetch(`/api/user/distributions/${distId}/respond`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
                    body: JSON.stringify({ answer: answer })
                });

                if (res.ok) {
                    document.getElementById('notificationCard').classList.add('hidden');
                    document.getElementById('notificationCard').classList.remove('flex');

                   if (answer === 'Ya') {

    let totalCals = 0;
    let totalPro = 0;

    activeDistributionData.foods.forEach(f => {

        console.log("FOOD :", f);

        const menuData = allMenusData.find(m =>
            String(m._id || m.id) === String(
                f.menu_id || f.menu?._id || f.menu?.id
            )
        );

        console.log("MENU FOUND :", menuData);

        if (menuData) {

            console.log("FULL MENU DATA :", menuData);

            const foodWeight = parseFloat(f.weight || 100);

            const servingSize = parseFloat(
                menuData.serving_size_g ||
                menuData.serving_size ||
                100
            );

            const ratio = foodWeight / servingSize;

            const calories = parseFloat(
                menuData.calories ||
                menuData.kalori ||
                menuData.energy ||
                menuData.kalori_total ||
                0
            );

            const protein = parseFloat(
                menuData.protein ||
                menuData.protein_g ||
                menuData.protein_total ||
                0
            );

            totalCals += calories * ratio;
            totalPro += protein * ratio;
        }
    });

    console.log("TOTAL CAL :", totalCals);
    console.log("TOTAL PRO :", totalPro);

    // Tentukan Keputusan AI
    const box = document.getElementById('balanceStatus');
    const iconBox = document.getElementById('modalIconBox');
    const icon = document.getElementById('modalIcon');

    const lowerBound = userTargetMealCalories * 0.8;
    const upperBound = userTargetMealCalories * 1.2;

    if (totalCals < lowerBound) {

        box.className =
            "flex items-center p-4 bg-yellow-50 text-yellow-700 rounded-xl text-sm font-semibold mb-6 border border-yellow-200 leading-snug";

        box.innerHTML =
            '<i class="fas fa-exclamation-circle text-2xl mr-3"></i> Menu ini <b>KURANG</b> dari kebutuhan kalori Anda. Anda mungkin merasa cepat lapar.';

        iconBox.className =
            "w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4";

        icon.className =
            "fas fa-battery-quarter text-yellow-500 text-2xl";

    } else if (totalCals > upperBound) {

        box.className =
            "flex items-center p-4 bg-red-50 text-red-700 rounded-xl text-sm font-semibold mb-6 border border-red-200 leading-snug";

        box.innerHTML =
            '<i class="fas fa-fire text-2xl mr-3"></i> Menu ini <b>BERLEBIHAN</b>. Kalorinya melebihi kapasitas standar makan utama Anda.';

        iconBox.className =
            "w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4";

        icon.className =
            "fas fa-chart-line text-red-500 text-2xl";

    } else {

        box.className =
            "flex items-center p-4 bg-emerald-50 text-emerald-700 rounded-xl text-sm font-semibold mb-6 border border-emerald-200 leading-snug";

        box.innerHTML =
            '<i class="fas fa-check-circle text-2xl mr-3"></i> Sangat <b>SEIMBANG!</b> Menu ini pas untuk pertumbuhan optimal Anda.';

        iconBox.className =
            "w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4";

        icon.className =
            "fas fa-robot text-emerald-500 text-2xl";
    }

    document.getElementById('calcCalories').innerHTML =
        `${Math.round(totalCals)} <span class="text-xs text-gray-500">kkal</span>`;

    document.getElementById('calcTarget').innerHTML =
        `${Math.round(userTargetMealCalories)} <span class="text-xs text-gray-500">kkal</span>`;

    toggleModal('nutritionCheckModal');

    updateDashboardCards(totalCals, totalPro);
}
                    loadHistory();
                }
            } catch (e) { }
        }

        // 4. Update Dashboard UI (Warna menyesuaikan Kurang/Pas/Lebih)
        function updateDashboardCards(calories, protein) {
            let percent = Math.round((calories / userTargetMealCalories) * 100);

            document.getElementById('dashCalText').innerText = Math.round(calories).toLocaleString();
            document.getElementById('dashProText').innerText = Math.round(protein).toLocaleString();
            document.getElementById('dashPercentText').innerText = percent + '%';

            const badge = document.getElementById('dashBalanceBadge');
            const dot = document.getElementById('dashBalanceDot');
            const text = document.getElementById('dashBalanceText');
            const title = document.getElementById('dashMainTitle');
            const circle = document.getElementById('dashCircle');

            // Logic UI Dashboard
            if (percent < 80) {
                badge.className = "inline-flex items-center bg-yellow-100 text-yellow-700 text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wide mb-4 transition-colors";
                dot.className = "w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5";
                text.innerText = "Defisit Gizi";
                title.innerText = "Asupan kalori Anda masih kurang.";
                circle.style.stroke = "#EAB308"; // Kuning
            } else if (percent > 120) {
                badge.className = "inline-flex items-center bg-red-100 text-red-700 text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wide mb-4 transition-colors";
                dot.className = "w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5";
                text.innerText = "Kalori Berlebih";
                title.innerText = "Asupan kalori melebihi target.";
                circle.style.stroke = "#EF4444"; // Merah
            } else {
                badge.className = "inline-flex items-center bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wide mb-4 transition-colors";
                dot.className = "w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5";
                text.innerText = "Seimbang";
                title.innerText = "Asupan Anda teroptimasi dengan sempurna.";
                circle.style.stroke = "#059669"; // Hijau
            }

            document.getElementById('dashSubTitle').innerText = `Anda telah mencapai ${percent}% dari target 1x makan utama Anda.`;

            if (percent > 100) percent = 100; // Lingkaran max 100%
            const offset = 251.2 - (251.2 * (percent / 100));
            setTimeout(() => { circle.style.strokeDashoffset = offset; }, 500);
        }

        // 5. Load Riwayat (Hanya untuk tabel kecil)
        async function loadHistory() {
            if (!currentUserId) return;
            try {
                const res = await fetch(`/api/user/history?page=1`, { headers: { 'Authorization': `Bearer ${token}` } });
                const data = await res.json();
                const tbody = document.getElementById('historyTableBody');
                tbody.innerHTML = '';

                if (data.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3" class="py-8 text-center text-gray-400">Belum ada riwayat.</td></tr>';
                    return;
                }

                // Ambil 3 teratas saja
                data.data.slice(0, 3).forEach(dist => {
                    const foodsText = dist.foods.map(f => f.name).join(', ');
                    let statusHtml = `<span class="text-yellow-500 text-[10px] font-bold">BELUM DIJAWAB</span>`;
                    if (dist.responses && dist.responses.length > 0) {
                        const r = dist.responses.find(x => x.user_id === currentUserId);
                        if (r) statusHtml = r.answer === 'Ya' ? `<span class="text-emerald-500 text-[10px] font-bold">DITERIMA</span>` : `<span class="text-gray-400 text-[10px] font-bold">TIDAK DITERIMA</span>`;
                    }
                    tbody.innerHTML += `
                        <tr class="border-b border-gray-50">
                            <td class="py-3 text-xs text-gray-800">${dist.distribution_date}</td>
                            <td class="py-3 text-xs text-gray-500 truncate max-w-[150px]">${foodsText}</td>
                            <td class="py-3 text-center">${statusHtml}</td>
                        </tr>`;
                });
            } catch (e) { }
        }

        async function loadTodayNutrition() {
    try {
        const res = await fetch(`/api/user/history?page=1`, {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        const data = await res.json();
        if (!data.data || data.data.length === 0) return;

        let totalCals = 0;
        let totalPro = 0;

        // Ambil tanggal hari ini dalam format YYYY-MM-DD (sesuaikan dengan format API Anda)
        const today = new Date().toISOString().split('T')[0];

        data.data.forEach(dist => {
            // FILTER: Hanya hitung jika tanggal distribusi adalah HARI INI
            // Asumsi dist.distribution_date formatnya adalah "YYYY-MM-DD"
            if (dist.distribution_date === today) {
                
                const accepted = dist.responses?.find(r =>
                    r.user_id === currentUserId &&
                    r.answer === 'Ya'
                );

                if (accepted) {
                    dist.foods.forEach(f => {
                        const menuData = allMenusData.find(m =>
                            String(m._id || m.id) === String(f.menu_id || f.menu?._id || f.menu?.id)
                        );

                        if (menuData) {
                            const foodWeight = parseFloat(f.weight || 100);
                            const servingSize = parseFloat(menuData.serving_size_g || menuData.serving_size || 100);
                            const ratio = foodWeight / servingSize;

                            const calories = parseFloat(menuData.calories || menuData.kalori || menuData.kalori_total || 0);
                            const protein = parseFloat(menuData.protein || menuData.protein_g || 0);

                            totalCals += calories * ratio;
                            totalPro += protein * ratio;
                        }
                    });
                }
            }
        });

        // Jika tidak ada data hari ini, totalCals akan tetap 0 (Otomatis Reset)
        updateDashboardCards(totalCals, totalPro);

    } catch (e) {
        console.log("Error loading today's nutrition:", e);
    }
}
(async () => {

    await loadMenusDatabase();
    await loadProfile();
    await loadTodayNutrition();
    await checkNotification();

})();
    </script>
@endsection