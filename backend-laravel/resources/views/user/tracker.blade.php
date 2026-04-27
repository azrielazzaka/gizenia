@extends('layouts.user')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-900">Pelacak Nutrisi Cerdas</h2>
    <p class="text-gray-500 text-sm mt-1">Rancang menu makanmu sendiri dan biarkan AI mengevaluasi keseimbangan gizinya.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-3xl p-6 shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-gray-50">
            <h3 class="font-bold text-lg text-gray-900 mb-4">Tambahkan Makanan</h3>
            
            <div class="flex flex-col md:flex-row gap-4 mb-2">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pilih dari Database</label>
                    <select id="menuSelect" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium outline-none focus:ring-2 focus:ring-emerald-500 transition-shadow">
                        <option value="">Memuat menu...</option>
                    </select>
                </div>
                <div class="w-full md:w-1/3">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Porsi (Gram)</label>
                    <input type="number" id="portionInput" value="100" min="1" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold outline-none focus:ring-2 focus:ring-emerald-500 transition-shadow">
                </div>
            </div>
            
            <button onclick="addFoodToCart()" class="w-full mt-4 bg-gray-900 hover:bg-black text-white font-bold py-3 rounded-xl shadow-md transition-colors flex justify-center items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Masukkan ke Piring Saya
            </button>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-gray-50 min-h-[300px]">
            <h3 class="font-bold text-lg text-gray-900 mb-4 border-b border-gray-50 pb-4">Isi Piring Saya</h3>
            <ul id="foodCartList" class="space-y-3">
                <li class="py-8 text-center text-gray-400 text-sm">Piring masih kosong. Tambahkan makanan di atas.</li>
            </ul>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-emerald-600 rounded-3xl p-6 shadow-lg shadow-emerald-500/30 text-white relative overflow-hidden h-full">
            <svg class="absolute top-0 right-0 w-32 h-32 text-white opacity-10 transform translate-x-10 -translate-y-10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>

            <h3 class="font-bold text-lg mb-6 relative z-10 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                Analisis Profil Gizi
            </h3>

            <div class="bg-white/10 rounded-2xl p-4 backdrop-blur-sm border border-white/20 mb-6 relative z-10">
                <p class="text-[10px] font-bold text-emerald-100 uppercase tracking-wider mb-1">Target 1x Makan Utama</p>
                <div class="flex items-baseline">
                    <h2 class="text-4xl font-extrabold" id="targetMeal">0</h2>
                    <span class="text-emerald-200 ml-1 font-medium">kkal</span>
                </div>
            </div>

            <div class="space-y-4 relative z-10">
                <div>
                    <div class="flex justify-between text-xs font-bold text-emerald-100 mb-1">
                        <span>Total Kalori Saat Ini</span>
                        <span id="currentCals">0 kkal</span>
                    </div>
                    <div class="w-full bg-emerald-800/50 rounded-full h-2"><div id="barCals" class="bg-white h-2 rounded-full transition-all duration-500" style="width: 0%"></div></div>
                </div>
                
                <div class="grid grid-cols-3 gap-3 pt-4 border-t border-emerald-500/50">
                    <div><p class="text-[9px] text-emerald-200 uppercase font-bold mb-1">Protein</p><p class="font-bold" id="currentPro">0g</p></div>
                    <div><p class="text-[9px] text-emerald-200 uppercase font-bold mb-1">Karbo</p><p class="font-bold" id="currentCarbs">0g</p></div>
                    <div><p class="text-[9px] text-emerald-200 uppercase font-bold mb-1">Lemak</p><p class="font-bold" id="currentFat">0g</p></div>
                </div>
            </div>

            <div id="aiVerdictBox" class="mt-8 bg-white text-emerald-700 rounded-2xl p-4 shadow-lg text-center transform transition-all relative z-10">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2" id="verdictIcon">
                    <i class="fas fa-robot text-gray-400 text-xl"></i>
                </div>
                <h4 class="font-bold text-lg mb-1" id="verdictTitle">Belum Ada Data</h4>
                <p class="text-xs font-medium opacity-80 leading-snug" id="verdictDesc">Tambahkan makanan untuk melihat apakah piringmu sudah seimbang.</p>
            </div>
        </div>
    </div>
</div>

<div id="customAlertModal" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-300 opacity-0">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300" id="customAlertContent">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-100">
                <i class="fas fa-exclamation-circle text-red-500 text-3xl animate-pulse"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2" id="customAlertTitle">Tunggu Dulu!</h3>
            <p class="text-sm text-gray-500 mb-6" id="customAlertMessage">Pesan error di sini.</p>
            <button onclick="closeCustomAlert()" class="w-full bg-gray-900 text-white font-bold py-3 rounded-xl hover:bg-black transition-colors shadow-md">
                Mengerti
            </button>
        </div>
    </div>
