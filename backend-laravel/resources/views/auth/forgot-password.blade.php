@extends('layouts.auth')

@section('content')
<body class="bg-gray-50 flex items-center justify-center h-screen">

<div class="absolute top-6 left-6">
    <a href="/login" class="flex items-center gap-2 text-sm text-gray-600 bg-white border border-gray-200 px-4 py-2 rounded-lg hover:bg-gray-50 transition">
        ← Kembali
    </a>
</div>

<div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">

    <!-- HEADER -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-emerald-600">
            GIZENIA<span class="text-gray-800">.AI</span>
        </h1>
        <p class="text-gray-500 mt-2 text-sm">
            Masukkan email untuk menerima kode OTP
        </p>
    </div>

    <!-- ALERT -->
    <div id="alertBox" class="hidden mb-4 p-3 rounded text-sm"></div>

    <!-- FORM -->
    <form id="forgotForm" class="space-y-5">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Email
            </label>
            <input 
                type="email" 
                id="email" 
                required 
                placeholder="admin@gizenia.com"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg 
                focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
            >
        </div>

        <button 
            type="submit" 
            id="submitBtn"
            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg transition"
        >
            Kirim OTP
        </button>

    </form>

    <!-- FOOTER -->
    <p class="mt-6 text-center text-sm text-gray-500">
        Kembali ke 
        <a href="/login" class="text-emerald-600 hover:underline font-medium">
            Login
        </a>
    </p>

</div>

<script>
document.getElementById('forgotForm').addEventListener('submit', async function(e){
    e.preventDefault();

    const email = document.getElementById('email').value;
    const btn = document.getElementById('submitBtn');
    const alertBox = document.getElementById('alertBox');

    btn.innerHTML = 'Mengirim...';
    btn.disabled = true;
    alertBox.classList.add('hidden');

    try {
        const res = await fetch('/api/auth/send-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email })
        });

        const data = await res.json();

        if(res.ok){
            window.location.href = '/reset-password?email=' + email;
        } else {
            alertBox.innerText = data.message || data.error || 'Gagal kirim OTP';
            alertBox.classList.remove('hidden');
            alertBox.classList.add('bg-red-100','text-red-600');
        }

    } catch (err) {
        alertBox.innerText = 'Terjadi kesalahan server';
        alertBox.classList.remove('hidden');
        alertBox.classList.add('bg-red-100','text-red-600');
    }

    btn.innerHTML = 'Kirim OTP';
    btn.disabled = false;
});
</script>

</body>
@endsection