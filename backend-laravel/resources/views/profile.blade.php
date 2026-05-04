<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - NutriSehat</title>
    @vite(['resources/css/app.css'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: #f0fdf4; /* hijau sangat muda */
            min-height: 100vh;
            color: #1f2e1c;
        }

        .pg {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2.5rem 2.5rem 3rem;
        }

        /* TOPBAR */
        .topbar {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2.5rem;
        }
        .back-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #ffffff;
            border: 1px solid #b2f0b2;
            color: #2e7d32;
            padding: 7px 16px;
            border-radius: 30px;
            font-size: 13px;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s;
            text-decoration: none;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .back-btn:hover { background: #e8f5e9; border-color: #81c784; }
        .logo {
            font-family: 'Fraunces', serif;
            font-size: 22px;
            color: #2e7d32;
            letter-spacing: -0.5px;
            font-weight: 600;
        }

        /* HERO */
        .hero {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 2rem;
            align-items: center;
            margin-bottom: 1.25rem;
            padding: 2rem 2.5rem;
            background: #ffffff;
            border-radius: 32px;
            box-shadow: 0 8px 20px rgba(46, 125, 50, 0.06);
            border: 1px solid #d9f0da;
        }
        .hero-label {
            font-size: 10px;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: #66bb6a;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .hero-name {
            font-family: 'Fraunces', serif;
            font-size: 52px;
            font-weight: 700;
            line-height: 1.05;
            color: #1b3b1a;
            letter-spacing: -1.5px;
        }
        .hero-name em { font-style: italic; color: #2e7d32; }
        .hero-sub {
            font-size: 13px;
            color: #5e6b5c;
            margin-top: 8px;
        }
        .hero-tags {
            display: flex;
            gap: 8px;
            margin-top: 1.25rem;
            flex-wrap: wrap;
        }
        .tag {
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 12px;
            background: #e9f7e8;
            color: #2c6e2c;
            border: 1px solid #c8e6c9;
            font-weight: 500;
        }

        /* AVATAR */
        .avatar-outer {
            width: 170px;
            height: 170px;
            border-radius: 50%;
            background: #f9fff9;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            flex-shrink: 0;
            box-shadow: 0 10px 20px rgba(0,0,0,0.02);
        }
        .avatar-ring {
            position: absolute;
            inset: -10px;
            border-radius: 50%;
            border: 1.5px solid #c8e6c9;
            animation: spin 20s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .avatar-inner {
            width: 144px;
            height: 144px;
            border-radius: 50%;
            background: #f1f8e9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Fraunces', serif;
            font-size: 48px;
            color: #2e7d32;
            font-weight: 700;
            overflow: hidden;
            background-size: cover;
            background-position: center;
        }

        /* STAT ROW */
        .stat-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.25rem;
        }
        .stat-box {
            background: #ffffff;
            border-radius: 24px;
            padding: 1.25rem;
            text-align: center;
            transition: all 0.2s;
            border: 1px solid #d9f0da;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }
        .stat-box:hover { border-color: #a5d6a7; background: #fefefe; transform: translateY(-2px); }
        .stat-box .num {
            font-family: 'Fraunces', serif;
            font-size: 38px;
            font-weight: 700;
            color: #2e7d32;
            line-height: 1;
        }
        .stat-box .unit { font-size: 12px; color: #6b7c68; margin-top: 4px; font-weight: 500;}
        .stat-box .desc { font-size: 10px; color: #9eaa9b; margin-top: 2px; letter-spacing: 0.5px; text-transform: uppercase; font-weight: 500;}

        /* GRID 3 */
        .grid3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        /* CARD */
        .card {
            background: #ffffff;
            border: 1px solid #d9f0da;
            border-radius: 24px;
            padding: 1.5rem;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }
        .card:hover { border-color: #a5d6a7; box-shadow: 0 8px 20px rgba(46,125,50,0.06); }
        .card-accent { background: #ffffff; border-left: 4px solid #66bb6a; }
        .clabel {
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #7d9e78;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        /* BMI */
        .bmi-num {
            font-family: 'Fraunces', serif;
            font-size: 48px;
            font-weight: 700;
            color: #2e7d32;
            line-height: 1;
        }
        .bmi-unit { font-size: 12px; color: #6b7c68; margin-top: 3px; }
        .bar-wrap {
            margin-top: 1rem;
            height: 6px;
            background: #e8f0e6;
            border-radius: 10px;
            overflow: hidden;
        }
        .bar { height: 100%; border-radius: 10px; transition: width 1s ease; background: linear-gradient(90deg, #4caf50, #81c784); }
        .bmi-badges { display: flex; gap: 6px; margin-top: 10px; }
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            background: #f4faf3;
            color: #5f7c5c;
            border: 1px solid #cfe9cf;
            font-weight: 500;
        }
        .badge.on {
            background: #e8f5e9;
            color: #2e7d32;
            border-color: #81c784;
            font-weight: 600;
        }
        .bmi-note {
            font-size: 12px;
            color: #5b6b58;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e3f2e2;
            line-height: 1.6;
        }

        /* INFO LIST */
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 11px 0;
            border-bottom: 1px solid #ecf7eb;
            font-size: 13px;
        }
        .info-item:last-child { border-bottom: none; }
        .info-item .k { color: #7d9e78; font-weight: 500; }
        .info-item .v { color: #1f3b1c; font-weight: 600; }
        .info-item .vg {
            color: #2e7d32;
            cursor: pointer;
            font-size: 12px;
            background: none;
            border: none;
            font-family: inherit;
            font-weight: 600;
            transition: opacity 0.2s;
        }
        .info-item .vg:hover { opacity: 0.7; text-decoration: underline; }

        /* KALORI */
        .kal-big {
            font-family: 'Fraunces', serif;
            font-size: 40px;
            font-weight: 700;
            color: #1f3b1c;
            line-height: 1;
        }
        .kal-sub { font-size: 11px; color: #8daa89; margin-top: 4px; letter-spacing: 0.5px; text-transform: uppercase; font-weight: 500; }
        .prog-label {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #7d9e78;
            margin-bottom: 5px;
            margin-top: 10px;
            font-weight: 500;
        }

        /* TIPS */
        .tips-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }
        .tip {
            background: #f9fef8;
            border: 1px solid #d9f0da;
            border-radius: 20px;
            padding: 14px;
            font-size: 12px;
            color: #345e30;
            line-height: 1.55;
            transition: all 0.2s;
        }
        .tip:hover { border-color: #a5d6a7; background: #ffffff; transform: translateY(-2px); }
        .tip-icon { font-size: 20px; margin-bottom: 8px; display: block; }

        /* FOOTER */
        .foot {
            text-align: center;
            margin-top: 2rem;
            font-size: 11px;
            color: #92ab8e;
            letter-spacing: 0.5px;
        }

        /* MODAL */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(30, 45, 28, 0.5);
            backdrop-filter: blur(2px);
            z-index: 100;
            align-items: center;
            justify-content: center;
        }
        .modal-box {
            background: #ffffff;
            border: 1px solid #c8e6c9;
            border-radius: 28px;
            padding: 2rem;
            width: 320px;
            text-align: center;
            box-shadow: 0 20px 30px rgba(0,0,0,0.05);
        }
        .modal-title {
            font-family: 'Fraunces', serif;
            font-size: 22px;
            color: #1f3b1c;
            margin-bottom: 6px;
        }
        .modal-sub { font-size: 13px; color: #7d9e78; margin-bottom: 1.5rem; }
        .modal-btn {
            background: #e8f5e9;
            border: 1px solid #c8e6c9;
            color: #2e7d32;
            padding: 10px 28px;
            border-radius: 40px;
            font-size: 14px;
            cursor: pointer;
            font-family: inherit;
            width: 100%;
            transition: background 0.2s;
            font-weight: 600;
        }
        .modal-btn:hover { background: #d4edd7; border-color: #81c784; }

        /* RESPONSIVE */
        @media (max-width: 960px) {
            .grid3 { grid-template-columns: 1fr 1fr; }
            .tips-grid { grid-template-columns: 1fr 1fr; }
            .hero-name { font-size: 38px; }
        }
        @media (max-width: 640px) {
            .pg { padding: 1.25rem 1rem 2rem; }
            .grid3 { grid-template-columns: 1fr; }
            .stat-row { gap: 8px; }
            .hero { grid-template-columns: 1fr; text-align: center; }
            .hero-name { font-size: 30px; }
            .avatar-outer { display: none; }
            .tips-grid { grid-template-columns: 1fr; }
            .hero-tags { justify-content: center; }
        }
    </style>
</head>
<body>
<div class="pg">

    <!-- TOPBAR -->
    <div class="topbar">
        <a href="/user/dashboard" class="back-btn">&#8592; Dashboard</a>
        <div class="logo">NutriSehat</div>
    </div>

    <!-- HERO -->
    <div class="hero">
        <div>
            <div class="hero-label">✨ Profil Siswa</div>
            <div class="hero-name" id="heroName">—<br><em>—</em></div>
            <div class="hero-sub" id="heroSub">Memuat data...</div>
            <div class="hero-tags">
                <span class="tag" id="tagBmi">BMI —</span>
                <span class="tag" id="tagUsia">— Tahun</span>
                <span class="tag" id="tagKalori">— kcal / hari</span>
                <span class="tag" id="tagKelas">— </span>
            </div>
        </div>
        <div class="avatar-outer">
            <div class="avatar-ring"></div>
            <div class="avatar-inner" id="avatar">?</div>
        </div>
    </div>

    <!-- STAT ROW -->
    <div class="stat-row">
        <div class="stat-box">
            <div class="num" id="statAge">-</div>
            <div class="unit">Tahun</div>
            <div class="desc">Usia</div>
        </div>
        <div class="stat-box">
            <div class="num" id="statWeight">-</div>
            <div class="unit">Kilogram</div>
            <div class="desc">Berat badan</div>
        </div>
        <div class="stat-box">
            <div class="num" id="statHeight">-</div>
            <div class="unit">Centimeter</div>
            <div class="desc">Tinggi badan</div>
        </div>
    </div>

    <!-- GRID 3 KOLOM -->
    <div class="grid3">

        <!-- BMI -->
        <div class="card card-accent">
            <div class="clabel">🌿 Indeks massa tubuh</div>
            <div class="bmi-num" id="bmiValue">-</div>
            <div class="bmi-unit">kg / m²</div>
            <div class="bar-wrap">
                <div class="bar" id="bmiBar" style="width:0%;"></div>
            </div>
            <div class="bmi-badges">
                <span class="badge" id="bKurus">Kurus</span>
                <span class="badge" id="bNormal">Normal</span>
                <span class="badge" id="bGemuk">Gemuk</span>
            </div>
            <div class="bmi-note" id="bmiNote">Memuat data...</div>
        </div>

        <!-- DATA AKUN -->
        <div class="card">
            <div class="clabel">📘 Data akun</div>
            <div class="info-item"><span class="k">Nama</span><span class="v" id="infoName">-</span></div>
            <div class="info-item"><span class="k">Email</span><span class="v" id="infoEmail">-</span></div>
            <div class="info-item"><span class="k">Kelas</span><span class="v" id="infoKelas">-</span></div>
            <div class="info-item"><span class="k">Usia</span><span class="v" id="infoUsia">-</span></div>
            <div class="info-item">
    <span class="k">Password</span>
    <button class="vg" id="ubahPasswordBtn">Ubah password →</button>
</div>
        </div>

        <!-- KALORI -->
        <div class="card">
            <div class="clabel">🔥 Kalori harian</div>
            <div class="kal-big" id="kaloriVal">-</div>
            <div class="kal-sub">kilokalori per hari</div>
            <div>
                <div class="prog-label"><span>🍚 Karbohidrat</span><span id="karbo">-</span></div>
                <div class="bar-wrap"><div class="bar" id="karboBar" style="width:0%;"></div></div>
                <div class="prog-label"><span>🍗 Protein</span><span id="protein">-</span></div>
                <div class="bar-wrap"><div class="bar" id="proteinBar" style="width:0%;"></div></div>
                <div class="prog-label"><span>🥑 Lemak</span><span id="lemak">-</span></div>
                <div class="bar-wrap"><div class="bar" id="lemakBar" style="width:0%;"></div></div>
            </div>
        </div>
    </div>

    <!-- TIPS GIZI -->
    <div class="card">
        <div class="clabel">💚 Tips gizi untukmu</div>
        <div class="tips-grid">
            <div class="tip"><span class="tip-icon">🍎</span>Konsumsi sayur dan buah minimal 5 porsi setiap hari untuk asupan serat optimal.</div>
            <div class="tip"><span class="tip-icon">💧</span>Minum air putih minimal 8 gelas atau 2 liter per hari untuk hidrasi tubuh.</div>
            <div class="tip"><span class="tip-icon">🍚</span>Pilih karbohidrat kompleks seperti nasi merah, oat, atau roti gandum.</div>
            <div class="tip"><span class="tip-icon">🏃</span>Aktif bergerak minimal 30 menit setiap hari, bisa jalan kaki atau olahraga ringan.</div>
        </div>
    </div>

    <div class="foot">🌱 Gizi seimbang, tumbuh kuat bersama NutriSehat · Hijau & Segar</div>
</div>

<!-- MODAL UBAH PASSWORD -->
<div id="settingsModal" class="modal-overlay">
    <div class="modal-box">
        <div style="font-size:32px;margin-bottom:8px;">🔒✨</div>
        <div class="modal-title">Ubah Password</div>
        <button id="closeSettingsBtn" class="modal-btn">Tutup</button>
    </div>
</div>

<script>
const token = localStorage.getItem('jwt_token');

if (!token) {
    window.location.href = '/login';
}

// ── Hitung BMI ──────────────────────────────────────────
function hitungBMI(berat, tinggiCm) {
    if (!berat || !tinggiCm || berat <= 0 || tinggiCm <= 0) return null;
    const tinggiM = tinggiCm / 100;
    return (berat / (tinggiM * tinggiM)).toFixed(1);
}

// ── Update status BMI ───────────────────────────────────
function updateBMIStatus(bmi) {
    const bKurus  = document.getElementById('bKurus');
    const bNormal = document.getElementById('bNormal');
    const bGemuk  = document.getElementById('bGemuk');
    const note    = document.getElementById('bmiNote');
    const bar     = document.getElementById('bmiBar');

    bKurus.className = 'badge';
    bNormal.className = 'badge';
    bGemuk.className = 'badge';

    if (bmi === null) {
        note.innerText = '⚠️ Masukkan berat & tinggi untuk melihat BMI';
        bar.style.width = '0%';
        return;
    }

    const persen = Math.min(Math.max((parseFloat(bmi) / 40) * 100, 5), 95);
    bar.style.width = persen + '%';

    if (bmi < 18.5) {
        bKurus.className = 'badge on';
        note.innerText = '📈 Perlu peningkatan asupan gizi seimbang. Konsultasikan dengan ahli gizi.';
    } else if (bmi >= 18.5 && bmi <= 24.9) {
        bNormal.className = 'badge on';
        note.innerText = '✅ Bagus! Pertahankan pola makan sehat dan tetap aktif.';
    } else {
        bGemuk.className = 'badge on';
        note.innerText = '⚠️ Mulai kurangi gula & perbanyak gerak. Jaga pola makan.';
    }
}

// ── Hitung & tampilkan kalori + makro ──────────────────
function updateKalori(berat) {
    const kal    = Math.round(berat * 40);
    const karbo  = Math.round(kal * 0.50 / 4);
    const prot   = Math.round(kal * 0.20 / 4);
    const fat    = Math.round(kal * 0.30 / 9);

    document.getElementById('kaloriVal').innerText = kal.toLocaleString('id-ID');
    document.getElementById('karbo').innerText   = karbo + ' g';
    document.getElementById('protein').innerText = prot  + ' g';
    document.getElementById('lemak').innerText   = fat   + ' g';

    document.getElementById('karboBar').style.width   = Math.min((karbo / 350) * 100, 100) + '%';
    document.getElementById('proteinBar').style.width = Math.min((prot  / 150) * 100, 100) + '%';
    document.getElementById('lemakBar').style.width   = Math.min((fat   / 100) * 100, 100) + '%';
}

// ── Load profil dari API ────────────────────────────────
async function loadProfile() {
    try {
        const res = await fetch('/api/auth/me', {
            headers: { 'Authorization': `Bearer ${token}` }
        });

        if (!res.ok) {
            localStorage.removeItem('jwt_token');
            window.location.href = '/login';
            return;
        }

        const user = await res.json();

        const nama   = user.name       ?? '-';
        const email  = user.email      ?? '-';
        const kelas  = user.class_room ?? '-';
        const age    = user.age        ?? null;
        const weight = user.weight     ? parseFloat(user.weight) : null;
        const height = user.height     ? parseFloat(user.height) : null;

        // Hero nama (split jadi 2 baris)
        const parts     = nama.trim().split(' ');
        const firstName = parts[0];
        const lastName  = parts.slice(1).join(' ');
        document.getElementById('heroName').innerHTML =
            firstName + (lastName ? '<br><em>' + lastName + '</em>' : '');

        document.getElementById('heroSub').innerText =
            email + (kelas !== '-' ? '  ·  ' + kelas : '');

        // Tags hero
        const bmi = hitungBMI(weight, height);
        document.getElementById('tagBmi').innerText    = bmi   ? 'BMI ' + bmi                              : 'BMI —';
        document.getElementById('tagUsia').innerText   = age   ? age + ' Tahun'                            : '— Tahun';
        document.getElementById('tagKalori').innerText = weight? Math.round(weight*40).toLocaleString('id-ID') + ' kcal / hari' : '— kcal / hari';
        document.getElementById('tagKelas').innerText  = kelas !== '-' ? kelas : '—';

        // Avatar
        const avatarEl = document.getElementById('avatar');
        const initials = nama !== '-' ? nama.substring(0, 2).toUpperCase() : '?';
        if (user.avatar && user.avatar !== '') {
            avatarEl.style.backgroundImage = `url(${user.avatar})`;
            avatarEl.style.backgroundSize  = 'cover';
            avatarEl.style.backgroundColor = 'transparent';
            avatarEl.innerText = '';
        } else {
            avatarEl.innerText = initials;
            const softGreenPalette = ['#c8e6c9','#a5d6a7','#81c784','#66bb6a','#4caf50'];
            avatarEl.style.backgroundColor = softGreenPalette[(nama.length || 0) % softGreenPalette.length];
            avatarEl.style.color = '#1f3b1c';
            avatarEl.style.display = 'flex';
            avatarEl.style.alignItems = 'center';
            avatarEl.style.justifyContent = 'center';
        }

        // Stat boxes
        document.getElementById('statAge').innerText    = age    ?? '-';
        document.getElementById('statWeight').innerText = weight ?? '-';
        document.getElementById('statHeight').innerText = height ?? '-';

        // BMI card
        document.getElementById('bmiValue').innerText = bmi ?? '-';
        updateBMIStatus(bmi ? parseFloat(bmi) : null);

        // Data akun
        document.getElementById('infoName').innerText  = nama;
        document.getElementById('infoEmail').innerText = email;
        document.getElementById('infoKelas').innerText = kelas;
        document.getElementById('infoUsia').innerText  = age ? age + ' tahun' : '-';

        // Kalori
        if (weight) updateKalori(weight);

    } catch (err) {
        console.error('Error load profile:', err);
        document.getElementById('heroSub').innerText = 'Gagal memuat data, silakan refresh';
    }
}

// ── Modal ───────────────────────────────────────────────
const modal = document.getElementById('settingsModal');

document.getElementById('ubahPasswordBtn').addEventListener('click', () => { modal.style.display = 'flex'; });
document.getElementById('closeSettingsBtn').addEventListener('click', () => { modal.style.display = 'none'; });
modal.addEventListener('click', (e) => { if (e.target === modal) modal.style.display = 'none'; });

document.getElementById('ubahPasswordBtn').addEventListener('click', function() {
    window.location.href = '/forgot-password';
});

// ── Init ────────────────────────────────────────────────
loadProfile();
</script>

</body>
</html>