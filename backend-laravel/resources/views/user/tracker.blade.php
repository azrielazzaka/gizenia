@extends('layouts.user')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Simulasi Isi Piringku</h2>
        <p class="text-gray-500 text-sm mt-1">Susun piring Anda dan biarkan AI menganalisis keseimbangan gizinya.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 lg:col-span-2 flex flex-col h-full">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                <span class="p-2 bg-emerald-50 text-emerald-600 rounded-xl"><i class="fas fa-utensils"></i></span>
                Daftar Makanan Anda
            </h3>
        </div>
        
        <div class="mb-6 bg-gray-50 p-4 rounded-2xl border border-gray-100">
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Cari & Tambahkan Makanan</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input
                    type="text"
                    id="foodSearch"
                    placeholder="Ketik nama makanan (Cth: Nasi Goreng)..."
                    class="w-full bg-white border border-gray-200 text-sm rounded-xl pl-10 pr-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 shadow-sm"
                    oninput="filterFoods()"
                    autocomplete="off"
                >
                <div id="foodDropdown" class="hidden absolute z-50 mt-2 w-full bg-white border border-gray-200 rounded-xl shadow-xl max-h-60 overflow-y-auto">
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto pr-2">
            <div id="plateContainer" class="space-y-3">
                <div class="py-12 text-center text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/50">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-hamburger text-2xl text-gray-300"></i>
                    </div>
                    Piring Anda masih kosong.<br>Ketik nama makanan di atas untuk memulai.
                </div>
            </div>
        </div>
    </div>

    <div class="bg-emerald-800 rounded-3xl p-6 shadow-xl shadow-emerald-900/20 lg:col-span-1 flex flex-col relative overflow-hidden">
        <svg class="absolute top-0 right-0 w-32 h-32 text-white opacity-5 transform translate-x-10 -translate-y-10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>

        <h3 class="font-bold text-white mb-6 z-10 flex items-center justify-center gap-2 text-lg">
            Profil Nutrisi Piringku
        </h3>
        
        <div class="relative w-40 h-40 mx-auto mb-4 z-10">
            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="12"></circle>
                <circle id="calCircle" cx="50" cy="50" r="40" fill="none" stroke="#FDE047" stroke-width="12" stroke-dasharray="251.2" stroke-dashoffset="251.2" stroke-linecap="round" class="transition-all duration-1000 ease-out"></circle>
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center text-white">
                <span id="totalCalText" class="text-3xl font-black">0</span>
                <span class="text-[10px] font-bold text-emerald-200 uppercase tracking-widest mt-1">Kcal</span>
            </div>
        </div>
        
        <div class="text-center z-10 mb-8">
            <span class="text-xs text-white/80 font-medium bg-black/20 px-3 py-1 rounded-full">Target Makan: <b id="targetCalText" class="text-yellow-400">700</b> kkal</span>
        </div>

        <div class="space-y-5 z-10 bg-white/10 p-5 rounded-2xl backdrop-blur-sm border border-white/10 mb-6">
            <div>
                <div class="flex justify-between text-xs font-bold mb-2 text-white">
                    <span>Karbohidrat</span>
                    <span id="txtCarbs" class="text-yellow-400">0g</span>
                </div>
                <div class="w-full bg-black/20 rounded-full h-2 overflow-hidden"><div id="barCarbs" class="bg-yellow-400 h-2 rounded-full w-0 transition-all duration-1000"></div></div>
            </div>
            <div>
                <div class="flex justify-between text-xs font-bold mb-2 text-white">
                    <span>Protein</span>
                    <span id="txtPro" class="text-blue-400">0g</span>
                </div>
                <div class="w-full bg-black/20 rounded-full h-2 overflow-hidden"><div id="barPro" class="bg-blue-400 h-2 rounded-full w-0 transition-all duration-1000"></div></div>
            </div>
            <div>
                <div class="flex justify-between text-xs font-bold mb-2 text-white">
                    <span>Lemak</span>
                    <span id="txtFat" class="text-purple-400">0g</span>
                </div>
                <div class="w-full bg-black/20 rounded-full h-2 overflow-hidden"><div id="barFat" class="bg-purple-400 h-2 rounded-full w-0 transition-all duration-1000"></div></div>
            </div>
        </div>

        <div id="aiVerdictBox" class="mb-6 p-5 rounded-2xl bg-white shadow-lg z-10 transition-all duration-500 hidden">
            <h4 id="aiVerdictTitle" class="font-black text-sm uppercase tracking-wide mb-2 text-gray-900">Menunggu Data</h4>
            <p id="aiVerdictText" class="text-xs text-gray-600 leading-relaxed font-medium"></p>
        </div>

        <button onclick="evaluateWithAI()" id="btnEvaluate" class="w-full bg-white hover:bg-gray-50 text-emerald-800 font-bold py-3.5 px-6 rounded-xl flex items-center justify-center text-sm shadow-xl transition-all z-10">
            <i class="fas fa-magic mr-2 text-emerald-600"></i> Evaluasi Piring dengan AI
        </button>
    </div>
