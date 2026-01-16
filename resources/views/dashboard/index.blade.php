@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- ================= FILTERS ================= -->
<div class="mb-6 grid md:grid-cols-2 gap-6">
    <div class="bg-white p-4 rounded-lg shadow-sm">
        <label for="routerSelect" class="block text-sm font-medium text-slate-600 mb-1">
            Pilih MikroTik
        </label>
        <select id="routerSelect" onchange="changeRouter()"
                class="block w-full rounded-md border-slate-300 shadow-sm
                       focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            @foreach($routers as $router)
                <option value="{{ $router->id }}">
                    {{ $router->name }} ({{ $router->ip }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="bg-white p-4 rounded-lg shadow-sm">
        <label for="searchInput" class="block text-sm font-medium text-slate-600 mb-1">
            Cari User
        </label>
        <input type="text" id="searchInput" onkeyup="searchTable()"
               class="block w-full rounded-md border-slate-300 shadow-sm
                      focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
               placeholder="Cari berdasarkan username, ip, atau mac...">
    </div>
</div>

<!-- ================= ALERT ================= -->
<div id="alertBox"
     class="hidden mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm"
     role="alert">
</div>

<!-- ================= GRAPH ================= -->
<div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mb-6">
    <h3 id="chartTitle" class="font-semibold text-slate-700 mb-2">
        Realtime Traffic (Top User)
    </h3>
    <div class="h-64">
        <canvas id="trafficChart"></canvas>
    </div>
</div>

<!-- ================= TABLE ================= -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="flex justify-between items-center px-6 py-4 border-b border-slate-200">
        <h3 class="font-semibold text-slate-700">User Aktif</h3>
        <div class="flex items-center gap-2">
            <!-- Tombol Urutkan Dropdown -->
            <div class="relative" id="sort-dropdown-container">
                <button onclick="toggleSortDropdown()"
                        class="px-3 py-2 bg-slate-200 text-slate-700 rounded-md text-sm font-semibold hover:bg-slate-300 transition-colors">
                    Urutkan
                </button>
                <div id="sort-dropdown"
                     class="hidden absolute right-0 mt-2 w-60 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                    <div class="py-2 px-4" role="menu" aria-orientation="vertical">
                        <!-- Konten dropdown di-generate oleh JS -->
                        <div class="mb-3">
                            <label for="sort-column-select" class="block text-sm font-medium text-slate-600 mb-1">
                                Urutkan berdasarkan
                            </label>
                            <select id="sort-column-select"
                                    class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                <!-- Opsi kolom di-generate oleh JS -->
                            </select>
                        </div>
                        <div class="mb-3">
                             <label class="block text-sm font-medium text-slate-600 mb-2">
                                Arah
                            </label>
                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-1 cursor-pointer">
                                    <input type="radio" name="sort_direction_radio" value="asc" class="text-indigo-600 focus:ring-indigo-500">
                                    <span>A-Z</span>
                                </label>
                                <label class="flex items-center gap-1 cursor-pointer">
                                    <input type="radio" name="sort_direction_radio" value="desc" class="text-indigo-600 focus:ring-indigo-500">
                                    <span>Z-A</span>
                                </label>
                            </div>
                        </div>
                        <button onclick="applySort()"
                                class="w-full mt-2 px-4 py-2 bg-indigo-600 text-white rounded-md font-semibold text-sm hover:bg-indigo-700 transition">
                            Terapkan
                        </button>
                    </div>
                </div>
            </div>
            <!-- Tombol Kolom -->
            <button onclick="openColumnModal()"
                    class="px-3 py-2 bg-slate-200 text-slate-700 rounded-md text-sm font-semibold hover:bg-slate-300 transition-colors">
                Kolom
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 font-semibold">
                <tr id="table-headers" class="text-left">
                    <th id="col-user" class="px-6 py-3">User</th>
                    <th id="col-status" class="px-6 py-3">Status</th>
                    <th id="col-address" class="px-6 py-3">IP Address</th>
                    <th id="col-mac" class="px-6 py-3">MAC Address</th>
                    <th id="col-uptime" class="px-6 py-3">Uptime</th>
                    <th id="col-idle_time" class="px-6 py-3">Idle Time</th>
                    <th id="col-rx_rate" class="px-6 py-3">Rx Rate</th>
                    <th id="col-tx_rate" class="px-6 py-3">Tx Rate</th>
                    <th id="col-bytes_in" class="px-6 py-3">Bytes In</th>
                    <th id="col-bytes_out" class="px-6 py-3">Bytes Out</th>
                    <th id="col-login_by" class="px-6 py-3">Login By</th>
                </tr>
            </thead>
            <tbody id="data" class="divide-y divide-slate-200">
                <tr>
                    <td colspan="11" class="text-center p-8 text-slate-500">
                        Memuat data...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ================= COLUMN MODAL ================= -->
<div id="columnModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-[999]">
    <div class="bg-white rounded-lg w-full max-w-md p-6 shadow-xl m-4">
        <h3 class="text-lg font-semibold mb-4">Tampilkan Kolom</h3>
        <div id="column-checkboxes" class="grid grid-cols-2 gap-4 text-sm">
            <!-- Checkboxes will be inserted here by JS -->
        </div>
        <div class="flex justify-end gap-3 mt-6">
            <button onclick="closeColumnModal()"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md font-semibold text-sm hover:bg-indigo-700 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- ================= SCRIPT ================= -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
/* ================= GLOBAL ================= */
const POLL_INTERVAL = 1000;
let isLoading = false;
let selectedUser = null;
let chart, labels = [], rxData = [], txData = [];
let alertShown = {};
let sortColumn = 'uptime';
let sortDirection = 'desc';

const columnState = {
    user:      { label: 'User',       visible: true, index: 0 },
    status:    { label: 'Status',     visible: false, index: 1 },
    address:   { label: 'IP Address', visible: true, index: 2 },
    mac:       { label: 'MAC Address',visible: false, index: 3 },
    uptime:    { label: 'Uptime',     visible: true, index: 4 },
    idle_time: { label: 'Idle Time',  visible: false, index: 5 },
    rx_rate:   { label: 'Rx Rate',    visible: true, index: 6 },
    tx_rate:   { label: 'Tx Rate',    visible: true, index: 7 },
    bytes_in:  { label: 'Bytes In',   visible: true, index: 8 },
    bytes_out: { label: 'Bytes Out',  visible: true, index: 9 },
    login_by:  { label: 'Login By',   visible: false, index: 10 },
};

/* ================= UTIL ================= */
function formatBytes(bytes, decimals = 2) {
    if (!+bytes) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
}

function formatRate(bps){
    if (bps >= 1024 * 1024 * 1024) return (bps / (1024 * 1024 * 1024)).toFixed(2) + ' Gbps';
    if (bps >= 1024 * 1024) return (bps / (1024 * 1024)).toFixed(2) + ' Mbps';
    if (bps >= 1024) return (bps / 1024).toFixed(2) + ' Kbps';
    return bps + ' bps';
}

function getStatus(u) {
    if(u.rx_rate > 5_000_000 || u.tx_rate > 5_000_000) return ['Heavy', 'bg-red-100 text-red-800'];
    const idle = u.idle_time.includes('s'); // crude but effective
    if(!idle) return ['Idle', 'bg-yellow-100 text-yellow-800'];
    return ['Active', 'bg-green-100 text-green-800'];
}

/* ================= SORTING DROPDOWN ================= */
function toggleSortDropdown() {
    const dropdown = document.getElementById('sort-dropdown');
    dropdown.classList.toggle('hidden');
    if (!dropdown.classList.contains('hidden')) {
        initializeSortDropdown();
    }
}

function initializeSortDropdown() {
    const select = document.getElementById('sort-column-select');
    select.innerHTML = '';
    for (const key in columnState) {
        const option = document.createElement('option');
        option.value = key;
        option.textContent = columnState[key].label;
        if (key === sortColumn) {
            option.selected = true;
        }
        select.appendChild(option);
    }
    document.querySelector(`input[name="sort_direction_radio"][value="${sortDirection}"]`).checked = true;
}

function applySort() {
    sortColumn = document.getElementById('sort-column-select').value;
    sortDirection = document.querySelector('input[name="sort_direction_radio"]:checked').value;
    loadData();
    toggleSortDropdown(); // Close dropdown after applying
}

// Close dropdown if clicked outside
window.addEventListener('click', function(e) {
    const container = document.getElementById('sort-dropdown-container');
    if (container && !container.contains(e.target)) {
        document.getElementById('sort-dropdown').classList.add('hidden');
    }
});


/* ================= COLUMN VISIBILITY ================= */
function openColumnModal() {
    document.getElementById('columnModal').classList.remove('hidden');
}

function closeColumnModal() {
    document.getElementById('columnModal').classList.add('hidden');
}

function toggleColumn(key) {
    columnState[key].visible = !columnState[key].visible;
    localStorage.setItem('mikrotikColumnState', JSON.stringify(columnState));
    applyColumnVisibility();
}

function applyColumnVisibility() {
    for (const key in columnState) {
        const visible = columnState[key].visible;
        document.getElementById(`col-${key}`).style.display = visible ? '' : 'none';
        document.querySelectorAll(`#data td[data-col="${key}"]`).forEach(cell => {
            cell.style.display = visible ? '' : 'none';
        });
    }
}

function initializeColumns() {
    const savedState = JSON.parse(localStorage.getItem('mikrotikColumnState'));
    if (savedState) {
        for (const key in columnState) {
            if (savedState[key] !== undefined) {
                columnState[key].visible = savedState[key].visible;
            }
        }
    }

    const container = document.getElementById('column-checkboxes');
    container.innerHTML = Object.keys(columnState).map(key => `
        <label for="check-${key}" class="flex items-center space-x-3 cursor-pointer">
            <input type="checkbox" id="check-${key}" onchange="toggleColumn('${key}')"
                   class="rounded border-slate-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-offset-0 focus:ring-indigo-200 focus:ring-opacity-50"
                   ${columnState[key].visible ? 'checked' : ''}>
            <span>${columnState[key].label}</span>
        </label>
    `).join('');

    applyColumnVisibility();
}


/* ================= CHART ================= */
function initChart(){
    chart=new Chart(document.getElementById('trafficChart'),{
        type:'line',
        data:{ labels, datasets:[
            {label:'Rx',data:rxData,borderColor:'#16a34a',backgroundColor:'#16a34a20',fill:true,tension:.3},
            {label:'Tx',data:txData,borderColor:'#2563eb',backgroundColor:'#2563eb20',fill:true,tension:.3}
        ]},
        options:{
            responsive:true, maintainAspectRatio: false, animation:false, interaction:{intersect:false, mode: 'index'},
            scales: { y: { ticks: { callback: value => formatRate(value) } } },
            plugins: { tooltip: { callbacks: { label: context => `${context.dataset.label}: ${formatRate(context.raw)}` } } }
        }
    });
}

function pushChart(rx,tx){
    if(rxData.length) {
        rx = (rxData.at(-1) + rx) / 2;
        tx = (txData.at(-1) + tx) / 2;
    }
    labels.push(new Date().toLocaleTimeString());
    rxData.push(rx);
    txData.push(tx);
    if(labels.length>30){ labels.shift(); rxData.shift(); txData.shift(); }
    chart.update('none');
}

function resetChart(){
    labels.length=rxData.length=txData.length=0;
    chart.update();
}

/* ================= ROUTER ================= */
function changeRouter(){
    selectedUser=null;
    alertShown={};
    resetChart();
    document.getElementById('chartTitle').innerText='Realtime Traffic (Top User)';
    loadData();
}

/* ================= USER ================= */
function selectUser(u){
    selectedUser=u;
    resetChart();
    document.getElementById('chartTitle').innerText=`Realtime Traffic (${u})`;
    loadData();
}

/* ================= LOAD DATA ================= */
function loadData(){
    if(isLoading) return;
    isLoading=true;
    const id = document.getElementById('routerSelect').value;
    const url = `/api/router/${id}?sort_column=${sortColumn}&sort_direction=${sortDirection}`;

    fetch(url)
    .then(r => r.json())
    .then(data => {
        let html = '';
        let target = selectedUser ? data.find(x => x.user === selectedUser) : null;
        if (!target && data.length) {
            target = data[0];
        }
        if (target) pushChart(target.rx_rate || 0, target.tx_rate || 0);

        data.forEach(u => {
            const [st, color] = getStatus(u);
            if ((u.rx_rate > 5_000_000 || u.tx_rate > 5_000_000) && !alertShown[u.user]) {
                alertShown[u.user] = true;
                const alertBox = document.getElementById('alertBox');
                alertBox.classList.remove('hidden');
                alertBox.innerHTML = `<strong>Peringatan Bandwidth Tinggi!</strong> Pengguna <strong>${u.user}</strong> menggunakan traffic lebih dari 5 Mbps.`;
            }

            html+=`
            <tr class="hover:bg-slate-50/70 transition-colors duration-150 cursor-pointer"
                onclick="selectUser('${u.user}')">
                <td data-col="user" class="px-6 py-4 whitespace-nowrap font-medium text-slate-800">${u.user}</td>
                <td data-col="status" class="px-6 py-4 whitespace-nowrap"><span class="px-2.5 py-1 text-xs font-semibold rounded-full ${color}">${st}</span></td>
                <td data-col="address" class="px-6 py-4 whitespace-nowrap text-slate-500">${u.address}</td>
                <td data-col="mac" class="px-6 py-4 whitespace-nowrap text-slate-500">${u.mac}</td>
                <td data-col="uptime" class="px-6 py-4 whitespace-nowrap text-slate-500">${u.uptime}</td>
                <td data-col="idle_time" class="px-6 py-4 whitespace-nowrap text-slate-500">${u.idle_time}</td>
                <td data-col="rx_rate" class="px-6 py-4 whitespace-nowrap text-green-700 font-medium">${formatRate(u.rx_rate)}</td>
                <td data-col="tx_rate" class="px-6 py-4 whitespace-nowrap text-blue-700 font-medium">${formatRate(u.tx_rate)}</td>
                <td data-col="bytes_in" class="px-6 py-4 whitespace-nowrap text-slate-500">${formatBytes(u.bytes_in)}</td>
                <td data-col="bytes_out" class="px-6 py-4 whitespace-nowrap text-slate-500">${formatBytes(u.bytes_out)}</td>
                <td data-col="login_by" class="px-6 py-4 whitespace-nowrap text-slate-500">${u.login_by}</td>
            </tr>`;
        });

        if(!data.length) {
            html=`<tr><td colspan="11" class="text-center p-8 text-slate-500">Tidak ada user aktif pada router ini.</td></tr>`;
        }
        document.getElementById('data').innerHTML = html;
        applyColumnVisibility();
    })
    .finally(() => isLoading = false);
}

/* ================= SEARCH ================= */
function searchTable() {
    const q = searchInput.value.toLowerCase();
    document.querySelectorAll('#data tr').forEach(tr => {
        const text = tr.innerText.toLowerCase();
        tr.style.display = text.includes(q) ? '' : 'none';
    });
}

/* ================= INIT ================= */
document.addEventListener('DOMContentLoaded', () => {
    initializeColumns();
    initializeSortDropdown();
    initChart();
    loadData();
    setInterval(loadData, POLL_INTERVAL);
});
</script>

@endsection
