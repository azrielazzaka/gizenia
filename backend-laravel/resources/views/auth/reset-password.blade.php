@extends('layouts.auth')

@section('content')

<div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">

    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-emerald-600">
            GIZENIA<span class="text-gray-800">.AI</span>
        </h1>
        <p class="text-gray-500 mt-2 text-sm">
            Masukkan OTP dan password baru
        </p>
    </div>

    <div id="alertBox" class="hidden mb-4 p-3 rounded text-sm"></div>

    <form id="resetForm" class="space-y-5">

        <input type="email" id="email" readonly
            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">

        <input type="text" id="otp" placeholder="Masukkan OTP" required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">

        <input type="password" id="password" placeholder="Password baru" required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">

        <input type="password" id="password_confirmation" placeholder="Ulangi password" required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">

        <!-- ✅ FIX: tambahin ID -->
        <button type="submit" id="submitBtn"
            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 rounded-lg transition">
            Reset Password
        </button>

    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Kembali ke 
        <a href="/login" class="text-emerald-600 hover:underline">Login</a>
    </p>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    console.log("RESET PAGE READY"); // debug

    // ambil email dari URL
    const urlParams = new URLSearchParams(window.location.search);
    const email = urlParams.get('email');

    const emailInput = document.getElementById('email');
    emailInput.value = email || '';

    const form = document.getElementById('resetForm');
    const btn = document.getElementById('submitBtn');
    const alertBox = document.getElementById('alertBox');

    form.addEventListener('submit', async function(e){
        e.preventDefault();

        const otp = document.getElementById('otp').value.trim();
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirmation').value;

        // reset alert
        alertBox.className = 'hidden mb-4 p-3 rounded text-sm';

        // VALIDASI
        if(password !== confirm){
            alertBox.innerText = "Password tidak sama!";
            alertBox.classList.remove('hidden');
            alertBox.classList.add('bg-red-100','text-red-600');
            return;
        }

        if(!email){
            alertBox.innerText = "Email tidak ditemukan!";
            alertBox.classList.remove('hidden');
            alertBox.classList.add('bg-red-100','text-red-600');
            return;
        }

        btn.innerHTML = 'Memproses...';
        btn.disabled = true;

        try {
            const res = await fetch('/api/auth/reset-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email,
                    otp,
                    password,
                    password_confirmation: confirm
                })
            });

            const data = await res.json();

            if(res.ok){
                alertBox.innerText = 'Password berhasil diubah!';
                alertBox.classList.remove('hidden');
                alertBox.classList.add('bg-green-100','text-green-600');

                setTimeout(() => {
                    window.location.href = '/login';
                }, 1500);
            } else {
                alertBox.innerText = data.message || data.error || 'Gagal reset password';
                alertBox.classList.remove('hidden');
                alertBox.classList.add('bg-red-100','text-red-600');
            }

        } catch (err) {
            console.error(err);
            alertBox.innerText = 'Terjadi kesalahan server';
            alertBox.classList.remove('hidden');
            alertBox.classList.add('bg-red-100','text-red-600');
        }

        btn.innerHTML = 'Reset Password';
        btn.disabled = false;
    });

});
</script>

@endsection