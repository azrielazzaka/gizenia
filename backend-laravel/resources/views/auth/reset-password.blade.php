<form id="resetForm" class="space-y-3">
    <input type="email" id="email" readonly
        class="w-full border p-2 rounded bg-gray-100">

    <input type="text" id="otp" placeholder="Masukkan OTP" required
        class="w-full border p-2 rounded">

    <input type="password" id="password" placeholder="Password baru" required
        class="w-full border p-2 rounded">

    <input type="password" id="password_confirmation" placeholder="Konfirmasi password" required
        class="w-full border p-2 rounded">

    <button type="submit"
        class="w-full bg-emerald-600 text-white py-2 rounded">
        Reset Password
    </button>
</form>

<div id="result" class="mt-3 text-sm text-center"></div>

<script>
// ambil email dari URL
const urlParams = new URLSearchParams(window.location.search);
document.getElementById('email').value = urlParams.get('email');

document.getElementById('resetForm').addEventListener('submit', async function(e){
    e.preventDefault();

    const email = document.getElementById('email').value;
    const otp = document.getElementById('otp').value;
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('password_confirmation').value;

    // ✅ VALIDASI FRONTEND
    if(password !== confirm){
        document.getElementById('result').innerText = "Password tidak sama!";
        return;
    }

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

    document.getElementById('result').innerText = data.message || data.error;

    if(res.ok){
        alert('Password berhasil diubah, silakan login');
        window.location.href = '/login';
    }
});
</script>