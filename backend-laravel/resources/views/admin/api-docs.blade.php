@extends('layouts.admin')

@section('content')

    {{--
    Override padding dari <main> di layout admin
        supaya API Tester bisa full height
        --}}
        <style>
            /* Hapus padding default dari main layout khusus halaman ini */
            main {
                padding: 0 !important;
                overflow: hidden !important;
            }

            /* ===== COLLECTION SIDEBAR ===== */
            #api-col-sidebar {
                width: 240px;
                flex-shrink: 0;
                background: #16161d;
                border-right: 1px solid #2a2a35;
                overflow-y: auto;
            }

            #api-col-sidebar::-webkit-scrollbar {
                width: 4px;
            }

            #api-col-sidebar::-webkit-scrollbar-thumb {
                background: #333;
                border-radius: 2px;
            }

            .col-section {
                padding: 10px 12px 4px;
                font-size: 10px;
                color: #555;
                text-transform: uppercase;
                letter-spacing: 0.6px;
            }

            .col-item {
                display: flex;
                align-items: center;
                gap: 7px;
                padding: 8px 12px;
                cursor: pointer;
                font-size: 12px;
                color: #aaa;
                transition: background 0.12s;
            }

            .col-item:hover {
                background: #222;
                color: #eee;
            }

            .col-item.selected {
                background: #1a2a22;
                color: #22d47a;
            }

            .col-badge {
                font-size: 9px;
                font-weight: 700;
                padding: 2px 5px;
                border-radius: 3px;
                flex-shrink: 0;
            }

            .badge-GET {
                background: #0d3320;
                color: #22d47a;
            }

            .badge-POST {
                background: #0d1a33;
                color: #4d8eff;
            }

            .badge-PUT {
                background: #332200;
                color: #ffb627;
            }

            .badge-DELETE {
                background: #330d11;
                color: #ff4d6a;
            }

            /* ===== API MAIN ===== */
            #api-main {
                flex: 1;
                display: flex;
                flex-direction: column;
                background: #0f0f14;
                min-width: 0;
            }

            /* ===== REQUEST ===== */
            #api-request {
                padding: 14px 16px;
                border-bottom: 1px solid #2a2a35;
                background: #16161d;
            }

            .api-row {
                display: flex;
                gap: 8px;
                align-items: center;
                margin-bottom: 10px;
            }

            #api-method {
                background: #1c1c25;
                border: 1px solid #333;
                color: #eee;
                padding: 8px 10px;
                border-radius: 6px;
                font-size: 12px;
                font-weight: 600;
                cursor: pointer;
                width: 90px;
            }

            #api-url {
                flex: 1;
                background: #1c1c25;
                border: 1px solid #333;
                color: #eee;
                padding: 8px 12px;
                border-radius: 6px;
                font-size: 12px;
                font-family: monospace;
            }

            #api-send-btn {
                background: #4d8eff;
                border: none;
                color: #fff;
                padding: 8px 20px;
                border-radius: 6px;
                font-size: 12px;
                font-weight: 600;
                cursor: pointer;
                white-space: nowrap;
                transition: opacity 0.15s;
            }

            #api-send-btn:hover {
                opacity: 0.85;
            }

            #api-token {
                width: 100%;
                background: #1c1c25;
                border: 1px solid #333;
                color: #eee;
                padding: 8px 12px;
                border-radius: 6px;
                font-size: 12px;
                margin-bottom: 8px;
            }

            .body-label {
                font-size: 10px;
                color: #555;
                text-transform: uppercase;
                letter-spacing: 0.4px;
                margin-bottom: 4px;
            }

            #api-body {
                width: 100%;
                background: #1c1c25;
                border: 1px solid #333;
                color: #eee;
                padding: 10px 12px;
                border-radius: 6px;
                font-size: 12px;
                font-family: monospace;
                height: 80px;
                resize: vertical;
                line-height: 1.5;
            }

            #api-method:focus,
            #api-url:focus,
            #api-token:focus,
            #api-body:focus {
                outline: none;
                border-color: #4d8eff;
            }

            /* ===== RESPONSE ===== */
            #api-response {
                flex: 1;
                overflow-y: auto;
                padding: 14px 16px;
            }

            #api-response::-webkit-scrollbar {
                width: 4px;
            }

            #api-response::-webkit-scrollbar-thumb {
                background: #333;
                border-radius: 2px;
            }

            .res-meta-bar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 10px;
            }

            .res-meta-left {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            #res-status {
                padding: 3px 10px;
                border-radius: 4px;
                font-size: 11px;
                font-weight: 700;
                background: #1c1c25;
                color: #555;
            }

            #res-time {
                font-size: 11px;
                color: #555;
            }

            #copy-btn {
                background: #1c1c25;
                border: 1px solid #333;
                color: #888;
                padding: 4px 12px;
                border-radius: 4px;
                font-size: 11px;
                cursor: pointer;
                transition: color 0.15s;
            }

            #copy-btn:hover {
                color: #eee;
            }

            #res-body {
                background: #1c1c25;
                border: 1px solid #2a2a35;
                border-radius: 8px;
                padding: 14px;
                font-family: monospace;
                font-size: 12px;
                color: #aaa;
                white-space: pre-wrap;
                word-break: break-word;
                min-height: 120px;
                line-height: 1.6;
            }

            /* ===== SYNTAX HIGHLIGHT ===== */
            .jk {
                color: #4d8eff;
            }

            /* key */
            .js {
                color: #22d47a;
            }

            /* string */
            .jn {
                color: #ffb627;
            }

            /* number */
            .jb {
                color: #ff4d6a;
            }

            /* boolean / null */
        </style>

        {{-- Wrapper full height --}}
        <div style="display:flex; height:100%; overflow:hidden;">

            {{-- ===== COLLECTION SIDEBAR ===== --}}
            <div id="api-col-sidebar">
                <div
                    style="padding:12px 12px 8px; font-size:10px; color:#444; text-transform:uppercase; letter-spacing:0.6px; border-bottom:1px solid #2a2a35;">
                    Collection
                </div>
                <div id="col-list"></div>
            </div>

            {{-- ===== API MAIN ===== --}}
            <div id="api-main">

                {{-- REQUEST --}}
                <div id="api-request">

                    {{-- Method + URL + Send --}}
                    <div class="api-row">
                        <select id="api-method">
                            <option>GET</option>
                            <option>POST</option>
                            <option>PUT</option>
                            <option>DELETE</option>
                        </select>

                        <input id="api-url" placeholder="http://127.0.0.1:8000/api/...">

                        <button id="api-send-btn" onclick="apiSend()">▶ Send</button>
                    </div>

                    {{-- Token --}}
                    <input id="api-token" placeholder="🔑 Bearer Token (opsional)">

                    {{-- Body --}}
                    <div class="body-label">Body (JSON)</div>
                    <textarea id="api-body" placeholder='{"email":"admin@email.com","password":"123456"}'></textarea>

                </div>

                {{-- RESPONSE --}}
                <div id="api-response">

                    <div class="res-meta-bar">
                        <div class="res-meta-left">
                            <span id="res-status">—</span>
                            <span id="res-time"></span>
                        </div>
                        <button id="copy-btn" onclick="copyResponse()">Copy</button>
                    </div>

                    <pre id="res-body">Response akan tampil di sini setelah klik Send...</pre>

                </div>

            </div>{{-- /api-main --}}

        </div>{{-- /wrapper --}}

