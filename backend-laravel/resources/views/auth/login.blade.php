<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GIZENIA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-emerald-600">GIZENIA<span class="text-gray-800">.AI</span></h1>
            <p class="text-gray-500 mt-2">Silakan login ke akun Anda</p>
        </div>

        <div id="alertBox" class="hidden mb-4 p-3 rounded bg-red-100 text-red-600 text-sm"></div>

        <form id="loginForm" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors" placeholder="admin@gizenia.com">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors" placeholder="••••••••">
                
                <div class="flex justify-end mt-2">
                    <a href="javascript:void(0)" onclick="handleForgotPassword()" class="text-xs text-emerald-600 hover:text-emerald-700 hover:underline font-medium transition-colors">Lupa kata sandi?</a>
                </div>
            </div>

            <button type="submit" id="submitBtn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Masuk
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            Belum punya akun? <a href="/register" class="text-emerald-600 hover:underline font-medium">Daftar di sini</a>
        </p>
    </div>

    <script>
        // Fungsi Login
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const btn = document.getElementById('submitBtn');
            const alertBox = document.getElementById('alertBox');
            
            btn.innerHTML = 'Memproses...';
            btn.disabled = true;
            alertBox.classList.add('hidden');

            try {
                // Memanggil API Login
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (response.ok) {
                    // Simpan token JWT ke localStorage browser
                    localStorage.setItem('jwt_token', data.access_token);
                    localStorage.setItem('user_data', JSON.stringify(data.user));
                    
                    // CEK ROLE DAN ARAHKAN KE DASHBOARD YANG TEPAT
                    if (data.user.role === 'admin') {
                        window.location.href = '/admin/dashboard';
                    } else {
                        window.location.href = '/user/dashboard';
                    }
                } else {
                    alertBox.innerHTML = data.message || data.error || 'Login gagal. Periksa kembali email dan password Anda.';
                    alertBox.classList.remove('hidden');
                }
            } catch (error) {
                alertBox.innerHTML = 'Terjadi kesalahan pada server.';
                alertBox.classList.remove('hidden');
            } finally {
                btn.innerHTML = 'Masuk';
                btn.disabled = false;
            }
        });

        // Fitur Lupa Password
        async function handleForgotPassword() {
            const email = document.getElementById('email').value;
            const alertBox = document.getElementById('alertBox');

            if (!email) {
                alertBox.innerHTML = 'Silakan isi email Anda terlebih dahulu di kolom email untuk mereset password.';
                alertBox.className = 'mb-4 p-3 rounded bg-yellow-100 text-yellow-700 text-sm';
                alertBox.classList.remove('hidden');
                return;
            }

            if (!confirm('Kirim instruksi reset password ke ' + email + '?')) return;

            try {
                const response = await fetch('/api/auth/forgot-password', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ email })
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    alertBox.innerHTML = data.message;
                    alertBox.className = 'mb-4 p-3 rounded bg-green-100 text-green-700 text-sm';
                } else {
                    alertBox.innerHTML = data.error || data.email[0] || 'Gagal mengirim instruksi.';
                    alertBox.className = 'mb-4 p-3 rounded bg-red-100 text-red-600 text-sm';
                }
                alertBox.classList.remove('hidden');
            } catch (e) {
                alertBox.innerHTML = 'Gagal menghubungi server.';
                alertBox.className = 'mb-4 p-3 rounded bg-red-100 text-red-600 text-sm';
                alertBox.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>