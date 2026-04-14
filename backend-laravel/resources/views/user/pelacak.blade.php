@extends('layouts.user')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h2 class="text-3xl font-bold text-gray-900">Pelacak Nutrisi & Evaluator AI</h2>
        <p class="text-gray-500 text-sm mt-1">Susun piring Anda dan biarkan AI menganalisis keseimbangan gizinya.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 lg:col-span-2 flex flex-col h-full">
        <div class="flex justify-between items-end mb-6">
            <h3 class="font-bold text-gray-900 text-lg">🍽️ Simulasi "Isi Piringku"</h3>
            <button onclick="evaluateWithAI()" id="btnEvaluate" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-5 rounded-xl flex items-center text-sm shadow-md transition-all">
                <i class="fas fa-magic mr-2"></i> Evaluasi dengan AI
            </button>
        </div>
        
        <div class="flex space-x-3 mb-6 bg-gray-50 p-3 rounded-2xl border border-gray-100">
            <select id="foodSelector" class="flex-1 bg-white border border-gray-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">Memuat data makanan dari server...</option>
            </select>
            <button onclick="addFoodToPlate()" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2.5 px-4 rounded-xl text-sm transition-colors shadow-sm">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>

        <div class="flex-1 overflow-y-auto">
            <div id="plateContainer" class="space-y-3">
                <div class="py-10 text-center text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-2xl">
                    <i class="fas fa-utensils text-3xl text-gray-300 mb-3"></i><br>
                    Piring Anda masih kosong.<br>Pilih makanan di atas untuk mulai simulasi.
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 lg:col-span-1 flex flex-col relative overflow-hidden">
        
        <h3 class="font-bold text-gray-900 mb-4 z-10">Ringkasan Analisis AI</h3>
        
        <div id="aiVerdictBox" class="mb-6 p-4 rounded-2xl bg-gray-50 border border-gray-100 z-10 transition-all duration-500 hidden">
            <h4 id="aiVerdictTitle" class="font-black text-sm uppercase tracking-wide mb-1">Menunggu Data</h4>
            <p id="aiVerdictText" class="text-xs text-gray-600 leading-relaxed"></p>
        </div>

        <div class="relative w-36 h-36 mx-auto mb-6 z-10">
            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="40" fill="none" stroke="#F3F4F6" stroke-width="12"></circle>
                <circle id="calCircle" cx="50" cy="50" r="40" fill="none" stroke="#059669" stroke-width="12" stroke-dasharray="251.2" stroke-dashoffset="251.2" stroke-linecap="round" class="transition-all duration-1000 ease-out"></circle>
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span id="totalCalText" class="text-2xl font-black text-gray-900">0</span>
                <span class="text-[10px] font-bold text-gray-400 uppercase">Kcal Total</span>
            </div>
        </div>

        <div class="space-y-5 z-10">
            <div>
                <div class="flex justify-between text-xs font-bold mb-1.5"><span class="text-gray-600">Karbohidrat (<span id="pctCarbs">0</span>%)</span><span id="txtCarbs" class="text-orange-600">0g</span></div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden"><div id="barCarbs" class="bg-orange-500 h-2.5 rounded-full w-0 transition-all duration-1000"></div></div>
            </div>
            <div>
                <div class="flex justify-between text-xs font-bold mb-1.5"><span class="text-gray-600">Protein (<span id="pctPro">0</span>%)</span><span id="txtPro" class="text-blue-600">0g</span></div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden"><div id="barPro" class="bg-blue-500 h-2.5 rounded-full w-0 transition-all duration-1000"></div></div>
            </div>
            <div>
                <div class="flex justify-between text-xs font-bold mb-1.5"><span class="text-gray-600">Lemak (<span id="pctFat">0</span>%)</span><span id="txtFat" class="text-purple-600">0g</span></div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden"><div id="barFat" class="bg-purple-500 h-2.5 rounded-full w-0 transition-all duration-1000"></div></div>
            </div>
        </div>
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

    let availableFoods = []; 
    let myPlate = [];        

    // FUNGSI UNTUK MENGONTROL MODAL KUSTOM
    function showWarningModal(title, message) {
        document.getElementById('warningModalTitle').innerText = title;
        document.getElementById('warningModalText').innerText = message;
        
        const modal = document.getElementById('warningModal');
        const content = document.getElementById('warningModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeWarningModal() {
        const modal = document.getElementById('warningModal');
        const content = document.getElementById('warningModalContent');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // 1. Ambil Katalog Menu dari Laravel untuk pilihan Dropdown
    async function fetchFoodCatalog() {
        try {
            const res = await fetch('/api/user/menus', { headers: { 'Authorization': `Bearer ${token}` }});
            const data = await res.json();
            availableFoods = data.all_menus.data; 
            
            const selector = document.getElementById('foodSelector');
            selector.innerHTML = '<option value="" disabled selected>-- Pilih Makanan ke Piring --</option>';
            
            availableFoods.forEach((food, index) => {
                selector.innerHTML += `<option value="${index}">${food.name} (${food.calories} kkal / ${food.serving_size_g || 100}g)</option>`;
            });
        } catch (e) {
            document.getElementById('foodSelector').innerHTML = '<option value="">Gagal memuat data menu.</option>';
        }
    }

    // 2. Tambahkan makanan dari dropdown ke array myPlate
    function addFoodToPlate() {
        const selector = document.getElementById('foodSelector');
        const selectedIndex = selector.value;
        
        // JIKA KOSONG, PANGGIL POP-UP KUSTOM (BUKAN ALERT)
        if (selectedIndex === "") {
            showWarningModal("Pilih Makanan Dulu!", "Anda belum memilih makanan dari daftar. Silakan pilih makanan sebelum menekan tombol tambah.");
            return;
        }

        const selectedFood = availableFoods[selectedIndex];
        myPlate.push(selectedFood);
        renderPlate();
        
        selector.value = "";
        resetAIUI();
    }

    // Menghapus makanan dari piring
    function removeFood(index) {
        myPlate.splice(index, 1);
        renderPlate();
        resetAIUI();
    }

    // 3. Render daftar HTML makanan di Piring
    function renderPlate() {
        const container = document.getElementById('plateContainer');
        if (myPlate.length === 0) {
            container.innerHTML = `
                <div class="py-10 text-center text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-2xl">
                    <i class="fas fa-utensils text-3xl text-gray-300 mb-3"></i><br>
                    Piring Anda masih kosong.<br>Pilih makanan di atas untuk mulai simulasi.
                </div>`;
            return;
        }

        container.innerHTML = '';
        myPlate.forEach((food, index) => {
            container.innerHTML += `
                <div class="flex items-center justify-between p-4 bg-white border border-gray-100 rounded-2xl shadow-sm hover:border-emerald-200 transition-colors">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm mr-4 shrink-0">
                            ${index + 1}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm leading-tight">${food.name}</h4>
                            <p class="text-[10px] font-bold text-gray-500 mt-0.5">K: ${food.carbohydrates || 0}g | P: ${food.protein || 0}g | L: ${food.fat || 0}g</p>
                        </div>
                    </div>
                    <button onclick="removeFood(${index})" class="text-red-400 hover:text-red-600 p-2"><i class="fas fa-trash-alt"></i></button>
                </div>
            `;
        });
    }

    // 4. MENGIRIM DATA KE MESIN AI PYTHON (FLASK) 🧠
    async function evaluateWithAI() {
        // JIKA PIRING KOSONG, PANGGIL POP-UP KUSTOM
        if (myPlate.length === 0) {
            showWarningModal("Piring Masih Kosong!", "Masukkan minimal 1 makanan ke piring Anda agar AI bisa mengevaluasi keseimbangan gizinya.");
            return;
        }

        const btn = document.getElementById('btnEvaluate');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menganalisis...';
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

        btn.innerHTML = '<i class="fas fa-magic mr-2"></i> Evaluasi dengan AI';
        btn.disabled = false;
    }

    // 5. Update UI berdasarkan vonis AI
    function updateUIWithAIResult(data) {
        const totalCal = data.summary.total_calories;
        document.getElementById('totalCalText').innerText = totalCal;
        const calOffset = Math.max(0, 251.2 - ((totalCal / 1500) * 251.2));
        document.getElementById('calCircle').style.strokeDashoffset = calOffset;

        document.getElementById('txtCarbs').innerText = data.summary.total_carbs + 'g';
        document.getElementById('pctCarbs').innerText = data.percentages.carbs;
        document.getElementById('barCarbs').style.width = data.percentages.carbs + '%';

        document.getElementById('txtPro').innerText = data.summary.total_protein + 'g';
        document.getElementById('pctPro').innerText = data.percentages.protein;
        document.getElementById('barPro').style.width = data.percentages.protein + '%';

        document.getElementById('txtFat').innerText = data.summary.total_fat + 'g';
        document.getElementById('pctFat').innerText = data.percentages.fat;
        document.getElementById('barFat').style.width = data.percentages.fat + '%';

        const verdictBox = document.getElementById('aiVerdictBox');
        const vTitle = document.getElementById('aiVerdictTitle');
        const vText = document.getElementById('aiVerdictText');
        
        verdictBox.classList.remove('hidden', 'bg-gray-50', 'bg-emerald-50', 'bg-orange-50', 'bg-red-50');
        vTitle.classList.remove('text-gray-900', 'text-emerald-700', 'text-orange-700', 'text-red-700');
        
        let bgColor = 'bg-gray-50';
        let textColor = 'text-gray-900';

        if (data.evaluation.verdict === "Seimbang") {
            bgColor = 'bg-emerald-50'; textColor = 'text-emerald-700';
            verdictBox.style.borderColor = '#A7F3D0';
        } else if (data.evaluation.verdict.includes("Tinggi")) {
            bgColor = 'bg-orange-50'; textColor = 'text-orange-700';
            verdictBox.style.borderColor = '#FDE68A';
        } else {
            bgColor = 'bg-red-50'; textColor = 'text-red-700';
            verdictBox.style.borderColor = '#FECACA';
        }

        verdictBox.classList.add(bgColor);
        vTitle.classList.add(textColor);
        vTitle.innerText = "VONIS AI: " + data.evaluation.verdict;
        
        const messagesHtml = data.evaluation.messages.map(msg => `<li>• ${msg}</li>`).join('');
        vText.innerHTML = `<ul class="space-y-1">${messagesHtml}</ul>`;
    }

    // Kembalikan UI ke 0 jika makanan ditambah/dihapus sebelum dievaluasi lagi
    function resetAIUI() {
        document.getElementById('totalCalText').innerText = '0';
        document.getElementById('calCircle').style.strokeDashoffset = 251.2;
        
        ['Carbs', 'Pro', 'Fat'].forEach(macro => {
            document.getElementById(`txt${macro}`).innerText = '0g';
            document.getElementById(`pct${macro}`).innerText = '0';
            document.getElementById(`bar${macro}`).style.width = '0%';
        });

        document.getElementById('aiVerdictBox').classList.add('hidden');
    }

    fetchFoodCatalog();

</script>
@endsection