@endsection


    @push('scripts')
        <script>

            /* ========================
               COLLECTIONS
            ======================== */
            const collections = [
                {
                    name: "AUTH", items: [
                        { method: "POST", name: "Login", url: "http://127.0.0.1:8000/api/auth/login" },
                        { method: "POST", name: "Register", url: "http://127.0.0.1:8000/api/auth/register" },
                        { method: "GET", name: "Me (User Login)", url: "http://127.0.0.1:8000/api/auth/me" },
                        { method: "POST", name: "Logout", url: "http://127.0.0.1:8000/api/auth/logout" }
                    ]
                },
                {
                    name: "USERS", items: [
                        { method: "GET", name: "Get Users", url: "http://127.0.0.1:8000/api/users" },
                        { method: "PUT", name: "Update User", url: "http://127.0.0.1:8000/api/users/1" },
                        { method: "DELETE", name: "Delete User", url: "http://127.0.0.1:8000/api/users/1" }
                    ]
                },
                {
                    name: "MENU", items: [
                        { method: "GET", name: "Get Menu", url: "http://127.0.0.1:8000/api/menus" },
                        { method: "POST", name: "Create Menu", url: "http://127.0.0.1:8000/api/menus" },
                        { method: "PUT", name: "Update Menu", url: "http://127.0.0.1:8000/api/menus/1" },
                        { method: "DELETE", name: "Delete Menu", url: "http://127.0.0.1:8000/api/menus/1" }
                    ]
                },
                {
                    name: "DISTRIBUTION", items: [
                        { method: "GET", name: "Get Distribusi", url: "http://127.0.0.1:8000/api/distributions" },
                        { method: "POST", name: "Create Distribusi", url: "http://127.0.0.1:8000/api/distributions" },
                        { method: "PUT", name: "Update Distribusi", url: "http://127.0.0.1:8000/api/distributions/1" },
                        { method: "DELETE", name: "Delete Distribusi", url: "http://127.0.0.1:8000/api/distributions/1" }
                    ]
                },
                {
                    name: "REPORT", items: [
                        { method: "GET", name: "Get Report", url: "http://127.0.0.1:8000/api/reports" }
                    ]
                },
                {
                    name: "USER", items: [
                        { method: "GET", name: "Notification", url: "http://127.0.0.1:8000/api/user/notification" },
                        { method: "POST", name: "Respond Distribusi", url: "http://127.0.0.1:8000/api/user/distributions/1/respond" },
                        { method: "GET", name: "History", url: "http://127.0.0.1:8000/api/user/history" },
                        { method: "GET", name: "User Menu", url: "http://127.0.0.1:8000/api/user/menus" }
                    ]
                }
            ];

            /* ========================
               RENDER COLLECTION
            ======================== */
            function renderCollection() {
                const list = document.getElementById("col-list");
                list.innerHTML = "";

                collections.forEach(col => {
                    list.innerHTML += `<div class="col-section">${col.name}</div>`;

                    col.items.forEach(item => {
                        list.innerHTML += `
                    <div class="col-item" onclick='loadItem(${JSON.stringify(item).replace(/"/g, "&quot;")}, this)'>
                        <span class="col-badge badge-${item.method}">${item.method}</span>
                        <span>${item.name}</span>
                    </div>`;
                    });
                });
            }

            /* ========================
               LOAD ITEM KE FORM
            ======================== */
            function loadItem(item, el) {
                // highlight selected
                document.querySelectorAll(".col-item").forEach(i => i.classList.remove("selected"));
                el.classList.add("selected");

                // isi form
                document.getElementById("api-method").value = item.method;
                document.getElementById("api-url").value = item.url;
                document.getElementById("api-body").value = getBodyTemplate(item);

                // reset response
                const bodyEl = document.getElementById("res-body");
                const statusEl = document.getElementById("res-status");
                bodyEl.textContent = "Response akan tampil di sini setelah klik Send...";
                statusEl.textContent = "—";
                statusEl.style.background = "#1c1c25";
                statusEl.style.color = "#555";
                document.getElementById("res-time").textContent = "";
            }

            /* ========================
               SEND REQUEST
            ======================== */
            async function apiSend() {
                const method = document.getElementById("api-method").value;
                const url = document.getElementById("api-url").value.trim();
                const token = document.getElementById("api-token").value.trim();
                const bodyVal = document.getElementById("api-body").value.trim();

                const statusEl = document.getElementById("res-status");
                const timeEl = document.getElementById("res-time");
                const bodyEl = document.getElementById("res-body");

                if (!url) {
                    bodyEl.textContent = "⚠ Masukkan URL terlebih dahulu.";
                    return;
                }

                // loading state
                statusEl.textContent = "Loading...";
                statusEl.style.background = "#1c1c25";
                statusEl.style.color = "#888";
                timeEl.textContent = "";
                bodyEl.innerHTML = "";

                const t0 = Date.now();

                try {
                    const headers = { "Content-Type": "application/json" };
                    if (token) headers["Authorization"] = "Bearer " + token;

                    const options = { method, headers };
                    if (method !== "GET" && bodyVal) options.body = bodyVal;

                    const res = await fetch(url, options);
                    const ms = Date.now() - t0;
                    const text = await res.text();

                    // format response
                    let formatted;
                    try {
                        formatted = syntaxHighlight(JSON.stringify(JSON.parse(text), null, 2));
                    } catch {
                        formatted = escapeHtml(text);
                    }

                    // update UI
                    statusEl.textContent = `${res.status} ${res.statusText}`;
                    statusEl.style.background = res.ok ? "#0d3320" : "#330d11";
                    statusEl.style.color = res.ok ? "#22d47a" : "#ff4d6a";
                    timeEl.textContent = `${ms} ms`;
                    bodyEl.innerHTML = formatted;

                } catch (err) {
                    statusEl.textContent = "Error";
                    statusEl.style.background = "#330d11";
                    statusEl.style.color = "#ff4d6a";
                    timeEl.textContent = `${Date.now() - t0} ms`;
                    bodyEl.textContent = "⚠ " + err.message
                        + "\n\nPastikan:\n• Server Laravel sudah jalan (php artisan serve)\n• CORS sudah dikonfigurasi di config/cors.php";
                }
            }

            /* ========================
               SYNTAX HIGHLIGHT JSON
            ======================== */
            function escapeHtml(str) {
                return str
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;");
            }

            function syntaxHighlight(json) {
                json = escapeHtml(json);
                return json.replace(
                    /("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g,
                    match => {
                        if (/^"/.test(match)) {
                            if (/:$/.test(match)) return `<span class="jk">${match}</span>`; // key
                            return `<span class="js">${match}</span>`;                        // string
                        }
                        if (/true|false/.test(match)) return `<span class="jb">${match}</span>`; // boolean
                        if (/null/.test(match)) return `<span class="jb">${match}</span>`; // null
                        return `<span class="jn">${match}</span>`;                               // number
                    }
                );
            }

            /* ========================
               COPY RESPONSE
            ======================== */
            function copyResponse() {
                const text = document.getElementById("res-body").innerText;
                navigator.clipboard.writeText(text).then(() => {
                    const btn = document.getElementById("copy-btn");
                    btn.textContent = "✓ Copied!";
                    setTimeout(() => btn.textContent = "Copy", 1500);
                }).catch(() => {
                    alert("Gagal copy. Coba select manual.");
                });
            }

            /* ========================
               BODY TEMPLATE
            ======================== */
            function getBodyTemplate(item) {

                const templates = {

                    /* ========================
                       AUTH
                    ======================== */

                    "POST:/api/auth/login": {
                        email: "admin@gmail.com",
                        password: "123456"
                    },

                    "POST:/api/auth/register": {
                        name: "Budi",
                        email: "budi@email.com",
                        password: "123456",
                        age: 20,
                        weight: 55,
                        height: 170,
                        class_room: "kelas 3"
                    },

                    "POST:/api/auth/send-otp": {
                        email: "budi@gmail.com"
                    },

                    "POST:/api/auth/reset-password-otp": {
                        email: "budi@gmail.com",
                        otp: "123456",
                        password: "123456",
                        password_confirmation: "123456"
                    },
                    /* ========================
               USERS (FIXED)
            ======================== */

                    "GET:/api/users": {},

                    "PUT:/api/users/1": {
                        name: "Nama Baru",
                        email: "emailbaru@gmail.com",
                        role: "user",
                        class_room: "Kelas 3",
                        age: 20,
                        weight: 55,
                        height: 170
                    },

                    "DELETE:/api/users/1": {},

                    /* ========================
                MENU
             ======================== */

                    "POST:/api/menus": {
                        name: "Nasi Goreng",
                        serving_size_g: 100,
                        calories: 250,
                        protein: 8,
                        carbohydrates: 30,
                        fat: 10,
                        fiber: 2,

                        description: "Menu makanan sehat",

                        vitamin_a: 0,
                        vitamin_c: 10,
                        calcium: 5,
                        iron: 2,
                        sodium: 100,

                        category: "Makanan Utama",
                        meal_time: "Pagi",

                        image_url: "https://example.com/nasi-goreng.jpg",
                        kaggle_id: "FOOD-001"
                    },

                    "PUT:/api/menus/1": {
                        name: "Nasi Goreng Special",
                        serving_size_g: 120,
                        calories: 300,
                        protein: 10,
                        carbohydrates: 35,
                        fat: 12,
                        fiber: 3,

                        description: "Update menu",

                        vitamin_a: 1,
                        vitamin_c: 15,
                        calcium: 6,
                        iron: 3,
                        sodium: 120,

                        category: "Makanan Utama",
                        meal_time: "Malam",

                        image_url: "https://example.com/nasi-goreng-special.jpg",
                        kaggle_id: "FOOD-001"
                    },

                    /* ========================
                       DISTRIBUTION
                    ======================== */

                    "POST:/api/distributions": {
                        distribution_date: "2026-04-06",
                        foods: [
                            {
                                menu_id: "69d1d35edaf06521690674b4",
                                weight: 100
                            },
                            {
                                menu_id: "69d1d35edaf06521690674af",
                                weight: 100
                            }
                        ],
                        target_classes: ["Kelas 1"]
                    },

                    "PUT:/api/distributions/1": {
                        foods: [
                            {
                                menu_id: 1,
                                weight: 100
                            }
                        ],
                        target_classes: ["kelas 1"]
                    },
                    /* ========================
                USER
             ======================== */

                    "GET:/api/user/notification": {
                        has_notification: true,
                        data: [
                            {
                                id: 1,
                                title: "Distribusi Makanan",
                                message: "Apakah sudah menerima makanan?",
                                created_at: "2026-05-08"
                            }
                        ]
                    },

                    "POST:/api/user/distributions/1/respond": {
                        "answer": "Ya"
                    },


                };

                const path = new URL(item.url).pathname;
                const key = `${item.method}:${path}`;

                if (templates[key]) {
                    return JSON.stringify(templates[key], null, 2);
                }

                return "";
            }

            /* ========================
               INIT
            ======================== */
            renderCollection();

        </script>
    @endpush