@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<h2 class="text-2xl font-bold mb-6">Dashboard</h2>

<!-- ================= SELECT ROUTER ================= -->
<div class="bg-white rounded shadow p-5 mb-4">
    <label class="block text-sm font-semibold mb-2">Pilih MikroTik</label>
    <select id="routerSelect"
            onchange="loadData()"
            class="w-full border rounded px-3 py-2">
        @foreach($routers as $router)
            <option value="{{ $router->id }}">
                {{ $router->name }} ({{ $router->ip }})
            </option>
        @endforeach
    </select>
</div>

<!-- ================= COLUMN BUTTON ================= -->
<div class="flex justify-end mb-3">
    <button onclick="openColumnModal()"
            class="px-3 py-2 bg-slate-800 text-white rounded">
        Columns
    </button>
</div>

<!-- ================= TABLE ================= -->
<div class="bg-white rounded shadow overflow-x-auto">
<table class="min-w-full text-sm">
<thead class="bg-slate-800 text-white">
<tr>
    <th data-col="user">User</th>
    <th data-col="address">Address</th>
    <th data-col="mac">MAC</th>
    <th data-col="server">Server</th>
    <th data-col="domain">Domain</th>
    <th data-col="uptime">Uptime</th>
    <th data-col="idle_time">Idle Time</th>
    <th data-col="session_time_left">Session Left</th>
    <th data-col="rx_rate">Rx Rate</th>
    <th data-col="tx_rate">Tx Rate</th>
    <th data-col="login_by">Login By</th>
</tr>
</thead>

<tbody id="data">
<tr>
<td colspan="11" class="text-center p-4 text-slate-500">
    Memuat data...
</td>
</tr>
</tbody>
</table>
</div>

<!-- ================= COLUMN MODAL ================= -->
<div id="columnModal"
     class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">

<div class="bg-white rounded w-full max-w-lg p-6">
<h3 class="font-bold mb-4">Columns</h3>

<div class="grid grid-cols-2 gap-2 text-sm">
@foreach([
'user'=>'User',
'address'=>'Address',
'mac'=>'MAC Address',
'server'=>'Server',
'domain'=>'Domain',
'uptime'=>'Uptime',
'idle_time'=>'Idle Time',
'session_time_left'=>'Session Time Left',
'rx_rate'=>'Rx Rate',
'tx_rate'=>'Tx Rate',
'login_by'=>'Login By'
] as $key=>$label)
<label class="flex items-center gap-2">
    <input type="checkbox"
           class="column-toggle"
           data-column="{{ $key }}"
           checked>
    {{ $label }}
</label>
@endforeach
</div>

<div class="flex justify-end mt-4">
<button onclick="closeColumnModal()"
        class="px-4 py-2 bg-slate-200 rounded">
    Close
</button>
</div>

</div>
</div>

<!-- ================= SCRIPT ================= -->
<script>
// ===== FORMAT RATE (SEPERTI WINBOX) =====
function formatRate(bps) {
    if (bps >= 1024 * 1024) return (bps / 1024 / 1024).toFixed(2) + ' MB/s';
    if (bps >= 1024) return (bps / 1024).toFixed(2) + ' KB/s';
    return bps + ' B/s';
}

// ===== LOAD DATA =====
function loadData(){
    const id = document.getElementById('routerSelect').value;

    fetch('/api/router/' + id)
        .then(res => res.json())
        .then(data => {

            let html = '';

            if (data.length === 0) {
                html = `
                <tr>
                    <td colspan="11"
                        class="text-center p-4 text-slate-500">
                        Tidak ada user aktif
                    </td>
                </tr>`;
            } else {
                data.forEach(u => {
                    html += `
                    <tr class="hover:bg-slate-50">
                        <td data-col="user">${u.user ?? '-'}</td>
                        <td data-col="address">${u.address ?? '-'}</td>
                        <td data-col="mac">${u.mac ?? '-'}</td>
                        <td data-col="server">${u.server ?? '-'}</td>
                        <td data-col="domain">${u.domain ?? '-'}</td>
                        <td data-col="uptime">${u.uptime ?? '-'}</td>
                        <td data-col="idle_time">${u.idle_time ?? '-'}</td>
                        <td data-col="session_time_left">${u.session_time_left ?? '-'}</td>
                        <td data-col="rx_rate" class="text-green-600">
                            ${formatRate(u.rx_rate ?? 0)}
                        </td>
                        <td data-col="tx_rate" class="text-blue-600">
                            ${formatRate(u.tx_rate ?? 0)}
                        </td>
                        <td data-col="login_by">${u.login_by ?? '-'}</td>
                    </tr>`;
                });
            }

            document.getElementById('data').innerHTML = html;
            applyColumnState();
        })
        .catch(() => {
            document.getElementById('data').innerHTML = `
            <tr>
                <td colspan="11"
                    class="text-center p-4 text-red-500">
                    Gagal mengambil data MikroTik
                </td>
            </tr>`;
        });
}

// ===== COLUMN TOGGLE =====
function toggleColumn(col, show){
    document.querySelectorAll('[data-col="'+col+'"]')
        .forEach(el => el.style.display = show ? '' : 'none');
}

function applyColumnState(){
    document.querySelectorAll('.column-toggle').forEach(cb => {
        const state = localStorage.getItem('col_' + cb.dataset.column);
        if (state === 'hidden') {
            cb.checked = false;
            toggleColumn(cb.dataset.column, false);
        }
    });
}

document.querySelectorAll('.column-toggle').forEach(cb => {
    cb.onchange = function(){
        toggleColumn(this.dataset.column, this.checked);
        localStorage.setItem(
            'col_' + this.dataset.column,
            this.checked ? 'show' : 'hidden'
        );
    };
});

// ===== MODAL =====
function openColumnModal(){
    document.getElementById('columnModal').classList.remove('hidden');
}
function closeColumnModal(){
    document.getElementById('columnModal').classList.add('hidden');
}

// ===== REALTIME =====
setInterval(loadData, 3000);
loadData();
</script>

@endsection