</div>

<script>
    const token = localStorage.getItem('jwt_token');
    if (!token) window.location.href = '/login';

    let allMenus = [];
    let userProfile = {};
    let targetCalories = 0;
    let foodCart = [];

    // FUNGSI KONTROL MODAL KUSTOM
    function showCustomAlert(title, message) {
        document.getElementById('customAlertTitle').innerText = title;
        document.getElementById('customAlertMessage').innerText = message;
        
        const modal = document.getElementById('customAlertModal');
        const content = document.getElementById('customAlertContent');

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeCustomAlert() {
        const modal = document.getElementById('customAlertModal');
        const content = document.getElementById('customAlertContent');

        modal.classList.add('opacity-0');
        content.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // 1. Ambil Profil & Hitung Target (BMR & TDEE)
    async function initTracker() {
        try {
            const resUser = await fetch('/api/auth/me', { headers: { 'Authorization': `Bearer ${token}` }});
            const user = await resUser.json();
            
            const w = user.weight || 40;
            const h = user.height || 140;
            const a = user.age || 12;
            
            const bmr = (10 * w) + (6.25 * h) - (5 * a) + 5;
            const tdee = bmr * 1.375;
            targetCalories = tdee * 0.35; 
            
            document.getElementById('targetMeal').innerText = Math.round(targetCalories);

            const resMenu = await fetch('/api/menus', { headers: { 'Authorization': `Bearer ${token}` }});
            allMenus = await resMenu.json();
            
            const select = document.getElementById('menuSelect');
            select.innerHTML = '<option value="">-- Pilih Makanan --</option>';
            allMenus.forEach(m => {
                select.innerHTML += `<option value="${m._id || m.id}">${m.name} (${m.serving_size_g}g per porsi standar)</option>`;
            });

        } catch(e) { console.error("Error init tracker"); }
    }

    // 2. Tambah ke Keranjang
    function addFoodToCart() {
        const select = document.getElementById('menuSelect');
        const portion = document.getElementById('portionInput').value;
        const menuId = select.value;

        if(!menuId) {
            showCustomAlert("Pilih Makanan Dulu!", "Anda belum memilih makanan dari daftar. Silakan pilih makanan sebelum menekan tombol tambah.");
            return;
        }
        if(portion <= 0) {
            showCustomAlert("Porsi Tidak Valid", "Porsi harus lebih dari 0 gram. Masukkan jumlah gramasi yang benar.");
            return;
        }

        const menuData = allMenus.find(m => (m._id === menuId || m.id === menuId));
        if(!menuData) return;

        const ratio = parseFloat(portion) / parseFloat(menuData.serving_size_g || 100);
        
        foodCart.push({
            id: Date.now(),
            name: menuData.name,
            weight: portion,
            cals: parseFloat(menuData.calories || 0) * ratio,
            pro: parseFloat(menuData.protein || 0) * ratio,
            carbs: parseFloat(menuData.carbohydrates || 0) * ratio,
            fat: parseFloat(menuData.fat || 0) * ratio
        });

        renderCart();
    }

    // 3. Render Keranjang & Hitung Total
    function renderCart() {
        const list = document.getElementById('foodCartList');
        list.innerHTML = '';

        let totCals = 0, totPro = 0, totCarbs = 0, totFat = 0;

        if(foodCart.length === 0) {
            list.innerHTML = '<li class="py-8 text-center text-gray-400 text-sm">Piring masih kosong.</li>';
            evaluateNutritionWithAI(0); // Panggil AI
            return;
        }

        foodCart.forEach(item => {
            totCals += item.cals; totPro += item.pro; totCarbs += item.carbs; totFat += item.fat;

            list.innerHTML += `
                <li class="flex justify-between items-center bg-gray-50 p-4 rounded-2xl border border-gray-100">
                    <div>
                        <h4 class="font-bold text-gray-900">${item.name}</h4>
                        <p class="text-xs text-gray-500 mt-1"><span class="font-bold text-emerald-600">${item.weight}g</span> • ${Math.round(item.cals)} kkal</p>
                    </div>
                    <button onclick="removeFood(${item.id})" class="w-8 h-8 rounded-full bg-white text-red-500 shadow-sm border border-gray-200 flex items-center justify-center hover:bg-red-50 transition-colors">
                        <i class="fas fa-trash-alt text-xs"></i>
                    </button>
                </li>
            `;
        });

        document.getElementById('currentCals').innerText = `${Math.round(totCals)} kkal`;
        document.getElementById('currentPro').innerText = `${Math.round(totPro)}g`;
        document.getElementById('currentCarbs').innerText = `${Math.round(totCarbs)}g`;
        document.getElementById('currentFat').innerText = `${Math.round(totFat)}g`;

        let percent = (totCals / targetCalories) * 100;
        if(percent > 100) percent = 100;
        document.getElementById('barCals').style.width = `${percent}%`;

        // PANGGIL AI SETIAP KALI KERANJANG BERUBAH
        evaluateNutritionWithAI(totCals);
    }

    // 4. Hapus Makanan
    function removeFood(id) {
        foodCart = foodCart.filter(item => item.id !== id);
        renderCart();
    }

    // 5. EVALUASI DENGAN AI (PYTHON FLASK)
    async function evaluateNutritionWithAI(totalCals) {
        const box = document.getElementById('aiVerdictBox');
        const icon = document.getElementById('verdictIcon');
        const title = document.getElementById('verdictTitle');
        const desc = document.getElementById('verdictDesc');

        // Jika piring kosong
        if(totalCals === 0 || foodCart.length === 0) {
            box.className = "mt-8 bg-white text-emerald-700 rounded-2xl p-4 shadow-lg text-center transform transition-all relative z-10";
            icon.innerHTML = '<i class="fas fa-robot text-gray-400 text-xl"></i>';
            icon.className = "w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2";
            title.innerText = "Belum Ada Data";
            desc.innerText = "Tambahkan makanan untuk melihat apakah piringmu sudah seimbang.";
            return;
        }

        // Tampilkan status Loading
        title.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menganalisis...';
        desc.innerText = "AI sedang membaca komposisi makronutrisi piring Anda.";

        try {
            // Sesuaikan variabel keranjang untuk dikirim ke Python
            const payloadFoods = foodCart.map(item => ({
                calories: item.cals,
                protein: item.pro,
                carbohydrates: item.carbs,
                fat: item.fat
            }));

            // Tembak ke API Flask Python
            const response = await fetch('http://127.0.0.1:5000/api/predict/evaluation', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ foods: payloadFoods })
            });

            const aiData = await response.json();

            if (aiData.status === 'success') {
                const verdict = aiData.evaluation.verdict;
                let messages = aiData.evaluation.messages.join(" ");

                // Ubah Warna Box berdasarkan Vonis dari Python
                if (verdict === "Seimbang") {
                    box.className = "mt-8 bg-white text-emerald-700 rounded-2xl p-4 shadow-lg text-center transform transition-all relative z-10 scale-105 border-b-4 border-emerald-500";
                    icon.innerHTML = '<i class="fas fa-check-circle text-emerald-500 text-2xl"></i>';
                    icon.className = "w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-2";
                } else if (verdict.includes("Tinggi") || verdict.includes("Kurang")) {
                    box.className = "mt-8 bg-white text-orange-700 rounded-2xl p-4 shadow-lg text-center transform transition-all relative z-10 border-b-4 border-orange-500";
                    icon.innerHTML = '<i class="fas fa-exclamation-triangle text-orange-500 text-xl"></i>';
                    icon.className = "w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-2";
                }

                // Kalkulasi Kalori (Digabung dengan hasil Makronutrisi Python)
                const lowerBound = targetCalories * 0.8; 
                const upperBound = targetCalories * 1.2; 

                if (totalCals < lowerBound) {
                    messages += "<br><br><b>Catatan Kalori:</b> Porsi ini belum cukup memenuhi kebutuhan energi Anda.";
                } else if (totalCals > upperBound) {
                    messages += "<br><br><b>Catatan Kalori:</b> Hati-hati! Kalori porsi ini melebihi target Anda.";
                }

                // Tampilkan Hasil Akhir
                title.innerText = "AI: " + verdict;
                desc.innerHTML = messages;

            } else {
                title.innerText = "AI Error";
                desc.innerText = aiData.message || "Terdapat kesalahan saat menganalisis.";
            }

        } catch (error) {
            box.className = "mt-8 bg-white text-red-700 rounded-2xl p-4 shadow-lg text-center transform transition-all relative z-10 border-b-4 border-red-500";
            icon.innerHTML = '<i class="fas fa-wifi text-red-500 text-xl"></i>';
            icon.className = "w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2";
            title.innerText = "Koneksi Terputus";
            desc.innerText = "Gagal terhubung ke AI. Pastikan server Python (Flask) sudah menyala di terminal.";
        }
    }

    initTracker();
</script>
@endsection