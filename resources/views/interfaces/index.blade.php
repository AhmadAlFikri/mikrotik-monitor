@extends('layouts.app')

@section('title', 'Interfaces')

@section('content')

<!-- ================= FILTERS ================= -->
<div class="mb-6 grid md:grid-cols-3 gap-6">
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
</div>

<!-- ================= ALERT ================= -->
<div id="alertBox"
     class="hidden mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm"
     role="alert">
</div>


<div class="bg-white rounded-lg shadow-sm">
    <div class="flex justify-between items-center px-6 py-4 border-b border-slate-200">
        <h3 class="font-semibold text-slate-700">Interfaces</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 font-semibold">
                <tr class="text-left">
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Type</th>
                    <th class="px-6 py-3">MAC Address</th>
                    <th class="px-6 py-3">Last Link Up</th>
                    <th class="px-6 py-3">Rx Bytes</th>
                    <th class="px-6 py-3">Tx Bytes</th>
                </tr>
            </thead>
            <tbody id="data" class="divide-y divide-slate-200">
                <tr>
                    <td colspan="7" class="text-center p-8 text-slate-500">
                        Memuat data...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    const POLL_INTERVAL = 2000;
    let isLoading = false;

    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    function changeRouter() {
        fetchData();
    }

    function fetchData() {
        if(isLoading) return;
        isLoading = true;

        const routerId = document.getElementById('routerSelect').value;
        const url = `/api/router-interfaces/${routerId}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                const alertBox = document.getElementById('alertBox');
                const dataContainer = document.getElementById('data');

                // 1. Handle Error
                if (data && data.error) {
                    const errorMessage = `Gagal terhubung ke router: ${data.error}`;
                    alertBox.classList.remove('hidden');
                    alertBox.innerHTML = `<strong>Error!</strong> ${errorMessage}`;
                    dataContainer.innerHTML = `<tr><td colspan="7" class="text-center p-8 text-red-500 font-medium">${errorMessage}</td></tr>`;
                    return;
                }

                alertBox.classList.add('hidden');

                // 2. Handle No Data
                if (!data || data.length === 0) {
                    dataContainer.innerHTML = `<tr><td colspan="7" class="text-center p-8 text-slate-500">Tidak ada interface pada router ini.</td></tr>`;
                    return;
                }

                // 3. Render Data
                let html = '';
                data.forEach(item => {
                    const isRunning = item.running === 'true';
                    const isDisabled = item.disabled === 'true';
                    let status, statusColor;

                    if(isDisabled) {
                        status = 'Disabled';
                        statusColor = 'bg-slate-200 text-slate-700';
                    } else if (isRunning) {
                        status = 'Running';
                        statusColor = 'bg-green-100 text-green-800';
                    } else {
                        status = 'Down';
                        statusColor = 'bg-red-100 text-red-800';
                    }

                    html += `
                        <tr class="hover:bg-slate-50/70 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-800">${item.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="px-2.5 py-1 text-xs font-semibold rounded-full ${statusColor}">${status}</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-500">${item.type}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-500">${item['mac-address'] || '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-500">${item['last-link-up-time'] || 'n/a'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-green-700 font-medium">${formatBytes(item['rx-byte'])}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-blue-700 font-medium">${formatBytes(item['tx-byte'])}</td>
                        </tr>
                    `;
                });
                dataContainer.innerHTML = html;
            })
            .catch(error => {
                console.error('Fetch error:', error);
                const alertBox = document.getElementById('alertBox');
                alertBox.classList.remove('hidden');
                alertBox.innerHTML = '<strong>Network Error:</strong> Tidak dapat terhubung ke server. Pastikan server berjalan dan tidak ada masalah jaringan.';
                document.getElementById('data').innerHTML = `<tr><td colspan="7" class="text-center p-8 text-red-500">Tidak dapat terhubung ke server.</td></tr>`;
            })
            .finally(() => {
                isLoading = false;
            });
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchData();
        setInterval(fetchData, POLL_INTERVAL);
    });
</script>
@endsection
