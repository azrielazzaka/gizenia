<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - GIZENIA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen py-10">

    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-emerald-600">GIZENIA<span class="text-gray-800">.AI</span></h1>
            <p class="text-gray-500 mt-2">Buat akun baru</p>
        </div>

        <div id="alertBox" class="hidden mb-4 p-3 rounded text-sm"></div>

        <form id="registerForm" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" id="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors" placeholder="Nama Anda">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors" placeholder="email@domain.com">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kelas (Ruang Belajar)</label>
                <select id="class_room" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none bg-white">
                    <option value="">-- Pilih Kelas Anda --</option>
                    <option value="Kelas 1">Kelas 1</option>
                    <option value="Kelas 2">Kelas 2</option>
                    <option value="Kelas 3">Kelas 3</option>
                    <option value="Kelas 4">Kelas 4</option>
                    <option value="Kelas 5">Kelas 5</option>
                    <option value="Kelas 6">Kelas 6</option>
                    <option value="Guru/Staff">Guru / Staff</option>
                </select>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Umur (Thn)</label>
                    <input type="number" id="age" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors" placeholder="10">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Berat (Kg)</label>
                    <input type="number" id="weight" step="0.1" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors" placeholder="35">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Tinggi (Cm)</label>
                    <input type="number" id="height" step="0.1" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors" placeholder="130">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" required minlength="6" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors" placeholder="Minimal 6 karakter">
            </div>

            <button type="submit" id="submitBtn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-lg transition-colors mt-4 shadow-md">
                Daftar Sekarang
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            Sudah punya akun? <a href="/login" class="text-emerald-600 hover:underline font-medium">Login di sini</a>
        </p>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('submitBtn');
            const alertBox = document.getElementById('alertBox');
            
            btn.innerHTML = 'Memproses...';
            btn.disabled = true;
            alertBox.className = 'hidden mb-4 p-3 rounded text-sm';

            // Ambil semua data inputan
            const payload = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                class_room: document.getElementById('class_room').value, // Data Kelas
                age: document.getElementById('age').value,
                weight: document.getElementById('weight').value,
                height: document.getElementById('height').value
            };

            try {
                // Memanggil API Register
                const response = await fetch('/api/auth/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (response.ok) {
                    alertBox.innerHTML = 'Registrasi berhasil! Mengalihkan...';
                    alertBox.classList.add('bg-green-100', 'text-green-700');
                    alertBox.classList.remove('hidden');
                    
                    // Simpan token & redirect
                    localStorage.setItem('jwt_token', data.access_token);
                    localStorage.setItem('user_data', JSON.stringify(data.user));
                    
                    setTimeout(() => {
                        window.location.href = '/user/dashboard';
                    }, 1500);
                } else {
                    // Menampilkan pesan error validasi pertama yang ditemukan
                    let errorMsg = 'Registrasi gagal.';
                    const errors = data; // Laravel biasanya me-return array errors
                    if(errors.email) errorMsg = errors.email[0];
                    else if(errors.password) errorMsg = errors.password[0];
                    else if(errors.class_room) errorMsg = errors.class_room[0];
                    
                    alertBox.innerHTML = errorMsg;
                    alertBox.classList.add('bg-red-100', 'text-red-600');
                    alertBox.classList.remove('hidden');
                }
            } catch (error) {
                alertBox.innerHTML = 'Terjadi kesalahan jaringan pada server.';
                alertBox.classList.add('bg-red-100', 'text-red-600');
                alertBox.classList.remove('hidden');
            } finally {
                btn.innerHTML = 'Daftar Sekarang';
                btn.disabled = false;
            }
        });
    </script>
</body>
</html>