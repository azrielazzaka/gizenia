<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - GIZENIA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: #f0fdf4;
            min-height: 100vh;
            color: #1f2e1c;
            font-family: 'Inter', sans-serif;
        }

        .pg {
            max-width: 600px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem;
        }

        /* TOPBAR */
        .topbar {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .back-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #ffffff;
            border: 1px solid #b2f0b2;
            color: #2e7d32;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }
        .back-btn:hover { background: #e8f5e9; border-color: #81c784; }

        /* CARD FORM */
        .card {
            background: #ffffff;
            border: 1px solid #d9f0da;
            border-radius: 32px;
            padding: 2.5rem;
            box-shadow: 0 8px 24px rgba(46, 125, 50, 0.05);
        }

        .form-header {
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-header h1 {
            font-family: 'Inter', sans-serif;
            font-size: 28px;
            color: #1b3b1a;
            margin-bottom: 8px;
        }

        .form-header p {
            font-size: 14px;
            color: #7d9e78;
        }

        /* INPUT STYLES */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #4a6348;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 14px;
            border: 1.5px solid #e3f2e2;
            background: #f9fef8;
            font-size: 15px;
            color: #1f3b1c;
            transition: all 0.2s;
            outline: none;
        }

        .form-input:focus {
            border-color: #81c784;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(129, 199, 132, 0.1);
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* BUTTONS */
        .btn-save {
            width: 100%;
            padding: 14px;
            background: #2e7d32;
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 1rem;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.2);
        }

        .btn-save:hover {
            background: #1b5e20;
            transform: translateY(-1px);
        }

        .btn-save:disabled {
            background: #a5d6a7;
            cursor: not-allowed;
        }

        /* LOADING STATE */
        .loading-overlay {
            display: none;
            text-align: center;
            color: #2e7d32;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="pg">
    <div class="topbar">
        <a href="/profile" class="back-btn">← Kembali</a>
    </div>

    <div class="card">
        <div class="form-header">
            <h1>Perbarui Profil</h1>
            <p>Sesuaikan data diri untuk perhitungan gizi yang akurat</p>
        </div>

        <form id="editProfileForm">
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" class="form-input" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="age">Usia (Tahun)</label>
                    <input type="number" id="age" class="form-input" placeholder="Contoh: 16" required>
                </div>
                <div class="form-group">
    <label for="class_room">Kelas</label>
    <select id="class_room" name="class_room" class="form-input" required>
        <option value="">-- Pilih Kelas --</option>
        <option value="Kelas 1">Kelas 1</option>
        <option value="Kelas 2">Kelas 2</option>
        <option value="Kelas 3">Kelas 3</option>
        <option value="Kelas 4">Kelas 4</option>
        <option value="Kelas 5">Kelas 5</option>
        <option value="Kelas 6">Kelas 6</option>
    </select>
</div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="weight">Berat Badan (kg)</label>
                    <input type="number" step="0.1" id="weight" class="form-input" placeholder="Contoh: 55.5" required>
                </div>
                <div class="form-group">
                    <label for="height">Tinggi Badan (cm)</label>
                    <input type="number" id="height" class="form-input" placeholder="Contoh: 165" required>
                </div>
            </div>

            <button type="submit" id="saveBtn" class="btn-save">Simpan Perubahan</button>
            <div id="loadingMsg" class="loading-overlay">Menyimpan data...</div>
        </form>
    </div>
</div>

<script>
const token = localStorage.getItem('jwt_token');

if (!token) {
    window.location.href = '/login';
}

// ── Load Data Awal ─────────────────────────────────────
async function fetchCurrentData() {
    try {
        const res = await fetch('/api/auth/me', {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        const user = await res.json();

        if (res.ok) {
            document.getElementById('name').value = user.name || '';
            document.getElementById('age').value = user.age || '';
            document.getElementById('class_room').value = user.class_room || '';
            document.getElementById('weight').value = user.weight || '';
            document.getElementById('height').value = user.height || '';
        }
    } catch (err) {
        console.error('Gagal mengambil data:', err);
        alert('Gagal mengambil data profil.');
    }
}

// ── Submit Perubahan ───────────────────────────────────
document.getElementById('editProfileForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const saveBtn = document.getElementById('saveBtn');
    const loadingMsg = document.getElementById('loadingMsg');
    
    saveBtn.disabled = true;
    loadingMsg.style.display = 'block';

    const payload = {
        name: document.getElementById('name').value,
        age: document.getElementById('age').value,
        class_room: document.getElementById('class_room').value,
        weight: document.getElementById('weight').value,
        height: document.getElementById('height').value,
    };

    try {
        // Ganti URL endpoint sesuai dengan API update profil kamu
        const res = await fetch('/api/auth/user/update-profile', {
            method: 'PUT', // atau POST sesuai backend
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        if (res.ok) {
            alert('Profil berhasil diperbarui!');
            window.location.href = '/profile'; // Kembali ke halaman profil
        } else {
            const errData = await res.json();
            alert('Gagal memperbarui: ' + (errData.message || 'Terjadi kesalahan'));
        }
    } catch (err) {
        console.error('Error updating profile:', err);
        alert('Terjadi kesalahan koneksi.');
    } finally {
        saveBtn.disabled = false;
        loadingMsg.style.display = 'none';
    }
});

// Jalankan saat load
fetchCurrentData();
</script>

</body>
</html>