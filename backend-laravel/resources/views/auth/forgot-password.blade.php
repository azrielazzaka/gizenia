@extends('layouts.auth')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded-xl shadow">

    <h2 class="text-xl font-bold mb-4 text-center">Reset Password</h2>

    <div id="alertBox" class="hidden mb-3 p-3 rounded text-sm"></div>

    <form id="forgotForm">
    <input type="email" id="email" placeholder="Masukkan email" required>
    <button type="submit">Kirim OTP</button>
</form>

<div id="result"></div>

<script>
document.getElementById('forgotForm').addEventListener('submit', async function(e){
    e.preventDefault();

    const email = document.getElementById('email').value;

    const res = await fetch('/api/auth/send-otp', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ email })
    });

    const data = await res.json();

    document.getElementById('result').innerText = data.message || data.error;

    // 👉 pindah ke halaman reset
    if(res.ok){
        window.location.href = '/reset-password?email=' + email;
    }
});
</script>
@endsection