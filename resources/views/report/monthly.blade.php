@extends('layouts.app')

@section('title', 'Laporan Bulanan')

@section('content')

<div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
    <h2 class="text-xl font-bold text-slate-800 mb-4">
        Rata-Rata Trafik Bulanan
    </h2>

    <div class="h-96">
        <canvas id="monthlyChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = {!! json_encode(
        $data->pluck('month')->map(fn($m) => date('F', mktime(0, 0, 0, $m, 1)))
    ) !!};

    const rx = {!! json_encode($data->pluck('avg_rx')) !!};
    const tx = {!! json_encode($data->pluck('avg_tx')) !!};

    // Helper to format bytes into Mbps
    const formatMbps = (bytes) => (bytes / 1024 / 1024).toFixed(2);

    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Avg Rx (Mbps)',
                data: rx.map(formatMbps),
                backgroundColor: '#10b981', // emerald-500
                borderColor: '#059669',     // emerald-600
                borderWidth: 1
            }, {
                label: 'Avg Tx (Mbps)',
                data: tx.map(formatMbps),
                backgroundColor: '#6366f1', // indigo-500
                borderColor: '#4f46e5',     // indigo-600
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + ' Mbps';
                        }
                    },
                    title: {
                        display: true,
                        text: 'Rata-rata Kecepatan'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
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
                },
                title: {
                    display: true,
                    text: 'Rata-rata Penggunaan Bandwidth per Bulan',
                    font: {
                        size: 16
                    },
                    padding: {
                        top: 10,
                        bottom: 20
                    }
                }
            }
        }
    });
</script>

@endsection
