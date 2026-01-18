@extends('layouts.app')

@section('title', 'Report')

@section('content')

<div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800 tracking-tight">
                Laporan Bandwidth
            </h1>
            <p class="mt-1 text-slate-500">
                Analisis rata-rata penggunaan bandwidth RX/TX dalam rentang waktu yang dipilih.
            </p>
        </div>
        <div class="mt-4 md:mt-0">
            <!-- Potential for other controls -->
        </div>
    </div>

    <!-- Chart Card -->
    <div class="bg-slate-50/50 rounded-xl p-4 md:p-6">
        <div class="h-96">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>

    <!-- Filters -->
    <div class="mt-6 flex justify-center">
        <form id="filterForm" action="" method="GET" class="flex items-center gap-4">
            <div class="flex items-center gap-1 bg-slate-200/60 p-1.5 rounded-full">
                <input type="hidden" name="filter" id="filterInput" value="{{ $filter }}">
                <button type="submit" name="filter" value="1D" class="px-4 py-1.5 text-sm font-semibold rounded-full transition-colors duration-200 {{ $filter == '1D' ? 'bg-white text-indigo-600 shadow' : 'text-slate-600 hover:text-indigo-600' }}">1D</button>
                <button type="submit" name="filter" value="5D" class="px-4 py-1.5 text-sm font-semibold rounded-full transition-colors duration-200 {{ $filter == '5D' ? 'bg-white text-indigo-600 shadow' : 'text-slate-600 hover:text-indigo-600' }}">5D</button>
                <button type="submit" name="filter" value="1M" class="px-4 py-1.5 text-sm font-semibold rounded-full transition-colors duration-200 {{ $filter == '1M' ? 'bg-white text-indigo-600 shadow' : 'text-slate-600 hover:text-indigo-600' }}">1M</button>
                <button type="submit" name="filter" value="3M" class="px-4 py-1.5 text-sm font-semibold rounded-full transition-colors duration-200 {{ $filter == '3M' ? 'bg-white text-indigo-600 shadow' : 'text-slate-600 hover:text-indigo-600' }}">3M</button>
                <button type="submit" name="filter" value="6M" class="px-4 py-1.5 text-sm font-semibold rounded-full transition-colors duration-200 {{ $filter == '6M' ? 'bg-white text-indigo-600 shadow' : 'text-slate-600 hover:text-indigo-600' }}">6M</button>
                <button type="submit" name="filter" value="1Y" class="px-4 py-1.5 text-sm font-semibold rounded-full transition-colors duration-200 {{ $filter == '1Y' ? 'bg-white text-indigo-600 shadow' : 'text-slate-600 hover:text-indigo-600' }}">1Y</button>
                <button type="submit" name="filter" value="5Y" class="px-4 py-1.5 text-sm font-semibold rounded-full transition-colors duration-200 {{ $filter == '5Y' ? 'bg-white text-indigo-600 shadow' : 'text-slate-600 hover:text-indigo-600' }}">5Y</button>
                <button type="submit" name="filter" value="All" class="px-4 py-1.5 text-sm font-semibold rounded-full transition-colors duration-200 {{ $filter == 'All' ? 'bg-white text-indigo-600 shadow' : 'text-slate-600 hover:text-indigo-600' }}">All</button>
                <select name="user" id="userSelect" class="px-6 py-1.5 text-sm font-semibold rounded-full transition-colors duration-200 bg-transparent text-slate-600 hover:text-indigo-600">
                    <option value="all" class="bg-white text-slate-600">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user }}" {{ $selectedUser == $user ? 'selected' : '' }} class="bg-white text-slate-600">{{ $user }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.getElementById('userSelect').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    const urlParams = new URLSearchParams(window.location.search);
    const filter = urlParams.get('filter') || '1M';

    let labels = {!! json_encode($data->pluck('label')) !!};

    if (filter === '1D') {
        labels = labels.map(label => {
            const date = new Date(label);
            return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
        });
    }

    const rxData = {!! json_encode($data->pluck('avg_rx')) !!};
    const txData = {!! json_encode($data->pluck('avg_tx')) !!};

    const formatMbps = (bytes) => (bytes / 1024 / 1024).toFixed(2);

    const ctx = document.getElementById('monthlyChart').getContext('2d');

    // Gradient for Rx
    const rxGradient = ctx.createLinearGradient(0, 0, 0, 400);
    rxGradient.addColorStop(0, 'rgba(34, 197, 94, 0.5)');
    rxGradient.addColorStop(1, 'rgba(34, 197, 94, 0)');

    // Gradient for Tx
    const txGradient = ctx.createLinearGradient(0, 0, 0, 400);
    txGradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)');
    txGradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Avg Rx (Mbps)',
                data: rxData.map(formatMbps),
                backgroundColor: rxGradient,
                borderColor: '#22c55e',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }, {
                label: 'Avg Tx (Mbps)',
                data: txData.map(formatMbps),
                backgroundColor: txGradient,
                borderColor: '#6366f1',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                        color: '#e2e8f0'
                    },
                    ticks: {
                        callback: function(value) {
                            return value + ' Mbps';
                        },
                        color: '#64748b'
                    },
                },
                x: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        color: '#64748b'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += context.parsed.y + ' Mbps';
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
</script>

@endsection