</div>

<div id="warningModal" class="fixed inset-0 z-[100] hidden bg-gray-900/70 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center" id="warningModalContent">
        <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-100">
            <i class="fas fa-exclamation-triangle text-red-500 text-2xl animate-bounce"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2" id="warningModalTitle">Ups, Tunggu Dulu!</h3>
        <p class="text-sm text-gray-500 mb-6" id="warningModalText">Pilih makanan terlebih dahulu.</p>
        <button onclick="closeWarningModal()" class="w-full bg-gray-900 text-white font-bold py-3 rounded-xl shadow-lg hover:bg-gray-800 transition-colors">
            Mengerti
        </button>
    </div>
</div>

<script>
    const token = localStorage.getItem('jwt_token');
    if (!token) window.location.href = '/login';

    let myPlate = [];        
    let currentSearchResults = []; 
    let debounceTimer;
    let targetMealCalories = 700; // Default 1x makan

    // MENGAMBIL TARGET KALORI PERSONAL SAAT HALAMAN DIBUKA
    async function initPelacak() {
        try {
            const res = await fetch(`/api/user/menus`, {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            const data = await res.json();
            
            // Cek apakah AI sudah pernah memprediksi targetnya
            if (data.ai_analysis && data.ai_analysis.target_nutrisi && data.ai_analysis.target_nutrisi.calories) {
                targetMealCalories = Math.round(data.ai_analysis.target_nutrisi.calories);
            } else if (data.user_stats && data.user_stats.target_meal) {
                targetMealCalories = Math.round(data.user_stats.target_meal);
            }
            
            document.getElementById('targetCalText').innerText = targetMealCalories;
        } catch (e) {
            console.error("Gagal mengambil data target kalori", e);
        }
    }

    document.addEventListener('DOMContentLoaded', initPelacak);

    function showWarningModal(title, message) {
        document.getElementById('warningModalTitle').innerText = title;
        document.getElementById('warningModalText').innerText = message;
        const modal = document.getElementById('warningModal');
        const content = document.getElementById('warningModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.remove('opacity-0'); content.classList.remove('scale-95'); }, 10);
    }

    function closeWarningModal() {
        const modal = document.getElementById('warningModal');
        const content = document.getElementById('warningModalContent');
        modal.classList.add('opacity-0'); content.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    async function filterFoods() {
        const keyword = document.getElementById('foodSearch').value.trim();
        const dropdown = document.getElementById('foodDropdown');
        
        if (keyword.length < 2) {
            dropdown.classList.add('hidden');
            return;
        }

        clearTimeout(debounceTimer);
        dropdown.classList.remove('hidden');
        dropdown.innerHTML = `<div class="px-5 py-4 text-sm text-gray-500 flex items-center"><i class="fas fa-circle-notch fa-spin mr-3 text-emerald-500"></i> Mencari database...</div>`;

        debounceTimer = setTimeout(async () => {
            try {
                const res = await fetch(`/api/user/menus?search=${keyword}`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await res.json();
                currentSearchResults = data.all_menus ? data.all_menus.data : data.data;

                dropdown.innerHTML = '';
                if (!currentSearchResults || currentSearchResults.length === 0) {
                    dropdown.innerHTML = `<div class="px-5 py-4 text-sm text-red-500 font-medium"><i class="fas fa-times-circle mr-2"></i> Makanan tidak ditemukan.</div>`;
                } else {
                    currentSearchResults.forEach((food, index) => {
                        dropdown.innerHTML += `
                            <div class="px-5 py-3 cursor-pointer hover:bg-emerald-50 border-b border-gray-50 last:border-0 transition flex justify-between items-center"
                                onclick="selectFood(${index})">
                                <div>
                                    <div class="font-bold text-gray-800 text-sm">${food.name}</div>
                                    <div class="text-[10px] font-bold text-gray-400 mt-0.5 uppercase tracking-wider">Porsi Standar: ${food.serving_size_g || 100}g</div>
                                </div>
                                <div class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2.5 py-1 rounded-lg shadow-sm">
                                    🔥 ${food.calories} kkal
                                </div>
                            </div>`;
                    });
                }
            } catch (e) {
                dropdown.innerHTML = `<div class="px-5 py-4 text-sm text-red-500"><i class="fas fa-wifi mr-2"></i> Gagal terhubung ke server.</div>`;
            }
        }, 400); 
    }

    function selectFood(index) {
        myPlate.push(currentSearchResults[index]);
        renderPlate();
        resetAIUI();
        document.getElementById('foodSearch').value = '';
        document.getElementById('foodDropdown').classList.add('hidden');
    }

    function removeFood(index) {
        myPlate.splice(index, 1);
        renderPlate();
        resetAIUI();
    }

    function renderPlate() {
        const container = document.getElementById('plateContainer');
        if (myPlate.length === 0) {
            container.innerHTML = `
                <div class="py-12 text-center text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/50">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-hamburger text-2xl text-gray-300"></i>
                    </div>
                    Piring Anda masih kosong.<br>Ketik nama makanan di atas untuk memulai.
                </div>`;
            return;
        }

        container.innerHTML = '';
        myPlate.forEach((food, index) => {
            container.innerHTML += `
                <div class="flex items-center justify-between p-4 bg-white border border-gray-100 rounded-2xl shadow-sm hover:border-emerald-200 transition group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-sm mr-4 shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition">
                            ${index + 1}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm leading-tight">${food.name}</h4>
                            <div class="flex gap-2 mt-1">
                                <span class="text-[10px] font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded">Karbo: ${food.carbohydrates || 0}g</span>
                                <span class="text-[10px] font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded">Pro: ${food.protein || 0}g</span>
                                <span class="text-[10px] font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded">Lem: ${food.fat || 0}g</span>
                            </div>
                        </div>
                    </div>
                    <button onclick="removeFood(${index})" class="w-8 h-8 rounded-full bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
        });
    }

    async function evaluateWithAI() {
        if (myPlate.length === 0) {
            showWarningModal("Piring Masih Kosong!", "Masukkan minimal 1 makanan ke piring Anda agar AI bisa mengevaluasi keseimbangan gizinya.");
            return;
        }

        const btn = document.getElementById('btnEvaluate');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2 text-emerald-600"></i> Menganalisis...';
        btn.disabled = true;

        try {
            const response = await fetch('http://127.0.0.1:5000/api/predict/evaluation', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ foods: myPlate })
            });
            const aiData = await response.json();
            
            if (aiData.status === 'success') {
                updateUIWithAIResult(aiData);
            } else {
                showWarningModal("AI Sedang Bingung", "Terdapat kesalahan saat menganalisis makanan. Pesan Error: " + aiData.message);
            }
        } catch (e) {
            showWarningModal("Gagal Terhubung ke AI", "Pastikan server Python (Flask) Anda sedang berjalan di terminal.");
        }

        btn.innerHTML = '<i class="fas fa-magic mr-2 text-emerald-600"></i> Evaluasi Piring dengan AI';
        btn.disabled = false;
    }

    function updateUIWithAIResult(data) {
        const totalCal = data.summary.total_calories;
        document.getElementById('totalCalText').innerText = totalCal;
        
        // MENGHITUNG OFFSET GRAFIK BERDASARKAN TARGET KALORI 1X MAKAN
        const calOffset = Math.max(0, 251.2 - ((totalCal / targetMealCalories) * 251.2)); 
        document.getElementById('calCircle').style.strokeDashoffset = calOffset;

        document.getElementById('txtCarbs').innerText = data.summary.total_carbs + 'g';
        document.getElementById('barCarbs').style.width = data.percentages.carbs + '%';

        document.getElementById('txtPro').innerText = data.summary.total_protein + 'g';
        document.getElementById('barPro').style.width = data.percentages.protein + '%';

        document.getElementById('txtFat').innerText = data.summary.total_fat + 'g';
        document.getElementById('barFat').style.width = data.percentages.fat + '%';

        const verdictBox = document.getElementById('aiVerdictBox');
        const vTitle = document.getElementById('aiVerdictTitle');
        const vText = document.getElementById('aiVerdictText');
        
        verdictBox.classList.remove('hidden');
        
        let textColor = 'text-gray-900';
        let icon = '<i class="fas fa-info-circle"></i>';

        if (data.evaluation.verdict.includes("Seimbang")) {
            textColor = 'text-emerald-600';
            icon = '<i class="fas fa-check-circle text-emerald-500"></i>';
        } else if (data.evaluation.verdict.includes("Tinggi")) {
            textColor = 'text-orange-600';
            icon = '<i class="fas fa-exclamation-triangle text-orange-500"></i>';
        } else {
            textColor = 'text-red-600';
            icon = '<i class="fas fa-times-circle text-red-500"></i>';
        }

        vTitle.className = `font-black text-sm uppercase tracking-wide mb-3 flex items-center gap-2 ${textColor}`;
        vTitle.innerHTML = `${icon} : ${data.evaluation.verdict}`;
        
        const messagesHtml = data.evaluation.messages.map(msg => `<li class="flex items-start gap-2"><i class="fas fa-circle text-[6px] mt-1.5 text-gray-300"></i><span>${msg}</span></li>`).join('');
        vText.innerHTML = `<ul class="space-y-2">${messagesHtml}</ul>`;
    }

    function resetAIUI() {
        document.getElementById('totalCalText').innerText = '0';
        document.getElementById('calCircle').style.strokeDashoffset = 251.2;
        ['Carbs', 'Pro', 'Fat'].forEach(macro => {
            document.getElementById(`txt${macro}`).innerText = '0g';
            document.getElementById(`bar${macro}`).style.width = '0%';
        });
        document.getElementById('aiVerdictBox').classList.add('hidden');
    }

    // Klik di luar menutup dropdown
    document.addEventListener('click', function (e) {
        if (!e.target.closest('#foodSearch') && !e.target.closest('#foodDropdown')) {
            document.getElementById('foodDropdown').classList.add('hidden');
        }
    });
</script>
@endsection