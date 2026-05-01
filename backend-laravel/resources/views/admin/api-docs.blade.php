<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>API Tester Pro</title>

<style>
body{
  margin:0;
  font-family:sans-serif;
  background:#0f0f14;
  color:#eee;
  display:flex;
  height:100vh;
}

/* SIDEBAR */
#sidebar{
  width:260px;
  background:#16161d;
  border-right:1px solid #2a2a35;
  overflow:auto;
}

.section{
  padding:10px;
  font-size:12px;
  color:#777;
}

.item{
  padding:8px 12px;
  cursor:pointer;
  display:flex;
  gap:6px;
}
.item:hover{background:#222}

.method{
  font-size:10px;
  font-weight:bold;
}
.get{color:#22d47a}
.post{color:#4d8eff}
.put{color:#ffb627}
.delete{color:#ff4d6a}

/* MAIN */
#main{
  flex:1;
  display:flex;
  flex-direction:column;
}

/* REQUEST */
#request{
  padding:12px;
  border-bottom:1px solid #2a2a35;
}

.row{
  display:flex;
  gap:8px;
  margin-bottom:8px;
}

input,select,textarea{
  background:#1c1c25;
  border:1px solid #333;
  color:#eee;
  padding:8px;
  border-radius:6px;
}

textarea{
  width:100%;
  height:100px;
}

button{
  background:#4d8eff;
  border:none;
  color:#fff;
  padding:8px 16px;
  border-radius:6px;
  cursor:pointer;
}

button:hover{
  opacity:0.9;
}

/* BACK BUTTON */
.back-btn{
  background:#1c1c25;
  border:1px solid #333;
  color:#aaa;
  padding:8px 12px;
}
.back-btn:hover{
  background:#2a2a35;
  color:#fff;
}

/* RESPONSE */
#response{
  flex:1;
  overflow:auto;
  padding:12px;
  font-family:monospace;
}

.meta{
  font-size:12px;
  margin-bottom:6px;
}

.success{color:#22d47a}
.error{color:#ff4d6a}

pre{
  white-space:pre-wrap;
  word-break:break-word;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div id="sidebar"></div>

<!-- MAIN -->
<div id="main">

  <!-- REQUEST -->
  <div id="request">

    <div class="row">

      <!-- 🔙 BACK KE DASHBOARD -->
      <button class="back-btn" onclick="goDashboard()">←</button>

      <select id="method">
        <option>GET</option>
        <option>POST</option>
        <option>PUT</option>
        <option>DELETE</option>
      </select>

      <input id="url" style="flex:1" placeholder="https://api.com/users">

      <button onclick="send()">Send</button>

    </div>

    <input id="token" placeholder="Bearer Token (tempel di sini)">
<textarea id="body" placeholder='{"email":"admin@email.com","password":"123456"}'></textarea>

  </div>

  <!-- RESPONSE -->
  <div id="response">
    <div class="meta" id="meta"></div>
    <pre id="res"></pre>
  </div>

</div>

<script>

/* ========================
   COLLECTION
======================== */
const collections = [
{
  name:"AUTH",
  items:[
    {method:"POST",name:"Login",url:"http://127.0.0.1:8000/api/auth/login"},
    {method:"POST",name:"Register",url:"http://127.0.0.1:8000/api/auth/register"},
    {method:"GET",name:"Me (User Login)",url:"http://127.0.0.1:8000/api/auth/me"},
    {method:"POST",name:"Logout",url:"http://127.0.0.1:8000/api/auth/logout"}
  ]
},
{
  name:"USERS",
  items:[
    {method:"GET",name:"Get Users",url:"http://127.0.0.1:8000/api/users"},
    {method:"POST",name:"Create User",url:"http://127.0.0.1:8000/api/users"},
    {method:"PUT",name:"Update User",url:"http://127.0.0.1:8000/api/users/1"},
    {method:"DELETE",name:"Delete User",url:"http://127.0.0.1:8000/api/users/1"}
  ]
},
{
  name:"MENU",
  items:[
    {method:"GET",name:"Get Menu",url:"http://127.0.0.1:8000/api/menus"},
    {method:"POST",name:"Create Menu",url:"http://127.0.0.1:8000/api/menus"},
    {method:"PUT",name:"Update Menu",url:"http://127.0.0.1:8000/api/menus/1"},
    {method:"DELETE",name:"Delete Menu",url:"http://127.0.0.1:8000/api/menus/1"}
  ]
},
{
  name:"DISTRIBUTION",
  items:[
    {method:"GET",name:"Get Distribusi",url:"http://127.0.0.1:8000/api/distributions"},
    {method:"POST",name:"Create Distribusi",url:"http://127.0.0.1:8000/api/distributions"},
    {method:"PUT",name:"Update Distribusi",url:"http://127.0.0.1:8000/api/distributions/1"},
    {method:"DELETE",name:"Delete Distribusi",url:"http://127.0.0.1:8000/api/distributions/1"}
  ]
},
{
  name:"REPORT",
  items:[
    {method:"GET",name:"Get Report",url:"http://127.0.0.1:8000/api/reports"}
  ]
},
{
  name:"ADMIN",
  items:[
    {method:"GET",name:"Dashboard Admin",url:"http://127.0.0.1:8000/api/admin/dashboard"}
  ]
},
{
  name:"USER",
  items:[
    {method:"GET",name:"Notification",url:"http://127.0.0.1:8000/api/user/notification"},
    {method:"POST",name:"Respond Distribusi",url:"http://127.0.0.1:8000/api/user/distributions/1/respond"},
    {method:"GET",name:"History",url:"http://127.0.0.1:8000/api/user/history"},
    {method:"GET",name:"User Menu",url:"http://127.0.0.1:8000/api/user/menus"}
  ]
}
];

/* ========================
   RENDER SIDEBAR
======================== */
function renderSidebar(){
  const sb = document.getElementById("sidebar");
  sb.innerHTML = "";

  collections.forEach(col=>{
    sb.innerHTML += `<div class="section">${col.name}</div>`;

    col.items.forEach(it=>{
      sb.innerHTML += `
      <div class="item" onclick='load(${JSON.stringify(it)})'>
        <span class="method ${it.method.toLowerCase()}">${it.method}</span>
        <span>${it.name}</span>
      </div>`;
    });
  });
}

/* ========================
   LOAD REQUEST
======================== */
function load(it){
  document.getElementById("method").value = it.method;
  document.getElementById("url").value = it.url;
}

/* ========================
   VARIABLE SUPPORT
======================== */
const vars = {
  host:"https://jsonplaceholder.typicode.com",
  user_id:"1"
};

function parseVars(str){
  return str.replace(/\$\{(.*?)\}/g,(_,v)=>vars[v]||"");
}

/* ========================
   BACK KE DASHBOARD
======================== */
function goDashboard(){
  window.location.href = "/admin/dashboard"; // 🔥 ganti sesuai punyamu
}

/* ========================
   SEND REQUEST
======================== */
async function send(){

  const method = document.getElementById("method").value;
  let url = document.getElementById("url").value;
  const body = document.getElementById("body").value;

  url = parseVars(url);

  const meta = document.getElementById("meta");
  const resEl = document.getElementById("res");

  meta.innerHTML = "Loading...";
  resEl.textContent = "";

  const t0 = Date.now();

  try{
    const token = document.getElementById("token").value;

const res = await fetch(url,{
  method,
  headers:{
    "Content-Type":"application/json",
    "Authorization": token ? "Bearer " + token : ""
  },
  body: method==="GET" ? null : body
});

    const t = Date.now()-t0;
    const txt = await res.text();

    let json;
    try{
      json = JSON.stringify(JSON.parse(txt),null,2);
    }catch{
      json = txt;
    }

    meta.innerHTML = `
      <span class="${res.ok?'success':'error'}">
        ${res.status}
      </span>
      | ${t} ms
    `;

    resEl.textContent = json;

  }catch(e){
    meta.innerHTML = `<span class="error">ERROR</span>`;
    resEl.textContent = e.message;
  }
}

/* INIT */
renderSidebar();

</script>

</body>
</html>